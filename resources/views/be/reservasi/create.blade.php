<!-- filepath: resources/views/be/reservasi/create.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Tambah Reservasi</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('reservasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-2">
            <label>Pelanggan</label>
            <select name="id_pelanggan" class="form-control" required>
                <option value="">Pilih Pelanggan</option>
                @foreach($pelanggan as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label>Paket Wisata</label>
            <select name="id_paket" id="id_paket" class="form-control" required>
                <option value="">Pilih Paket</option>
                @foreach($paket as $p)
                    <option value="{{ $p->id }}" data-harga="{{ $p->harga_per_pack }}" data-durasi="{{ $p->durasi }}">
                        {{ $p->nama_paket }} (Rp{{ number_format($p->harga_per_pack,0,',','.') }}/hari, max {{ $p->durasi }} hari)
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label>Tanggal Mulai</label>
            <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Tanggal Akhir</label>
            <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Jumlah Peserta</label>
            <input type="number" name="jumlah_peserta" id="jumlah_peserta" class="form-control" min="1" required>
        </div>
        <div class="mb-2">
            <label>Harga per Hari</label>
            <input type="text" id="harga_per_hari" class="form-control" readonly>
        </div>
        <div class="mb-2">
            <label>Lama Reservasi (hari)</label>
            <input type="number" id="lama_reservasi" name="lama_reservasi" class="form-control" readonly>
        </div>
        <div class="mb-2">
            <label>Total Bayar</label>
            <input type="text" id="total_bayar_format" class="form-control" readonly>
            <input type="hidden" name="total_bayar" id="total_bayar">
        </div>
        <div class="mb-2">
            <label>Bukti Transfer</label>
            <input type="file" name="file_bukti_tf" class="form-control">
        </div>
        <div class="mb-2">
            <label>Status Reservasi</label>
            <select name="status_reservasi_wisata" class="form-control" required>
                <option value="pesan">Pesan</option>
                <option value="dibayar">Dibayar</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('reservasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
const tanggalPenuh = @json($tanggalPenuh);

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

</script>
@endpush