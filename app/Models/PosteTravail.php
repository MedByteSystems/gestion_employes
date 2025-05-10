<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosteTravail extends Model
{
    use HasFactory;
    
    protected $table = 'postes_travail';
    
    protected $fillable = [
        'employe_id',
        'nom',
        'code_poste',
        'device_id',
        'adresse_mac',
        'adresse_ip',
        'localisation',
        'actif'
    ];
    
    protected $casts = [
        'actif' => 'boolean',
    ];
    
    /**
     * Get the employee that owns the workstation.
     */
    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }
}
