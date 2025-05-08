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
          <div class="card h-100">
            <img src="{{ $paket->foto1 ? asset('storage/'.$paket->foto1) : asset('fe/assets/images/hero-slider-1.jpg') }}" class="card-img-top" alt="{{ $paket->nama_paket }}">
            <div class="card-body">
              <h5 class="card-title">{{ $paket->nama_paket }}</h5>
              <p class="card-text">{{ Str::limit($paket->deskripsi, 80) }}</p>
              <div class="mb-2"><strong>Harga:</strong> Rp{{ number_format($paket->harga_per_pack) }}</div>
              <a href="{{ route('fe.reservasi.index', ['paket' => $paket->id]) }}" class="btn btn-sm btn-success">Pesan</a>
              <a href="{{ route('fe.reservasi.detail', $paket->id) }}" class="btn btn-sm btn-primary">Detail</a>
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