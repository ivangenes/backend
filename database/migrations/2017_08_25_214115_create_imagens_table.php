<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->String('Descripcion');
            $table->char('estado')->default('1');
            $table->integer('complejo_id')->unsigned();
            $table->foreign('complejo_id')->references('id')->on('complejos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *$table->integer('complejo_id')->unsigned();
          
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagens');
    }
}
