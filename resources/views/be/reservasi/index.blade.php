<!-- filepath: resources/views/be/reservasi/index.blade.php -->
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
                        <img src="{{ asset('storage/'.$r->file_bukti_tf) }}" width="40" style="cursor:pointer"
                             onclick="showImgPreview('{{ asset('storage/'.$r->file_bukti_tf) }}')">
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($r->status_reservasi_wisata == 'pesan')
                        <span class="badge bg-warning">Pesan</span>
                    @elseif($r->status_reservasi_wisata == 'dibayar')
                        <span class="badge bg-success">Dibayar</span>
                    @elseif($r->status_reservasi_wisata == 'selesai')
                        <span class="badge bg-info">Selesai</span>
                    @elseif($r->status_reservasi_wisata == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>
                <td>{{ $r->created_at->format('d-m-Y') }}</td>
                <td>
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $r->id }}">Detail</button>
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

    {{-- Modal Detail --}}
    @foreach($reservasi as $r)
    <div class="modal fade" id="detailModal{{ $r->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $r->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="detailModalLabel{{ $r->id }}">Detail Reservasi #{{ $r->id }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <table class="table table-borderless">
                <tr><th>Pelanggan</th><td>{{ $r->pelanggan->nama_lengkap ?? '-' }}</td></tr>
                <tr><th>Paket Wisata</th><td>{{ $r->paket->nama_paket ?? '-' }}</td></tr>
                <tr><th>Tanggal Reservasi</th><td>{{ $r->tgl_reservasi_wisata }}</td></tr>
                <tr><th>Harga</th><td>Rp{{ number_format($r->harga,0,',','.') }}</td></tr>
                <tr><th>Jumlah Peserta</th><td>{{ $r->jumlah_peserta }}</td></tr>
                <tr><th>Diskon</th><td>{{ $r->diskon ? $r->diskon.'% (Rp'.number_format($r->nilai_diskon,0,',','.').')' : '-' }}</td></tr>
                <tr><th>Total Bayar</th><td>Rp{{ number_format($r->total_bayar,0,',','.') }}</td></tr>
                <tr>
                    <th>Bukti Transfer</th>
                    <td>
                        @if($r->file_bukti_tf)
                            <img src="{{ asset('storage/'.$r->file_bukti_tf) }}" alt="Bukti Transfer" style="max-width:200px;max-height:200px;"style="cursor:pointer"
                                 onclick="showImgPreview('{{ asset('storage/'.$r->file_bukti_tf) }}')">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                <tr><th>Status</th>
                <td>
                    @if($r->status_reservasi_wisata == 'pesan')
                        <span class="badge bg-warning">Pesan</span>
                    @elseif($r->status_reservasi_wisata == 'dibayar')
                        <span class="badge bg-success">Dibayar</span>
                    @elseif($r->status_reservasi_wisata == 'selesai')
                        <span class="badge bg-info">Selesai</span>
                    @elseif($r->status_reservasi_wisata == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>
                </tr>
                <tr><th>Dibuat</th><td>{{ $r->created_at->format('d-m-Y') }}</td></tr>
            </table>
            </div>
            <div class="modal-footer">
            <a href="{{ route('reservasi.edit', $r->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('reservasi.destroy', $r->id) }}" method="POST" style="display:inline-block;">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Delete</button>
            </form>
            @if($r->status_reservasi_wisata == 'pesan')
                <form action="{{ route('reservasi.terima', $r->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button class="btn btn-success btn-sm">Terima</button>
                </form>
                <form action="{{ route('reservasi.tolak', $r->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button class="btn btn-secondary btn-sm">Tolak</button>
                </form>
            @endif
            @if($r->status_reservasi_wisata == 'dibayar')
                <form action="{{ route('reservasi.selesai', $r->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button class="btn btn-info btn-sm">Selesai</button>
                </form>
            @endif
            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
        </div>
    </div>
    @endforeach
</div>
<!-- Modal Preview Gambar -->
<div class="modal fade" id="imgPreviewModal" tabindex="-1" aria-labelledby="imgPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <img id="imgPreview" src="" alt="Preview" style="max-width:100%;max-height:70vh;">
        </div>
      </div>
    </div>
  </div>
  <script>
  function showImgPreview(src) {
      document.getElementById('imgPreview').src = src;
      var myModal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
      myModal.show();
  }
  </script>
@endsection