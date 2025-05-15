{{-- filepath: resources/views/fe/obyek/index.blade.php --}}
@extends('fe.master')
@section('content')
<div class="hero hero-inner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mx-auto text-center">
          <div class="intro-wrap">
            <h1 class="mb-0">Objek Wisata</h1>
            <p class="text-white">Cek terkait Objek Wisata </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
    <div class="row">
        @forelse($wisata as $w)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($w->foto1)
                <img src="{{ asset('storage/'.$w->foto1) }}" class="card-img-top" alt="{{ $w->nama_wisata }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $w->nama_wisata }}</h5>
                    <span class="badge bg-info mb-2"><i class="fa fa-tag"></i> {{ $w->kategori->kategori_wisata ?? '-' }}</span>
                    <p class="card-text">{{ Str::limit($w->deskripsi_wisata, 80) }}</p>
                    <a href="{{ route('fe.wisata.show', $w->id) }}" class="btn btn-sm btn-primary">Detail</a>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center">Tidak ada objek wisata ditemukan.</p>
        @endforelse
    </div>
</div>
@endsection
@section('footer')
    @include('fe.footer')
@endsection