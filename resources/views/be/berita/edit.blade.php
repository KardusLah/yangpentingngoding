@extends('be.master')
@section('content')
<form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-2">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control" value="{{ old('judul', $berita->judul) }}" required>
    </div>
    <div class="mb-2">
        <label>Kategori</label>
        <select name="id_kategori_berita" class="form-control" required>
            <option value="">Pilih Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}" {{ $berita->id_kategori_berita == $k->id ? 'selected' : '' }}>
                    {{ $k->kategori_berita }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label>Isi Berita</label>
        <textarea name="berita" class="form-control" rows="5" required>{{ old('berita', $berita->berita) }}</textarea>
    </div>
    <div class="mb-2">
        <label>Tanggal Post</label>
        <input type="date" name="tgl_post" class="form-control" value="{{ old('tgl_post', $berita->tgl_post) }}" required>
    </div>
    <div class="mb-2">
        <label>Foto</label>
        @if($berita->foto)
            <div class="mb-2">
                <img src="{{ asset('storage/'.$berita->foto) }}" width="100">
            </div>
        @endif
        <input type="file" name="foto" class="form-control">
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('berita.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection