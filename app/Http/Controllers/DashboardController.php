<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Employe;
use App\Models\Conge;
use App\Models\Pointage;
use App\Models\Absence;
use App\Models\Equipe;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // Données pour le graphique de répartition des employés par département
        $departements = Departement::withCount('employes')->orderBy('name')->get();
        $departementNames = $departements->pluck('name')->toArray();
        $departementCounts = $departements->pluck('employes_count')->toArray();
        
        // Données pour le graphique des absences par mois
        $months = [];
        $unjustifiedData = [];
        $justifiedData = [];
        $pendingData = [];
        $rejectedData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $unjustifiedData[] = \App\Models\Absence::where('statut', 'non_justifiée')
                ->where(function($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('date_debut', [$startOfMonth, $endOfMonth])
                          ->orWhereBetween('date_fin', [$startOfMonth, $endOfMonth])
                          ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                              $q->where('date_debut', '<', $startOfMonth)
                                ->where('date_fin', '>', $endOfMonth);
                          });
                })
                ->count();
                
            $justifiedData[] = \App\Models\Absence::where('statut', 'justifiée')
                ->where(function($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('date_debut', [$startOfMonth, $endOfMonth])
                          ->orWhereBetween('date_fin', [$startOfMonth, $endOfMonth])
                          ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                              $q->where('date_debut', '<', $startOfMonth)
                                ->where('date_fin', '>', $endOfMonth);
                          });
                })
                ->count();
                
            $pendingData[] = \App\Models\Absence::where('statut', 'en_attente')
                ->where(function($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('date_debut', [$startOfMonth, $endOfMonth])
                          ->orWhereBetween('date_fin', [$startOfMonth, $endOfMonth])
                          ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                              $q->where('date_debut', '<', $startOfMonth)
                                ->where('date_fin', '>', $endOfMonth);
                          });
                })
                ->count();
                
            $rejectedData[] = \App\Models\Absence::where('statut', 'rejetée')
                ->where(function($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('date_debut', [$startOfMonth, $endOfMonth])
                          ->orWhereBetween('date_fin', [$startOfMonth, $endOfMonth])
                          ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                              $q->where('date_debut', '<', $startOfMonth)
                                ->where('date_fin', '>', $endOfMonth);
                          });
                })
                ->count();
        }
        
        // Récupérer les équipes avec leurs membres et responsables
        $equipes = Equipe::with(['employes', 'responsable'])->get();
        $equipeCount = $equipes->count();
        $recentEquipes = Equipe::with(['employes', 'responsable'])->latest()->take(3)->get();
        
        return view('admin.dashboard', [
            'employeeCount' => Employe::count(),
            'departmentCount' => Departement::count(),
            'pendingLeaveCount' => Conge::where('status', 'En attente')->count(),
            'onLeaveCount' => Conge::where('status', 'Approuvé')
                                ->whereDate('start_date', '<=', now())
                                ->whereDate('end_date', '>=', now())
                                ->count(),
            'recentConges' => Conge::with('employee')
                                ->latest()
                                ->take(5)
                                ->get(),
            'recentEmployees' => Employe::latest()
                                    ->take(3)
                                    ->get(),
            'departements' => $departements,
            'departementNames' => $departementNames,
            'departementCounts' => $departementCounts,
            'months' => $months,
            'unjustifiedData' => $unjustifiedData,
            'justifiedData' => $justifiedData,
            'pendingData' => $pendingData,
            'rejectedData' => $rejectedData,
            'equipes' => $equipes,
            'equipeCount' => $equipeCount,
            'recentEquipes' => $recentEquipes
        ]);
    }

    public function employeDashboard()
    {
        $user = auth()->user();
        $employee = $user->employe;
        
        // Vérifier si l'employé existe
        if (!$employee) {
            // Rediriger vers une page d'erreur ou afficher un message
            session()->flash('error', 'Votre compte utilisateur n\'a pas encore été associé à un profil employé. Veuillez contacter l\'administrateur.');
            
            // Déconnecter l'utilisateur car il ne peut pas accéder au tableau de bord
            auth()->logout();
            
            // Rediriger vers la page de connexion
            return redirect()->route('login');
        }
        
        // Récupérer le dernier pointage de l'employé pour aujourd'hui
        $dernierPointage = Pointage::where('employe_id', $employee->id)
                                ->whereDate('date', Carbon::today())
                                ->latest()
                                ->first();
        
        // Récupérer les 5 derniers pointages pour l'activité récente
        $derniersPointages = Pointage::where('employe_id', $employee->id)
                                ->latest()
                                ->take(5)
                                ->get();
        
        // Récupérer les absences de l'employé
        $absencesNonJustifiees = Absence::where('employe_id', $employee->id)
                                    ->where('statut', 'non_justifiée')
                                    ->count();
        
        $absencesEnAttente = Absence::where('employe_id', $employee->id)
                                ->where('statut', 'en_attente')
                                ->count();
        
        $dernieresAbsences = Absence::where('employe_id', $employee->id)
                                ->latest('date_debut')
                                ->take(3)
                                ->get();
        
        // Calculer le nombre de jours de congés disponibles
        $availableLeaveDays = 25; // Valeur par défaut, à remplacer par la logique métier réelle
        
        return view('employe.dashboard', [
            'pendingRequestsCount' => Conge::where('employee_id', $employee->id)
                                        ->where('status', 'En attente')
                                        ->count(),
            
            'nextApprovedLeave' => Conge::where('employee_id', $employee->id)
                                      ->where('status', 'Approuvé')
                                      ->whereDate('end_date', '>=', now())
                                      ->orderBy('start_date')
                                      ->first(),
                                      
            'dernierPointage' => $dernierPointage,
            'derniersPointages' => $derniersPointages,
            'availableLeaveDays' => $availableLeaveDays,
            'absencesNonJustifiees' => $absencesNonJustifiees,
            'absencesEnAttente' => $absencesEnAttente,
            'dernieresAbsences' => $dernieresAbsences
        ]);
}
}