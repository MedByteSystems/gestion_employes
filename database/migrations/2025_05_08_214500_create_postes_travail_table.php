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
        Schema::create('postes_travail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained();
            $table->string('nom');
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
