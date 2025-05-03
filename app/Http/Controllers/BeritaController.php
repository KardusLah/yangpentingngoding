<?php
namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::with('kategori')->orderBy('tgl_post', 'desc')->get();
        return view('be.berita.index', compact('berita'));
    }

    public function create()
    {
        $kategori = KategoriBerita::whereIn('kategori_berita', ['wisata', 'hotel', 'reservasi'])->get();
        return view('be.berita.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required',
            'berita' => 'required', // pastikan ada
            'tgl_post' => 'required|date',
            'id_kategori_berita' => 'required|exists:kategori_berita,id',
            'foto' => 'nullable|image',
        ]);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('berita', 'public');
        }
        Berita::create($data);
        return redirect()->route('berita.index')->with('success', 'Berita berhasil ditambah');
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        $kategori = KategoriBerita::whereIn('kategori_berita', ['wisata', 'hotel', 'reservasi'])->get();
        return view('be.berita.edit', compact('berita', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $data = $request->validate([
            'judul' => 'required',
            'berita' => 'required',
            'tgl_post' => 'required|date',
            'id_kategori_berita' => 'required',
            'foto' => 'nullable|image',
        ]);
        if ($request->hasFile('foto')) {
            if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
                Storage::disk('public')->delete($berita->foto);
            }
            $data['foto'] = $request->file('foto')->store('berita', 'public');
        }
        $berita->update($data);
        return redirect()->route('berita.index')->with('success', 'Berita berhasil diupdate');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
            Storage::disk('public')->delete($berita->foto);
        }
        $berita->delete();
        return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus');
    }

    // Untuk halaman daftar berita di frontend
    public function frontendIndex()
    {
        $berita = \App\Models\Berita::orderBy('tgl_post', 'desc')->get();
        return view('fe.berita.index', compact('berita'));
    }

    // Untuk halaman detail berita di frontend
    public function show($id)
    {
        $news = \App\Models\Berita::findOrFail($id);
        return view('fe.berita.show', compact('news'));
    }
}