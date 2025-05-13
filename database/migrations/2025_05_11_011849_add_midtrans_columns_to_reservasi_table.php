<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('reservasi', function ($table) {
            $table->string('midtrans_order_id')->nullable()->after('status_reservasi_wisata');
            $table->string('payment_url')->nullable()->after('midtrans_order_id');
        });
    }

    public function down()
    {
        Schema::table('reservasi', function ($table) {
            $table->dropColumn(['midtrans_order_id', 'payment_url']);
        });
    }
};
