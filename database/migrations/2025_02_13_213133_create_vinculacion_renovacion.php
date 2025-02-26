<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVinculacionRenovacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinculacion_solicitud', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitudId')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // el usuario al que se le asigna el permiso
            $table->unsignedBigInteger('idusrregistra')->nullable(); // usuario el que tiene iniciado la sesion
            $table->bigInteger('idRel')->nullable();
            $table->bigInteger('catalog_prices_id')->nullable();

            $table->foreign('solicitudId')->references('id')->on('solicitudes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('idusrregistra')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vinculacion_solicitud');
    }
}
