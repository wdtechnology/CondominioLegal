<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Reclamacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         //Reclamacoes feitas pelos moradores.
         Schema::create('reclamacoes', function(Blueprint $table){
            $table->id();
            $table->integer('id_unidade');
            $table->string('titulo');
            $table->string('status')->default('em_analise');
            $table->date('data_criacao');
            $table->text('fotos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reclamacoes');
       
    }
}
