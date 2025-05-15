{{-- filepath: resources/views/fe/penginapan/show.blade.php --}}
@extends('fe.master')
@section('content')

<div class="untree_co-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-7">
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
                    <h3 class="section-title">Tentang Penginapan</h3>
                    <div class="feature-1">
                        <p class="lead">{{ $item->deskripsi }}</p>
                    </div>
                </div>

                <!-- Gallery Section -->
                <div class="mb-5" id="gallery">
                    <h3 class="section-title">Galeri Penginapan</h3>
                    <div class="row gutter-v2 gallery">
                        @for($i=1; $i<=5; $i++)
                            @php $foto = 'foto'.$i; @endphp
                            @if($item->$foto)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <a href="{{ asset('storage/'.$item->$foto) }}" class="gal-item" data-fancybox="gallery">
                                        <img src="{{ asset('storage/'.$item->$foto) }}" alt="Penginapan {{ $i }}" class="img-fluid rounded-20" style="height:200px;object-fit:cover;width:100%;">
                                    </a>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Facilities Section -->
                <div class="bg-white p-4 rounded-20 shadow mb-4" id="facilities">
                    <h3 class="section-title mb-4">Fasilitas</h3>
                    <div class="list-check">
                        @foreach(explode(',', $item->fasilitas) as $facility)
                            <li>{{ trim($facility) }}</li>
                        @endforeach
                    </div>
                </div>

                <!-- Contact/Booking Section -->
                <div class="bg-white p-4 rounded-20 shadow">
                    <h3 class="section-title mb-4">Informasi Booking</h3>
                    <div class="feature-1">
                        <p>Untuk informasi lebih lanjut atau reservasi, silakan hubungi:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-phone mr-2"></i> +62 123 4567 8910</li>
                            <li class="mb-2"><i class="fas fa-envelope mr-2"></i> info@penginapan.com</li>
                            <li><i class="fas fa-map-marker-alt mr-2"></i> Alamat Penginapan</li>
                        </ul>
                    </div>
                    <a href="#" class="btn btn-primary btn-block mt-3">Pesan Sekarang</a>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-5">
            <a href="{{ route('fe.penginapan.index') }}" class="btn btn-outline-primary">Kembali ke Daftar Penginapan</a>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('fe.footer')
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
<style>
    .rounded-20 {
        border-radius: 20px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize fancybox for gallery
        $('[data-fancybox="gallery"]').fancybox({
            buttons: [
                "zoom",
                "share",
                "slideShow",
                "fullScreen",
                "download",
                "thumbs",
                "close"
            ]
        });
    });
</script>
@endpush