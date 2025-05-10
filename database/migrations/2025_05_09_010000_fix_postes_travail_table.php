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
        if (Schema::hasTable('postes_travail')) {
            Schema::dropIfExists('postes_travail');
        }

        // Création de la table postes_travail avec tous les champs nécessaires
        Schema::create('postes_travail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained();
            $table->string('nom');
            $table->string('code_poste')->nullable();
            $table->string('device_id')->nullable();
            $table->string('adresse_mac')->unique();
            $table->string('adresse_ip')->nullable();
            $table->string('localisation')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postes_travail');
    }
};
