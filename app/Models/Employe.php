<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'cin',
        'marital_status',
        'birth_date',
        'gender',
        'position',
        'hire_date',
        'photo',
        'departement_id',
        'user_id'
    ];

    protected $casts = [
        'hire_date' => 'datetime',
        'birth_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }
    public function pointages()
    {
        return $this->hasMany(Pointage::class);
    }
    
    /**
     * Get the workstations associated with the employee.
     */
    public function postesTravail()
    {
        return $this->hasMany(PosteTravail::class);
    }
    
    /**
     * Get the absences associated with the employee.
     */
    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
    
    /**
     * Get the custom schedules associated with the employee.
     */
    public function horaires()
    {
        return $this->hasMany(HoraireEmploye::class);
    }
    
    /**
     * Get the teams that the employee belongs to.
     */
    public function equipes()
    {
        return $this->belongsToMany(Equipe::class, 'equipe_employe');
    }
    
    /**
     * Get the teams that the employee leads.
     */
    public function equipesResponsable()
    {
        return $this->hasMany(Equipe::class, 'responsable_id');
    }
}