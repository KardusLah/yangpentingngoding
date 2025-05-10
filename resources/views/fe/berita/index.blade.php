{{-- filepath: resources/views/fe/berita/index.blade.php --}}
@extends('fe.master')

{{-- @section('navbar')
    @include('fe.navbar')
@endsection --}}

@section('content')
<div class="hero hero-inner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mx-auto text-center">
          <div class="intro-wrap">
            <h1 class="mb-0">Berita</h1>
            <p class="text-white">Cek terkait berita terkini </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
    <h2 class="mb-4">Berita & Info Wisata</h2>
    <div class="row">
        @foreach($berita as $item)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ $item->foto ? asset('storage/'.$item->foto) : asset('fe/assets/images/hero-slider-1.jpg') }}" class="card-img-top" alt="{{ $item->judul }}">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="icofont-calendar text-primary mr-2"></span>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($item->tgl_post)->format('d M Y') }}</small>
                    </div>
                    <h5 class="card-title">{{ $item->judul }}</h5>
                    <p class="card-text">{{ Str::limit($item->berita, 80) }}</p>
                    <a href="{{ route('fe.berita.show', $item->id) }}" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('footer')
    @include('fe.footer')
@endsection