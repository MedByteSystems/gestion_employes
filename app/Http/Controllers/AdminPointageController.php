<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\Departement;
use App\Models\Pointage;
use App\Models\PosteTravail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminPointageController extends Controller
{
    public function index(Request $request)
    {
        $employes = Employe::with(['pointages', 'departement', 'postesTravail'])
            ->when($request->departement, function($query) use ($request) {
                return $query->where('departement_id', $request->departement);
            })
            ->paginate(10);

        $departements = Departement::all();

        return view('admin.pointages.index', compact('employes', 'departements'));
    }
    
    /**
     * Affiche les détails des pointages d'un employé spécifique
     */
    public function details(Employe $employe)
    {
        $pointages = Pointage::where('employe_id', $employe->id)
            ->orderBy('date', 'desc')
            ->orderBy('heure_pointage', 'desc')
            ->paginate(15);
            
        return view('admin.pointages.details', compact('employe', 'pointages'));
    }
    
    /**
     * Valide un pointage manuellement
     */
    public function validerPointage(Pointage $pointage)
    {
        $pointage->validé = true;
        $pointage->save();
        
        return redirect()->back()->with('success', 'Pointage validé avec succès');
    }
    
    /**
     * Affiche la liste des pointages pour une date spécifique
     */
    public function pointagesParDate(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        
        $pointages = Pointage::with('employe')
            ->whereDate('date', $date)
            ->orderBy('heure_pointage')
            ->paginate(20);
            
        return view('admin.pointages.par_date', compact('pointages', 'date'));
    }
    
    /**
     * Affiche les statistiques de pointage
     */
    public function statistiques(Request $request)
    {
        $periode = $request->periode ?? 'mois';
        $debut = null;
        $fin = Carbon::today();
        
        switch ($periode) {
            case 'semaine':
                $debut = Carbon::today()->subDays(7);
                break;
            case 'mois':
                $debut = Carbon::today()->subMonth();
                break;
            case 'trimestre':
                $debut = Carbon::today()->subMonths(3);
                break;
            default:
                $debut = Carbon::today()->subMonth();
        }
        
        // Statistiques globales
        $totalPointages = Pointage::whereBetween('created_at', [$debut, $fin])->count();
        $totalRetards = Pointage::whereBetween('created_at', [$debut, $fin])->where('retard_minutes', '>', 0)->count();
        $totalAbsences = Pointage::whereBetween('created_at', [$debut, $fin])->whereNull('heure_reelle')->count();
        
        // Statistiques par département
        $statsDepartements = Departement::withCount([
            'employes',
            'employes as pointages_count' => function ($query) use ($debut, $fin) {
                $query->whereHas('pointages', function ($q) use ($debut, $fin) {
                    $q->whereBetween('created_at', [$debut, $fin]);
                });
            },
            'employes as retards_count' => function ($query) use ($debut, $fin) {
                $query->whereHas('pointages', function ($q) use ($debut, $fin) {
                    $q->whereBetween('created_at', [$debut, $fin])->where('retard_minutes', '>', 0);
                });
            }
        ])->get();
        
        return view('admin.pointages.statistiques', compact(
            'totalPointages', 
            'totalRetards', 
            'totalAbsences', 
            'statsDepartements',
            'periode',
            'debut',
            'fin'
        ));
    }
}