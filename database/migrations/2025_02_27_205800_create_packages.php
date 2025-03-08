<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //1- activo 0-desactivado 2-borrado
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('precio')->nullable();
            $table->smallInteger('status')->nullable()->default(1);
            $table->unsignedBigInteger('idusrregistra')->nullable();
            $table->timestamps();
            $table->foreign('idusrregistra')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('item_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('catalog_price_id')->nullable();
            $table->timestamps();

            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('catalog_price_id')->references('id')->on('catalog_prices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
        Schema::dropIfExists('itemPackages');
    }
}
