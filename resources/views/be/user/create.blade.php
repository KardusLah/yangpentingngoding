{{-- filepath: resources/views/be/user/create.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Tambah Pengguna</h2>
    <form action="{{ route('user.store') }}" method="POST">
        @csrf
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
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-2">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Level</label>
            <select name="level" class="form-control" id="level" required onchange="toggleForm()">
                <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="bendahara" {{ old('level') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                <option value="pelanggan" {{ old('level') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                <option value="pemilik" {{ old('level') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
            </select>
        </div>
        <div class="mb-2">
            <label>Status</label>
            <select name="aktif" class="form-control" required>
                <option value="1" {{ old('aktif') == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('aktif') == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        {{-- Form Pelanggan --}}
        <div id="form-pelanggan" style="display: none;">
            <div class="mb-2">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}">
            </div>
            <div class="mb-2">
                <label>No HP</label>
                <input type="text" name="no_hp_pelanggan" class="form-control" value="{{ old('no_hp_pelanggan') }}">
            </div>
            <div class="mb-2">
                <label>Alamat</label>
                <input type="text" name="alamat_pelanggan" class="form-control" value="{{ old('alamat_pelanggan') }}">
            </div>
        </div>

        {{-- Form Karyawan --}}
        <div id="form-karyawan" style="display: none;">
            <div class="mb-2">
                <label>Nama Karyawan</label>
                <input type="text" name="nama_karyawan" id="nama_karyawan"
                    class="form-control"
                    value="{{ old('nama_karyawan') }}">
            </div>
            <div class="mb-2">
                <label>No HP</label>
                <input type="text" name="no_hp_karyawan" class="form-control" value="{{ old('no_hp_karyawan') }}">
            </div>
            <div class="mb-2">
                <label>Alamat</label>
                <input type="text" name="alamat_karyawan" class="form-control" value="{{ old('alamat_karyawan') }}">
            </div>
            <div class="mb-2">
                <label>Jabatan</label>
                <select name="jabatan" class="form-control">
                    <option value="administrasi" {{ old('jabatan') == 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                    <option value="bendahara" {{ old('jabatan') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                    <option value="pemilik" {{ old('jabatan') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<script>
function toggleForm() {
    var level = document.getElementById('level').value;
    var formPelanggan = document.getElementById('form-pelanggan');
    var formKaryawan = document.getElementById('form-karyawan');
    var namaKaryawan = document.getElementById('nama_karyawan');
    if(level == 'pelanggan') {
        formPelanggan.style.display = 'block';
        formKaryawan.style.display = 'none';
        if(namaKaryawan){
            namaKaryawan.value = '';
            namaKaryawan.setAttribute('readonly', true);
            namaKaryawan.setAttribute('disabled', true);
            namaKaryawan.style.background = '#eee';
        }
    } else {
        formPelanggan.style.display = 'none';
        formKaryawan.style.display = 'block';
        if(namaKaryawan){
            namaKaryawan.removeAttribute('readonly');
            namaKaryawan.removeAttribute('disabled');
            namaKaryawan.style.background = '';
        }
    }
}
document.addEventListener('DOMContentLoaded', toggleForm);
</script>
@endsection