<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTempAndEstaturaToConsultas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->string('temperatura')->nullable()->after('receta');
            $table->string('estatura')->nullable()->after('receta');
            $table->string('indicaciones_generales')->nullable()->after('receta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropColumn('temperatura');
            $table->dropColumn('estatura');
            $table->dropColumn('indicaciones_generales');
        });
    }
}
