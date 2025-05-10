<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployesTable extends Migration
{
    public function up()
{
    Schema::create('employes', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('cin')->unique();
        $table->string('marital_status');
        $table->date('birth_date');
        $table->string('gender');
        $table->string('position');
        $table->date('hire_date');
        $table->string('photo')->nullable();
        $table->foreignId('departement_id')->constrained();
        $table->foreignId('user_id')->constrained();
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('employes');
    }
}
