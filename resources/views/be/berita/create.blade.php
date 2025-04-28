@extends('be.master')
@section('content')
<form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-2">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Kategori</label>
        <select name="id_kategori_berita" class="form-control" required>
            <option value="">Pilih Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}">{{ $k->kategori_berita }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label>Isi Berita</label>
        <textarea name="berita" class="form-control" rows="5" required></textarea>
    </div>
    <div class="mb-2">
        <label>Tanggal Post</label>
        <input type="date" name="tgl_post" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Foto</label>
        <input type="file" name="foto" class="form-control">
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('berita.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection