{{-- filepath: resources/views/fe/penginapan/index.blade.php --}}
@extends('fe.master')
@section('content')
<div class="hero hero-inner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mx-auto text-center">
          <div class="intro-wrap">
            <h1 class="mb-0">Penginapan</h1>
            <p class="text-white">Cek terkait informasi penginapan </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container" style="padding-top: 80px;">
    <h2 class="mb-4">Daftar Penginapan</h2>
    <div class="row">
        @forelse($penginapan as $p)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($p->foto1)
                <img src="{{ asset('storage/'.$p->foto1) }}" class="card-img-top" alt="{{ $p->nama_penginapan }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $p->nama_penginapan }}</h5>
                    <p class="card-text">{{ Str::limit($p->deskripsi, 80) }}</p>
                    <a href="{{ route('fe.penginapan.show', $p->id) }}" class="btn btn-sm btn-success">Detail</a>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center">Tidak ada penginapan ditemukan.</p>
        @endforelse
    </div>
</div>
@endsection
@section('footer')
    @include('fe.footer')
@endsection