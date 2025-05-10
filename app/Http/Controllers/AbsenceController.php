<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absence;
use App\Models\Employe;
use App\Models\Pointage;
use App\Models\JourFerie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AbsenceController extends Controller
{
    /**
     * Affiche la liste des absences pour l'employé connecté
     */
    public function index(Request $request)
    {
        $employe = Auth::user()->employe;
        $query = $employe->absences()->orderBy('date_debut', 'desc');
        
        // Filtrer par statut si un filtre est spécifié
        if ($request->has('filtre')) {
            $filtre = $request->query('filtre');
            switch ($filtre) {
                case 'non_justifiee':
                    $query->where('statut', 'non_justifiée');
                    break;
                case 'en_attente':
                    $query->where('statut', 'en_attente');
                    break;
                case 'justifiee':
                    $query->where('statut', 'justifiée');
                    break;
                case 'rejetee':
                    $query->where('statut', 'rejetée');
                    break;
            }
        }
        
        $absences = $query->paginate(10)->withQueryString();
        
        return view('employe.absences.index', compact('absences'));
    }
    
    /**
     * Affiche le formulaire pour justifier une absence
     */
    public function create(Request $request)
    {
        $employe = Auth::user()->employe;
        $absenceId = $request->query('absence_id');
        
        // Si un ID d'absence est fourni, vérifier qu'elle existe et appartient à l'employé
        if ($absenceId) {
            $absence = Absence::where('id', $absenceId)
                ->where('employe_id', $employe->id)
                ->where('statut', 'non_justifiée')
                ->firstOrFail();
                
            return view('employe.absences.create', compact('absence'));
        }
        
        // Sinon, rediriger vers la liste des absences avec un message
        return redirect()->route('Employé.absences.index')
            ->with('error', 'Vous ne pouvez justifier que les absences détectées par le système.');
    }
    
    /**
     * Enregistre une justification pour une absence existante
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'absence_id' => 'required|exists:absences,id',
            'motif' => 'required|string|max:255',
            'justification' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $employe = Auth::user()->employe;
        
        // Vérifier que l'absence existe et appartient à l'employé
        $absence = Absence::where('id', $request->absence_id)
            ->where('employe_id', $employe->id)
            ->where('statut', 'non_justifiée')
            ->firstOrFail();
        
        $documentPath = $absence->document_path; // Conserver le document existant par défaut
        
        // Traiter le document s'il est fourni
        if ($request->hasFile('document') && $request->file('document')->isValid()) {
            // Supprimer l'ancien document s'il existe
            if ($documentPath && Storage::disk('public')->exists($documentPath)) {
                Storage::disk('public')->delete($documentPath);
            }
            
            $document = $request->file('document');
            $documentPath = $document->store('documents/absences/' . $employe->id, 'public');
        }
        
        // Mettre à jour l'absence existante
        $absence->update([
            'motif' => $request->motif,
            'justification' => $request->justification,
            'document_path' => $documentPath,
            'statut' => 'en_attente',
        ]);
        
        return redirect()->route('Employé.absences.index')
            ->with('success', 'Votre justification d\'absence a été soumise et est en attente de validation');
    }
    
    /**
     * Affiche les détails d'une absence
     */
    public function show(Absence $absence)
    {
        // Vérifier que l'absence appartient bien à l'employé connecté
        if (Auth::user()->role === 'employe' && $absence->employe_id !== Auth::user()->employe->id) {
            abort(403, 'Vous n\'avez pas accès à cette absence.');
        }
        
        return view('employe.absences.show', compact('absence'));
    }
    
    /**
     * Affiche le tableau de bord des absences pour l'administrateur
     */
    public function dashboard()
    {
        return view('admin.absences.dashboard');
    }
    
    /**
     * Affiche la liste des absences pour l'administrateur
     */
    public function adminIndex(Request $request)
    {
        $query = Absence::with('employe');
        
        // Filtrer par statut si spécifié
        if ($request->has('statut') && !empty($request->statut)) {
            $query->where('statut', $request->statut);
        }
        
        // Filtrer par employé si spécifié
        if ($request->has('employe_id') && !empty($request->employe_id)) {
            $query->where('employe_id', $request->employe_id);
        }
        
        $absences = $query->orderBy('date_debut', 'desc')->paginate(15);
        $employes = Employe::orderBy('first_name')->get();
        
        return view('admin.absences.index', compact('absences', 'employes'));
    }
    
    /**
     * Affiche les détails d'une absence pour l'administrateur
     */
    public function adminShow(Absence $absence)
    {
        return view('admin.absences.show', compact('absence'));
    }
    
    /**
     * Traite une absence (valider ou rejeter)
     */
    public function traiter(Request $request, Absence $absence)
    {
        $validator = Validator::make($request->all(), [
            'commentaire_admin' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Déterminer le statut en fonction de l'action (approuver ou rejeter)
        if ($request->action === 'approuver') {
            $absence->statut = 'justifiée';
        } elseif ($request->action === 'rejeter') {
            $absence->statut = 'rejetée';
        }
        
        $absence->commentaire_admin = $request->commentaire_admin;
        $absence->save();
        
        return redirect()->route('admin.absences.index')
            ->with('success', 'L\'absence a été traitée avec succès.');
    }
    
    /**
     * Détecte automatiquement les absences en fonction des pointages manquants
     * Cette méthode peut être appelée par une tâche planifiée
     */
    public function detecterAbsences(Request $request)
    {
        // Limiter strictement la période pour éviter de détecter trop d'absences
        $dateDebut = Carbon::yesterday()->subDays(6); // 7 derniers jours, y compris hier
        $dateFin = Carbon::yesterday(); // Jusqu'à hier uniquement
        
        // Afficher les dates de début et de fin pour vérification
        $periodeDebut = $dateDebut->format('Y-m-d');
        $periodeFin = $dateFin->format('Y-m-d');
        
        // Récupérer tous les jours fériés dans la période
        $joursFeries = JourFerie::whereBetween('date', [$periodeDebut, $periodeFin])
            ->pluck('date')
            ->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();
        
        // Récupérer uniquement les employés actifs
        $employes = Employe::all();
        
        $absencesDetectees = 0;
        $employesTraites = 0;
        $joursVerifies = 0;
        
        // Nettoyer les absences existantes détectées automatiquement si demandé
        if ($request->has('reset') && $request->reset == 1) {
            $absencesSupprimees = Absence::where('statut', 'non_justifiée')
                ->whereBetween('date_debut', [$periodeDebut, $periodeFin])
                ->delete();
            
            return redirect()->route('admin.absences.dashboard')->with('success', $absencesSupprimees . ' absence(s) non justifiée(s) supprimée(s). Vous pouvez maintenant relancer la détection.');
        }
        
        foreach ($employes as $employe) {
            $employesTraites++;
            // Parcourir chaque jour de la période
            $date = clone $dateDebut;
            
            while ($date->lte($dateFin)) {
                $joursVerifies++;
                $dateFormatee = $date->format('Y-m-d');
                
                // Vérifier si c'est un jour férié
                if (in_array($dateFormatee, $joursFeries)) {
                    $date->addDay();
                    continue;
                }
                
                // Vérifier si l'employé est censé travailler ce jour-là
                $jourSemaine = $date->dayOfWeek; // 0 = dimanche, 1 = lundi, etc.
                
                // Vérifier si l'employé a un horaire personnalisé pour ce jour
                $horairePersonnalise = $employe->horaires()
                    ->where('jour_semaine', $jourSemaine)
                    ->first(); // Suppression du filtre 'actif' qui pourrait causer des problèmes
                
                // Si l'employé n'a pas d'horaire personnalisé et que ce n'est pas un jour ouvrable standard (lundi-vendredi)
                if (!$horairePersonnalise && !in_array($jourSemaine, [1, 2, 3, 4, 5])) {
                    $date->addDay();
                    continue;
                }
                
                // Vérifier si l'employé a pointé ce jour-là
                $pointage = Pointage::where('employe_id', $employe->id)
                    ->whereDate('date', $date)
                    ->first();
                
                // Vérifier si l'absence est déjà enregistrée
                $absenceExistante = Absence::where('employe_id', $employe->id)
                    ->where(function($query) use ($date) {
                        $query->where('date_debut', '<=', $date)
                            ->where('date_fin', '>=', $date);
                    })
                    ->first();
                
                // Si pas de pointage et pas d'absence enregistrée, créer une absence
                if (!$pointage && !$absenceExistante) {
                    Absence::create([
                        'employe_id' => $employe->id,
                        'date_debut' => $dateFormatee,
                        'date_fin' => $dateFormatee,
                        'statut' => 'non_justifiée',
                    ]);
                    
                    $absencesDetectees++;
                }
                
                $date->addDay();
            }
        }
        
        $message = 'Détection des absences effectuée avec succès.<br>';
        $message .= 'Période analysée : du ' . $dateDebut->format('d/m/Y') . ' au ' . $dateFin->format('d/m/Y') . '<br>';
        $message .= $employesTraites . ' employé(s) traité(s), ' . $joursVerifies . ' jour(s) vérifié(s)<br>';
        $message .= $absencesDetectees . ' nouvelle(s) absence(s) détectée(s).';
        
        return redirect()->route('admin.absences.dashboard')->with('success', $message);
    }
}
