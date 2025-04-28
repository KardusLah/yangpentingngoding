<!-- resources/views/be/penginapan/create.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Tambah Penginapan</h2>
    <form action="{{ route('penginapan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-2">
            <label>Nama Penginapan</label>
            <input type="text" name="nama_penginapan" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required></textarea>
        </div>
        <div class="mb-2">
            <label>Fasilitas</label>
            <textarea name="fasilitas" class="form-control" required></textarea>
        </div>
        @for($i=1; $i<=5; $i++)
        <div class="mb-2">
            <label>Foto {{ $i }}</label>
            <input type="file" name="foto{{ $i }}" class="form-control">
        </div>
        @endfor
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('penginapan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection