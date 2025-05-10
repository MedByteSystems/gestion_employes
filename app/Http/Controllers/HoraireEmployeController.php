<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HoraireEmploye;
use App\Models\Employe;
use Illuminate\Support\Facades\Validator;

class HoraireEmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employe_id = $request->employe_id;
        $employe = null;
        $horaires = collect();
        
        if ($employe_id) {
            $employe = Employe::findOrFail($employe_id);
            $horaires = $employe->horaires()->orderBy('jour_semaine')->get();
        }
        
        // Récupérer tous les employés sans filtrer par 'actif' car cette colonne n'existe pas
        $employes = Employe::orderBy('first_name')->get();
        
        return view('admin.horaires.index', compact('employes', 'employe', 'horaires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employe_id = $request->employe_id;
        $employe = Employe::findOrFail($employe_id);
        
        // Jours de la semaine
        $joursSemaine = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            0 => 'Dimanche',
        ];
        
        // Jours déjà configurés
        $joursConfigures = $employe->horaires()->pluck('jour_semaine')->toArray();
        
        return view('admin.horaires.create', compact('employe', 'joursSemaine', 'joursConfigures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employe_id' => 'required|exists:employes,id',
            'jour_semaine' => 'required|integer|min:0|max:6',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Vérifier si un horaire existe déjà pour ce jour
        $exists = HoraireEmploye::where('employe_id', $request->employe_id)
            ->where('jour_semaine', $request->jour_semaine)
            ->exists();
            
        if ($exists) {
            return redirect()->back()->withErrors(['jour_semaine' => 'Un horaire existe déjà pour ce jour.'])->withInput();
        }
        
        HoraireEmploye::create([
            'employe_id' => $request->employe_id,
            'jour_semaine' => $request->jour_semaine,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'actif' => true,
        ]);
        
        return redirect()->route('admin.horaires.index', ['employe_id' => $request->employe_id])
            ->with('success', 'L\'horaire a été ajouté avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HoraireEmploye $horaire)
    {
        $employe = $horaire->employe;
        
        // Jours de la semaine
        $joursSemaine = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            0 => 'Dimanche',
        ];
        
        return view('admin.horaires.edit', compact('horaire', 'employe', 'joursSemaine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HoraireEmploye $horaire)
    {
        $validator = Validator::make($request->all(), [
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'actif' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $horaire->update([
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'actif' => $request->has('actif'),
        ]);
        
        return redirect()->route('admin.horaires.index', ['employe_id' => $horaire->employe_id])
            ->with('success', 'L\'horaire a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HoraireEmploye $horaire)
    {
        $employe_id = $horaire->employe_id;
        $horaire->delete();
        
        return redirect()->route('admin.horaires.index', ['employe_id' => $employe_id])
            ->with('success', 'L\'horaire a été supprimé avec succès.');
    }
    
    /**
     * Créer des horaires standards pour un employé
     */
    public function creerHorairesStandards(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employe_id' => 'required|exists:employes,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $employe_id = $request->employe_id;
        
        // Horaires standards (lundi au vendredi, 9h-17h)
        $horairesStandards = [
            ['jour_semaine' => 1, 'heure_debut' => '09:00', 'heure_fin' => '17:00'], // Lundi
            ['jour_semaine' => 2, 'heure_debut' => '09:00', 'heure_fin' => '17:00'], // Mardi
            ['jour_semaine' => 3, 'heure_debut' => '09:00', 'heure_fin' => '17:00'], // Mercredi
            ['jour_semaine' => 4, 'heure_debut' => '09:00', 'heure_fin' => '17:00'], // Jeudi
            ['jour_semaine' => 5, 'heure_debut' => '09:00', 'heure_fin' => '17:00'], // Vendredi
        ];
        
        $count = 0;
        foreach ($horairesStandards as $horaire) {
            // Vérifier si un horaire existe déjà pour ce jour
            $exists = HoraireEmploye::where('employe_id', $employe_id)
                ->where('jour_semaine', $horaire['jour_semaine'])
                ->exists();
                
            if (!$exists) {
                HoraireEmploye::create([
                    'employe_id' => $employe_id,
                    'jour_semaine' => $horaire['jour_semaine'],
                    'heure_debut' => $horaire['heure_debut'],
                    'heure_fin' => $horaire['heure_fin'],
                    'actif' => true,
                ]);
                
                $count++;
            }
        }
        
        return redirect()->route('admin.horaires.index', ['employe_id' => $employe_id])
            ->with('success', $count . ' horaire(s) standard(s) créé(s) avec succès.');
    }
}
