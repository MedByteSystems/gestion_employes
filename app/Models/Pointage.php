<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pointage extends Model
{
    use HasFactory;

    protected $fillable = [
        'employe_id',
        'poste_travail_id',
        'date',
        'heure_pointage',
        'heure_reelle',
        'statut',
        'retard_minutes',
        'localisation',
        'adresse_ip',
        'adresse_mac',
        'commentaire',
        'validé'
    ];

    protected $casts = [
        'date' => 'date',
        'heure_prevue' => 'datetime',
        'heure_reelle' => 'datetime',
        'validé' => 'boolean',
    ];

    /**
     * Get the employee that owns the pointage.
     */
    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }
    
    /**
     * Get the workstation that was used for this pointage.
     */
    public function posteTravail()
    {
        return $this->belongsTo(PosteTravail::class);
    }
}
