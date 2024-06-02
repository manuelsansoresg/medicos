<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCitas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_citas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('doctor_id');
            $table->bigInteger('paciente_id');
            $table->time('hora');
            $table->date('fecha');
            $table->text('motivo')->nullable();
            $table->bigInteger('id_consultorio')->nullable();
            $table->bigInteger('id_clinica')->nullable();
            $table->smallInteger('status')->nullable();
            $table->bigInteger('consulta_asignado_id')->nullable();
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
        Schema::dropIfExists('user_citas');
    }
}
