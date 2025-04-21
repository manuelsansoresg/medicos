<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsLinkByCurpToVinculoPacienteUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vinculo_paciente_usuarios', function (Blueprint $table) {
            $table->boolean('is_link_by_curp')->default(false)->after('paciente_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vinculo_paciente_usuarios', function (Blueprint $table) {
            $table->dropColumn('is_link_by_curp');
        });
    }
}
