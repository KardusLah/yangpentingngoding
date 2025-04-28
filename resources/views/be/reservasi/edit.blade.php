<!-- filepath: resources/views/be/reservasi/edit.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Edit Reservasi</h2>
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
            <select name="id_paket" class="form-control" required>
                <option value="">Pilih Paket</option>
                @foreach($paket as $p)
                    <option value="{{ $p->id }}" {{ $reservasi->id_paket == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_paket }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label>Tanggal Reservasi</label>
            <input type="date" name="tgl_reservasi_wisata" class="form-control" value="{{ old('tgl_reservasi_wisata', $reservasi->tgl_reservasi_wisata) }}" required>
        </div>
        <div class="mb-2">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', $reservasi->harga) }}" required>
        </div>
        <div class="mb-2">
            <label>Jumlah Peserta</label>
            <input type="number" name="jumlah_peserta" class="form-control" value="{{ old('jumlah_peserta', $reservasi->jumlah_peserta) }}" required>
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
            <label>Total Bayar</label>
            <input type="number" name="total_bayar" class="form-control" value="{{ old('total_bayar', $reservasi->total_bayar) }}" required>
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
            </select>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('reservasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection