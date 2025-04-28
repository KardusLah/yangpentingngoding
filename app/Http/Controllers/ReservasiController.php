<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Pelanggan;
use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasi = Reservasi::with(['pelanggan', 'paket'])->get();
        return view('be.reservasi.index', compact('reservasi'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::all();
        $paket = PaketWisata::all();
        return view('be.reservasi.create', compact('pelanggan', 'paket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required',
            'id_paket' => 'required',
            'tgl_reservasi_wisata' => 'required|date',
            'harga' => 'required|numeric',
            'jumlah_peserta' => 'required|integer',
            'diskon' => 'nullable|numeric',
            'nilai_diskon' => 'nullable|numeric',
            'total_bayar' => 'required|numeric',
            'file_bukti_tf' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'status_reservasi_wisata' => 'required',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_bukti_tf')) {
            $data['file_bukti_tf'] = $request->file('file_bukti_tf')->store('bukti_tf', 'public');
        }

        Reservasi::create($data);

        return redirect()->route('reservasi.index')->with('success', 'Reservasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $pelanggan = Pelanggan::all();
        $paket = PaketWisata::all();
        return view('be.reservasi.edit', compact('reservasi', 'pelanggan', 'paket'));
    }

    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);

        $request->validate([
            'id_pelanggan' => 'required',
            'id_paket' => 'required',
            'tgl_reservasi_wisata' => 'required|date',
            'harga' => 'required|numeric',
            'jumlah_peserta' => 'required|integer',
            'diskon' => 'nullable|numeric',
            'nilai_diskon' => 'nullable|numeric',
            'total_bayar' => 'required|numeric',
            'file_bukti_tf' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'status_reservasi_wisata' => 'required',
        ]);

        $data = $request->except('_token', '_method');

        if ($request->hasFile('file_bukti_tf')) {
            // Hapus file lama jika ada
            if ($reservasi->file_bukti_tf && Storage::disk('public')->exists($reservasi->file_bukti_tf)) {
                Storage::disk('public')->delete($reservasi->file_bukti_tf);
            }
            $data['file_bukti_tf'] = $request->file('file_bukti_tf')->store('bukti_tf', 'public');
        }

        $reservasi->update($data);

        return redirect()->route('reservasi.index')->with('success', 'Reservasi berhasil diupdate!');
    }

    public function destroy($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        // Hapus file bukti transfer jika ada
        if ($reservasi->file_bukti_tf && Storage::disk('public')->exists($reservasi->file_bukti_tf)) {
            Storage::disk('public')->delete($reservasi->file_bukti_tf);
        }
        $reservasi->delete();
        return redirect()->route('reservasi.index')->with('success', 'Reservasi berhasil dihapus!');
    }
}