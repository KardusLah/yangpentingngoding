<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalPaket = \App\Models\PaketWisata::count();
        $totalReservasi = \App\Models\Reservasi::count();
        $totalWisata = \App\Models\ObyekWisata::count();
        $totalPenginapan = \App\Models\Penginapan::count();
    
        $totalPendapatan = \App\Models\Reservasi::whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->sum('total_bayar');
    
        $reservasiPerPaket = \App\Models\Reservasi::selectRaw('id_paket, COUNT(*) as jumlah')
            ->groupBy('id_paket')->with('paket')->get();
    
        return view('be.admin.index', [
            'title' => 'Admin',
            'totalPaket' => $totalPaket,
            'totalReservasi' => $totalReservasi,
            'totalWisata' => $totalWisata,
            'totalPenginapan' => $totalPenginapan,
            'totalPendapatan' => $totalPendapatan,
            'reservasiPerPaket' => $reservasiPerPaket,
        ]);
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