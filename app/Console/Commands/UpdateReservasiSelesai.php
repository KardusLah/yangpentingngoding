<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservasi;
use Carbon\Carbon;

class UpdateReservasiSelesai extends Command
{
    protected $signature = 'reservasi:selesai';
    protected $description = 'Update status reservasi menjadi selesai jika tanggal akhir sudah lewat';

    public function handle()
    {
        $today = Carbon::today();
        $updated = Reservasi::where('status_reservasi_wisata', 'dibayar')
            ->whereDate('tgl_akhir', '<', $today)
            ->update(['status_reservasi_wisata' => 'selesai']);

        $this->info("Reservasi selesai diupdate: $updated");
    }
}