{{-- filepath: resources/views/be/kategori_wisata/index.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Manajemen Kategori Wisata</h2>
    <a href="{{ route('kategori-wisata.create') }}" class="btn btn-primary mb-3">Tambah Kategori</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategori as $i => $k)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $k->kategori_wisata }}</td>
                <td>
                    <a href="{{ route('kategori-wisata.edit', $k->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('kategori-wisata.destroy', $k->id) }}" method="POST" style="display:inline-block;">
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