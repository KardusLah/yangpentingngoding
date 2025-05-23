<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Pelanggan;
use App\Models\PaketWisata;
use App\Models\DiskonPaket;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
// use Midtrans\Snap;
// use Midtrans\Config;

class ReservasiController extends Controller
{
    // =========================
    // BACKEND: LIST RESERVASI
    // =========================
    /**
     * Display a listing of the reservasi.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = Reservasi::with(['pelanggan', 'paket']);

        if ($status && $status !== 'all') {
            $query->where('status_reservasi_wisata', $status);
        }

        $reservasi = $query->get();

        return view('be.reservasi.index', compact('reservasi', 'status'));
    }

    // =========================
    // BACKEND: FORM TAMBAH RESERVASI
    // =========================
    /**
     * Show the form for creating a new reservasi.
     */
    public function create()
    {
        $pelanggan = Pelanggan::all();
        $paket = PaketWisata::with(['reservasiAktif'])->get();
        $bankList = Bank::all(); // <--- ambil data bank

        // Buat array tanggal penuh per paket (hanya status 'dibayar')
        $tanggalPenuh = [];
        foreach ($paket as $p) {
            $tanggalPenuh[$p->id] = [];
            foreach ($p->reservasiAktif->where('status_reservasi_wisata', 'dibayar') as $r) {
                $mulai = Carbon::parse($r->tgl_mulai ?? $r->tgl_reservasi_wisata);
                $akhir = Carbon::parse($r->tgl_akhir ?? $r->tgl_reservasi_wisata);
                for ($d = 0; $d <= $mulai->diffInDays($akhir); $d++) {
                    $tanggalPenuh[$p->id][] = $mulai->copy()->addDays($d)->toDateString();
                }
            }
        }

        return view('be.reservasi.create', compact('pelanggan', 'paket', 'tanggalPenuh', 'bankList'));
    }

    // =========================
    // STORE RESERVASI (FE & BE)
    // =========================
    /**
     * Store a newly created reservasi in storage.
     */
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
            'file_bukti_tf' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // wajib
        ]);

        $paket = PaketWisata::findOrFail($request->id_paket);
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglAkhir = Carbon::parse($request->tgl_akhir);
        $lama = $tglMulai->diffInDays($tglAkhir) + 1;

        // Validasi lama reservasi tidak melebihi durasi paket
        if ($lama > $paket->durasi) {
            return back()->withErrors(['tgl_akhir' => 'Durasi maksimal reservasi untuk paket ini adalah '.$paket->durasi.' hari.'])->withInput();
        }

        // Validasi tanggal penuh (hanya status 'dibayar' yang dianggap penuh)
        $reservasiAktif = Reservasi::where('id_paket', $paket->id)
            ->where('status_reservasi_wisata', 'dibayar')
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
        $data['status_reservasi_wisata'] = $request->status_reservasi_wisata ?? 'pesan';
        if ($request->hasFile('file_bukti_tf')) {
            $data['file_bukti_tf'] = $request->file('file_bukti_tf')->store('bukti_tf', 'public');
        }

        Reservasi::create($data);

        // Redirect ke halaman profil FE setelah booking
        return redirect()->route('profile')->with('success', 'Reservasi berhasil dibuat! Silakan cek status reservasi Anda.');
    }

    // =========================
    // BACKEND: EDIT RESERVASI
    // =========================
    /**
     * Show the form for editing the specified reservasi.
     */
    public function edit($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $pelanggan = Pelanggan::all();
        $paket = PaketWisata::with(['reservasiAktif'])->get();
        $bankList = Bank::all(); // <--- ambil data bank

        // Buat array tanggal penuh per paket (hanya status 'dibayar', kecuali reservasi ini sendiri)
        $tanggalPenuh = [];
        foreach ($paket as $p) {
            $tanggalPenuh[$p->id] = [];
            foreach ($p->reservasiAktif->where('status_reservasi_wisata', 'dibayar') as $r) {
                if ($r->id == $reservasi->id) continue;
                $mulai = Carbon::parse($r->tgl_mulai ?? $r->tgl_reservasi_wisata);
                $akhir = Carbon::parse($r->tgl_akhir ?? $r->tgl_reservasi_wisata);
                for ($d = 0; $d <= $mulai->diffInDays($akhir); $d++) {
                    $tanggalPenuh[$p->id][] = $mulai->copy()->addDays($d)->toDateString();
                }
            }
        }

        return view('be.reservasi.edit', compact('reservasi', 'pelanggan', 'paket', 'tanggalPenuh', 'bankList'));
    }

    // =========================
    // BACKEND: UPDATE RESERVASI
    // =========================
    /**
     * Update the specified reservasi in storage.
     */
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
            ->where('status_reservasi_wisata', 'dibayar')
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

    // =========================
    // BACKEND: HAPUS RESERVASI
    // =========================
    /**
     * Remove the specified reservasi from storage.
     */
    public function destroy($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        if ($reservasi->file_bukti_tf && Storage::disk('public')->exists($reservasi->file_bukti_tf)) {
            Storage::disk('public')->delete($reservasi->file_bukti_tf);
        }
        $reservasi->delete();
        return redirect()->route('reservasi.index')->with('success', 'Reservasi berhasil dihapus!');
    }

    // =========================
    // BACKEND: SIMULASI PEMESANAN
    // =========================
    /**
     * Simulasi pemesanan reservasi.
     */
    public function simulasi(Request $request)
    {
        $paket = PaketWisata::with(['reservasiAktif'])->get();

        // Tanggal penuh per paket
        foreach ($paket as $p) {
            $tanggalPenuh[$p->id] = [];
            foreach ($p->reservasiAktif->where('status_reservasi_wisata', 'dibayar') as $r) {
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

    // =========================
    // BACKEND: SET STATUS RESERVASI
    // =========================
    /**
     * Set status reservasi menjadi 'dibayar'.
     */
    public function terima($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status_reservasi_wisata = 'dibayar';
        $reservasi->save();
        return back()->with('success', 'Reservasi diterima.');
    }

    /**
     * Set status reservasi menjadi 'ditolak'.
     */
    public function tolak($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status_reservasi_wisata = 'ditolak';
        $reservasi->save();
        return back()->with('success', 'Reservasi ditolak.');
    }

    /**
     * Set status reservasi menjadi 'selesai'.
     */
    public function selesai($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status_reservasi_wisata = 'selesai';
        $reservasi->save();
        return back()->with('success', 'Reservasi selesai.');
    }

    // =========================
    // BACKEND: BULK ACTION
    // =========================
    /**
     * Bulk action for reservasi.
     */
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

    // =========================
    // FRONTEND: HALAMAN BOOKING
    // =========================
    /**
     * Show the frontend booking page.
     */
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

    // =========================
    // FRONTEND: HALAMAN DETAIL RESERVASI
    // =========================
    /**
     * Show the frontend reservasi detail page.
     */
    public function detail($id)
    {
        $paket = PaketWisata::with('kategori')->findOrFail($id);
        $diskon = DiskonPaket::where('paket_id', $id)->where('aktif', 1)->first();
        return view('fe.reservasi.detail', compact('paket', 'diskon'));
    }

    // =========================
    // MIDTRANS CALLBACK
    // =========================
    /**
     * Handle Midtrans payment callback.
     */
    public function midtransCallback(Request $request)
    {
        Log::info('Midtrans callback masuk', $request->all());
        $notif = new \Midtrans\Notification();
        $orderId = $notif->order_id;
        $transactionStatus = $notif->transaction_status;

        $reservasi = Reservasi::where('midtrans_order_id', $orderId)->first();
        if ($reservasi) {
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                $reservasi->status_reservasi_wisata = 'dibayar';
            } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'expire') {
                $reservasi->status_reservasi_wisata = 'ditolak';
            }
            $reservasi->save();
        }
        return response()->json(['status' => 'ok']);
    }
    
}