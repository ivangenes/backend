<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->time('duracion');
            $table->time('horaDesde');
            $table->time('horaHasta');
            $table->integer('cancha_id')->unsigned();
            $table->foreign('cancha_id')->references('id')->on('canchas');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->char('estado')->default('1');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
    
     * Reverse the migrations.
     *protected $fillable = ['fecha,duracion,horaDesde,horaHasta,cancha_id,user_id,'];
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
