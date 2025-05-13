{{-- filepath: c:\xampp\htdocs\WISATA\reservasi-online\resources\views\be\laporan\pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .section { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 5px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <div class="title">Laporan Keuangan</div>
    <div class="section">
        <strong>Total Pendapatan:</strong> Rp {{ number_format($totalPendapatan,0,',','.') }}
    </div>
    <div class="section">
        <strong>Paket Wisata Paling Laris:</strong> {{ $paketLaris->paket->nama_paket ?? '-' }} ({{ $paketLaris->jumlah ?? 0 }} Reservasi)
    </div>
    <div class="section">
        <strong>Statistik Peserta Wisata:</strong>
        <table>
            <thead>
                <tr>
                    <th>Paket Wisata</th>
                    <th>Total Peserta</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statistikPeserta as $row)
                <tr>
                    <td>{{ $row->paket->nama_paket ?? '-' }}</td>
                    <td>{{ $row->total_peserta }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="section">
        <strong>Grafik Pendapatan per Bulan:</strong>
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grafikPendapatan as $row)
                <tr>
                    <td>{{ $row->bulan }}</td>
                    <td>Rp {{ number_format($row->total,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="section">
        <strong>Daftar Reservasi:</strong>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Paket Wisata</th>
                    <th>Tgl Reservasi</th>
                    <th>Harga</th>
                    <th>Jumlah Peserta</th>
                    <th>Diskon</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservasi as $i => $r)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $r->pelanggan->nama_lengkap ?? '-' }}</td>
                    <td>{{ $r->paket->nama_paket ?? '-' }}</td>
                    <td>{{ $r->tgl_reservasi_wisata }}</td>
                    <td>Rp{{ number_format($r->harga,0,',','.') }}</td>
                    <td>{{ $r->jumlah_peserta }}</td>
                    <td>
                        @if($r->diskon)
                            {{ $r->diskon }}% (Rp{{ number_format($r->nilai_diskon,0,',','.') }})
                        @else
                            -
                        @endif
                    </td>
                    <td>Rp{{ number_format($r->total_bayar,0,',','.') }}</td>
                    <td>
                        @if($r->status_reservasi_wisata == 'pesan')
                            Pesan
                        @elseif($r->status_reservasi_wisata == 'dibayar')
                            Dibayar
                        @elseif($r->status_reservasi_wisata == 'selesai')
                            Selesai
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d-m-Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>