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
        Schema::create('equipe_employe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Empêcher les doublons
            $table->unique(['equipe_id', 'employe_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipe_employe');
    }
};
