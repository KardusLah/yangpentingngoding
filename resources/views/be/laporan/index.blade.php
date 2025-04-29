{{-- filepath: resources/views/be/laporan/index.blade.php --}}
{{-- @extends('be.master')
@section('content')
<div class="page-heading">
    <h3>Laporan Keuangan</h3>
</div>
<div class="page-content">
    <div class="mb-3">
        <a href="{{ route('laporan.exportPdf') }}" class="btn btn-danger">Export PDF</a>
        <a href="{{ route('laporan.exportExcel') }}" class="btn btn-success">Export Excel</a>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Pendapatan</h6>
                    <h3 class="text-success">Rp {{ number_format($totalPendapatan,0,',','.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Paket Wisata Paling Laris</h6>
                    <h3>{{ $paketLaris->paket->nama_paket ?? '-' }}</h3>
                    <small>{{ $paketLaris->jumlah ?? 0 }} Reservasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Statistik Peserta Wisata</h6>
                    <ul class="list-group">
                        @foreach($statistikPeserta as $row)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $row->paket->nama_paket ?? '-' }}
                            <span class="badge bg-primary rounded-pill">{{ $row->total_peserta }} peserta</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header"><h4>Grafik Pendapatan per Bulan</h4></div>
                <div class="card-body">
                    <canvas id="grafikPendapatan"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafikPendapatan').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($grafikPendapatan->pluck('bulan')) !!},
        datasets: [{
            label: 'Pendapatan',
            data: {!! json_encode($grafikPendapatan->pluck('total')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection --}}