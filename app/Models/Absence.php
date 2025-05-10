<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'employe_id',
        'date_debut',
        'date_fin',
        'motif',
        'justification',
        'document_path',
        'statut', // 'non_justifiée', 'en_attente', 'justifiée', 'rejetée'
        'commentaire_admin',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    /**
     * Obtenir l'employé associé à cette absence
     */
    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    /**
     * Vérifier si l'absence est en cours
     */
    public function estEnCours()
    {
        $aujourdhui = Carbon::today();
        return $this->date_debut->lte($aujourdhui) && $this->date_fin->gte($aujourdhui);
    }

    /**
     * Calculer la durée de l'absence en jours
     */
    public function dureeJours()
    {
        return $this->date_debut->diffInDays($this->date_fin) + 1; // +1 pour inclure le jour de fin
    }
}
