<?php
namespace App\Http\Controllers;

use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaketWisataController extends Controller
{
    public function index()
    {
        $paket = PaketWisata::all();
        return view('be.paket.index', compact('paket'));
    }

    public function create()
    {
        return view('be.paket.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_paket' => 'required',
            'deskripsi' => 'required',
            'fasilitas' => 'required',
            'harga_per_pack' => 'required|numeric',
            'foto1' => 'nullable|image',
            'foto2' => 'nullable|image',
            'foto3' => 'nullable|image',
            'foto4' => 'nullable|image',
            'foto5' => 'nullable|image',
        ]);

        // Upload foto jika ada
        for ($i = 1; $i <= 5; $i++) {
            $foto = 'foto' . $i;
            if ($request->hasFile($foto)) {
                $data[$foto] = $request->file($foto)->store('paket', 'public');
            }
        }

        PaketWisata::create($data);
        return redirect()->route('paket.index')->with('success', 'Paket berhasil ditambah');
    }

    public function edit($id)
    {
        $paket = PaketWisata::findOrFail($id);
        return view('be.paket.edit', compact('paket'));
    }

    public function update(Request $request, $id)
    {
        $paket = PaketWisata::findOrFail($id);
        $data = $request->validate([
            'nama_paket' => 'required',
            'deskripsi' => 'required',
            'fasilitas' => 'required',
            'harga_per_pack' => 'required|numeric',
            'foto1' => 'nullable|image',
            'foto2' => 'nullable|image',
            'foto3' => 'nullable|image',
            'foto4' => 'nullable|image',
            'foto5' => 'nullable|image',
        ]);
        for ($i = 1; $i <= 5; $i++) {
            $foto = 'foto' . $i;
            if ($request->hasFile($foto)) {
                // Hapus file lama jika ada
                if ($paket->$foto && Storage::disk('public')->exists($paket->$foto)) {
                    Storage::disk('public')->delete($paket->$foto);
                }
                $data[$foto] = $request->file($foto)->store('paket', 'public');
            }
        }
        $paket->update($data);
        return redirect()->route('paket.index')->with('success', 'Paket berhasil diupdate');
    }

    public function destroy($id)
    {
        $paket = PaketWisata::findOrFail($id);
        $paket->delete();
        return redirect()->route('paket.index')->with('success', 'Paket berhasil dihapus');
        for ($i = 1; $i <= 5; $i++) {
            $foto = 'foto' . $i;
            if ($paket->$foto && Storage::disk('public')->exists($paket->$foto)) {
                Storage::disk('public')->delete($paket->$foto);
            }
        }
        $paket->delete();
    }
}