<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Boletos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Boletos/Constas destinados aos moradores.
        Schema::create('boletos', function(Blueprint $table){
            $table->id();
            $table->integer('id_unidade');
            $table->string('titulo');
            $table->string('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boletos');
        
    }
}
