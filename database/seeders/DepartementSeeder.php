<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;

class DepartementSeeder extends Seeder
{
    public function run()
    {
        Departement::create(['name' => 'Ressources Humaines']);
        Departement::create(['name' => 'Informatique']);
        Departement::create(['name' => 'Marketing']);
        Departement::create(['name' => 'Finance']);
        Departement::create(['name' => 'Direction']);
        Departement::create(['name' => 'Production']);
    }
}