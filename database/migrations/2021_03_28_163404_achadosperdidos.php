<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Achadosperdidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       //Achados e perdidos
       Schema::create('achadosperdidos', function(Blueprint $table){
        $table->id();
        $table->string('status')->default('perdido');
        $table->string('foto');
        $table->string('descricao');
        $table->string('local');
        $table->date('data_criacao');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('achadosperdidos');
       
    }
}
