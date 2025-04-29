{{-- filepath: resources/views/be/laporan/pdf.blade.php --}}
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
</body>
</html>