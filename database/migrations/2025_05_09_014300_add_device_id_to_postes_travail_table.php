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
        Schema::table('postes_travail', function (Blueprint $table) {
            if (!Schema::hasColumn('postes_travail', 'device_id')) {
                $table->string('device_id', 64)->nullable()->after('nom');
                $table->index('device_id');
            }
            
            if (!Schema::hasColumn('postes_travail', 'code_poste')) {
                $table->string('code_poste')->nullable()->after('nom');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postes_travail', function (Blueprint $table) {
            if (Schema::hasColumn('postes_travail', 'device_id')) {
                $table->dropIndex(['device_id']);
                $table->dropColumn('device_id');
            }
            
            if (Schema::hasColumn('postes_travail', 'code_poste')) {
                $table->dropColumn('code_poste');
            }
        });
    }
};
