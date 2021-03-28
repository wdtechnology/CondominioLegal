<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Areas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         //Areas de lazer do condominio
         Schema::create('areas', function(Blueprint $table){
            $table->id();
            $table->integer('liberado')->default(1);
            $table->string('titulo');
            $table->string('capa');
            $table->string('dia');
            $table->time('data_abertura');
            $table->time('data_fechamento');
        });

        //Indisponibilizer as areas de lazer em determinado dia.
        Schema::create('areasdiamanutencao', function(Blueprint $table){
            $table->id();
            $table->integer('id_area');
            $table->date('dia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
        Schema::dropIfExists('areasdiamanutencao');
        
    }
}
