{{-- filepath: resources/views/be/kategori_berita/edit.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Edit Kategori Berita</h2>
    <form action="{{ route('kategori-berita.update', $kategori->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="kategori_berita" class="form-label">Nama Kategori</label>
            <input type="text" name="kategori_berita" id="kategori_berita" class="form-control" required value="{{ old('kategori_berita', $kategori->kategori_berita) }}">
            @error('kategori_berita')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('kategori-berita.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection