<!-- resources/views/be/reservasi/create.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Tambah Reservasi</h2>
    <form action="{{ route('reservasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-2">
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
                <select name="id_paket" class="form-control" required>
                    <option value="">Pilih Paket</option>
                    @foreach($paket as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_paket }}</option>
                    @endforeach
                </select>
            </div>
        <div class="mb-2">
            <label>Tanggal Reservasi</label>
            <input type="date" name="tgl_reservasi_wisata" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Jumlah Peserta</label>
            <input type="number" name="jumlah_peserta" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Diskon (%)</label>
            <input type="number" name="diskon" class="form-control">
        </div>
        <div class="mb-2">
            <label>Nilai Diskon</label>
            <input type="number" name="nilai_diskon" class="form-control">
        </div>
        <div class="mb-2">
            <label>Total Bayar</label>
            <input type="number" name="total_bayar" class="form-control" required>
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