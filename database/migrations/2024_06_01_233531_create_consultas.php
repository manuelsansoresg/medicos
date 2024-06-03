<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_cita_id')->nullable();
            $table->bigInteger('paciente_id')->nullable();
            $table->bigInteger('idusrregistra')->nullable();
            $table->date('fecha')->nullable();
            $table->string('motivo');
            $table->string('exploracion')->nullable();
            $table->string('peso')->nullable();
            $table->text('receta')->nullable();
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
        Schema::dropIfExists('consultas');
    }
}
