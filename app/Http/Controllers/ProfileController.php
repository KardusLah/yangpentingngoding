<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Reservasi;
use App\Models\Pelanggan;
use App\Models\Karyawan;

class ProfileController extends Controller
{
    // Halaman profil & riwayat reservasi
    public function index()
    {
        $user = Auth::user();

        // Ambil data profil sesuai level
        $profil = null;
        if ($user->level == 'pelanggan') {
            $profil = $user->pelanggan;
        } elseif (in_array($user->level, ['admin', 'bendahara', 'pemilik', 'owner'])) {
            $profil = $user->karyawan;
        }

        // Ambil riwayat reservasi jika pelanggan
        $riwayat = [];
        if ($user->level == 'pelanggan' && $profil) {
            $riwayat = Reservasi::with('paket')
                ->where('id_pelanggan', $profil->id)
                ->orderByDesc('created_at')
                ->get();
        }

        // Notifikasi reservasi yang perlu aksi
        $notifikasi = [];
        if ($user->level == 'pelanggan' && $profil) {
            $notifikasi = Reservasi::where('id_pelanggan', $profil->id)
                ->whereIn('status_reservasi_wisata', ['menunggu', 'pesan'])
                ->get();
        }

        return view('fe.profile', compact('user', 'profil', 'riwayat', 'notifikasi'));
    }

    // Update profil (nama, alamat, foto, no hp)
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required',
            'no_hp' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        if ($user->level == 'pelanggan') {
            $rules['nama_lengkap'] = 'required';
            $rules['alamat'] = 'required';
        } else {
            $rules['nama_karyawan'] = 'required';
            $rules['alamat'] = 'required';
        }

        $data = $request->validate($rules);

        // Update foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('user', 'public');
        }

        // Update user
        $user->update([
            'name' => $data['name'],
            'no_hp' => $data['no_hp'],
        ]);

        // Update profil
        if ($user->level == 'pelanggan' && $user->pelanggan) {
            $updateData = [
                'nama_lengkap' => $data['nama_lengkap'],
                'alamat' => $data['alamat'],
            ];
            if ($fotoPath) {
                if ($user->pelanggan->foto) {
                    Storage::disk('public')->delete($user->pelanggan->foto);
                }
                $updateData['foto'] = $fotoPath;
            }
            $user->pelanggan->update($updateData);
        } elseif ($user->karyawan) {
            $updateData = [
                'nama_karyawan' => $data['nama_karyawan'],
                'alamat' => $data['alamat'],
            ];
            if ($fotoPath) {
                if ($user->karyawan->foto) {
                    Storage::disk('public')->delete($user->karyawan->foto);
                }
                $updateData['foto'] = $fotoPath;
            }
            $user->karyawan->update($updateData);
        }

        return back()->with('success', 'Profil berhasil diupdate!');
    }

    // Upload bukti pembayaran reservasi
    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'file_bukti_tf' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $reservasi = Reservasi::findOrFail($id);

        // Hanya pemilik reservasi yang boleh upload
        if (Auth::user()->level == 'pelanggan') {
            $profil = Auth::user()->pelanggan;
            if ($reservasi->id_pelanggan != $profil->id) {
                abort(403);
            }
        }

        // Hapus file lama jika ada
        if ($reservasi->file_bukti_tf) {
            Storage::disk('public')->delete($reservasi->file_bukti_tf);
        }

        $path = $request->file('file_bukti_tf')->store('bukti_tf', 'public');
        $reservasi->file_bukti_tf = $path;
        $reservasi->status_reservasi_wisata = 'pesan'; // update status jika perlu
        $reservasi->save();

        return back()->with('success', 'Bukti pembayaran berhasil diunggah!');
    }
}