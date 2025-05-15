{{-- filepath: resources/views/be/user/index.blade.php --}}
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Manajemen Pengguna & Level Akses</h2>
    <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Tambah User</a>
    {{-- Bulk Action Buttons --}}
    <form id="bulkActionForm" method="POST" action="">
        @csrf
        <div class="mb-2 d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-danger btn-sm" onclick="submitBulk('delete')">Hapus</button>
            <button type="button" class="btn btn-success btn-sm" onclick="submitBulk('aktifkan')">Aktifkan</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="submitBulk('nonaktifkan')">Nonaktifkan</button>
        </div>
        <div class="mb-3">
            @php
                $tabStatus = request('status', 'all');
                $statuses = [
                    'all' => 'Semua',
                    'Aktif' => 'Aktif',
                    'Nonaktif' => 'Nonaktif'
                ];
            @endphp
            <ul class="nav nav-tabs">
                @foreach($statuses as $key => $label)
                    <li class="nav-item">
                        <a class="nav-link {{ $tabStatus === $key ? 'active' : '' }}"
                        href="{{ route('user.index', $key !== 'all' ? ['status' => $key] : []) }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
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
                    <td>
                        <input type="checkbox" name="selected[]" value="{{ $u->id }}" class="row-checkbox">
                    </td>
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
                        <input type="checkbox"
                            class="form-check-input status-toggle"
                            data-id="{{ $u->id }}"
                            {{ $u->aktif ? 'checked' : '' }}>
                        <span class="ms-1">{{ $u->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                    </td>
                    <td>
                        {{ $u->karyawan->jabatan ?? '-' }}
                    </td>
                    <td>
                        @if($u->level == 'pelanggan' && $u->pelanggan && $u->pelanggan->foto)
                            <img src="{{ asset('storage/'.$u->pelanggan->foto) }}" width="40" style="cursor:pointer"
                                 onclick="showImgPreview('{{ asset('storage/'.$u->pelanggan->foto) }}')">
                        @elseif($u->karyawan && $u->karyawan->foto)
                            <img src="{{ asset('storage/'.$u->karyawan->foto) }}" width="40" style="cursor:pointer"
                                 onclick="showImgPreview('{{ asset('storage/'.$u->karyawan->foto) }}')">
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('user.edit', $u->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('user.destroy', $u->id) }}" method="POST" style="display:inline-block;">
                            @csrf 
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
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
document.querySelectorAll('.status-toggle').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        var userId = this.getAttribute('data-id');
        var aktif = this.checked ? 1 : 0;
        fetch("{{ url('be/user/status') }}/" + userId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ aktif: aktif })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                this.nextElementSibling.textContent = aktif ? 'Aktif' : 'Nonaktif';
            } else {
                alert('Gagal update status!');
                this.checked = !this.checked;
            }
        })
        .catch(() => {
            alert('Gagal update status!');
            this.checked = !this.checked;
        });
    });
});

// Bulk select all
document.getElementById('selectAll').addEventListener('change', function() {
    let checked = this.checked;
    document.querySelectorAll('.row-checkbox').forEach(cb => { cb.checked = checked; });
});

// Bulk action submit
function submitBulk(action) {
    let form = document.getElementById('bulkActionForm');
    let checkedRows = document.querySelectorAll('.row-checkbox:checked');
    if (checkedRows.length === 0) {
        alert('Pilih minimal satu user!');
        return;
    }
    if (action === 'delete' && !confirm('Yakin hapus user terpilih?')) return;
    form.action = "{{ url('be/user/bulk') }}/" + action;
    form.submit();
}
</script>
@endsection