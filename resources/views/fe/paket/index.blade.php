{{-- filepath: resources/views/fe/paket/index.blade.php --}}
@extends('fe.master')


@section('content')
<div class="hero hero-inner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mx-auto text-center">
          <div class="intro-wrap">
            <h1 class="mb-0">Paket Wisata</h1>
            <p class="text-white">Pilih destinasi favorit mu disini! </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
    {{-- FILTER --}}
    <form method="GET" action="{{ route('paket.index') }}" class="mb-4">
        <div class="row align-items-end g-2">
            <div class="col-md-3 mb-2">
                <select name="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori_wisata as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->kategori_wisata }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <select name="durasi" class="form-control">
                    <option value="">Semua Durasi</option>
                    <option value="1">1 Hari</option>
                    <option value="2">2 Hari</option>
                    <option value="3">3 Hari</option>
                    <option value="4">4 Hari+</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <input type="number" name="max_harga" class="form-control" placeholder="Harga Maksimal (Rp)">
            </div>
            <div class="col-md-3 mb-2">
                <button class="btn btn-primary w-100" type="submit">
                    <span class="icofont-filter"></span> Filter
                </button>
            </div>
        </div>
    </form>

    {{-- GRID PAKET WISATA --}}
    <div class="row">
        @forelse($pakets as $paket)
        <div class="col-md-4 mb-4">
            <div class="card paket-card h-100 shadow-sm">
                <img src="{{ $paket->foto1 ? asset('storage/'.$paket->foto1) : asset('fe/assets/images/hero-slider-1.jpg') }}"
                     class="paket-card-img" alt="{{ $paket->nama_paket }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="paket-title mb-1">{{ $paket->nama_paket }}</h5>
                    <div class="paket-harga mb-2">
                        <span><i class="icofont-price"></i> Rp{{ number_format($paket->harga_per_pack) }}/pack</span>
                        @if($paket->durasi)
                        <span class="ml-2"><i class="icofont-clock-time"></i> {{ $paket->durasi }} Hari</span>
                        @endif
                    </div>
                    <p class="card-text mb-2">{{ Str::limit($paket->deskripsi, 80) }}</p>
                    <div class="paket-fasilitas mb-3">
                        @foreach(explode(',', $paket->fasilitas) as $fasilitas)
                            <span class="badge mr-1 mb-1">
                                <i class="icofont-check-circled text-success"></i> {{ trim($fasilitas) }}
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-auto d-flex justify-content-between">
                        <a href="{{ route('fe.paket.show', $paket->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="icofont-eye"></i> Detail
                        </a>
                        <a href="{{ route('reservasi.create', ['paket' => $paket->id]) }}" class="btn btn-primary btn-sm">
                            <i class="icofont-cart"></i> Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted">Tidak ada paket wisata ditemukan.</div>
        @endforelse
    </div>
</div>
@endsection

@section('footer')
    @include('fe.footer')
@endsection