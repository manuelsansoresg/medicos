<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentTypeAndObservacionesToSolicitudes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->string('payment_type')->nullable()->after('source_id');
            $table->string('observaciones')->nullable()->after('payment_type');
            $table->date('fecha_pago')->nullable()->after('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->dropColumn('observaciones');
            $table->dropColumn('fecha_pago');
        });
    }
}
