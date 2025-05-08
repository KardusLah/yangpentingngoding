@extends('fe.master')

@section('content')
<!-- Tambahkan padding top ekstra untuk menghindari navbar -->
<div class="container" style="padding-top: 80px; padding-bottom: 40px;">
    <div class="row">
        <div class="col-md-6">
            <!-- Gallery utama dengan carousel -->
            <div id="paketGallery" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    @if($paket->foto1)
                        <div class="carousel-item active">
                            <img src="{{ asset('storage/'.$paket->foto1) }}" class="d-block w-100" alt="{{ $paket->nama_paket }}">
                        </div>
                    @endif
                    @for($i=2; $i<=5; $i++)
                        @php $foto = 'foto'.$i; @endphp
                        @if($paket->$foto)
                            <div class="carousel-item">
                                <img src="{{ asset('storage/'.$paket->$foto) }}" class="d-block w-100" alt="Gallery image {{ $i }}">
                            </div>
                        @endif
                    @endfor
                </div>
                @if($paket->foto1 && ($paket->foto2 || $paket->foto3 || $paket->foto4 || $paket->foto5))
                    <button class="carousel-control-prev" type="button" data-bs-target="#paketGallery" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#paketGallery" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>

            <!-- Thumbnail gallery -->
            <div class="row g-2">
                @for($i=1; $i<=5; $i++)
                    @php $foto = 'foto'.$i; @endphp
                    @if($paket->$foto)
                        <div class="col-4 col-md-3">
                            <img src="{{ asset('storage/'.$paket->$foto) }}" 
                                 class="img-thumbnail cursor-pointer" 
                                 style="height: 80px; object-fit: cover;"
                                 onclick="document.getElementById('paketGallery').carousel.to({{ $i-1 }})"
                                 alt="Thumbnail {{ $i }}">
                        </div>
                    @endif
                @endfor
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="card-title mb-3">{{ $paket->nama_paket }}</h2>
                    <div class="mb-4">
                        <p class="card-text text-muted">{{ $paket->deskripsi }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="fw-bold">Fasilitas:</h5>
                        <div class="text-muted">{!! nl2br(e($paket->fasilitas)) !!}</div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-6">
                            <h5 class="fw-bold">Kategori:</h5>
                            <span class="badge bg-primary">
                                {{ $paket->kategori->kategori_wisata ?? '-' }}
                            </span>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-bold">Durasi:</h5>
                            <p>{{ $paket->durasi }} hari</p>
                        </div>
                    </div>
                    
                    <div class="bg-light p-3 rounded mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold mb-0">Harga:</h5>
                            @if($diskon)
                                <span class="text-danger text-decoration-line-through">
                                    Rp{{ number_format($paket->harga_per_pack) }}/hari
                                </span>
                            @endif
                        </div>
                        <h4 class="text-primary fw-bold">
                            Rp{{ number_format($diskon ? ($paket->harga_per_pack * (100 - $diskon->persen)/100) : $paket->harga_per_pack) }}/hari
                        </h4>
                        @if($diskon)
                            <span class="badge bg-success">
                                Diskon {{ $diskon->persen }}% (hingga {{ \Carbon\Carbon::parse($diskon->tanggal_akhir)->format('d M Y') }})
                            </span>
                        @endif
                    </div>
                    
                    @auth
                        <a href="{{ route('fe.reservasi.index', ['paket' => $paket->id]) }}" 
                           class="btn btn-primary btn-lg w-100 py-3">
                            <i class="fas fa-calendar-check me-2"></i> Pesan Sekarang
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg w-100 py-3">
                            <i class="fas fa-sign-in-alt me-2"></i> Login untuk Memesan
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Pastikan konten tidak tertutup navbar */
    body {
        padding-top: 70px; /* Sesuaikan dengan tinggi navbar Anda */
    }
    
    /* Untuk navbar fixed-top */
    .navbar.fixed-top {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1030;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Jika ada paketTerpilih, trigger event agar ringkasan dan tanggal update otomatis
        @if(isset($paketTerpilih) && $paketTerpilih)
            const paketSelect = document.getElementById('id_paket');
            if (paketSelect) {
                paketSelect.dispatchEvent(new Event('change'));
            }
        @endif
        
        // Inisialisasi carousel
        const myCarousel = document.querySelector('#paketGallery');
        if (myCarousel) {
            new bootstrap.Carousel(myCarousel);
        }
    });
</script>
@endsection