<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormularioEntryFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulario_entry_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('formulario_entry_id');
            $table->unsignedBigInteger('formulario_field_id');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('formulario_entry_id')->references('id')->on('formulario_entries')->onDelete('cascade');
            $table->foreign('formulario_field_id')->references('id')->on('formulario_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formulario_entry_fields');
    }
}
