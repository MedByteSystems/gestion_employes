<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employe;
use App\Models\Pointage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PointageController extends Controller
{
    /**
     * Affiche la page de pointage pour l'employé
     */
    public function index(Request $request)
    {
        $employe = auth()->user()->employe;
        
        // Vérifier si l'employé a déjà pointé aujourd'hui
        $dernierPointage = $employe->pointages()
            ->whereDate('date', Carbon::today())
            ->latest()
            ->first();
        
        // Récupérer les postes de travail autorisés pour cet employé
        $postesTravail = $employe->postesTravail()->where('actif', true)->get();
        
        // Initialiser les variables
        $posteAutorise = false;
        $posteSelectionne = null;
        
        // Récupérer le device ID depuis la requête, le cookie ou la session
        $deviceId = $request->input('device_id') ?? $request->cookie('device_id') ?? session('device_id');
        
        // Initialiser les variables
        $posteSelectionne = null;
        $posteAutorise = false;
        
        // Vérifier si un poste de travail est associé à cet appareil
        if ($deviceId) {
            $posteSelectionne = $this->trouverPosteTravailParDeviceId($employe, $deviceId);
            $posteAutorise = ($posteSelectionne !== null);
        }
        
        // Si un poste de travail a été trouvé, stocker son ID en session
        if ($posteAutorise && $posteSelectionne) {
            session(['poste_travail_id' => $posteSelectionne->id]);
        } else {
            session()->forget('poste_travail_id');
        }
        
        $heureActuelle = Carbon::now();
        $heurePrevue = Carbon::now()->setTime(9, 0); // Heure d'arrivée prévue: 9h00
        $heureDepart = Carbon::now()->setTime(17, 0); // Heure de départ prévue: 17h00
        
        // Définir estDansReseauEntreprise comme toujours vrai car nous ne vérifions plus l'IP
        $estDansReseauEntreprise = true;
        
        return view('employe.pointage.index', compact(
            'employe', 
            'dernierPointage', 
            'heureActuelle', 
            'heurePrevue', 
            'heureDepart', 
            'postesTravail',
            'estDansReseauEntreprise',
            'posteAutorise',
            'posteSelectionne'
        ));
    }

    /**
     * Enregistre un nouveau pointage
     */
    public function store(Request $request)
    {
        // Débogage - Enregistrer toutes les données de la requête
        \Log::info('Données de la requête de pointage:', $request->all());
        
        $employe = auth()->user()->employe;
        \Log::info('Employé ID: ' . $employe->id);
        
        // Valider les données du formulaire
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:entree,sortie',
            'localisation' => 'nullable|string',
            'commentaire' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            \Log::error('Validation a échoué:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
      
        $deviceId = $request->input('device_id');
        \Log::info('Device ID utilisé pour le pointage: ' . $deviceId);
        
        // Trouver le poste de travail directement à partir de l'ID fourni dans le formulaire
        $posteTravailId = $request->input('poste_travail_id');
        \Log::info('ID du poste de travail depuis le formulaire: ' . $posteTravailId);
        
        if (empty($posteTravailId)) {
            \Log::error('Aucun ID de poste de travail fourni dans le formulaire');
        }
        
        // Vérifier si un poste de travail a été sélectionné
        if (!$request->has('poste_travail_id') || empty($request->poste_travail_id)) {
            \Log::error('Aucun poste de travail sélectionné');
            return redirect()->back()->with('error', 'Vous devez sélectionner un poste de travail pour pointer')->withInput();
        }
        
        // Vérifier si le poste de travail sélectionné appartient à l'employé
        $posteTravail = $employe->postesTravail()
            ->where('id', $request->poste_travail_id)
            ->where('actif', true)
            ->first();
            
        \Log::info('Recherche du poste de travail: ID=' . $request->poste_travail_id . ', Résultat: ' . ($posteTravail ? 'Trouvé' : 'Non trouvé'));
        
        if (!$posteTravail) {
            \Log::error('Poste de travail non valide ou n\'appartient pas à l\'employé');
            return redirect()->back()->with('error', 'Le poste de travail sélectionné n\'est pas valide ou ne vous appartient pas')->withInput();
        }
        
        // Utiliser le device_id au lieu de l'adresse MAC
        $deviceId = $request->input('device_id');
        \Log::info('Device ID utilisé pour le pointage: ' . $deviceId);
        
        // Si le poste n'a pas encore de device_id, le mettre à jour
        if (!$posteTravail->device_id && $deviceId) {
            $posteTravail->device_id = $deviceId;
            $posteTravail->save();
            \Log::info('Mise à jour du device_id du poste de travail: ' . $deviceId);
        }
        
        // Création du pointage
        // Définir les heures de travail normales
        $heureDebut = Carbon::today()->setTime(9, 0); // 9h00 du matin
        $heureFin = Carbon::today()->setTime(17, 0);  // 17h00 (5h00 PM)
        $heureReelle = Carbon::now();
        
        // Journaliser l'heure réelle pour débogage
        \Log::info('Heure réelle du pointage: ' . $heureReelle->format('H:i:s'));
        \Log::info('Heure de début de journée: ' . $heureDebut->format('H:i:s'));
        
        // Vérifier si le pointage est en dehors des heures de travail autorisées
        // Autoriser le pointage entre 7h00 et 18h00 (plage élargie pour tenir compte des arrivées tôt et départs tardifs)
        $heureMin = Carbon::today()->setTime(7, 0); // 7h00 du matin
        $heureMax = Carbon::today()->setTime(18, 0); // 18h00 (6h00 PM)
        
        if ($heureReelle->hour < 7 || $heureReelle->hour >= 18) {
            \Log::error('Tentative de pointage en dehors des heures autorisées: ' . $heureReelle->format('H:i:s'));
            return redirect()->back()->with('error', 'Le pointage n\'est autorisé qu\'entre 7h00 et 18h00')->withInput();
        }
        
        // Déterminer le statut et le retard
        $statut = 'à l\'heure';
        $retard = 0;
        $heurePrevue = $heureDebut; // Par défaut, l'heure prévue est 9h00
        
        // Vérifier si l'heure réelle est en dehors des heures de travail normales
        if ($heureReelle->hour < 9) {
            // Pointage avant 9h00 - considéré comme à l'heure
            \Log::info('Pointage avant 9h00 - considéré comme à l\'heure');
            $statut = 'à l\'heure';
            $retard = 0;
        } 
        elseif ($heureReelle->hour >= 9 && $heureReelle->hour < 9 + 0) { // 0 = marge de 15 minutes divisée par 60
            // Entre 9h00 et 9h15 - considéré comme à l'heure
            \Log::info('Pointage entre 9h00 et 9h15 - considéré comme à l\'heure');
            $statut = 'à l\'heure';
            $retard = 0;
        }
        elseif ($heureReelle->hour >= 9 && $heureReelle->hour < 17) {
            // Pointage après 9h15 mais avant 17h00
            // Calculer le retard en minutes par rapport à 9h00
            $retard = $heureReelle->diffInMinutes($heureDebut);
            
            // Si le retard est supérieur à 15 minutes, marquer comme retard
            if ($retard > 15) {
                \Log::info('Pointage après 9h15 - retard de ' . $retard . ' minutes');
                $statut = 'retard';
            } else {
                \Log::info('Pointage avec moins de 15 minutes de retard - considéré comme à l\'heure');
                $statut = 'à l\'heure';
                $retard = 0;
            }
        }
        else {
            // Pointage après 17h00 ou très tôt le matin (avant minuit)
            \Log::info('Pointage en dehors des heures de travail normales');
            $statut = 'hors horaire';
            $retard = 0; // Pas de retard calculé pour les pointages hors horaire
        }
        
        try {
            $pointage = new Pointage([
                'employe_id' => $employe->id,
                'date' => Carbon::today(),
                'heure_prevue' => $heurePrevue,
                'heure_reelle' => $heureReelle,
                'statut' => $statut,
                'retard_minutes' => $statut === 'retard' ? $retard : 0,
                'localisation' => $request->localisation,
                'commentaire' => $request->commentaire,
                'poste_travail_id' => $posteTravail->id,
                'validé' => true // Auto-validé car le poste est vérifié plus haut
            ]);
            
            $pointage->save();
            \Log::info('Pointage créé avec succès. ID: ' . $pointage->id);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du pointage: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement du pointage: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('Employé.dashboard')->with('success', 'Pointage enregistré avec succès');
    }
    
    /**
     * Affiche l'historique des pointages de l'employé connecté
     */
    public function historique(Request $request)
    {
        $employe = auth()->user()->employe;
        
        // Récupérer les pointages
        $pointages = $employe->pointages()
            ->when($request->has('mois'), function($query) use ($request) {
                [$annee, $mois] = explode('-', $request->mois);
                return $query->whereYear('date', $annee)
                            ->whereMonth('date', $mois);
            }, function($query) {
                return $query->whereMonth('date', Carbon::now()->month)
                            ->whereYear('date', Carbon::now()->year);
            })
            ->with('posteTravail') // Charger la relation posteTravail pour l'affichage
            ->orderBy('date', 'desc')
            ->paginate(15);
            
        // Préparer les données pour le filtre par mois
        $moisDisponibles = $employe->pointages()
            ->selectRaw('DISTINCT YEAR(date) as annee, MONTH(date) as mois')
            ->orderBy('annee', 'desc')
            ->orderBy('mois', 'desc')
            ->get()
            ->map(function($item) {
                $date = Carbon::createFromDate($item->annee, $item->mois, 1);
                return [
                    'valeur' => $item->annee . '-' . str_pad($item->mois, 2, '0', STR_PAD_LEFT),
                    'libelle' => $date->translatedFormat('F Y')
                ];
            });

        return view('employe.pointage.historique', compact('pointages', 'moisDisponibles'));
    }


    
    
    /**
     * Trouve un poste de travail autorisé pour l'employé en fonction du code de poste
     */
    private function trouverPosteTravailParCode(Employe $employe, string $codePoste)
    {
        return $employe->postesTravail()
            ->where('code_poste', $codePoste)
            ->where('actif', true)
            ->first();
    }
    
    /**
     * Trouve un poste de travail autorisé pour l'employé en fonction de l'ID de l'appareil
     */
    private function trouverPosteTravailParDeviceId(Employe $employe, ?string $deviceId)
    {
        if (empty($deviceId)) {
            return null;
        }
        
        // D'abord, essayer de trouver un poste de travail avec ce device_id pour cet employé
        $posteTravail = $employe->postesTravail()
            ->where('device_id', $deviceId)
            ->where('actif', true)
            ->first();
        
        // Si aucun poste n'est trouvé, vérifier s'il existe un poste avec ce device_id dans le système
        // Si oui, et qu'il n'est pas encore assigné à un employé, l'assigner à cet employé
        if (!$posteTravail) {
            \Log::info('Aucun poste de travail trouvé pour le device_id: ' . $deviceId . ' et l\'employé ID: ' . $employe->id);
            
            // Enregistrer le device_id en session pour débogage
            session(['debug_device_id' => $deviceId]);
            session(['debug_employe_id' => $employe->id]);
        }
        
        return $posteTravail;
    }
    
 
}