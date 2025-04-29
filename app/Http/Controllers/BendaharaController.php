<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class BendaharaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Statistik keuangan
        $totalPendapatan = \App\Models\Reservasi::where('status_reservasi_wisata', 'dibayar')->sum('total_bayar');
        $totalReservasiDibayar = \App\Models\Reservasi::where('status_reservasi_wisata', 'dibayar')->count();
        $totalReservasiMenunggu = \App\Models\Reservasi::where('status_reservasi_wisata', 'pesan')->count();
    
        // Grafik pendapatan bulanan (contoh)
        $pendapatanBulanan = \App\Models\Reservasi::selectRaw('MONTH(tgl_reservasi_wisata) as bulan, SUM(total_bayar) as total')
            ->where('status_reservasi_wisata', 'dibayar')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
    
        // Daftar reservasi untuk manajemen pembayaran
        $reservasi = \App\Models\Reservasi::with(['pelanggan', 'paket'])
            ->orderByDesc('created_at')
            ->get();
    
        // Paket wisata paling laris
        $paketLaris = \App\Models\Reservasi::selectRaw('id_paket, COUNT(*) as jumlah')
            ->where('status_reservasi_wisata', 'dibayar')
            ->groupBy('id_paket')
            ->orderByDesc('jumlah')
            ->with('paket')
            ->first();
    
        return view('be.bendahara.index', compact(
            'totalPendapatan',
            'totalReservasiDibayar',
            'totalReservasiMenunggu',
            'pendapatanBulanan',
            'reservasi',
            'paketLaris'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
