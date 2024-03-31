<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Crear un campo para almacenar el ID del usuario
            $table->integer('num_doctor')->nullable();
            $table->integer('num_auxiliar')->nullable();
            $table->integer('dias')->nullable();
            $table->decimal('costo')->nullable();
            $table->smallInteger('is_pagado')->nullable();
            $table->smallInteger('status')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('access');
    }
}
