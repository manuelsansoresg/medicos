<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserCitaIdAndPacienteIdAndIdusrregistraToFormularioEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_entries', function (Blueprint $table) {
            $table->bigInteger('paciente_id')->nullable()->after('consulta_id');
            $table->bigInteger('user_cita_id')->nullable()->after('consulta_id');
            $table->bigInteger('idusrregistra')->nullable()->after('consulta_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulario_entries', function (Blueprint $table) {
            $table->dropColumn('paciente_id');
            $table->dropColumn('idusrregistra');
            $table->dropColumn('user_cita_id');
        });
    }
}
