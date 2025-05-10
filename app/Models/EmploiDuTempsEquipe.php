<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploiDuTempsEquipe extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'equipe_id',
        'titre',
        'description',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'lieu',
        'type_activite',
        'recurrent',
        'date_specifique'
    ];
    
    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'recurrent' => 'boolean',
        'date_specifique' => 'date'
    ];
    
    /**
     * Get the team that owns this schedule.
     */
    public function equipe()
    {
        return $this->belongsTo(Equipe::class);
    }
    
    /**
     * Get all employees that are part of the team.
     */
    public function employes()
    {
        return $this->equipe->employes();
    }
}
