<!-- filepath: resources/views/be/paket/create.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Tambah Paket Wisata</h2>
    <form action="{{ route('paket.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-2">
            <label>Nama Paket</label>
            <input type="text" name="nama_paket" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required></textarea>
        </div>
        <div class="mb-2">
            <label>Fasilitas</label>
            <textarea name="fasilitas" class="form-control" required></textarea>
        </div>
        <div class="mb-2">
            <label>Harga per Hari</label>
            <input type="number" name="harga_per_pack" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Durasi Paket (hari)</label>
            <input type="number" name="durasi" class="form-control" min="1" value="{{ old('durasi', isset($paket) ? $paket->durasi : 1) }}" required>
        </div>
        <div class="mb-2">
            <label>Foto 1</label>
            <input type="file" name="foto1" class="form-control">
        </div>
        <div class="mb-2">
            <label>Foto 2</label>
            <input type="file" name="foto2" class="form-control">
        </div>
        <div class="mb-2">
            <label>Foto 3</label>
            <input type="file" name="foto3" class="form-control">
        </div>
        <div class="mb-2">
            <label>Foto 4</label>
            <input type="file" name="foto4" class="form-control">
        </div>
        <div class="mb-2">
            <label>Foto 5</label>
            <input type="file" name="foto5" class="form-control">
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('paket.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection