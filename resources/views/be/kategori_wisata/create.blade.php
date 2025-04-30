{{-- filepath: resources/views/be/kategori_wisata/create.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Tambah Kategori Wisata</h2>
    <form action="{{ route('kategori-wisata.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kategori_wisata" class="form-label">Nama Kategori</label>
            <input type="text" name="kategori_wisata" id="kategori_wisata" class="form-control" required value="{{ old('kategori_wisata') }}">
            @error('kategori_wisata')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kategori-wisata.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection