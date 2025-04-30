{{-- filepath: resources/views/be/kategori_berita/create.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Tambah Kategori Berita</h2>
    <form action="{{ route('kategori-berita.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kategori_berita" class="form-label">Nama Kategori</label>
            <input type="text" name="kategori_berita" id="kategori_berita" class="form-control" required value="{{ old('kategori_berita') }}">
            @error('kategori_berita')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kategori-berita.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection