<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nom',
        'description',
        'responsable_id',
        'emploi_du_temps_pdf',
        'emploi_du_temps_nom'
    ];
    
    /**
     * Get the employees that belong to this team.
     */
    public function employes()
    {
        return $this->belongsToMany(Employe::class, 'equipe_employe');
    }
    
    /**
     * Get the team leader (responsible employee).
     */
    public function responsable()
    {
        return $this->belongsTo(Employe::class, 'responsable_id');
    }
    
    /**
     * Get the schedules for this team.
     */
    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTempsEquipe::class);
    }
}
