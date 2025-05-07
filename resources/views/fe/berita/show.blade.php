{{-- filepath: resources/views/fe/berita/show.blade.php --}}
@extends('fe.master')

@section('content')
<div class="hero hero-inner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mx-auto text-center">
          <div class="intro-wrap">
            <h1 class="mb-0">{{ $news->judul }}</h1>
            <p class="text-white">Cek terkait berita terkini </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-10">
            <article class="card shadow-lg border-0">
                @if($news->foto)
                <div class="position-relative">
                    <img src="{{ asset('storage/'.$news->foto) }}" class="card-img-top rounded-top" alt="{{ $news->judul }}" style="object-fit:cover; width:100%; max-height:350px; min-height:180px; background:#eee;">
                    <span class="badge bg-primary position-absolute top-0 start-0 m-3 px-3 py-2 fs-6 shadow" style="font-size:1rem; border-radius:0.5rem; opacity:0.95;">
                        <i class="icofont-calendar"></i>
                        {{ \Carbon\Carbon::parse($news->tgl_post)->format('d M Y') }}
                    </span>
                </div>
                @endif
                <div class="card-body p-4">
                    <h1 class="card-title mb-3 fw-bold" style="font-size:2rem; line-height:1.2;">{{ $news->judul }}</h1>
                    <div class="mb-3 text-muted small" style="font-size:1rem;">
                        @if($news->penulis)
                            <i class="icofont-user"></i> {{ $news->penulis }} &nbsp;|&nbsp;
                        @endif
                        <i class="icofont-clock-time"></i>
                        {{ \Carbon\Carbon::parse($news->tgl_post)->diffForHumans() }}
                    </div>
                    <hr>
                    <div class="card-text fs-5" style="line-height:1.8; font-size:1.15rem; color:#222; margin-bottom:2rem;">
                        {!! nl2br(e($news->berita)) !!}
                    </div>
                    <div class="mt-4 d-flex flex-wrap align-items-center gap-2">
                        <a href="{{ route('fe.berita.index') }}" class="btn btn-outline-primary">
                            <i class="icofont-arrow-left"></i> Kembali ke Daftar Berita
                        </a>
                        <span class="ms-auto text-muted">Bagikan:</span>
                        <div class="article-share-btns d-flex">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-light border me-2" style="border-radius:50%; width:38px; height:38px; display:flex; align-items:center; justify-content:center;">
                                <i class="icofont-facebook text-primary" style="font-size:1.2rem;"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($news->judul) }}" target="_blank" class="btn btn-light border me-2" style="border-radius:50%; width:38px; height:38px; display:flex; align-items:center; justify-content:center;">
                                <i class="icofont-twitter text-info" style="font-size:1.2rem;"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($news->judul.' '.request()->fullUrl()) }}" target="_blank" class="btn btn-light border" style="border-radius:50%; width:38px; height:38px; display:flex; align-items:center; justify-content:center;">
                                <i class="icofont-whatsapp text-success" style="font-size:1.2rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection