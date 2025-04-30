<?php

namespace App\Http\Controllers;

use App\Models\KategoriBerita;
use Illuminate\Http\Request;

class KategoriBeritaController extends Controller
{
    public function index()
    {
        $kategori = KategoriBerita::all();
        return view('be.kategori_berita.index', compact('kategori'));
    }

    public function create()
    {
        return view('be.kategori_berita.create');
    }

    public function store(Request $request)
    {
        $request->validate(['kategori_berita' => 'required|unique:kategori_berita']);
        KategoriBerita::create($request->only('kategori_berita'));
        return redirect()->route('kategori-berita.index')->with('success', 'Kategori Berita berhasil ditambah');
    }

    public function edit($id)
    {
        $kategori = KategoriBerita::findOrFail($id);
        return view('be.kategori_berita.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriBerita::findOrFail($id);
        $request->validate(['kategori_berita' => 'required|unique:kategori_berita,kategori_berita,'.$id]);
        $kategori->update($request->only('kategori_berita'));
        return redirect()->route('kategori-berita.index')->with('success', 'Kategori Berita berhasil diupdate');
    }

    public function destroy($id)
    {
        $kategori = KategoriBerita::findOrFail($id);
        $kategori->delete();
        return redirect()->route('kategori-berita.index')->with('success', 'Kategori Berita berhasil dihapus');
    }
}