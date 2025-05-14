<!-- filepath: resources/views/be/reservasi/index.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Manajemen Reservasi</h2>
    <a href="{{ route('reservasi.simulasi') }}" class="btn btn-info mb-3">Simulasi Pemesanan</a>
    <a href="{{ route('reservasi.create') }}" class="btn btn-primary mb-3">Tambah Reservasi</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form id="bulkActionForm" method="POST" action="">
        @csrf
        <div class="mb-2 d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-danger btn-sm" onclick="submitBulk('delete')">Hapus</button>
            <button type="button" class="btn btn-success btn-sm" onclick="submitBulk('terima')" id="btnBulkTerima" disabled>Terima</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="submitBulk('tolak')" id="btnBulkTolak" disabled>Tolak</button>
            <button type="button" class="btn btn-info btn-sm" onclick="submitBulk('selesai')" id="btnBulkSelesai" disabled>Selesai</button>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Paket Wisata</th>
                    <th>Tgl Booking</th>
                    <th>Tgl Selesai</th>
                    <th>Jumlah Peserta</th>
                    <th>Diskon</th>
                    <th>Total Harga</th>
                    <th>Bukti Transfer</th>
                    <th>Status</th>
                    {{-- <th>Order ID</th>         
                    <th>Payment URL</th>       --}}
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservasi as $i => $r)
                <tr>
                    <td>
                        <input type="checkbox" name="selected[]" value="{{ $r->id }}" class="row-checkbox">
                    </td>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $r->pelanggan->nama_lengkap ?? '-' }}</td>
                    <td>{{ $r->paket->nama_paket ?? '-' }}</td>
                    <td>{{ $r->tgl_mulai }}</td>
                    <td>{{ $r->tgl_akhir }}</td>
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
                        {{-- Tampilkan no rekening, contoh: --}}
                        {{ $r->no_rekening ?? ($r->bank->no_rekening ?? '-') }}
                    </td>
                    <td class="status-cell" data-status="{{ $r->status_reservasi_wisata }}">
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
                    <td>
                        <a href="{{ route('reservasi.edit', $r->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $r->id }}">Detail</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- ...existing code... --}}

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
                <tr><th>Tanggal Booking</th><td>{{ $r->tgl_mulai }}</td></tr>
                <tr><th>Tanggal Selesai</th><td>{{ $r->tgl_akhir }}</td></tr>
                <tr><th>Harga per Hari</th><td>Rp{{ number_format($r->harga,0,',','.') }}</td></tr>
                <tr><th>Jumlah Peserta</th><td>{{ $r->jumlah_peserta }}</td></tr>
                <tr><th>Lama Reservasi</th><td>{{ $r->lama_reservasi }} hari</td></tr>
                <tr><th>Diskon</th><td>{{ $r->diskon ? $r->diskon.'% (Rp'.number_format($r->nilai_diskon,0,',','.').')' : '-' }}</td></tr>
                <tr><th>Total Bayar</th><td>Rp{{ number_format($r->total_bayar,0,',','.') }}</td></tr>
                <tr>
                    <th>No. Rekening</th>
                    <td>{{ $r->no_rekening ?? ($r->bank->no_rekening ?? '-') }}</td>
                </tr>
                <tr>
                    <th>Bukti Transfer</th>
                    <td>
                        @if($r->file_bukti_tf)
                            <img src="{{ asset('storage/'.$r->file_bukti_tf) }}" alt="Bukti Transfer" style="max-width:200px;max-height:200px;cursor:pointer"
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
                @csrf
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
  <script>
    function updateBulkButtons() {
        let checked = Array.from(document.querySelectorAll('.row-checkbox:checked'));
        let statuses = checked.map(cb => cb.closest('tr').querySelector('.status-cell').dataset.status);
        document.getElementById('btnBulkTerima').disabled   = !statuses.includes('pesan');
        document.getElementById('btnBulkTolak').disabled    = !statuses.includes('pesan');
        document.getElementById('btnBulkSelesai').disabled  = !statuses.includes('dibayar');
    }
    document.getElementById('selectAll').addEventListener('change', function() {
        let checked = this.checked;
        document.querySelectorAll('.row-checkbox').forEach(cb => { cb.checked = checked; });
        updateBulkButtons();
    });
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkButtons);
    });
    updateBulkButtons();

    function submitBulk(action) {
        let form = document.getElementById('bulkActionForm');
        let checkedRows = document.querySelectorAll('.row-checkbox:checked');
        if (checkedRows.length === 0) {
            alert('Pilih minimal satu reservasi!');
            return;
        }
        if (action === 'delete' && !confirm('Yakin hapus data terpilih?')) return;
        form.action = "{{ url('be/reservasi/bulk') }}/" + action;
        form.submit();
    }
    </script>
@endsection