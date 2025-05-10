<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pointages', function (Blueprint $table) {
            // Vérifier si les colonnes existent avant de les ajouter
            if (!Schema::hasColumn('pointages', 'date')) {
                $table->date('date')->after('employe_id');
            }
            
            if (!Schema::hasColumn('pointages', 'localisation')) {
                $table->string('localisation')->nullable()->after('retard_minutes');
            }
            
            if (!Schema::hasColumn('pointages', 'adresse_ip')) {
                $table->string('adresse_ip')->nullable()->after('localisation');
            }
            
            if (!Schema::hasColumn('pointages', 'adresse_mac')) {
                $table->string('adresse_mac')->nullable()->after('adresse_ip');
            }
            
            if (!Schema::hasColumn('pointages', 'commentaire')) {
                $table->text('commentaire')->nullable()->after('adresse_mac');
            }
            
            if (!Schema::hasColumn('pointages', 'validé')) {
                $table->boolean('validé')->default(false)->after('commentaire');
            }
            
            if (!Schema::hasColumn('pointages', 'poste_travail_id')) {
                $table->foreignId('poste_travail_id')->nullable()->after('employe_id')->constrained('postes_travail')->nullOnDelete();
            }
            
            // Modification du champ statut pour simplifier les valeurs si la colonne existe
            // Note: On ne peut pas modifier directement une colonne enum, on doit utiliser une migration séparée
            // Cette partie sera gérée par la migration 2025_05_09_010100_fix_pointages_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pointages', function (Blueprint $table) {
            // Suppression des nouveaux champs
            $table->dropColumn(['date', 'localisation', 'adresse_ip', 'adresse_mac', 'commentaire', 'validé']);
            
            // Suppression du champ statut modifié
            $table->dropColumn('statut');
        });
        
        // Recréation du champ statut avec les anciennes valeurs
        Schema::table('pointages', function (Blueprint $table) {
            $table->enum('statut', ['à l\\\'heure', 'retard', 'absent'])->default('à l\\\'heure')->after('heure_reelle');
        });
    }
};
