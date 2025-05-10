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
        Schema::create('horaire_employes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('jour_semaine'); // 1 = lundi, 2 = mardi, etc.
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            // Un employé ne peut avoir qu'un seul horaire par jour de la semaine
            $table->unique(['employe_id', 'jour_semaine']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horaire_employes');
    }
};
