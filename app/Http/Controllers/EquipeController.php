<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class EquipeController extends Controller
{
    /**
     * Display a listing of the teams.
     */
    public function index()
    {
        $equipes = Equipe::with(['responsable', 'employes'])->get();
        return view('equipes.index', compact('equipes'));
    }

    /**
     * Show the form for creating a new team.
     */
    public function create()
    {
        $employes = Employe::all();
        return view('equipes.create', compact('employes'));
    }

    /**
     * Store a newly created team in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsable_id' => 'nullable|exists:employes,id',
            'employes' => 'required|array',
            'employes.*' => 'exists:employes,id',
        ]);

        $equipe = Equipe::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'responsable_id' => $validated['responsable_id'],
        ]);

        $equipe->employes()->attach($validated['employes']);

        return redirect()->route('equipes.index')
            ->with('success', 'Équipe créée avec succès.');
    }

    /**
     * Display the specified team.
     */
    public function show(Equipe $equipe)
    {
        $equipe->load(['responsable', 'employes']);
        return view('equipes.show', compact('equipe'));
    }

    /**
     * Show the form for editing the specified team.
     */
    public function edit(Equipe $equipe)
    {
        $employes = Employe::all();
        $equipe->load('employes');
        $membresIds = $equipe->employes->pluck('id')->toArray();
        
        return view('equipes.edit', compact('equipe', 'employes', 'membresIds'));
    }

    /**
     * Update the specified team in storage.
     */
    public function update(Request $request, Equipe $equipe)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsable_id' => 'nullable|exists:employes,id',
            'employes' => 'required|array',
            'employes.*' => 'exists:employes,id',
        ]);

        $equipe->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'responsable_id' => $validated['responsable_id'],
        ]);

        $equipe->employes()->sync($validated['employes']);

        return redirect()->route('equipes.index')
            ->with('success', 'Équipe mise à jour avec succès.');
    }

    /**
     * Remove the specified team from storage.
     */
    public function destroy(Equipe $equipe)
    {
        $equipe->employes()->detach();
        $equipe->delete();

        return redirect()->route('equipes.index')
            ->with('success', 'Équipe supprimée avec succès.');
    }
    
    /**
     * Recherche d'employés par nom ou prénom pour l'autocomplétion.
     */
    public function searchEmployes(Request $request)
    {
        $query = $request->input('query');
        
        $employes = Employe::where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$query}%")
            ->orWhere('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('position', 'LIKE', "%{$query}%")
            ->with('departement')
            ->limit(10)
            ->get()
            ->map(function($employe) {
                return [
                    'id' => $employe->id,
                    'text' => $employe->first_name . ' ' . $employe->last_name . ' (' . ($employe->departement->name ?? 'Sans département') . ')',
                    'photo' => $employe->photo ? asset('storage/' . $employe->photo) : null
                ];
            });
            
        return response()->json($employes);
    }
    
    /**
     * Affiche le formulaire pour téléverser un emploi du temps PDF pour une équipe.
     */
    public function uploadEmploiDuTempsPdfForm(Equipe $equipe)
    {
        return view('equipes.upload-pdf', compact('equipe'));
    }
    
    /**
     * Traite le téléversement d'un emploi du temps PDF pour une équipe.
     */
    public function uploadEmploiDuTempsPdf(Request $request, Equipe $equipe)
    {
        $request->validate([
            'emploi_du_temps_pdf' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);
        
        // Suppression de l'ancien fichier s'il existe
        if ($equipe->emploi_du_temps_pdf && file_exists(public_path('storage/emplois-du-temps/' . $equipe->emploi_du_temps_pdf))) {
            unlink(public_path('storage/emplois-du-temps/' . $equipe->emploi_du_temps_pdf));
        }
        
        // Génération d'un nom de fichier unique
        $fileName = time() . '_' . $equipe->id . '.pdf';
        
        // Stockage du fichier
        $request->file('emploi_du_temps_pdf')->storeAs('public/emplois-du-temps', $fileName);
        
        // Mise à jour de l'équipe
        $equipe->update([
            'emploi_du_temps_pdf' => $fileName,
            'emploi_du_temps_nom' => $request->file('emploi_du_temps_pdf')->getClientOriginalName(),
        ]);
        
        return redirect()->route('equipes.show', $equipe)
            ->with('success', 'Emploi du temps PDF téléversé avec succès.');
    }
    
    /**
     * Télécharge l'emploi du temps PDF d'une équipe (pour les administrateurs).
     */
    public function downloadEmploiDuTempsPdf(Equipe $equipe)
    {
        if (!$equipe->emploi_du_temps_pdf) {
            return redirect()->back()->with('error', 'Aucun fichier PDF téléversé.');
        }

        $filePath = storage_path('app/public/public/emplois-du-temps/' . $equipe->emploi_du_temps_pdf);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Fichier introuvable.');
        }

        $nom = $equipe->emploi_du_temps_nom ?? 'emploi-du-temps.pdf';
        
        return response()->download($filePath, $nom, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nom . '"'
        ]);
    }
    
    /**
     * Télécharge l'emploi du temps PDF d'une équipe (pour les employés).
     * Vérifie que l'employé connecté appartient bien à l'équipe.
     */
    public function downloadEmploiDuTempsPdfEmploye(Equipe $equipe)
    {
        // Vérifier que l'employé connecté appartient à cette équipe
        $employe = auth()->user()->employe;
        $appartientEquipe = $employe->equipes()->where('equipes.id', $equipe->id)->exists();
        
        if (!$appartientEquipe) {
            return redirect()->route('Employé.emplois-du-temps')
                ->with('error', 'Vous n\'avez pas accès à cet emploi du temps.');
        }
        
        if (!$equipe->emploi_du_temps_pdf) {
            return redirect()->route('Employé.emplois-du-temps')
                ->with('error', 'Aucun fichier PDF n\'a été téléversé pour cette équipe.');
        }
        
        $filePath = storage_path('app/public/public/emplois-du-temps/' . $equipe->emploi_du_temps_pdf);
        
        if (!file_exists($filePath)) {
            return redirect()->route('Employé.emplois-du-temps')
                ->with('error', 'Le fichier PDF n\'a pas été trouvé sur le serveur.');
        }
        
        $nom = $equipe->emploi_du_temps_nom ?? 'emploi-du-temps.pdf';
        
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nom . '"'
        ]);
    }
    
    /**
     * Génère un PDF dynamique de l'emploi du temps d'une équipe.
     */
    public function generatePdfEmploiDuTemps(Equipe $equipe)
    {
        // Chargement de la vue avec les données de l'équipe
        $html = View::make('pdf.emploi-du-temps', compact('equipe'))->render();
        
        // Configuration de DomPDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Génération du nom de fichier
        $filename = 'emploi-du-temps-' . $equipe->nom . '.pdf';
        
        // Téléchargement du PDF
        return $dompdf->stream($filename, ['Attachment' => true]);
    }
}
