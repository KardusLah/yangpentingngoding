{{-- filepath: resources/views/be/user/index.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Manajemen Pengguna & Level Akses</h2>
    <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Tambah User</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Level</th>
                <th>Status</th>
                <th>Jabatan</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $i => $u)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>
                    @if($u->level == 'pelanggan')
                        {{ $u->pelanggan->nama_lengkap ?? '-' }}
                    @else
                        {{ $u->karyawan->nama_karyawan ?? '-' }}
                    @endif
                </td>
                <td>{{ $u->email }}</td>
                <td>
                    @if($u->level == 'pelanggan')
                        {{ $u->pelanggan->no_hp ?? '-' }}
                    @else
                        {{ $u->karyawan->no_hp ?? '-' }}
                    @endif
                </td>
                <td>
                    @if($u->level == 'pelanggan')
                        {{ $u->pelanggan->alamat ?? '-' }}
                    @else
                        {{ $u->karyawan->alamat ?? '-' }}
                    @endif
                </td>
                <td>
                    <span class="badge bg-info">{{ ucfirst($u->level) }}</span>
                </td>
                <td>
                    @if($u->aktif)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Nonaktif</span>
                    @endif
                </td>
                <td>
                    {{ $u->karyawan->jabatan ?? '-' }}
                </td>
                <td>
                    @if($u->level == 'pelanggan' && $u->pelanggan && $u->pelanggan->foto)
                        <img src="{{ asset('storage/'.$u->pelanggan->foto) }}" width="40">
                    @elseif($u->karyawan && $u->karyawan->foto)
                        <img src="{{ asset('storage/'.$u->karyawan->foto) }}" width="40">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('user.edit', $u->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('user.destroy', $u->id) }}" method="POST" style="display:inline-block;">
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