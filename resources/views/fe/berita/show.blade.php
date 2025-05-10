@extends('fe.master')

@section('content')
<div class="news-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e0e0e0;">
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 0;">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('fe.berita.index') }}">Berita</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($news->judul, 50) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="news-article">
                <h1 class="article-title mb-3" style="font-size: 2.2rem; font-weight: 700; line-height: 1.3;">{{ $news->judul }}</h1>
                
                <div class="article-meta mb-4">
                    <div class="d-flex align-items-center flex-wrap">
                        @if($news->penulis)
                        <span class="d-flex align-items-center me-3">
                            <i class="icofont-user me-1"></i> {{ $news->penulis }}
                        </span>
                        @endif
                        <span class="d-flex align-items-center me-3">
                            <i class="icofont-calendar me-1"></i> 
                            {{ \Carbon\Carbon::parse($news->tgl_post)->translatedFormat('l, d F Y') }}
                        </span>
                        <span class="d-flex align-items-center">
                            <i class="icofont-clock-time me-1"></i>
                            {{ \Carbon\Carbon::parse($news->tgl_post)->diffForHumans() }}
                        </span>
                    </div>
                </div>

                @if($news->foto)
                <figure class="article-image mb-4">
                    <img src="{{ asset('storage/'.$news->foto) }}" class="img-fluid rounded" alt="{{ $news->judul }}" style="width: 100%; max-height: 500px; object-fit: cover;">
                    @if($news->caption_foto)
                    <figcaption class="mt-2 text-muted small">{{ $news->caption_foto }}</figcaption>
                    @endif
                </figure>
                @endif

                <div class="article-content" style="font-size: 1.1rem; line-height: 1.8; color: #333;">
                    {!! nl2br(e($news->berita)) !!}
                </div>

                <div class="article-tags mt-4 mb-5">
                    <!-- Jika ada tags/kategori -->
                    <span class="badge bg-secondary me-1">#Berita</span>
                    <span class="badge bg-secondary me-1">#Update</span>
                </div>

                <div class="article-share border-top pt-4">
                    <h6 class="mb-3">Bagikan Berita Ini:</h6>
                    <div class="d-flex">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2 d-flex align-items-center">
                            <i class="icofont-facebook me-1"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($news->judul) }}" target="_blank" class="btn btn-sm btn-outline-info me-2 d-flex align-items-center">
                            <i class="icofont-twitter me-1"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($news->judul.' '.request()->fullUrl()) }}" target="_blank" class="btn btn-sm btn-outline-success d-flex align-items-center">
                            <i class="icofont-whatsapp me-1"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </article>

            <!-- Related News Section -->
            <div class="related-news mt-5 pt-4 border-top">
                <h4 class="mb-4">Berita Terkait</h4>
                <div class="row">
                    @forelse($relatedNews as $item)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <img src="{{ $item->foto ? asset('storage/'.$item->foto) : 'https://via.placeholder.com/300x200' }}" class="card-img-top" alt="{{ $item->judul }}">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('fe.berita.show', $item->id) }}" class="text-dark">{{ Str::limit($item->judul, 50) }}</a>
                                </h5>
                                <p class="card-text text-muted small">{{ \Carbon\Carbon::parse($item->tgl_post)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted">Tidak ada berita terkait.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="news-sidebar">
                <!-- Popular News -->
                <div class="sidebar-widget mb-5">
                    <h5 class="widget-title mb-4 pb-2 border-bottom">Berita Populer</h5>
                    <div class="popular-news-list">
                        @foreach($popularNews as $pop)
                        <div class="popular-news-item d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{ $pop->foto ? asset('storage/'.$pop->foto) : 'https://via.placeholder.com/80x60' }}" alt="{{ $pop->judul }}" width="80" class="rounded">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1"><a href="{{ route('fe.berita.show', $pop->id) }}" class="text-dark">{{ Str::limit($pop->judul, 40) }}</a></h6>
                                <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($pop->tgl_post)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Categories -->
                <div class="sidebar-widget mb-5">
                    <h5 class="widget-title mb-4 pb-2 border-bottom">Kategori</h5>
                    <ul class="category-list list-unstyled">
                        @foreach($categories as $cat)
                        <li class="mb-2">
                            <a href="{{ route('fe.berita.index', ['kategori' => $cat->id]) }}" class="d-flex justify-content-between align-items-center">
                                {{ $cat->kategori_berita }}
                                <span class="badge bg-primary rounded-pill">{{ $cat->berita_count }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="sidebar-widget">
                    <h5 class="widget-title mb-4 pb-2 border-bottom">Newsletter</h5>
                    <p class="small text-muted">Dapatkan update berita terbaru langsung ke email Anda.</p>
                    <form>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Alamat Email">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Berlangganan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('fe.footer')
@endsection