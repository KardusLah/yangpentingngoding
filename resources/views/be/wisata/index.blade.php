<!-- resources/views/be/wisata/index.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Manajemen Objek Wisata</h2>
    <a href="{{ route('wisata.create') }}" class="btn btn-primary mb-3">Tambah Objek Wisata</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Wisata</th>
                <th>Kategori</th>
                <th>Fasilitas</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wisata as $i => $w)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $w->nama_wisata }}</td>
                <td>{{ $w->kategori->kategori_wisata ?? '-' }}</td>
                <td>{{ $w->fasilitas }}</td>
                <td>
                    @for($f=1;$f<=5;$f++)
                        @php $foto = 'foto'.$f; @endphp
                        @if($w->$foto)
                            <img src="{{ asset('storage/'.$w->$foto) }}" width="40">
                        @endif
                    @endfor
                </td>
                <td>
                    <a href="{{ route('wisata.edit', $w->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('wisata.destroy', $w->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection