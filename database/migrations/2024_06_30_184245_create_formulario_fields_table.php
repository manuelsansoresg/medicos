<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormularioFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulario_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('formulario_configuration_id');
            $table->string('field_name');
            $table->string('field_type');
            $table->boolean('is_required');
            $table->text('options')->nullable();
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
        Schema::dropIfExists('formulario_fields');
    }
}
