<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdAndPrincipalToFormularioConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_configurations', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('name'); // Crear un campo para almacenar el ID del usuario
            $table->smallInteger('active')->nullable()->default(0)->after('name');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulario_configurations', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Eliminar la relación con la tabla 'users'
            $table->dropColumn(['user_id', 'active']); // Eliminar las columnas 'user_id' y 'active'
        });
    }
}
