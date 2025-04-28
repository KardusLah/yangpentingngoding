<!-- filepath: resources/views/be/paket/edit.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Edit Paket Wisata</h2>
    <form action="{{ route('paket.update', $paket->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-2">
            <label>Nama Paket</label>
            <input type="text" name="nama_paket" class="form-control" value="{{ old('nama_paket', $paket->nama_paket) }}" required>
        </div>
        <div class="mb-2">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required>{{ old('deskripsi', $paket->deskripsi) }}</textarea>
        </div>
        <div class="mb-2">
            <label>Fasilitas</label>
            <textarea name="fasilitas" class="form-control" required>{{ old('fasilitas', $paket->fasilitas) }}</textarea>
        </div>
        <div class="mb-2">
            <label>Harga per Pack</label>
            <input type="number" name="harga_per_pack" class="form-control" value="{{ old('harga_per_pack', $paket->harga_per_pack) }}" required>
        </div>
        @for($i=1; $i<=5; $i++)
            @php $foto = 'foto'.$i; @endphp
            <div class="mb-2">
                <label>Foto {{ $i }}</label>
                @if($paket->$foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$paket->$foto) }}" width="80">
                    </div>
                @endif
                <input type="file" name="foto{{ $i }}" class="form-control">
            </div>
        @endfor
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('paket.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection