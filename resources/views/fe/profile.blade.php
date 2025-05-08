{{-- filepath: resources/views/fe/profile.blade.php --}}
@extends('fe.master')

@section('content')
<div class="untree_co-section bg-light" style="margin-top: 90px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title text-center mb-5">
                    <h2>Profil & Riwayat Reservasi</h2>
                </div>

                {{-- Notifikasi --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-20" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if($notifikasi && count($notifikasi))
                    <div class="alert alert-warning alert-dismissible fade show rounded-20 mb-4" role="alert">
                        <h5 class="alert-heading d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Ada reservasi yang perlu aksi
                        </h5>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($notifikasi as $notif)
                                <span class="badge bg-primary">
                                    {{ $notif->paket->nama_paket ?? '-' }} ({{ ucfirst($notif->status_reservasi_wisata) }})
                                </span>
                            @endforeach
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    {{-- Profil Section --}}
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="card shadow-sm rounded-20 h-100">
                            <div class="card-body p-4 text-center">
                                <div class="position-relative mx-auto" style="width: 150px; height: 150px;">
                                    <img src="{{ $profil && $profil->foto ? asset('storage/'.$profil->foto) : asset('fe/assets/images/user.png') }}" 
                                         class="img-fluid rounded-circle border border-4 border-white shadow-sm" 
                                         alt="Foto Profil"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                
                                <h4 class="mt-4 mb-3">{{ $user->name }}</h4>
                                
                                <div class="text-start bg-light p-3 rounded-10 mb-3">
                                    <p class="mb-2"><i class="fas fa-envelope me-2 text-primary"></i> {{ $user->email }}</p>
                                    <p class="mb-2"><i class="fas fa-phone me-2 text-primary"></i> {{ $user->no_hp }}</p>
                                    @if($user->level == 'pelanggan')
                                        <p class="mb-2"><i class="fas fa-user me-2 text-primary"></i> {{ $profil->nama_lengkap ?? '-' }}</p>
                                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $profil->alamat ?? '-' }}</p>
                                    @else
                                        <p class="mb-2"><i class="fas fa-user-tie me-2 text-primary"></i> {{ $profil->nama_karyawan ?? '-' }}</p>
                                        <p class="mb-2"><i class="fas fa-briefcase me-2 text-primary"></i> {{ $profil->jabatan ?? '-' }}</p>
                                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $profil->alamat ?? '-' }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Edit Profil & Riwayat Reservasi --}}
                    <div class="col-lg-8">
                        <div class="card shadow-sm rounded-20 mb-4">
                            <div class="card-header bg-primary text-white rounded-top-20">
                                <h5 class="mb-0">Edit Profil</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Akun</label>
                                            <input type="text" name="name" class="form-control" 
                                                   value="{{ old('name', $user->name) }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">No HP</label>
                                            <input type="text" name="no_hp" class="form-control" 
                                                   value="{{ old('no_hp', $user->no_hp) }}" required>
                                        </div>
                                    </div>

                                    @if($user->level == 'pelanggan')
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap" class="form-control" 
                                                   value="{{ old('nama_lengkap', $profil->nama_lengkap ?? '') }}" required>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nama Karyawan</label>
                                                <input type="text" name="nama_karyawan" class="form-control" 
                                                       value="{{ old('nama_karyawan', $profil->nama_karyawan ?? '') }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Jabatan</label>
                                                <input type="text" class="form-control" 
                                                       value="{{ $profil->jabatan ?? '-' }}" disabled>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $profil->alamat ?? '') }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Foto Profil</label>
                                        <input type="file" name="foto" class="form-control">
                                        <small class="text-muted">Format: JPG, PNG (Maks. 2MB)</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Riwayat Reservasi --}}
                        <div class="card shadow-sm rounded-20">
                            <div class="card-header bg-primary text-white rounded-top-20">
                                <h5 class="mb-0">Riwayat Reservasi</h5>
                            </div>
                            <div class="card-body p-0">
                                @if($riwayat && count($riwayat))
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Paket</th>
                                                    <th>Tanggal</th>
                                                    <th>Peserta</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($riwayat as $r)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('fe.reservasi.detail', $r->id_paket) }}" 
                                                           class="text-primary">
                                                            {{ $r->paket->nama_paket ?? '-' }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($r->tgl_mulai)->format('d M Y') }}<br>
                                                        <small class="text-muted">{{ $r->lama_reservasi }} hari</small>
                                                    </td>
                                                    <td>{{ $r->jumlah_peserta }} orang</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($r->status_reservasi_wisata == 'selesai') bg-success
                                                            @elseif($r->status_reservasi_wisata == 'dibayar') bg-primary
                                                            @elseif($r->status_reservasi_wisata == 'ditolak') bg-danger
                                                            @else bg-warning text-dark @endif">
                                                            {{ ucfirst($r->status_reservasi_wisata) }}
                                                        </span>
                                                        
                                                        @if($r->status_reservasi_wisata == 'menunggu' && !$r->file_bukti_tf)
                                                            <small class="d-block text-danger mt-1">
                                                                <i class="fas fa-exclamation-circle"></i> Belum upload bukti
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ route('fe.reservasi.detail', $r->id_paket) }}" 
                                                               class="btn btn-sm btn-outline-primary" title="Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            
                                                            @if($r->status_reservasi_wisata == 'menunggu' && !$r->file_bukti_tf)
                                                                <button class="btn btn-sm btn-outline-success upload-bukti" 
                                                                        data-id="{{ $r->id }}" title="Upload Bukti">
                                                                    <i class="fas fa-upload"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada riwayat reservasi</h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Bukti Pembayaran -->
<div class="modal fade" id="uploadBuktiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-20">
            <div class="modal-header bg-primary text-white rounded-top-20">
                <h5 class="modal-title">Upload Bukti Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadBuktiForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih File Bukti Transfer</label>
                        <input type="file" name="file_bukti_tf" class="form-control" accept="image/*,.pdf" required>
                        <small class="text-muted">Format: JPG, PNG, PDF (Maks. 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle upload bukti modal
    const uploadBuktiButtons = document.querySelectorAll('.upload-bukti');
    const uploadBuktiForm = document.getElementById('uploadBuktiForm');
    const modal = new bootstrap.Modal(document.getElementById('uploadBuktiModal'));
    
    uploadBuktiButtons.forEach(button => {
        button.addEventListener('click', function() {
            const reservasiId = this.getAttribute('data-id');
            uploadBuktiForm.action = `/reservasi/${reservasiId}/upload-bukti`;
            modal.show();
        });
    });
});
</script>
@endpush