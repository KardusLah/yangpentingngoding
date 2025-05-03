<?php
namespace App\Http\Controllers;

use App\Models\ObyekWisata;
use App\Models\KategoriWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObyekWisataController extends Controller
{
    public function index()
    {
        $wisata = ObyekWisata::with('kategori')->get();
        return view('be.wisata.index', compact('wisata'));
    }

    public function create()
    {
        $kategori = KategoriWisata::all();
        return view('be.wisata.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_wisata' => 'required',
            'deskripsi_wisata' => 'required',
            'id_kategori_wisata' => 'required',
            'fasilitas' => 'required',
            'foto1' => 'nullable|image',
            'foto2' => 'nullable|image',
            'foto3' => 'nullable|image',
            'foto4' => 'nullable|image',
            'foto5' => 'nullable|image',
        ]);
        for ($i = 1; $i <= 5; $i++) {
            $foto = 'foto'.$i;
            if ($request->hasFile($foto)) {
                $data[$foto] = $request->file($foto)->store('wisata', 'public');
            }
        }
        ObyekWisata::create($data);
        return redirect()->route('wisata.index')->with('success', 'Objek wisata berhasil ditambah');
    }

    public function edit($id)
    {
        $wisata = ObyekWisata::findOrFail($id);
        $kategori = KategoriWisata::all();
        return view('be.wisata.edit', compact('wisata', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $wisata = ObyekWisata::findOrFail($id);
        $data = $request->validate([
            'nama_wisata' => 'required',
            'deskripsi_wisata' => 'required',
            'id_kategori_wisata' => 'required',
            'fasilitas' => 'required',
            'foto1' => 'nullable|image',
            'foto2' => 'nullable|image',
            'foto3' => 'nullable|image',
            'foto4' => 'nullable|image',
            'foto5' => 'nullable|image',
        ]);
        for ($i = 1; $i <= 5; $i++) {
            $foto = 'foto'.$i;
            if ($request->hasFile($foto)) {
                if ($wisata->$foto && Storage::disk('public')->exists($wisata->$foto)) {
                    Storage::disk('public')->delete($wisata->$foto);
                }
                $data[$foto] = $request->file($foto)->store('wisata', 'public');
            }
        }
        $wisata->update($data);
        return redirect()->route('wisata.index')->with('success', 'Objek wisata berhasil diupdate');
    }

    public function destroy($id)
    {
        $wisata = ObyekWisata::findOrFail($id);
        for ($i = 1; $i <= 5; $i++) {
            $foto = 'foto'.$i;
            if ($wisata->$foto && Storage::disk('public')->exists($wisata->$foto)) {
                Storage::disk('public')->delete($wisata->$foto);
            }
        }
        $wisata->delete();
        return redirect()->route('wisata.index')->with('success', 'Objek wisata berhasil dihapus');
    }

    
}