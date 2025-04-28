<!-- filepath: [index.blade.php](http://_vscodecontentref_/1) -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Manajemen Reservasi</h2>
    <a href="{{ route('reservasi.create') }}" class="btn btn-primary mb-3">Tambah Reservasi</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Paket Wisata</th>
                <th>Tgl Reservasi</th>
                <th>Harga</th>
                <th>Jumlah Peserta</th>
                <th>Diskon</th>
                <th>Total Bayar</th>
                <th>Bukti Transfer</th>
                <th>Status</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservasi as $i => $r)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $r->pelanggan->nama_lengkap ?? '-' }}</td>
                <td>{{ $r->paket->nama_paket ?? '-' }}</td>
                <td>{{ $r->tgl_reservasi_wisata }}</td>
                <td>Rp{{ number_format($r->harga,0,',','.') }}</td>
                <td>{{ $r->jumlah_peserta }}</td>
                <td>
                    @if($r->diskon)
                        {{ $r->diskon }}% (Rp{{ number_format($r->nilai_diskon,0,',','.') }})
                    @else
                        -
                    @endif
                </td>
                <td>Rp{{ number_format($r->total_bayar,0,',','.') }}</td>
                <td>
                    @if($r->file_bukti_tf)
                        <a href="{{ asset('storage/'.$r->file_bukti_tf) }}" target="_blank">Lihat</a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($r->status_reservasi_wisata == 'pesan')
                        <span class="badge badge-warning">Pesan</span>
                    @elseif($r->status_reservasi_wisata == 'dibayar')
                        <span class="badge badge-info">Dibayar</span>
                    @elseif($r->status_reservasi_wisata == 'selesai')
                        <span class="badge badge-success">Selesai</span>
                    @endif
                </td>
                <td>{{ $r->created_at->format('d-m-Y') }}</td>
                <td>
                    <a href="{{ route('reservasi.edit', $r->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('reservasi.destroy', $r->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection