<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('type'); // Annuel, Maladie, Maternité, Sans solde
            $table->text('reason');
            $table->string('status')->default('En attente'); // En attente, Approuvé, Rejeté
            
            // Clé étrangère vers l'employé
            $table->foreignId('employee_id')
                  ->constrained('employes')
                  ->onDelete('cascade');
            
            // Clé étrangère vers l'utilisateur qui a approuvé (nullable)
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('conges');
    }
};