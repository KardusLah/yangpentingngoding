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
        $totalPendapatan = Reservasi::where('status_reservasi_wisata', 'dibayar')->sum('total_bayar');

        $paketLaris = Reservasi::selectRaw('id_paket, COUNT(*) as jumlah')
            ->where('status_reservasi_wisata', 'dibayar')
            ->groupBy('id_paket')
            ->orderByDesc('jumlah')
            ->with('paket')
            ->first();

        $statistikPeserta = Reservasi::selectRaw('id_paket, SUM(jumlah_peserta) as total_peserta')
            ->where('status_reservasi_wisata', 'dibayar')
            ->groupBy('id_paket')
            ->with('paket')
            ->get();

        $grafikPendapatan = Reservasi::selectRaw('DATE_FORMAT(tgl_reservasi_wisata, "%Y-%m") as bulan, SUM(total_bayar) as total')
            ->where('status_reservasi_wisata', 'dibayar')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('be.laporan.index', compact(
            'totalPendapatan', 'paketLaris', 'statistikPeserta', 'grafikPendapatan'
        ));
    }

    public function exportPdf()
    {
        $totalPendapatan = Reservasi::where('status_reservasi_wisata', 'dibayar')->sum('total_bayar');
        $paketLaris = Reservasi::selectRaw('id_paket, COUNT(*) as jumlah')
            ->where('status_reservasi_wisata', 'dibayar')
            ->groupBy('id_paket')
            ->orderByDesc('jumlah')
            ->with('paket')
            ->first();
        $statistikPeserta = Reservasi::selectRaw('id_paket, SUM(jumlah_peserta) as total_peserta')
            ->where('status_reservasi_wisata', 'dibayar')
            ->groupBy('id_paket')
            ->with('paket')
            ->get();
        $grafikPendapatan = Reservasi::selectRaw('DATE_FORMAT(tgl_reservasi_wisata, "%Y-%m") as bulan, SUM(total_bayar) as total')
            ->where('status_reservasi_wisata', 'dibayar')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $pdf = PDF::loadView('be.laporan.pdf', compact(
            'totalPendapatan', 'paketLaris', 'statistikPeserta', 'grafikPendapatan'
        ));
        return $pdf->download('laporan-keuangan.pdf');
    }

    public function exportExcel()
    {
        $reservasi = Reservasi::where('status_reservasi_wisata', 'dibayar')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'ID Pelanggan');
        $sheet->setCellValue('C1', 'ID Paket');
        $sheet->setCellValue('D1', 'Tanggal Reservasi');
        $sheet->setCellValue('E1', 'Jumlah Peserta');
        $sheet->setCellValue('F1', 'Total Bayar');

        // Set data
        $row = 2;
        foreach ($reservasi as $r) {
            $sheet->setCellValue('A'.$row, $r->id);
            $sheet->setCellValue('B'.$row, $r->id_pelanggan);
            $sheet->setCellValue('C'.$row, $r->id_paket);
            $sheet->setCellValue('D'.$row, $r->tgl_reservasi_wisata);
            $sheet->setCellValue('E'.$row, $r->jumlah_peserta);
            $sheet->setCellValue('F'.$row, $r->total_bayar);
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