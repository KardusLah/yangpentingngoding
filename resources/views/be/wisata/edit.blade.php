<!-- resources/views/be/wisata/create.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Edit Objek Wisata</h2>
    <form action="{{ route('wisata.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-2">
            <label>Nama Wisata</label>
            <input type="text" name="nama_wisata" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Kategori Wisata</label>
            <select name="id_kategori_wisata" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id }}">{{ $k->kategori_wisata }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label>Deskripsi Wisata</label>
            <textarea name="deskripsi_wisata" class="form-control" required></textarea>
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
        <a href="{{ route('wisata.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection