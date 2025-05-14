<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankToReservasiTable extends Migration
{
    public function up()
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id')->nullable()->after('lama_reservasi');
            $table->string('no_rekening', 100)->nullable()->after('bank_id');

            $table->foreign('bank_id')->references('id')->on('bank')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
            $table->dropColumn(['bank_id', 'no_rekening']);
        });
    }
}
