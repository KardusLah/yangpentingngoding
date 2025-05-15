{{-- filepath: resources/views/fe/obyek/show.blade.php --}}
@extends('fe.master')
@section('content')

<div class="untree_co-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-8">
                <!-- Image Gallery -->
                <div class="owl-single dots-absolute owl-carousel mb-5">
                    @for($i=1; $i<=5; $i++)
                        @php $foto = 'foto'.$i; @endphp
                        @if($item->$foto)
                            <div class="item">
                                <img src="{{ asset('storage/'.$item->$foto) }}" alt="Image {{ $i }}" class="img-fluid rounded-20" style="height:500px;object-fit:cover;width:100%;">
                            </div>
                        @endif
                    @endfor
                </div>

                <!-- Description Section -->
                <div class="mb-5">
                    <h3 class="section-title">Deskripsi</h3>
                    <p class="lead">{{ $item->deskripsi_wisata }}</p>
                </div>

                <!-- Facilities Section -->
                <div class="mb-5">
                    <h3 class="section-title">Fasilitas</h3>
                    <div class="list-check">
                        @foreach(explode(',', $item->fasilitas) as $facility)
                            <li>{{ trim($facility) }}</li>
                        @endforeach
                    </div>
                </div>

                <!-- Back Button -->
                <div class="text-center mt-5">
                    <a href="{{ route('fe.wisata.index') }}" class="btn btn-primary">Kembali ke Daftar Wisata</a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="bg-white p-4 rounded-20 shadow mb-4">
                    <h3 class="section-title mb-4">Informasi Wisata</h3>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <strong class="d-block mb-1">Kategori:</strong>
                            <span class="badge bg-primary">{{ $item->kategori->kategori_wisata ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <strong class="d-block mb-1">Lokasi:</strong>
                            <span>{{ $item->deskripsi_wisata }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Additional Info (can be customized) -->
                <div class="bg-white p-4 rounded-20 shadow">
                    <h3 class="section-title mb-4">Tips Wisata</h3>
                    <div class="feature-1">
                        <h3>Waktu Terbaik Berkunjung</h3>
                        <p>Pagi hari atau sore hari untuk menghindari keramaian.</p>
                    </div>
                    <div class="feature-1">
                        <h3>Apa yang Harus Dibawa</h3>
                        <p>Bawa kamera, air minum, dan pakaian yang nyaman.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('fe.footer')
@endsection

@push('styles')
<style>
    .rounded-20 {
        border-radius: 20px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize owl carousel for image gallery
        $('.owl-single').owlCarousel({
            items: 1,
            loop: true,
            margin: 20,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true
        });
    });
</script>
@endpush