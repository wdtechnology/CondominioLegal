<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Reservas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Indisponibilizer as areas de lazer em determinado dia.
        Schema::create('reservas', function(Blueprint $table){
            $table->id();
            $table->integer('id_unidade');
            $table->integer('id_area');
            $table->datetime('data_reserva');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
