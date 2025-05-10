<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmploiDuTempsEquipeController extends Controller
{
    /**
     * Display all teams with links to their schedules for admin.
     */
    public function adminListeEquipes()
    {
        $equipes = Equipe::all();
        return view('emplois-du-temps.liste-equipes', compact('equipes'));
    }
    
    /**
     * Display all schedules for the teams that the employee belongs to.
     */
    public function employeEmploisDuTemps()
    {
        $employe = Auth::user()->employe;
        $equipes = $employe->equipes;
        
        return view('emplois-du-temps.employe', compact('equipes', 'employe'));
    }
}
