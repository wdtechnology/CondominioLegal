<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Veiculos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Veiculos associado a unidade.
        Schema::create('veiculos', function(Blueprint $table){
            $table->id();
            $table->integer('id_unidade');
            $table->string('titulo');
            $table->string('cor');
            $table->string('placa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veiculos');
        
    }
}
