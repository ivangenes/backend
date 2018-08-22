<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanchasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canchas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion');
            $table->double('precio');
            $table->time('horaDesde');
            $table->time('horaHasta');
            $table->char('estado')->default('1');
            $table->integer('complejo_id')->unsigned();
            $table->foreign('complejo_id')->references('id')->on('complejos');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     * ;$table->foreign('rol_id')->references('id')->on('roles');
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('canchas');
    }
}
