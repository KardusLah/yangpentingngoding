{{-- filepath: c:\xampp\htdocs\WISATA\reservasi-online\resources\views\be\bank\create.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Tambah Bank</h2>
    <form action="{{ route('bank.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Bank</label>
            <input type="text" name="nama_bank" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>No. Rekening</label>
            <input type="text" name="no_rekening" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Atas Nama</label>
            <input type="text" name="atas_nama" class="form-control">
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('bank.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection