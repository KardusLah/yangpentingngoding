@extends('fe.master')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            @if($paket->foto1)
                <img src="{{ asset('storage/'.$paket->foto1) }}" class="img-fluid mb-3 rounded" alt="{{ $paket->nama_paket }}">
            @endif
            @for($i=2; $i<=5; $i++)
                @php $foto = 'foto'.$i; @endphp
                @if($paket->$foto)
                    <img src="{{ asset('storage/'.$paket->$foto) }}" class="img-fluid mb-2 mr-2 rounded" style="max-width:120px;" alt="">
                @endif
            @endfor
        </div>
        <div class="col-md-6">
            <h2>{{ $paket->nama_paket }}</h2>
            <p>{{ $paket->deskripsi }}</p>
            <p><strong>Fasilitas:</strong> {{ $paket->fasilitas }}</p>
            <p><strong>Kategori:</strong> {{ $paket->kategori->kategori_wisata ?? '-' }}</p>
            <p><strong>Harga per Hari:</strong> Rp{{ number_format($paket->harga_per_pack) }}</p>
            <p><strong>Durasi Maksimal Booking:</strong> {{ $paket->durasi }} hari</p>
            @if($diskon)
                <p class="text-success"><strong>Diskon:</strong> {{ $diskon->persen }}% ({{ $diskon->tanggal_mulai }} s/d {{ $diskon->tanggal_akhir }})</p>
            @endif
            <a href="{{ route('fe.reservasi.index', ['paket' => $paket->id]) }}" class="btn btn-primary mt-3">Pesan Sekarang</a>
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Jika ada paketTerpilih, trigger event agar ringkasan dan tanggal update otomatis
        @if(isset($paketTerpilih) && $paketTerpilih)
            document.getElementById('id_paket').dispatchEvent(new Event('change'));
        @endif
    });
</script>