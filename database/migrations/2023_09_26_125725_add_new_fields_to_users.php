<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('vapellido')->after('name')->nullable();
            $table->string('ttelefono')->after('name')->nullable();
            $table->string('tdireccion')->after('name')->nullable();
            $table->integer('idpuesto')->after('name')->nullable();
            $table->string('vcedula')->after('name')->nullable();
            $table->string('RFC')->after('name')->nullable();
            $table->string('especialidad')->after('name')->nullable();
            $table->integer('idclinica')->after('name')->nullable();
            $table->integer('idoctora')->after('name')->nullable();
            $table->smallInteger('status')->after('name')->nullable();
            $table->integer('usuario_alta')->after('name')->nullable();
            $table->string('vcodigodocto')->after('name')->nullable();
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
            $table->dropColumn('vapellido');
            $table->dropColumn('ttelefono');
            $table->dropColumn('tdireccion');
            $table->dropColumn('idpuesto');
            $table->dropColumn('vcedula');
            $table->dropColumn('RFC');
            $table->dropColumn('especialidad');
            $table->dropColumn('idclinica');
            $table->dropColumn('idoctora');
            $table->dropColumn('status');
            $table->dropColumn('usuario_alta');
            $table->dropColumn('vcodigodocto');
        });
    }
}
