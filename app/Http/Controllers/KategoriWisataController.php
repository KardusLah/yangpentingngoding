<?php

namespace App\Http\Controllers;

use App\Models\KategoriWisata;
use Illuminate\Http\Request;

class KategoriWisataController extends Controller
{
    public function index()
    {
        $kategori = KategoriWisata::all();
        return view('be.kategori_wisata.index', compact('kategori'));
    }

    public function create()
    {
        return view('be.kategori_wisata.create');
    }

    public function store(Request $request)
    {
        $request->validate(['kategori_wisata' => 'required|unique:kategori_wisata']);
        KategoriWisata::create($request->only('kategori_wisata'));
        return redirect()->route('kategori-wisata.index')->with('success', 'Kategori Wisata berhasil ditambah');
    }

    public function edit($id)
    {
        $kategori = KategoriWisata::findOrFail($id);
        return view('be.kategori_wisata.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriWisata::findOrFail($id);
        $request->validate(['kategori_wisata' => 'required|unique:kategori_wisata,kategori_wisata,'.$id]);
        $kategori->update($request->only('kategori_wisata'));
        return redirect()->route('kategori-wisata.index')->with('success', 'Kategori Wisata berhasil diupdate');
    }

    public function destroy($id)
    {
        $kategori = KategoriWisata::findOrFail($id);
        $kategori->delete();
        return redirect()->route('kategori-wisata.index')->with('success', 'Kategori Wisata berhasil dihapus');
    }
}