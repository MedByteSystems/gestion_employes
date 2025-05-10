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
        Schema::table('equipes', function (Blueprint $table) {
            $table->string('emploi_du_temps_pdf')->nullable()->after('responsable_id');
            $table->string('emploi_du_temps_nom')->nullable()->after('emploi_du_temps_pdf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipes', function (Blueprint $table) {
            $table->dropColumn('emploi_du_temps_pdf');
            $table->dropColumn('emploi_du_temps_nom');
        });
    }
};
