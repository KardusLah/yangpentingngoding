<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\PaketWisata;

class OwnerController extends Controller
{
    public function index()
    {
        // Statistik
        $totalPendapatan = Reservasi::whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])->sum('total_bayar');
        $totalReservasi = Reservasi::count();
        $totalReservasiDibayar = Reservasi::whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])->count();
        $totalReservasiMenunggu = Reservasi::where('status_reservasi_wisata', 'pesan')->count();

        // Grafik pendapatan bulanan
        $pendapatanBulanan = Reservasi::selectRaw('MONTH(tgl_reservasi_wisata) as bulan, SUM(total_bayar) as total')
            ->whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Paket wisata paling laris
        $paketLaris = Reservasi::selectRaw('id_paket, COUNT(*) as jumlah')
            ->whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->groupBy('id_paket')
            ->orderByDesc('jumlah')
            ->with('paket')
            ->first();

        // Semua reservasi (read-only)
        $reservasi = Reservasi::with(['pelanggan', 'paket'])->orderByDesc('created_at')->get();

        // Semua paket wisata (read-only)
        $paket = PaketWisata::all();

        return view('be.owner.index', compact(
            'totalPendapatan',
            'totalReservasi',
            'totalReservasiDibayar',
            'totalReservasiMenunggu',
            'pendapatanBulanan',
            'paketLaris',
            'reservasi',
            'paket'
        ));
    }
}