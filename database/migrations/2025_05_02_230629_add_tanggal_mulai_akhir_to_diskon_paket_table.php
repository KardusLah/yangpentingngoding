<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('diskon_paket', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable()->after('persen');
            $table->date('tanggal_akhir')->nullable()->after('tanggal_mulai');
        });
    }

    public function down()
    {
        Schema::table('diskon_paket', function (Blueprint $table) {
            $table->dropColumn(['tanggal_mulai', 'tanggal_akhir']);
        });
    }
};