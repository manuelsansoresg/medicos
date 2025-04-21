<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultorioUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultorio_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id'); // Crear un campo para almacenar el ID del usuario
            $table->unsignedInteger('consultorio_id');
            // Definir la clave foránea
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('consultorio_id')->references('idconsultorios')->on('consultorios')->onDelete('cascade');
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
        Schema::dropIfExists('consultorio_user');
    }
}
