{{-- filepath: resources/views/be/user/edit.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Edit Pengguna</h2>
    <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="mb-2">
            <label>Nama User</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-2">
            <label>Password <small>(Kosongkan jika tidak ingin mengubah)</small></label>
            <input type="password" name="password" class="form-control">
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
        <div class="mb-2">
            <label>Foto</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
            @if($user->level == 'pelanggan' && $user->pelanggan && $user->pelanggan->foto)
                <img src="{{ asset('storage/'.$user->pelanggan->foto) }}" width="60" class="mt-2">
            @elseif($user->karyawan && $user->karyawan->foto)
                <img src="{{ asset('storage/'.$user->karyawan->foto) }}" width="60" class="mt-2">
            @endif
        </div>

        {{-- Data Pelanggan --}}
        <div id="form-pelanggan" style="{{ $user->level == 'pelanggan' ? '' : 'display:none;' }}">
            <div class="mb-2">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->pelanggan->nama_lengkap ?? '') }}">
            </div>
            <div class="mb-2">
                <label>No HP</label>
                <input type="text" name="no_hp_pelanggan" class="form-control" value="{{ old('no_hp_pelanggan', $user->pelanggan->no_hp ?? '') }}">
            </div>
            <div class="mb-2">
                <label>Alamat</label>
                <input type="text" name="alamat_pelanggan" class="form-control" value="{{ old('alamat_pelanggan', $user->pelanggan->alamat ?? '') }}">
            </div>
        </div>

        {{-- Data Karyawan --}}
        <div id="form-karyawan" style="{{ $user->level != 'pelanggan' ? '' : 'display:none;' }}">
            <div class="mb-2">
                <label>Nama Karyawan</label>
                <input type="text" name="nama_karyawan" class="form-control" value="{{ old('nama_karyawan', $user->karyawan->nama_karyawan ?? '') }}">
            </div>
            <div class="mb-2">
                <label>No HP</label>
                <input type="text" name="no_hp_karyawan" class="form-control" value="{{ old('no_hp_karyawan', $user->karyawan->no_hp ?? '') }}">
            </div>
            <div class="mb-2">
                <label>Alamat</label>
                <input type="text" name="alamat_karyawan" class="form-control" value="{{ old('alamat_karyawan', $user->karyawan->alamat ?? '') }}">
            </div>
            <div class="mb-2">
                <label>Jabatan</label>
                <select name="jabatan" class="form-control">
                    <option value="administrasi" {{ (isset($user->karyawan) && $user->karyawan->jabatan == 'administrasi') ? 'selected' : '' }}>Administrasi</option>
                    <option value="bendahara" {{ (isset($user->karyawan) && $user->karyawan->jabatan == 'bendahara') ? 'selected' : '' }}>Bendahara</option>
                    <option value="pemilik" {{ (isset($user->karyawan) && $user->karyawan->jabatan == 'pemilik') ? 'selected' : '' }}>Pemilik</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<script>
    // Tampilkan form sesuai level user
    document.querySelector('select[name="level"]').addEventListener('change', function() {
        if(this.value === 'pelanggan') {
            document.getElementById('form-pelanggan').style.display = '';
            document.getElementById('form-karyawan').style.display = 'none';
        } else {
            document.getElementById('form-pelanggan').style.display = 'none';
            document.getElementById('form-karyawan').style.display = '';
        }
    });
</script>
@endsection