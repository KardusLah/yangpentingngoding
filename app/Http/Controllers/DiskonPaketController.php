<?php
namespace App\Http\Controllers;

use App\Models\DiskonPaket;
use App\Models\PaketWisata;
use Illuminate\Http\Request;

class DiskonPaketController extends Controller
{
    public function index()
    {
        $paket = PaketWisata::all();
        $diskon = DiskonPaket::with('paket')->get()->keyBy('paket_id');
        return view('be.diskon.index', compact('paket', 'diskon'));
    }

    public function update(Request $request)
    {
        foreach ($request->paket_id as $id) {
            DiskonPaket::updateOrCreate(
                ['paket_id' => $id],
                [
                    'aktif' => in_array($id, $request->aktif ?? []) ? 1 : 0,
                    'persen' => $request->persen[$id] ?? 0,
                    'tanggal_mulai' => $request->tanggal_mulai[$id] ?? null,
                    'tanggal_akhir' => $request->tanggal_akhir[$id] ?? null,
                ]
            );
        }
        return back()->with('success', 'Diskon berhasil diperbarui!');
    }
}