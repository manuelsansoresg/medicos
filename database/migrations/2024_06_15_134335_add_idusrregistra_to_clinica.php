<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdusrregistraToClinica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinica', function (Blueprint $table) {
            $table->bigInteger('idusrregistra')->nullable()->after('idclinica');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinica', function (Blueprint $table) {
            $table->dropColumn('idusrregistra');
        });
    }
}
