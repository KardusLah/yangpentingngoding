<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaketWisata;
use App\Models\Penginapan;
use App\Models\Berita;
use App\Models\KategoriWisata;
use App\Models\DiskonPaket;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $pakets = PaketWisata::all();
    $penginapan = Penginapan::latest()->take(3)->get();
    $berita = Berita::latest()->take(3)->get();
    $destinations = [];
    $kategori_wisata = KategoriWisata::all();
    $diskon = DiskonPaket::where('aktif', 1)->get()->groupBy('paket_id');

    return view('fe.homepage.index', [
        'title' => 'Home',
        'pakets' => $pakets,
        'penginapan' => $penginapan,
        'berita' => $berita,
        'destinations' => $destinations,
        'kategori_wisata' => $kategori_wisata,
        'diskon' => $diskon
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