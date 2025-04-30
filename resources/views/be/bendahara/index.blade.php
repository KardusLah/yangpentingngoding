@extends('be.master')
@section('content')
<div class="page-heading">
    <h3>Dashboard Bendahara</h3>
</div>
<div class="mb-3">
    <a href="{{ route('laporan.exportPdf') }}" class="btn btn-danger">Export PDF</a>
    <a href="{{ route('laporan.exportExcel') }}" class="btn btn-success">Export Excel</a>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon blue mb-2"><i class="bi bi-cash-stack fs-3"></i></div>
                            <h6 class="text-muted">Total Pendapatan</h6>
                            <h6 class="font-extrabold mb-0">Rp {{ number_format($totalPendapatan,0,',','.') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon green mb-2"><i class="bi bi-check-circle fs-3"></i></div>
                            <h6 class="text-muted">Reservasi Dibayar</h6>
                            <h6 class="font-extrabold mb-0">{{ $totalReservasiDibayar }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon red mb-2"><i class="bi bi-clock fs-3"></i></div>
                            <h6 class="text-muted">Menunggu Pembayaran</h6>
                            <h6 class="font-extrabold mb-0">{{ $totalReservasiMenunggu }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon purple mb-2"><i class="bi bi-star fs-3"></i></div>
                            <h6 class="text-muted">Paket Paling Laris</h6>
                            <h6 class="font-extrabold mb-0">{{ $paketLaris->paket->nama_paket ?? '-' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Grafik pendapatan bulanan --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header"><h4>Grafik Pendapatan Bulanan</h4></div>
                        <div class="card-body">
                            <canvas id="chartPendapatan"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Daftar reservasi untuk manajemen pembayaran --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header"><h4>Manajemen Pembayaran Reservasi</h4></div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Pelanggan</th>
                                        <th>Paket</th>
                                        <th>Peserta</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Bukti Bayar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservasi as $r)
                                    <tr>
                                        <td>{{ $r->pelanggan->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $r->paket->nama_paket ?? '-' }}</td>
                                        <td>{{ $r->jumlah_peserta }}</td>
                                        <td>Rp{{ number_format($r->total_bayar,0,',','.') }}</td>
                                        <td>
                                            @if($r->status_reservasi_wisata == 'pesan')
                                                <span class="badge bg-warning">Pesan</span>
                                            @elseif($r->status_reservasi_wisata == 'dibayar')
                                                <span class="badge bg-success">Dibayar</span>
                                            @elseif($r->status_reservasi_wisata == 'selesai')
                                                <span class="badge bg-info">Selesai</span>
                                            @elseif($r->status_reservasi_wisata == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($r->file_bukti_tf)
                                            <img src="{{ asset('storage/'.$r->file_bukti_tf) }}" width="40" style="cursor:pointer"
                                                 onclick="showImgPreview('{{ asset('storage/'.$r->file_bukti_tf) }}')">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('reservasi.index') }}" class="btn btn-light btn-sm d-flex align-items-center">
                                                Detail
                                            </a>
                                        </td>
                                        
                                        
                                        {{-- <td>
                                            @if($r->status_reservasi_wisata == 'pesan' && $r->file_bukti_tf)
                                                <form action="{{ route('bendahara.konfirmasi', $r->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pembayaran ini?')">Konfirmasi</button>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar kanan: Info User -->
        <div class="col-12 col-lg-3 mt-4 mt-lg-0">
            <div class="card h-100">
                <div class="card-header"><h5>Profil Bendahara</h5></div>
                <div class="card-body text-center">
                    @php
                    $user = Auth::user();
                    $foto = null;
                    if ($user->level == 'pelanggan' && $user->pelanggan && $user->pelanggan->foto) {
                        $foto = asset('storage/' . $user->pelanggan->foto);
                    } elseif ($user->karyawan && $user->karyawan->foto) {
                        $foto = asset('storage/' . $user->karyawan->foto);
                    } else {
                        $foto = asset('be/assets/images/faces/1.jpg');
                    }
                    @endphp
                    <img src="{{ $foto }}" alt="Foto Profil" class="rounded-circle mb-3" width="80" height="80">
                    @if(Auth::check())
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                        <div class="mt-2">
                            <span class="badge bg-primary">{{ ucfirst(Auth::user()->level) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
{{-- Grafik JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartPendapatan').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($pendapatanBulanan->pluck('bulan')) !!},
        datasets: [{
            label: 'Pendapatan',
            data: {!! json_encode($pendapatanBulanan->pluck('total')) !!},
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
<!-- Modal Preview Gambar -->
<div class="modal fade" id="imgPreviewModal" tabindex="-1" aria-labelledby="imgPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <img id="imgPreview" src="" alt="Preview" style="max-width:100%;max-height:70vh;">
        </div>
      </div>
    </div>
  </div>
  <script>
  function showImgPreview(src) {
      document.getElementById('imgPreview').src = src;
      var myModal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
      myModal.show();
  }
  </script>
@endsection