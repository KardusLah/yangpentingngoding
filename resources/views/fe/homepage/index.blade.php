@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="intro-wrap">
          <h1 class="mb-5"><span class="d-block">Temukan Wisata Impianmu</span> Bersama <span class="typed-words">Wisata Kami</span></h1>
          <form class="form" action="{{ route('reservasi.store') }}" method="POST">
            @csrf
            <div class="row mb-2">
                <div class="col-lg-3 mb-2">
                    <select name="id_paket" id="id_paket" class="form-control" required>
                        <option value="">Pilih Paket Wisata</option>
                        @foreach($pakets as $paket)
                          <option value="{{ $paket->id }}"
                            data-harga="{{ $paket->harga_per_pack }}"
                            data-durasi="{{ $paket->durasi }}"
                            data-diskon='@json(($diskon[$paket->id] ?? collect())->map(function($d){
                                return [
                                    'persen' => $d->persen,
                                    'mulai' => $d->tanggal_mulai,
                                    'akhir' => $d->tanggal_akhir
                                ];
                            })->values())'
                          >{{ $paket->nama_paket }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 mb-2">
                    <input type="number" class="form-control" name="jumlah_peserta" id="jumlah_peserta" placeholder="Jumlah Peserta" min="1" required>
                </div>
                <div class="col-lg-3 mb-2">
                    <input type="date" class="form-control" name="tgl_mulai" id="tgl_mulai" required placeholder="Tanggal Mulai">
                </div>
                <div class="col-lg-3 mb-2">
                    <input type="date" class="form-control" name="tgl_akhir" id="tgl_akhir" required placeholder="Tanggal Akhir">
                </div>
                <div class="col-lg-4 mb-2">
                    <label for="total_harga" class="form-label">Total Harga</label>
                    <input type="text" class="form-control" id="total_harga" placeholder="Total Harga" readonly>
                    <input type="hidden" id="total_harga_raw" name="total_harga">
                    <div id="diskon_info" class="small text-success"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Pesan Sekarang</button>
        </form>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="slides">
          <img src="{{ asset('fe/assets/images/hero-slider-1.jpg') }}" alt="Image" class="img-fluid active">
        </div>
      </div>
    </div>
  </div>
</div>

{{-- FORM PENCARIAN DESTINASI MINIMALIS --}}
<div class="container py-4">
  <form action="{{ route('fe.paket.index') }}" method="GET"
    class="d-flex flex-wrap align-items-center justify-content-center shadow-sm rounded-pill bg-white px-3 py-2 w-100 search-bar-custom"
    style="max-width:1100px; margin:0 auto;">
    <input type="text" name="lokasi"
      class="form-control border-0 rounded-pill mb-2 mb-md-0 mr-md-2 flex-grow-1"
      placeholder="Cari Lokasi" style="min-width:120px; max-width:300px;">
    <select name="kategori"
      class="form-control border-0 rounded-pill mb-2 mb-md-0 mr-md-2"
      style="min-width:120px; max-width:220px;">
      <option value="">Semua Kategori</option>
      @foreach($kategori_wisata as $kategori)
        <option value="{{ $kategori->id }}">{{ $kategori->kategori_wisata }}</option>
      @endforeach
    </select>
    <input type="date" name="tanggal"
      class="form-control border-0 rounded-pill mb-2 mb-md-0 mr-md-2"
      style="min-width:120px; max-width:200px;">
    <button class="btn btn-primary rounded-pill d-flex align-items-center px-4"
      type="submit" style="height:42px;">
      <span class="icofont-search mr-1"></span> Cari
    </button>
  </form>
</div>


{{-- Paket Wisata --}}
<div class="untree_co-section">
  <div class="container">
    <div class="row mb-4">
      <div class="col-lg-8">
        <h2 class="section-title">Paket Wisata</h2>
      </div>
      <div class="col-lg-4 text-right">
        <a href="{{ route('paket.index') }}" class="btn btn-outline-third btn-sm">Lihat Semua Paket</a>
      </div>
    </div>
    <div class="row">
      @foreach($pakets as $paket)
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
      @endforeach
    </div>
  </div>
</div>

{{-- Obyek Wisata --}}
<div class="untree_co-section bg-light">
  <div class="container">
    <div class="row mb-4">
      <div class="col-lg-8">
        <h2 class="section-title">Obyek Wisata</h2>
      </div>
      <div class="col-lg-4 text-right">
        <a href="{{ route('fe.paket.index') }}" class="btn btn-outline-third btn-sm">Lihat Semua Obyek</a>
      </div>
    </div>
    <div class="row">
      @foreach($destinations as $destination)
      <div class="col-md-3 mb-4">
        <div class="card h-100">
          <img src="{{ $destination->foto ? asset('storage/'.$destination->foto) : asset('fe/assets/images/hero-slider-2.jpg') }}" class="card-img-top" alt="{{ $destination->nama_wisata }}">
          <div class="card-body">
            <h5 class="card-title">{{ $destination->nama_wisata }}</h5>
            <a href="{{ route('wisata.show', $destination->id) }}" class="btn btn-sm btn-primary">Detail</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- Penginapan --}}
<div class="untree_co-section">
  <div class="container">
    <div class="row mb-4">
      <div class="col-lg-8">
        <h2 class="section-title">Informasi Penginapan</h2>
      </div>
      <div class="col-lg-4 text-right">
        <a href="{{ route('fe.penginapan.index') }}" class="btn btn-outline-third btn-sm">Lihat Semua Penginapan</a>
      </div>
    </div>
    <div class="row">
      @foreach($penginapan as $item)
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="{{ $item->foto1 ? asset('storage/'.$item->foto1) : asset('fe/assets/images/hero-slider-3.jpg') }}" class="card-img-top" alt="{{ $item->nama_penginapan }}">
          <div class="card-body">
            <h5 class="card-title">{{ $item->nama_penginapan }}</h5>
            <p class="card-text">{{ Str::limit($item->deskripsi, 80) }}</p>
            <a href="{{ route('fe.penginapan.show', $item->id) }}" class="btn btn-sm btn-primary">Detail</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- Berita --}}
<div class="untree_co-section bg-light">
  <div class="container">
    <div class="row mb-4">
      <div class="col-lg-8">
        <h2 class="section-title">Informasi Berita</h2>
      </div>
      <div class="col-lg-4 text-right">
        <a href="{{ route('fe.berita.index') }}" class="btn btn-outline-third btn-sm">Lihat Semua Berita</a>
      </div>
    </div>
    <div class="row">
      @foreach($berita as $news)
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="{{ $news->foto ? asset('storage/'.$news->foto) : asset('fe/assets/images/hero-slider-4.jpg') }}" class="card-img-top" alt="{{ $news->judul }}">
          <div class="card-body">
            <h5 class="card-title">{{ $news->judul }}</h5>
            <p class="card-text">{{ Str::limit($news->berita, 80) }}</p>
            <a href="{{ route('fe.berita.show', $news->id) }}" class="btn btn-sm btn-primary">Detail</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection

@section('footer')
    @include('fe.footer')
@endsection

@section('scripts')
<script>
function getMaxDurasi() {
    const select = document.getElementById('id_paket');
    const opt = select.options[select.selectedIndex];
    return parseInt(opt?.getAttribute('data-durasi') || 1);
}
function formatRupiah(angka) {
    return angka.toLocaleString('id-ID', {style:'currency', currency:'IDR', minimumFractionDigits:0});
}
function getDiskon(paketDiskon, tglMulai, tglAkhir) {
    if (!paketDiskon || !Array.isArray(paketDiskon)) return 0;
    let diskonAktif = 0;
    paketDiskon.forEach(function(d) {
        if (!d.persen) return;
        if ((!d.mulai || tglMulai >= d.mulai) && (!d.akhir || tglAkhir <= d.akhir)) {
            diskonAktif = Math.max(diskonAktif, d.persen);
        }
    });
    return diskonAktif;
}

function updateTanggalAkhir() {
    const tglMulai = document.getElementById('tgl_mulai').value;
    const maxDurasi = getMaxDurasi();
    const tglAkhirInput = document.getElementById('tgl_akhir');
    if (tglMulai) {
        const min = tglMulai;
        const max = new Date(new Date(tglMulai).getTime() + (maxDurasi-1)*24*60*60*1000);
        tglAkhirInput.setAttribute('min', min);
        tglAkhirInput.setAttribute('max', max.toISOString().slice(0,10));
        if (maxDurasi === 1) {
            tglAkhirInput.value = tglMulai;
            tglAkhirInput.setAttribute('readonly', true);
        } else {
            if (!tglAkhirInput.value || tglAkhirInput.value < min || tglAkhirInput.value > max.toISOString().slice(0,10)) {
                tglAkhirInput.value = '';
            }
            tglAkhirInput.removeAttribute('readonly');
        }
    } else {
        tglAkhirInput.value = '';
        tglAkhirInput.removeAttribute('readonly');
    }
}

function updateHarga() {
    const select = document.getElementById('id_paket');
    const jumlah = parseInt(document.getElementById('jumlah_peserta').value) || 0;
    const harga = parseInt(select.options[select.selectedIndex]?.getAttribute('data-harga')) || 0;
    const tglMulai = document.getElementById('tgl_mulai').value;
    const tglAkhir = document.getElementById('tgl_akhir').value;
    let lama = 0;
    if (tglMulai && tglAkhir) {
        const start = new Date(tglMulai);
        const end = new Date(tglAkhir);
        lama = Math.floor((end - start) / (1000*60*60*24)) + 1;
        if (lama < 1) lama = 1;
        const maxDurasi = getMaxDurasi();
        if (lama > maxDurasi) lama = maxDurasi;
    }
    let total = harga * jumlah * lama;
    let diskonInfo = '';
    let diskonPersen = 0;

    // Ambil diskon dari data attribute
    let paketDiskon = [];
    try {
        paketDiskon = JSON.parse(select.options[select.selectedIndex]?.getAttribute('data-diskon') || '[]');
    } catch(e) {}

    if (paketDiskon.length && tglMulai && tglAkhir) {
        diskonPersen = getDiskon(paketDiskon, tglMulai, tglAkhir);
        if (diskonPersen > 0) {
            let potongan = Math.round(total * diskonPersen / 100);
            diskonInfo = `Diskon ${diskonPersen}% (-${formatRupiah(potongan)})`;
            total = total - potongan;
        }
    }

    document.getElementById('total_harga').value = total ? formatRupiah(total) : '';
    document.getElementById('total_harga_raw').value = total;
    document.getElementById('diskon_info').innerText = diskonInfo;
}

document.getElementById('id_paket').addEventListener('change', function() {
    updateTanggalAkhir();
    updateHarga();
});
document.getElementById('tgl_mulai').addEventListener('change', function() {
    updateTanggalAkhir();
    updateHarga();
});
document.getElementById('tgl_akhir').addEventListener('change', updateHarga);
document.getElementById('jumlah_peserta').addEventListener('input', updateHarga);
</script>
@endsection