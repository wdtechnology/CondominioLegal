<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Animais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Animais de estimação.
        Schema::create('animais', function(Blueprint $table){
            $table->id();
            $table->integer('id_unidade');
            $table->string('nome');
            $table->string('raca');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animais');
       
    }
}
