<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormularioEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulario_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('consulta_id');
            $table->unsignedBigInteger('formulario_configuration_id');
            $table->timestamps();

            $table->foreign('formulario_configuration_id')->references('id')->on('formulario_configurations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formulario_entries');
    }
}
