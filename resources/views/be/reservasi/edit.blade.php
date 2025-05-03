<!-- filepath: resources/views/be/reservasi/edit.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Edit Reservasi</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('reservasi.update', $reservasi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-2">
            <label>Pelanggan</label>
            <select name="id_pelanggan" class="form-control" required>
                <option value="">Pilih Pelanggan</option>
                @foreach($pelanggan as $p)
                    <option value="{{ $p->id }}" {{ $reservasi->id_pelanggan == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_lengkap }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label>Paket Wisata</label>
            <select name="id_paket" id="id_paket" class="form-control" required>
                <option value="">Pilih Paket</option>
                @foreach($paket as $p)
                    <option value="{{ $p->id }}" data-harga="{{ $p->harga_per_pack }}" data-durasi="{{ $p->durasi }}"
                        {{ $reservasi->id_paket == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_paket }} (Rp{{ number_format($p->harga_per_pack,0,',','.') }}/hari, max {{ $p->durasi }} hari)
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label>Tanggal Mulai</label>
            <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control"
                value="{{ old('tgl_mulai', $reservasi->tgl_mulai) }}" required>
        </div>
        <div class="mb-2">
            <label>Tanggal Akhir</label>
            <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control"
                value="{{ old('tgl_akhir', $reservasi->tgl_akhir) }}" required>
        </div>
        <div class="mb-2">
            <label>Jumlah Peserta</label>
            <input type="number" name="jumlah_peserta" id="jumlah_peserta" class="form-control" min="1"
                value="{{ old('jumlah_peserta', $reservasi->jumlah_peserta) }}" required>
        </div>
        <div class="mb-2">
            <label>Harga per Hari</label>
            <input type="text" id="harga_per_hari" class="form-control" readonly>
        </div>
        <div class="mb-2">
            <label>Lama Reservasi (hari)</label>
            <input type="number" id="lama_reservasi" name="lama_reservasi" class="form-control" readonly
                value="{{ old('lama_reservasi', $reservasi->lama_reservasi) }}">
        </div>
        <div class="mb-2">
            <label>Total Bayar</label>
            <input type="text" id="total_bayar_format" class="form-control" readonly>
            <input type="hidden" name="total_bayar" id="total_bayar" value="{{ old('total_bayar', $reservasi->total_bayar) }}">
        </div>
        <div class="mb-2">
            <label>Diskon (%)</label>
            <input type="number" name="diskon" class="form-control" value="{{ old('diskon', $reservasi->diskon) }}">
        </div>
        <div class="mb-2">
            <label>Nilai Diskon</label>
            <input type="number" name="nilai_diskon" class="form-control" value="{{ old('nilai_diskon', $reservasi->nilai_diskon) }}">
        </div>
        <div class="mb-2">
            <label>Bukti Transfer</label>
            @if($reservasi->file_bukti_tf)
                <div class="mb-2">
                    <a href="{{ asset('storage/'.$reservasi->file_bukti_tf) }}" target="_blank">Lihat Bukti Transfer</a>
                </div>
            @endif
            <input type="file" name="file_bukti_tf" class="form-control">
        </div>
        <div class="mb-2">
            <label>Status Reservasi</label>
            <select name="status_reservasi_wisata" class="form-control" required>
                <option value="pesan" {{ $reservasi->status_reservasi_wisata == 'pesan' ? 'selected' : '' }}>Pesan</option>
                <option value="dibayar" {{ $reservasi->status_reservasi_wisata == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                <option value="selesai" {{ $reservasi->status_reservasi_wisata == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ $reservasi->status_reservasi_wisata == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('reservasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
function getMaxDurasi() {
    const select = document.getElementById('id_paket');
    const opt = select.options[select.selectedIndex];
    return parseInt(opt.getAttribute('data-durasi') || 1);
}
function getHargaPerHari() {
    const select = document.getElementById('id_paket');
    const opt = select.options[select.selectedIndex];
    return parseInt(opt.getAttribute('data-harga') || 0);
}
function formatRupiah(angka) {
    return 'Rp' + angka.toLocaleString('id-ID');
}

function updateTanggalAkhir() {
    const tglMulai = document.getElementById('tgl_mulai').value;
    const maxDurasi = getMaxDurasi();
    if (tglMulai) {
        const min = tglMulai;
        const max = new Date(new Date(tglMulai).getTime() + (maxDurasi-1)*24*60*60*1000);
        document.getElementById('tgl_akhir').setAttribute('min', min);
        document.getElementById('tgl_akhir').setAttribute('max', max.toISOString().slice(0,10));
    }
}

function updateHargaDanTotal() {
    const harga = getHargaPerHari();
    document.getElementById('harga_per_hari').value = harga ? formatRupiah(harga) : '';
    const tglMulai = document.getElementById('tgl_mulai').value;
    const tglAkhir = document.getElementById('tgl_akhir').value;
    const jumlahPeserta = parseInt(document.getElementById('jumlah_peserta').value) || 0;
    if (tglMulai && tglAkhir && jumlahPeserta > 0) {
        const start = new Date(tglMulai);
        const end = new Date(tglAkhir);
        let lama = Math.floor((end - start) / (1000*60*60*24)) + 1;
        const maxDurasi = getMaxDurasi();
        if (lama > maxDurasi) lama = maxDurasi;
        if (lama < 1) lama = 1;
        document.getElementById('lama_reservasi').value = lama;
        const total = harga * lama * jumlahPeserta;
        document.getElementById('total_bayar').value = total;
        document.getElementById('total_bayar_format').value = total ? formatRupiah(total) : '';
    } else {
        document.getElementById('lama_reservasi').value = '';
        document.getElementById('total_bayar').value = '';
        document.getElementById('total_bayar_format').value = '';
    }
}

document.getElementById('id_paket').addEventListener('change', function() {
    updateTanggalAkhir();
    updateHargaDanTotal();
});
document.getElementById('tgl_mulai').addEventListener('change', function() {
    updateTanggalAkhir();
    updateHargaDanTotal();
});
document.getElementById('tgl_akhir').addEventListener('change', updateHargaDanTotal);
document.getElementById('jumlah_peserta').addEventListener('input', updateHargaDanTotal);

// Set initial value on page load
window.addEventListener('DOMContentLoaded', function() {
    updateTanggalAkhir();
    updateHargaDanTotal();
});
</script>
@endpush