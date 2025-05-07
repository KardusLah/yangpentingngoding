<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Pelanggan;
use App\Models\PaketWisata;
use App\Models\DiskonPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    // Backend: List reservasi
    public function index()
    {
        $reservasi = Reservasi::with(['pelanggan', 'paket'])->get();
        return view('be.reservasi.index', compact('reservasi'));
    }

    // Backend: Form tambah reservasi
    public function create()
    {
        $pelanggan = Pelanggan::all();
        $paket = PaketWisata::with(['reservasiAktif'])->get();

        // Buat array tanggal penuh per paket
        $tanggalPenuh = [];
        foreach ($paket as $p) {
            $tanggalPenuh[$p->id] = [];
            foreach ($p->reservasiAktif as $r) {
                $mulai = Carbon::parse($r->tgl_mulai ?? $r->tgl_reservasi_wisata);
                $akhir = Carbon::parse($r->tgl_akhir ?? $r->tgl_reservasi_wisata);
                for ($d = 0; $d <= $mulai->diffInDays($akhir); $d++) {
                    $tanggalPenuh[$p->id][] = $mulai->copy()->addDays($d)->toDateString();
                }
            }
        }

        return view('be.reservasi.create', compact('pelanggan', 'paket', 'tanggalPenuh'));
    }

    // Store reservasi (FE & BE)
    public function store(Request $request)
    {
        // Ambil user login (multi-role)
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login untuk melakukan reservasi.']);
        }
    
        // Cek level user yang boleh booking
        if (!in_array($user->level, ['pelanggan', 'admin', 'bendahara', 'owner', 'pemilik'])) {
            return back()->withErrors(['login' => 'Akun Anda tidak diizinkan melakukan reservasi.']);
        }
    
        // Hanya override id_pelanggan jika login sebagai pelanggan (FE)
        if ($user->level == 'pelanggan' && method_exists($user, 'pelanggan') && $user->pelanggan) {
            $request->merge(['id_pelanggan' => $user->pelanggan->id]);
        }
        // Untuk admin/bendahara/owner, id_pelanggan tetap dari form!
    
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id',
            'id_paket' => 'required|exists:paket_wisata,id',
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
            'metode_pembayaran' => 'nullable|string',
            'file_bukti_tf' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
    
        $paket = PaketWisata::findOrFail($request->id_paket);
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglAkhir = Carbon::parse($request->tgl_akhir);
        $lama = $tglMulai->diffInDays($tglAkhir) + 1;
    
        // Validasi lama reservasi tidak melebihi durasi paket
        if ($lama > $paket->durasi) {
            return back()->withErrors(['tgl_akhir' => 'Durasi maksimal reservasi untuk paket ini adalah '.$paket->durasi.' hari.'])->withInput();
        }
    
        // Validasi tanggal penuh
        $reservasiAktif = Reservasi::where('id_paket', $paket->id)
            ->whereNotIn('status_reservasi_wisata', ['ditolak', 'selesai'])
            ->get();
    
        for ($d = 0; $d < $lama; $d++) {
            $tglCek = $tglMulai->copy()->addDays($d)->toDateString();
            foreach ($reservasiAktif as $r) {
                $mulai = Carbon::parse($r->tgl_mulai ?? $r->tgl_reservasi_wisata);
                $akhir = Carbon::parse($r->tgl_akhir ?? $r->tgl_reservasi_wisata);
                if ($tglCek >= $mulai->toDateString() && $tglCek <= $akhir->toDateString()) {
                    return back()->withErrors(['tgl_mulai' => 'Tanggal '.$tglCek.' sudah penuh, silakan pilih tanggal lain.'])->withInput();
                }
            }
        }
    
        // Hitung harga & diskon
        $total_bayar = $paket->harga_per_pack * $lama * $request->jumlah_peserta;
    
        // Ambil diskon aktif & berlaku
        $diskon = DiskonPaket::where('paket_id', $paket->id)
            ->where('aktif', 1)
            ->where(function($q) use ($tglMulai) {
                $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', $tglMulai->toDateString());
            })
            ->where(function($q) use ($tglMulai) {
                $q->whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', $tglMulai->toDateString());
            })
            ->first();
    
        $persen_diskon = $diskon ? $diskon->persen : 0;
        $nilai_diskon = $persen_diskon > 0 ? ($total_bayar * $persen_diskon / 100) : 0;
        $total_bayar_setelah_diskon = $total_bayar - $nilai_diskon;
    
        $data = $request->all();
        $data['lama_reservasi'] = $lama;
        $data['harga'] = $paket->harga_per_pack;
        $data['total_bayar'] = $total_bayar_setelah_diskon;
        $data['diskon'] = $persen_diskon;
        $data['nilai_diskon'] = $nilai_diskon;
        $data['tgl_reservasi_wisata'] = $request->tgl_mulai;
        $data['status_reservasi_wisata'] = $request->status_reservasi_wisata ?? 'menunggu';
    
        if ($request->hasFile('file_bukti_tf')) {
            $data['file_bukti_tf'] = $request->file('file_bukti_tf')->store('bukti_tf', 'public');
        }
    
        Reservasi::create($data);
    
        // Redirect sesuai asal (FE/BE)
        if (\Request::route()->getName() === 'reservasi.store') {
            return redirect()->route('reservasi.index')->with('success', 'Reservasi berhasil ditambahkan!');
        } else {
            return redirect()->route('fe.reservasi.index')->with('success', 'Reservasi berhasil! Silakan cek status reservasi Anda.');
        }
    }

    // Backend: Edit reservasi
    public function edit($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $pelanggan = Pelanggan::all();
        $paket = PaketWisata::with(['reservasiAktif'])->get();

        // Buat array tanggal penuh per paket (kecuali reservasi ini sendiri)
        $tanggalPenuh = [];
        foreach ($paket as $p) {
            $tanggalPenuh[$p->id] = [];
            foreach ($p->reservasiAktif as $r) {
                if ($r->id == $reservasi->id) continue;
                $mulai = Carbon::parse($r->tgl_mulai ?? $r->tgl_reservasi_wisata);
                $akhir = Carbon::parse($r->tgl_akhir ?? $r->tgl_reservasi_wisata);
                for ($d = 0; $d <= $mulai->diffInDays($akhir); $d++) {
                    $tanggalPenuh[$p->id][] = $mulai->copy()->addDays($d)->toDateString();
                }
            }
        }

        return view('be.reservasi.edit', compact('reservasi', 'pelanggan', 'paket', 'tanggalPenuh'));
    }

    // Backend: Update reservasi
    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);

        $request->validate([
            'id_pelanggan' => 'required',
            'id_paket' => 'required|exists:paket_wisata,id',
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
        ]);

        $paket = PaketWisata::findOrFail($request->id_paket);
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglAkhir = Carbon::parse($request->tgl_akhir);
        $lama = $tglMulai->diffInDays($tglAkhir) + 1;

        if ($lama > $paket->durasi) {
            return back()->withErrors(['tgl_akhir' => 'Durasi maksimal reservasi untuk paket ini adalah '.$paket->durasi.' hari.'])->withInput();
        }

        // Validasi tanggal penuh (abaikan reservasi ini sendiri)
        $reservasiAktif = Reservasi::where('id_paket', $paket->id)
            ->where('id', '!=', $reservasi->id)
            ->whereNotIn('status_reservasi_wisata', ['ditolak', 'selesai'])
            ->get();

        for ($d = 0; $d < $lama; $d++) {
            $tglCek = $tglMulai->copy()->addDays($d)->toDateString();
            foreach ($reservasiAktif as $r) {
                $mulai = Carbon::parse($r->tgl_mulai ?? $r->tgl_reservasi_wisata);
                $akhir = Carbon::parse($r->tgl_akhir ?? $r->tgl_reservasi_wisata);
                if ($tglCek >= $mulai->toDateString() && $tglCek <= $akhir->toDateString()) {
                    return back()->withErrors(['tgl_mulai' => 'Tanggal '.$tglCek.' sudah penuh, silakan pilih tanggal lain.'])->withInput();
                }
            }
        }

        // Hitung harga & diskon
        $total_bayar = $paket->harga_per_pack * $lama * $request->jumlah_peserta;

        // Ambil diskon aktif & berlaku
        $diskon = DiskonPaket::where('paket_id', $paket->id)
            ->where('aktif', 1)
            ->where(function($q) use ($tglMulai) {
                $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', $tglMulai->toDateString());
            })
            ->where(function($q) use ($tglMulai) {
                $q->whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', $tglMulai->toDateString());
            })
            ->first();

        $persen_diskon = $diskon ? $diskon->persen : 0;
        $nilai_diskon = $persen_diskon > 0 ? ($total_bayar * $persen_diskon / 100) : 0;
        $total_bayar_setelah_diskon = $total_bayar - $nilai_diskon;

        $data = $request->all();
        $data['lama_reservasi'] = $lama;
        $data['harga'] = $paket->harga_per_pack;
        $data['total_bayar'] = $total_bayar_setelah_diskon;
        $data['diskon'] = $persen_diskon;
        $data['nilai_diskon'] = $nilai_diskon;

        if ($request->hasFile('file_bukti_tf')) {
            if ($reservasi->file_bukti_tf && Storage::disk('public')->exists($reservasi->file_bukti_tf)) {
                Storage::disk('public')->delete($reservasi->file_bukti_tf);
            }
            $data['file_bukti_tf'] = $request->file('file_bukti_tf')->store('bukti_tf', 'public');
        }

        $reservasi->update($data);

        return redirect()->route('reservasi.index')->with('success', 'Reservasi berhasil diupdate!');
    }

    // Backend: Hapus reservasi
    public function destroy($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        if ($reservasi->file_bukti_tf && Storage::disk('public')->exists($reservasi->file_bukti_tf)) {
            Storage::disk('public')->delete($reservasi->file_bukti_tf);
        }
        $reservasi->delete();
        return redirect()->route('reservasi.index')->with('success', 'Reservasi berhasil dihapus!');
    }

    // Backend: Simulasi pemesanan
    public function simulasi(Request $request)
    {
        $paket = PaketWisata::with(['reservasiAktif'])->get();

        // Tanggal penuh per paket
        $tanggalPenuh = [];
        foreach ($paket as $p) {
            $tanggalPenuh[$p->id] = [];
            foreach ($p->reservasiAktif as $r) {
                $mulai = Carbon::parse($r->tgl_mulai ?? $r->tgl_reservasi_wisata);
                $akhir = Carbon::parse($r->tgl_akhir ?? $r->tgl_reservasi_wisata);
                for ($d = 0; $d <= $mulai->diffInDays($akhir); $d++) {
                    $tanggalPenuh[$p->id][] = $mulai->copy()->addDays($d)->toDateString();
                }
            }
        }

        $simulasi = null;
        if ($request->isMethod('post')) {
            $paketId = $request->id_paket;
            $tanggalMulai = $request->tanggal_mulai;
            $tanggalAkhir = $request->tanggal_akhir;
            $paketDipilih = $paket->where('id', $paketId)->first();
            $harga = $paketDipilih ? $paketDipilih->harga_per_pack : 0;
            $durasi = $paketDipilih ? $paketDipilih->durasi : 1;

            $bisa = true;
            $tanggalCek = [];
            if ($paketDipilih && $tanggalMulai && $tanggalAkhir) {
                $mulai = Carbon::parse($tanggalMulai);
                $akhir = Carbon::parse($tanggalAkhir);
                $lama = $mulai->diffInDays($akhir) + 1;
                if ($lama > $durasi) $bisa = false;
                for ($i = 0; $i < $lama; $i++) {
                    $tgl = $mulai->copy()->addDays($i)->toDateString();
                    $tanggalCek[] = $tgl;
                    if (in_array($tgl, $tanggalPenuh[$paketId])) {
                        $bisa = false;
                    }
                }
            }
            $simulasi = [
                'paket' => $paketDipilih,
                'harga' => $harga,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_akhir' => $tanggalAkhir,
                'tanggalCek' => $tanggalCek,
                'lama' => isset($lama) ? $lama : 0,
                'total' => isset($lama) ? $harga * $lama : 0,
                'bisa' => $bisa,
            ];
        }

        return view('be.reservasi.simulasi', compact('paket', 'tanggalPenuh', 'simulasi'));
    }

    // Backend: Set status reservasi
    public function terima($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status_reservasi_wisata = 'dibayar';
        $reservasi->save();
        return back()->with('success', 'Reservasi diterima.');
    }

    public function tolak($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status_reservasi_wisata = 'ditolak';
        $reservasi->save();
        return back()->with('success', 'Reservasi ditolak.');
    }

    public function selesai($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status_reservasi_wisata = 'selesai';
        $reservasi->save();
        return back()->with('success', 'Reservasi selesai.');
    }

    // Backend: Bulk action
    public function bulk(Request $request, $action)
    {
        $ids = $request->selected;
        if (!$ids) return back()->with('error', 'Tidak ada data dipilih.');

        switch ($action) {
            case 'delete':
                Reservasi::whereIn('id', $ids)->delete();
                break;
            case 'terima':
                Reservasi::whereIn('id', $ids)->update(['status_reservasi_wisata' => 'dibayar']);
                break;
            case 'tolak':
                Reservasi::whereIn('id', $ids)->update(['status_reservasi_wisata' => 'ditolak']);
                break;
            case 'selesai':
                Reservasi::whereIn('id', $ids)->update(['status_reservasi_wisata' => 'selesai']);
                break;
        }
        return back()->with('success', 'Aksi massal berhasil dijalankan.');
    }

    // FE: Halaman booking
    public function feIndex(Request $request)
    {
        $pakets = PaketWisata::all();
        $diskon = DiskonPaket::where('aktif', 1)->get()->groupBy('paket_id');
        $paketTerpilih = $request->paket; // dari query string

        // Kirim tanggal penuh untuk kebutuhan kalender booking
        $paketList = PaketWisata::with(['reservasiAktif'])->get();
        $tanggalPenuh = [];
        foreach ($paketList as $p) {
            $tanggalPenuh[$p->id] = [];
            foreach ($p->reservasiAktif as $r) {
                $mulai = Carbon::parse($r->tgl_mulai ?? $r->tgl_reservasi_wisata);
                $akhir = Carbon::parse($r->tgl_akhir ?? $r->tgl_reservasi_wisata);
                for ($d = 0; $d <= $mulai->diffInDays($akhir); $d++) {
                    $tanggalPenuh[$p->id][] = $mulai->copy()->addDays($d)->toDateString();
                }
            }
        }

        return view('fe.reservasi.index', compact('pakets', 'diskon', 'paketTerpilih', 'tanggalPenuh'));
    }

    // FE: Halaman detail reservasi
    public function detail($id)
    {
        $paket = PaketWisata::with('kategori')->findOrFail($id);
        $diskon = DiskonPaket::where('paket_id', $id)->where('aktif', 1)->first();
        return view('fe.reservasi.detail', compact('paket', 'diskon'));
    }
}