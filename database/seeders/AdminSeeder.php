<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'Nom' => 'Admin',
            'Prenom' => 'System',
            'Email' => 'admin@entreprise.com',
            'MotDePasse' => Hash::make('Admin123'),
            'Rôle' => 'Admin'
        ]);
    }
}