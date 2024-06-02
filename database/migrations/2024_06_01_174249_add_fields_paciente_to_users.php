<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsPacienteToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('fecha_nacimiento')->after('usuario_principal')->nullable();
            $table->string('codigo_paciente')->after('usuario_principal')->nullable();
            $table->string('num_seguro')->after('usuario_principal')->nullable();
            $table->string('foto')->after('usuario_principal')->nullable();
            $table->string('ruta_foto')->after('usuario_principal')->nullable();
            $table->string('sexo')->after('usuario_principal')->nullable();
            $table->text('alergias')->after('usuario_principal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fecha_nacimiento');
            $table->dropColumn('codigo_paciente');
            $table->dropColumn('num_seguro');
            $table->dropColumn('foto');
            $table->dropColumn('ruta_foto');
            $table->dropColumn('sexo');
            $table->dropColumn('alergias');
        });
    }
}
