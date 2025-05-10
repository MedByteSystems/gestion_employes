<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\AdminPointageController;
use App\Http\Controllers\PosteTravailController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\JourFerieController;
use App\Http\Controllers\HoraireEmployeController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\EmploiDuTempsEquipeController;
use Illuminate\Support\Facades\Auth;



// Authentication Routes
require __DIR__.'/auth.php';

// Redirect Root to Login
Route::redirect('/', '/login');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Registration Routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Admin Routes (capital A)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('Admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // Departments
    Route::resource('departements', DepartementController::class)->names([
        'index' => 'departements.index',
        'create' => 'departements.create',
        'store' => 'departements.store',
        'show' => 'departements.show',
        'edit' => 'departements.edit',
        'update' => 'departements.update',
        'destroy' => 'departements.destroy'
    ]);

    // Employees
    Route::resource('employes', EmployeController::class)->names([
        'index' => 'employes.index',
        'create' => 'employes.create',
        'store' => 'employes.store',
        'show' => 'employes.show',
        'edit' => 'employes.edit', 
        'update' => 'employes.update',
        'destroy' => 'employes.destroy'
    ]);
    
    Route::get('/employes/create', [EmployeController::class, 'create'])->name('employes.create');
    Route::post('/employes', [EmployeController::class, 'store'])->name('employes.store');

    // Leave Management
    Route::get('/conges', [CongeController::class, 'index'])->name('conges.index');
    Route::patch('/conges/{conge}', [CongeController::class, 'update'])->name('conges.update');
    Route::get('/admin/pointages', [PointageController::class, 'index'])->name('pointages.index');

});

// Manager Routes (capital M)
Route::prefix('manager')->middleware(['auth', 'manager'])->name('Manager.')->group(function () {
    Route::get('/dashboard', function () {
        return view('manager.dashboard');
    })->name('dashboard');

    // Employees
    Route::resource('employes', EmployeController::class)->names([
        'index' => 'employes.index',
        'create' => 'employes.create',
        'store' => 'employes.store',
        'show' => 'employes.show',
        'edit' => 'employes.edit',
        'update' => 'employes.update',
        'destroy' => 'employes.destroy'
    ]);

    Route::get('/conges', [CongeController::class, 'index'])->name('conges.index');
    Route::patch('/conges/{conge}', [CongeController::class, 'update'])->name('conges.update');
});

// Employee Routes (with French accent)
Route::prefix('employe')->middleware(['auth', 'employe'])->name('Employé.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'employeDashboard'])->name('dashboard');
   
    // Congés
    Route::get('/conges/create', [CongeController::class, 'create'])->name('conges.create');
    Route::post('/conges', [CongeController::class, 'store'])->name('conges.store');
    
    // Pointage
    Route::get('/pointage', [PointageController::class, 'index'])->name('pointage.index');
    Route::post('/pointage/pointer', [PointageController::class, 'store'])->name('pointage.pointer');
    Route::get('/pointage/historique', [PointageController::class, 'historique'])->name('pointage.historique');
    
    // Emplois du temps des équipes
    Route::get('/mes-emplois-du-temps', [EmploiDuTempsEquipeController::class, 'employeEmploisDuTemps'])->name('emplois-du-temps');
    Route::get('/equipes/{equipe}/download-pdf', [EquipeController::class, 'downloadEmploiDuTempsPdfEmploye'])->name('equipes.download-pdf');
    
    // Route pour la vérification des appareils supprimée - Nous utilisons maintenant device_id directement
    
    // Absences
    Route::get('/absences', [AbsenceController::class, 'index'])->name('absences.index');
    Route::get('/absences/create', [AbsenceController::class, 'create'])->name('absences.create');
    Route::post('/absences', [AbsenceController::class, 'store'])->name('absences.store');
    Route::get('/absences/{absence}', [AbsenceController::class, 'show'])->name('absences.show');
});



Route::middleware('auth')->get('/dashboard', function () {
    $role = Auth::user()->role;
    return redirect()->route($role . '.dashboard');
})->name('dashboard');

Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route(auth()->user()->role . '.dashboard')
        : redirect('/login');
});



Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');


// Ces routes sont maintenant définies dans le groupe 'Employé' ci-dessus

// Pour les admins
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Routes pour les pointages
    Route::get('/pointages', [AdminPointageController::class, 'index'])->name('admin.pointages');
    Route::get('/pointages/employe/{employe}', [AdminPointageController::class, 'details'])->name('admin.pointages.employe');
    Route::get('/pointages/date', [AdminPointageController::class, 'pointagesParDate'])->name('admin.pointages.date');
    Route::get('/pointages/statistiques', [AdminPointageController::class, 'statistiques'])->name('admin.pointages.statistiques');
    Route::patch('/pointages/{pointage}/valider', [AdminPointageController::class, 'validerPointage'])->name('admin.pointages.valider');
    
    // Routes pour la gestion des postes de travail
    Route::resource('postes-travail', PosteTravailController::class)->names([
        'index' => 'admin.postes-travail.index',
        'create' => 'admin.postes-travail.create',
        'store' => 'admin.postes-travail.store',
        'edit' => 'admin.postes-travail.edit',
        'update' => 'admin.postes-travail.update',
        'destroy' => 'admin.postes-travail.destroy'
    ]);
    
    // Route pour voir les postes de travail d'un employé spécifique
    Route::get('/employes/{employe}/postes-travail', [PosteTravailController::class, 'employePostes'])->name('admin.employes.postes-travail');
    
    // Route pour la recherche d'employés (autocomplétion)
    Route::match(['get', 'post'], '/search-employes', [PosteTravailController::class, 'searchEmployes'])->name('admin.search-employes');
    
    // Route pour générer une adresse MAC unique
    Route::post('/postes-travail/generer-mac', [PosteTravailController::class, 'genererMac'])->name('admin.postes-travail.generer-mac');
    
    // Routes pour la gestion des absences
    Route::get('/absences/dashboard', [AbsenceController::class, 'dashboard'])->name('admin.absences.dashboard');
    Route::get('/absences/detecter', [AbsenceController::class, 'detecterAbsences'])->name('admin.absences.detecter');
    Route::get('/absences', [AbsenceController::class, 'adminIndex'])->name('admin.absences.index');
    Route::get('/absences/{absence}', [AbsenceController::class, 'adminShow'])->name('admin.absences.show');
    Route::put('/absences/{absence}/traiter', [AbsenceController::class, 'traiter'])->name('admin.absences.traiter');
    
    // Routes pour la gestion des jours fériés
    Route::get('/jours-feries', [JourFerieController::class, 'index'])->name('admin.jours-feries.index');
    Route::get('/jours-feries/create', [JourFerieController::class, 'create'])->name('admin.jours-feries.create');
    Route::post('/jours-feries', [JourFerieController::class, 'store'])->name('admin.jours-feries.store');
    Route::get('/jours-feries/{jourFerie}', [JourFerieController::class, 'show'])->name('admin.jours-feries.show');
    Route::get('/jours-feries/{jourFerie}/edit', [JourFerieController::class, 'edit'])->name('admin.jours-feries.edit');
    Route::put('/jours-feries/{jourFerie}', [JourFerieController::class, 'update'])->name('admin.jours-feries.update');
    Route::delete('/jours-feries/{jourFerie}', [JourFerieController::class, 'destroy'])->name('admin.jours-feries.destroy');
    Route::post('/jours-feries/importer', [JourFerieController::class, 'importerJoursFeries'])->name('admin.jours-feries.importer');
    
    // Routes pour la gestion des horaires personnalisés
    Route::resource('horaires', HoraireEmployeController::class)->except(['show'])->names([
        'index' => 'admin.horaires.index',
        'create' => 'admin.horaires.create',
        'store' => 'admin.horaires.store',
        'edit' => 'admin.horaires.edit',
        'update' => 'admin.horaires.update',
        'destroy' => 'admin.horaires.destroy'
    ]);
    Route::post('/horaires/standards', [HoraireEmployeController::class, 'creerHorairesStandards'])->name('admin.horaires.standards');
    
    // Routes pour la gestion des équipes
    Route::resource('equipes', EquipeController::class)->names([
        'index' => 'equipes.index',
        'create' => 'equipes.create',
        'store' => 'equipes.store',
        'show' => 'equipes.show',
        'edit' => 'equipes.edit',
        'update' => 'equipes.update',
        'destroy' => 'equipes.destroy'
    ]);
    
    // Routes pour la gestion des fichiers PDF d'emploi du temps
    Route::get('/equipes/{equipe}/upload-pdf', [EquipeController::class, 'uploadEmploiDuTempsPdfForm'])->name('equipes.upload-pdf-form');
    Route::post('/equipes/{equipe}/upload-pdf', [EquipeController::class, 'uploadEmploiDuTempsPdf'])->name('equipes.upload-pdf');
    Route::get('/equipes/{equipe}/download-pdf', [EquipeController::class, 'downloadEmploiDuTempsPdf'])->name('equipes.download-pdf');
    
    // Route pour la liste des équipes avec leurs emplois du temps (pour l'admin)
    Route::get('/emplois-du-temps-equipes', [EmploiDuTempsEquipeController::class, 'adminListeEquipes'])->name('admin.emplois-du-temps-equipes');
    
    // Route pour la recherche d'employés (autocomplétion)
    Route::get('/search-employes', [EquipeController::class, 'searchEmployes'])->name('search-employes');
});

