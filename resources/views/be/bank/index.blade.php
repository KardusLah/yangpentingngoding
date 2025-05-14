{{-- filepath: c:\xampp\htdocs\WISATA\reservasi-online\resources\views\be\bank\index.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Manajemen Bank</h2>
    <a href="{{ route('bank.create') }}" class="btn btn-primary mb-3">Tambah Bank</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bank</th>
                <th>No. Rekening</th>
                <th>Atas Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banks as $i => $bank)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $bank->nama_bank }}</td>
                <td>{{ $bank->no_rekening }}</td>
                <td>{{ $bank->atas_nama }}</td>
                <td>
                    <a href="{{ route('bank.edit', $bank->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('bank.destroy', $bank->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection