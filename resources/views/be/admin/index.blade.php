@extends('be.master')
@section('content')
<div class="page-heading">
    <h3>Dashboard & Statistik</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body px-4 py-4-5 d-flex flex-column align-items-center">
                            <div class="stats-icon purple mb-2 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="bi bi-box fs-3"></i>
                            </div>
                            <h6 class="text-muted font-semibold text-center">Paket Wisata</h6>
                            <h6 class="font-extrabold mb-0 text-center">{{ $totalPaket }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body px-4 py-4-5 d-flex flex-column align-items-center">
                            <div class="stats-icon blue mb-2 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="bi bi-calendar-check fs-3"></i>
                            </div>
                            <h6 class="text-muted font-semibold text-center">Reservasi</h6>
                            <h6 class="font-extrabold mb-0 text-center">{{ $totalReservasi }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body px-4 py-4-5 d-flex flex-column align-items-center">
                            <div class="stats-icon green mb-2 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="bi bi-geo-alt fs-3"></i>
                            </div>
                            <h6 class="text-muted font-semibold text-center">Objek Wisata</h6>
                            <h6 class="font-extrabold mb-0 text-center">{{ $totalWisata }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body px-4 py-4-5 d-flex flex-column align-items-center">
                            <div class="stats-icon red mb-2 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="bi bi-house-door fs-3"></i>
                            </div>
                            <h6 class="text-muted font-semibold text-center">Penginapan</h6>
                            <h6 class="font-extrabold mb-0 text-center">{{ $totalPenginapan }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Statistik Keuangan -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Total Pendapatan Reservasi</h4>
                        </div>
                        <div class="card-body">
                            <h2 class="text-success">Rp {{ number_format($totalPendapatan,0,',','.') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Statistik Reservasi per Paket -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Statistik Reservasi per Paket Wisata</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Paket Wisata</th>
                                        <th>Jumlah Reservasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservasiPerPaket as $row)
                                    <tr>
                                        <td>{{ $row->paket->nama_paket ?? '-' }}</td>
                                        <td>{{ $row->jumlah }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar kanan (optional, bisa diisi info user, dsb) -->
        <div class="col-12 col-lg-3 mt-4 mt-lg-0">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Profil User</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('be/assets/images/faces/1.jpg') }}" alt="Foto Profil" class="rounded-circle mb-3" width="80" height="80">
                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                    <div class="mt-2">
                        <span class="badge bg-primary">{{ ucfirst(Auth::user()->level) }}</span>
                    </div>
                    {{-- Tambahkan info lain jika perlu --}}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection