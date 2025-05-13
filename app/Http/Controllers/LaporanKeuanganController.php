<?php
namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanKeuanganController extends Controller
{
    public function index()
    {
        $totalPendapatan = Reservasi::whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])->sum('total_bayar');

        $paketLaris = Reservasi::selectRaw('id_paket, COUNT(*) as jumlah')
            ->whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->groupBy('id_paket')
            ->orderByDesc('jumlah')
            ->with('paket')
            ->first();

        $statistikPeserta = Reservasi::selectRaw('id_paket, SUM(jumlah_peserta) as total_peserta')
            ->whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->groupBy('id_paket')
            ->with('paket')
            ->get();

        $grafikPendapatan = Reservasi::selectRaw('DATE_FORMAT(tgl_reservasi_wisata, "%Y-%m") as bulan, SUM(total_bayar) as total')
            ->whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('be.laporan.index', compact(
            'totalPendapatan', 'paketLaris', 'statistikPeserta', 'grafikPendapatan'
        ));
    }

    public function exportPdf()
    {
        $totalPendapatan = Reservasi::whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])->sum('total_bayar');
        $paketLaris = Reservasi::selectRaw('id_paket, COUNT(*) as jumlah')
            ->whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->groupBy('id_paket')
            ->orderByDesc('jumlah')
            ->with('paket')
            ->first();
        $statistikPeserta = Reservasi::selectRaw('id_paket, SUM(jumlah_peserta) as total_peserta')
            ->whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->groupBy('id_paket')
            ->with('paket')
            ->get();
        $grafikPendapatan = Reservasi::selectRaw('DATE_FORMAT(tgl_reservasi_wisata, "%Y-%m") as bulan, SUM(total_bayar) as total')
            ->whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Tambahkan data reservasi detail
        $reservasi = Reservasi::with(['pelanggan', 'paket'])
            ->whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->orderByDesc('created_at')
            ->get();

        $pdf = PDF::loadView('be.laporan.pdf', compact(
            'totalPendapatan', 'paketLaris', 'statistikPeserta', 'grafikPendapatan', 'reservasi'
        ));
        return $pdf->download('laporan-keuangan.pdf');
    }

    public function exportExcel()
    {
        $reservasi = Reservasi::with(['pelanggan', 'paket'])
            ->whereIn('status_reservasi_wisata', ['dibayar', 'selesai'])
            ->orderByDesc('created_at')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Pelanggan');
        $sheet->setCellValue('C1', 'Paket Wisata');
        $sheet->setCellValue('D1', 'Tgl Reservasi');
        $sheet->setCellValue('E1', 'Harga');
        $sheet->setCellValue('F1', 'Jumlah Peserta');
        $sheet->setCellValue('G1', 'Diskon');
        $sheet->setCellValue('H1', 'Total Bayar');
        $sheet->setCellValue('I1', 'Status');
        $sheet->setCellValue('J1', 'Dibuat');

        // Set data
        $row = 2;
        foreach ($reservasi as $i => $r) {
            $sheet->setCellValue('A'.$row, $i+1);
            $sheet->setCellValue('B'.$row, $r->pelanggan->nama_lengkap ?? '-');
            $sheet->setCellValue('C'.$row, $r->paket->nama_paket ?? '-');
            $sheet->setCellValue('D'.$row, $r->tgl_reservasi_wisata);
            $sheet->setCellValue('E'.$row, $r->harga);
            $sheet->setCellValue('F'.$row, $r->jumlah_peserta);
            if ($r->diskon) {
                $sheet->setCellValue('G'.$row, $r->diskon . '% (Rp' . number_format($r->nilai_diskon,0,',','.') . ')');
            } else {
                $sheet->setCellValue('G'.$row, '-');
            }
            $sheet->setCellValue('H'.$row, $r->total_bayar);
            $status = '-';
            if ($r->status_reservasi_wisata == 'pesan') $status = 'Pesan';
            elseif ($r->status_reservasi_wisata == 'dibayar') $status = 'Dibayar';
            elseif ($r->status_reservasi_wisata == 'selesai') $status = 'Selesai';
            $sheet->setCellValue('I'.$row, $status);
            $sheet->setCellValue('J'.$row, \Carbon\Carbon::parse($r->created_at)->format('d-m-Y'));
            $row++;
        }

        // Output to browser
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-keuangan.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}