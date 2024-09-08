<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserConfigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('field_config_downloads', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(); // el usuario al que se le asigna el permiso
            $table->unsignedBigInteger('idusrregistra')->nullable(); // usuario el que tiene iniciado la sesion
            
            $table->unsignedBigInteger('formulario_field_id')->nullable(); // id que indica el campo del formulario
            $table->smallInteger('is_download')->nullable();
            
            $table->foreign('formulario_field_id')->references('id')->on('formulario_fields')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idusrregistra')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('field_config_downloads');
    }
}
