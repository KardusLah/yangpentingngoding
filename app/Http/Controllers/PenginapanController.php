<?php

namespace App\Http\Controllers;

use App\Models\Penginapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenginapanController extends Controller
{
    public function index()
    {
        $penginapan = Penginapan::all();
        return view('be.penginapan.index', compact('penginapan'));
    }

    public function create()
    {
        return view('be.penginapan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_penginapan' => 'required',
            'deskripsi' => 'required',
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
                $data[$foto] = $request->file($foto)->store('penginapan', 'public');
            }
        }
        Penginapan::create($data);
        return redirect()->route('penginapan.index')->with('success', 'Penginapan berhasil ditambah');
    }

    public function edit($id)
    {
        $penginapan = Penginapan::findOrFail($id);
        return view('be.penginapan.edit', compact('penginapan'));
    }

    public function update(Request $request, $id)
    {
        $penginapan = Penginapan::findOrFail($id);
        $data = $request->validate([
            'nama_penginapan' => 'required',
            'deskripsi' => 'required',
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
                if ($penginapan->$foto && Storage::disk('public')->exists($penginapan->$foto)) {
                    Storage::disk('public')->delete($penginapan->$foto);
                }
                $data[$foto] = $request->file($foto)->store('penginapan', 'public');
            }
        }
        $penginapan->update($data);
        return redirect()->route('penginapan.index')->with('success', 'Penginapan berhasil diupdate');
    }

    public function destroy($id)
    {
        $penginapan = Penginapan::findOrFail($id);
        for ($i = 1; $i <= 5; $i++) {
            $foto = 'foto'.$i;
            if ($penginapan->$foto && Storage::disk('public')->exists($penginapan->$foto)) {
                Storage::disk('public')->delete($penginapan->$foto);
            }
        }
        $penginapan->delete();
        return redirect()->route('penginapan.index')->with('success', 'Penginapan berhasil dihapus');
    }
}