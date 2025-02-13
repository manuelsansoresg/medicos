<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // el usuario al que se le asigna el permiso
            $table->unsignedBigInteger('idusrregistra')->nullable(); // usuario el que tiene iniciado la sesion
            $table->text('msg');
            $table->smallInteger('leido')->default(0);
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
        Schema::dropIfExists('notifications');
    }
}
