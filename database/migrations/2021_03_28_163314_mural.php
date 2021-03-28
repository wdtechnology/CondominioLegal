<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mural extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Mural de avisos.
        Schema::create('mural', function(Blueprint $table){
            $table->id();
            $table->string('titulo');
            $table->string('descricao');
            $table->datetime('data_criacao');
        });

        //Likes dos avisos.
        Schema::create('murallikes', function(Blueprint $table){
            $table->id();
            $table->integer('id_mural');
            $table->integer('id_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mural');
        Schema::dropIfExists('murallikes');
        
    }
}
