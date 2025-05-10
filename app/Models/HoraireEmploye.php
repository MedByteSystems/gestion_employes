<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoraireEmploye extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employe_id',
        'jour_semaine', // 1 = lundi, 2 = mardi, etc.
        'heure_debut',
        'heure_fin',
        'actif'
    ];
    
    protected $casts = [
        'actif' => 'boolean',
    ];
    
    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }
}
