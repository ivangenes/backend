<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Users', function (Blueprint $table) 
        {
            $table->string('dni')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('telefono')->nullable()->change();
            $table->string('domicilio')->nullable()->change();
            $table->string('estado') ->nullnable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Users', function (Blueprint $table) 
        {
           $table->dropColumn('dni');
           $table->dropColumn('name');
           $table->dropColumn('telefono');
           $table->dropColumn('domicilio');
           $table->dropColumn('estado');
        });
    }
}
