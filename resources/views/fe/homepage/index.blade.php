{{-- filepath: c:\xampp\htdocs\WISATA\reservasi-online\resources\views\fe\homepage\index.blade.php --}}
@extends('fe.master')

{{-- @section('navbar')
    @include('fe.navbar')
@endsection --}}

@section('content')
<div class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="intro-wrap">
          <h1 class="mb-5"><span class="d-block">Temukan Wisata Impianmu</span> Bersama <span class="typed-words">Wisata Kami</span></h1>
          <form class="form" id="form-homepage-booking" action="javascript:void(0);" method="POST">
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
                                {{ (request('paket') == $paket->id || old('id_paket') == $paket->id) ? 'selected' : '' }}
                            >{{ $paket->nama_paket }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 mb-2">
                    <input type="number" class="form-control" name="jumlah_peserta" id="jumlah_peserta" placeholder="Jumlah Peserta" min="1" required
                        value="{{ request('jumlah_peserta') ?? old('jumlah_peserta') }}">
                </div>
                <div class="col-lg-3 mb-2">
                    <input type="text" class="form-control" name="tgl_mulai" id="tgl_mulai" required placeholder="Tanggal Mulai"
                        value="{{ request('tgl_mulai') ?? old('tgl_mulai') }}" autocomplete="off">
                </div>
                <div class="col-lg-3 mb-2">
                    <input type="text" class="form-control" name="tgl_akhir" id="tgl_akhir" required placeholder="Tanggal Akhir"
                        value="{{ request('tgl_akhir') ?? old('tgl_akhir') }}" autocomplete="off">
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
        <a href="{{ route('fe.wisata.index') }}" class="btn btn-outline-third btn-sm">Lihat Semua Obyek</a>
      </div>
    </div>
    <div class="row">
      @foreach($wisata as $destination)
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="{{ $destination->foto1 ? asset('storage/'.$destination->foto1) : asset('fe/assets/images/hero-slider-2.jpg') }}" class="card-img-top" alt="{{ $destination->nama_wisata }}">
          <div class="card-body">
            <h5 class="card-title">{{ $destination->nama_wisata }}</h5>
            <a href="{{ route('fe.wisata.show', $destination->id) }}" class="btn btn-sm btn-primary">Detail</a>
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

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalPenuh = @json($tanggalPenuh ?? []);
    const paketSelect = document.getElementById('id_paket');
    const tglMulaiInput = document.getElementById('tgl_mulai');
    const tglAkhirInput = document.getElementById('tgl_akhir');
    const jumlahPeserta = document.getElementById('jumlah_peserta');
    const totalHargaInput = document.getElementById('total_harga');
    const totalHargaRaw = document.getElementById('total_harga_raw');
    const diskonInfo = document.getElementById('diskon_info');

    function getTanggalPenuh() {
        const paketId = paketSelect.value;
        return tanggalPenuh[paketId] || [];
    }
    function getMaxDurasi() {
        const opt = paketSelect.options[paketSelect.selectedIndex];
        return parseInt(opt?.getAttribute('data-durasi') || 1);
    }
    function getHargaDanDiskon() {
        const opt = paketSelect.options[paketSelect.selectedIndex];
        const harga = parseInt(opt?.getAttribute('data-harga')) || 0;
        const diskonData = opt ? JSON.parse(opt.getAttribute('data-diskon')) : [];
        return { harga, diskonData };
    }
    function formatRupiah(angka) {
        if (isNaN(angka) || angka === null) return '-';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(angka);
    }

    function hitungTotal() {
        // Pastikan semua input terisi sebelum hitung
        if (!paketSelect.value || !jumlahPeserta.value || !tglMulaiInput.value || !tglAkhirInput.value) {
            totalHargaInput.value = '';
            totalHargaRaw.value = '';
            diskonInfo.innerText = '';
            return;
        }
        const { harga, diskonData } = getHargaDanDiskon();
        const jumlah = parseInt(jumlahPeserta.value) || 0;
        const tglMulai = tglMulaiInput.value;
        const tglAkhir = tglAkhirInput.value;
        let lama = 0;
        let hargaAkhir = harga;
        let diskonText = '';

        if (tglMulai && tglAkhir) {
            const start = new Date(tglMulai);
            const end = new Date(tglAkhir);
            lama = Math.floor((end - start) / (1000*60*60*24)) + 1;
            if (lama < 1) lama = 1;
        }

        // Cek diskon aktif
        if (diskonData.length > 0 && tglMulai) {
            const tgl = new Date(tglMulai);
            const diskonAktif = diskonData.find(d => {
                return (!d.mulai || new Date(d.mulai) <= tgl) && (!d.akhir || tgl <= new Date(d.akhir));
            });
            if (diskonAktif) {
                const diskonPersen = diskonAktif.persen || 0;
                hargaAkhir = harga * (100 - diskonPersen) / 100;
                diskonText = `Diskon ${diskonPersen}% (Hemat ${formatRupiah(harga * diskonPersen / 100)})`;
            }
        }

        let total = hargaAkhir * jumlah * lama;
        totalHargaInput.value = formatRupiah(total);
        totalHargaRaw.value = total;
        diskonInfo.innerText = diskonText;
    }

    let tglMulaiPicker, tglAkhirPicker;
    function setupFlatpickr() {
        const penuh = getTanggalPenuh();
        if (tglMulaiPicker) tglMulaiPicker.destroy();
        if (tglAkhirPicker) tglAkhirPicker.destroy();

        tglMulaiPicker = flatpickr(tglMulaiInput, {
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: penuh,
            locale: "id",
            onChange: function(selectedDates, dateStr) {
                const maxDurasi = getMaxDurasi();
                let min = dateStr;
                let maxDate = new Date(dateStr);
                maxDate.setDate(maxDate.getDate() + maxDurasi - 1);
                let max = maxDate.toISOString().slice(0,10);

                tglAkhirPicker.set('minDate', min);
                tglAkhirPicker.set('maxDate', max);
                tglAkhirPicker.set('disable', penuh);

                if (!tglAkhirInput.value || tglAkhirInput.value < min || tglAkhirInput.value > max || penuh.includes(tglAkhirInput.value)) {
                    tglAkhirPicker.setDate(min);
                }
                hitungTotal();
            }
        });

        tglAkhirPicker = flatpickr(tglAkhirInput, {
            dateFormat: "Y-m-d",
            minDate: tglMulaiInput.value || "today",
            maxDate: (() => {
                if (tglMulaiInput.value) {
                    let maxDate = new Date(tglMulaiInput.value);
                    maxDate.setDate(maxDate.getDate() + getMaxDurasi() - 1);
                    return maxDate.toISOString().slice(0,10);
                }
                return null;
            })(),
            disable: penuh,
            locale: "id",
            onChange: hitungTotal
        });
    }

    paketSelect.addEventListener('change', function() {
        setupFlatpickr();
        hitungTotal();
    });
    jumlahPeserta.addEventListener('input', hitungTotal);
    tglMulaiInput.addEventListener('input', hitungTotal);
    tglAkhirInput.addEventListener('input', hitungTotal);

    setupFlatpickr();
    hitungTotal();

    // Redirect ke halaman reservasi dengan pilihan tersimpan
    document.getElementById('form-homepage-booking').addEventListener('submit', function(e) {
        e.preventDefault();
        // Validasi sebelum redirect
        if (!paketSelect.value || !jumlahPeserta.value || !tglMulaiInput.value || !tglAkhirInput.value) {
            alert('Lengkapi semua data pemesanan terlebih dahulu!');
            return;
        }
        var paket = paketSelect.value;
        var peserta = jumlahPeserta.value;
        var tglMulai = tglMulaiInput.value;
        var tglAkhir = tglAkhirInput.value;
        var url = "{{ route('fe.reservasi.index') }}" +
            "?paket=" + encodeURIComponent(paket) +
            "&jumlah_peserta=" + encodeURIComponent(peserta) +
            "&tgl_mulai=" + encodeURIComponent(tglMulai) +
            "&tgl_akhir=" + encodeURIComponent(tglAkhir);
        window.location.href = url;
    });
});
</script>
@endpush