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
        Schema::create('pointages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained();
            $table->dateTime('heure_prevue');
            $table->dateTime('heure_reelle')->nullable();
            
            // Double-escaped apostrophe
            $table->enum('statut', [
                'à l\\\'heure',  // Double backslash + escaped quote
                'retard',
                'absent'
            ])->default('à l\\\'heure');
            
            $table->integer('retard_minutes')->default(0);
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
