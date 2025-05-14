{{-- filepath: c:\xampp\htdocs\WISATA\reservasi-online\resources\views\be\bank\edit.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Edit Bank</h2>
    <form action="{{ route('bank.update', $bank->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nama Bank</label>
            <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank', $bank->nama_bank) }}" required>
        </div>
        <div class="mb-3">
            <label>No. Rekening</label>
            <input type="text" name="no_rekening" class="form-control" value="{{ old('no_rekening', $bank->no_rekening) }}" required>
        </div>
        <div class="mb-3">
            <label>Atas Nama</label>
            <input type="text" name="atas_nama" class="form-control" value="{{ old('atas_nama', $bank->atas_nama) }}">
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('bank.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection