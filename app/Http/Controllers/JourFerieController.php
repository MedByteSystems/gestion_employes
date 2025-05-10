<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JourFerie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class JourFerieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $joursFeries = JourFerie::orderBy('date', 'desc')->paginate(10);
        return view('admin.jours_feries.index', compact('joursFeries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jours_feries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|unique:jour_feries,date',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        JourFerie::create([
            'date' => Carbon::parse($request->date),
            'nom' => $request->nom,
            'description' => $request->description,
        ]);
        
        return redirect()->route('admin.jours-feries.index')
            ->with('success', 'Le jour férié a été ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JourFerie $jourFerie)
    {
        return view('admin.jours_feries.show', compact('jourFerie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JourFerie $jourFerie)
    {
        return view('admin.jours_feries.edit', compact('jourFerie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JourFerie $jourFerie)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|unique:jour_feries,date,' . $jourFerie->id,
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $jourFerie->update([
            'date' => Carbon::parse($request->date),
            'nom' => $request->nom,
            'description' => $request->description,
        ]);
        
        return redirect()->route('admin.jours-feries.index')
            ->with('success', 'Le jour férié a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JourFerie $jourFerie)
    {
        $jourFerie->delete();
        
        return redirect()->route('admin.jours-feries.index')
            ->with('success', 'Le jour férié a été supprimé avec succès.');
    }
    
    /**
     * Import des jours fériés pour une année donnée
     */
    public function importerJoursFeries(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'annee' => 'required|integer|min:2023|max:2030',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $annee = $request->annee;
        
        // Liste des jours fériés en France pour l'année donnée
        $joursFeries = [
            ['date' => $annee . '-01-01', 'nom' => 'Jour de l\'an', 'description' => 'Premier jour de l\'année'],
            ['date' => $annee . '-05-01', 'nom' => 'Fête du travail', 'description' => 'Journée internationale des travailleurs'],
            ['date' => $annee . '-05-08', 'nom' => 'Victoire 1945', 'description' => 'Commémoration de la fin de la Seconde Guerre mondiale en Europe'],
            ['date' => $annee . '-07-14', 'nom' => 'Fête nationale', 'description' => 'Commémoration de la prise de la Bastille'],
            ['date' => $annee . '-08-15', 'nom' => 'Assomption', 'description' => 'Fête catholique célébrant l\'assomption de Marie'],
            ['date' => $annee . '-11-01', 'nom' => 'Toussaint', 'description' => 'Fête de tous les saints'],
            ['date' => $annee . '-11-11', 'nom' => 'Armistice 1918', 'description' => 'Commémoration de l\'armistice de la Première Guerre mondiale'],
            ['date' => $annee . '-12-25', 'nom' => 'Noël', 'description' => 'Fête de la nativité de Jésus-Christ'],
        ];
        
        // Calcul de Pâques (algorithme de Butcher)
        $a = $annee % 19;
        $b = floor($annee / 100);
        $c = $annee % 100;
        $d = floor($b / 4);
        $e = $b % 4;
        $f = floor(($b + 8) / 25);
        $g = floor(($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = floor($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = floor(($a + 11 * $h + 22 * $l) / 451);
        $month = floor(($h + $l - 7 * $m + 114) / 31);
        $day = (($h + $l - 7 * $m + 114) % 31) + 1;
        $paques = Carbon::create($annee, $month, $day);
        
        // Ajouter les jours fériés mobiles basés sur Pâques
        $joursFeries[] = ['date' => $paques->format('Y-m-d'), 'nom' => 'Pâques', 'description' => 'Fête chrétienne célébrant la résurrection de Jésus-Christ'];
        $joursFeries[] = ['date' => $paques->copy()->addDays(1)->format('Y-m-d'), 'nom' => 'Lundi de Pâques', 'description' => 'Lendemain de Pâques'];
        $joursFeries[] = ['date' => $paques->copy()->addDays(39)->format('Y-m-d'), 'nom' => 'Ascension', 'description' => 'Fête chrétienne célébrant l\'ascension de Jésus-Christ'];
        $joursFeries[] = ['date' => $paques->copy()->addDays(50)->format('Y-m-d'), 'nom' => 'Pentecôte', 'description' => 'Fête chrétienne célébrant la descente du Saint-Esprit'];
        $joursFeries[] = ['date' => $paques->copy()->addDays(51)->format('Y-m-d'), 'nom' => 'Lundi de Pentecôte', 'description' => 'Lendemain de la Pentecôte'];
        
        $count = 0;
        foreach ($joursFeries as $jourFerie) {
            // Vérifier si le jour férié existe déjà
            $exists = JourFerie::where('date', $jourFerie['date'])->exists();
            
            if (!$exists) {
                JourFerie::create($jourFerie);
                $count++;
            }
        }
        
        return redirect()->route('admin.jours-feries.index')
            ->with('success', $count . ' jour(s) férié(s) importé(s) avec succès pour l\'année ' . $annee . '.');
    }
}
