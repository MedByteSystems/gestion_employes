<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = ['name'];  // Changed from 'Nom' to 'name'

    public function employes()
    {
        return $this->hasMany(Employe::class);
    }

    // Removed users() relationship as there's no departement_id in users table
}