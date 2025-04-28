@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Edit Pengguna</h2>
    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @csrf
        <div class="mb-2">
            <label>Nama User</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-2">
            <label>Level</label>
            <select name="level" class="form-control" required>
                <option value="admin" {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="bendahara" {{ $user->level == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                <option value="pelanggan" {{ $user->level == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                <option value="pemilik" {{ $user->level == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
            </select>
        </div>
        <div class="mb-2">
            <label>Status</label>
            <select name="aktif" class="form-control" required>
                <option value="1" {{ $user->aktif ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ !$user->aktif ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        @if($user->level == 'pelanggan' && $user->pelanggan)
            <div class="mb-2">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->pelanggan->nama_lengkap) }}">
            </div>
            <div class="mb-2">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->pelanggan->no_hp) }}">
            </div>
            <div class="mb-2">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $user->pelanggan->alamat) }}">
            </div>
        @elseif($user->karyawan)
            <div class="mb-2">
                <label>Nama Karyawan</label>
                <input type="text" name="nama_karyawan" class="form-control" value="{{ old('nama_karyawan', $user->karyawan->nama_karyawan) }}">
            </div>
            <div class="mb-2">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->karyawan->no_hp) }}">
            </div>
            <div class="mb-2">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $user->karyawan->alamat) }}">
            </div>
            <div class="mb-2">
                <label>Jabatan</label>
                <select name="jabatan" class="form-control">
                    <option value="administrasi" {{ $user->karyawan->jabatan == 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                    <option value="bendahara" {{ $user->karyawan->jabatan == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                    <option value="pemilik" {{ $user->karyawan->jabatan == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                </select>
            </div>
        @endif
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection