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
        // Si la table existe déjà, on la supprime pour la recréer proprement
        if (Schema::hasTable('pointages')) {
            Schema::dropIfExists('pointages');
        }

        // Création de la table pointages avec tous les champs nécessaires
        Schema::create('pointages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained();
            $table->foreignId('poste_travail_id')->nullable()->constrained('postes_travail')->nullOnDelete();
            $table->date('date');
            $table->dateTime('heure_prevue');
            $table->dateTime('heure_reelle')->nullable();
            $table->enum('statut', ['présent', 'retard', 'absent', 'congé'])->default('présent');
            $table->integer('retard_minutes')->default(0);
            $table->string('localisation')->nullable();
            $table->string('adresse_ip')->nullable();
            $table->string('adresse_mac')->nullable();
            $table->text('commentaire')->nullable();
            $table->boolean('validé')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointages');
    }
};
