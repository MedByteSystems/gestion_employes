<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AbsenceController;

class DetecterAbsences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absences:detecter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Détecte automatiquement les absences des employés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new AbsenceController();
        $controller->detecterAbsences();
        $this->info('Détection des absences effectuée avec succès.');
    }
}
