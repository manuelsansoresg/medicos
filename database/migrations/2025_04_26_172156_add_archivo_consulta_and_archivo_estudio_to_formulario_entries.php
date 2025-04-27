<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArchivoConsultaAndArchivoEstudioToFormularioEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_entries', function (Blueprint $table) {
            $table->string('archivo')->nullable()->after('idusrregistra');
        });

        Schema::table('estudios', function (Blueprint $table) {
            $table->string('archivo')->nullable()->after('diagnosticos');
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
            $table->dropColumn('archivo');
        });

        Schema::table('estudios', function (Blueprint $table) {
            $table->dropColumn('archivo');
        });
    }
}
