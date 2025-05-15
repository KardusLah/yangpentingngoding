{{-- filepath: resources/views/fe/paket/index.blade.php --}}
@extends('fe.master')

@section('content')
<div class="hero hero-inner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mx-auto text-center">
                <div class="intro-wrap">
                    <h1 class="mb-0">Paket Wisata</h1>
                    <p class="text-white">Pilih destinasi favorit mu disini!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    {{-- GRID PAKET WISATA --}}
    <div class="row">
        @forelse($pakets as $paket)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                {{-- Image Container dengan Fixed Aspect Ratio --}}
                <div class="ratio ratio-16x9">
                    <img src="{{ $paket->foto1 ? asset('storage/'.$paket->foto1) : asset('fe/assets/images/hero-slider-1.jpg') }}" 
                         class="card-img-top object-fit-cover" 
                         alt="{{ $paket->nama_paket }}">
                </div>
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $paket->nama_paket }}</h5>
                    <p class="card-text flex-grow-1">{{ Str::limit($paket->deskripsi, 80) }}</p>
                    <div class="mb-2"><strong>Harga:</strong> Rp{{ number_format($paket->harga_per_pack) }}</div>
                    <div class="d-grid gap-2 d-md-block">
                        <a href="{{ route('fe.reservasi.index', ['paket' => $paket->id]) }}" 
                           class="btn btn-sm btn-success">Pesan</a>
                        <a href="{{ route('fe.reservasi.detail', $paket->id) }}" 
                           class="btn btn-sm btn-primary">Detail</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                <p class="h4">Tidak ada paket wisata ditemukan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('footer')
    @include('fe.footer')
@endsection

@push('styles')
<style>
    .object-fit-cover {
        object-fit: cover;
        object-position: center;
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
    }
    
    .card-title {
        min-height: 3rem;
    }
</style>
@endpush