<div class="untree_co-section bg-light">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="card shadow-sm rounded-20">
            <div class="card-body p-5">
              <div class="section-title text-center mb-5">
                <h2>Riwayat Reservasi</h2>
              </div>
              
              <!-- Notifikasi -->
              @if($reservasiMenunggu->count() > 0)
                <div class="alert alert-warning mb-4">
                  <h5 class="alert-heading">
                    <i class="fas fa-exclamation-circle mr-2"></i> 
                    Anda memiliki {{ $reservasiMenunggu->count() }} reservasi yang perlu dikonfirmasi
                  </h5>
                  <p class="mb-0">Silakan unggah bukti pembayaran untuk mempercepat proses verifikasi.</p>
                </div>
              @endif
              
              <!-- Tabs -->
              <ul class="nav nav-tabs mb-4" id="reservasiTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="all-tab" data-bs-toggle="tab" 
                          data-bs-target="#all" type="button" role="tab">
                    Semua ({{ $reservasi->count() }})
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="waiting-tab" data-bs-toggle="tab" 
                          data-bs-target="#waiting" type="button" role="tab">
                    Menunggu ({{ $reservasiMenunggu->count() }})
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="paid-tab" data-bs-toggle="tab" 
                          data-bs-target="#paid" type="button" role="tab">
                    Dibayar ({{ $reservasiDibayar->count() }})
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="done-tab" data-bs-toggle="tab" 
                          data-bs-target="#done" type="button" role="tab">
                    Selesai ({{ $reservasiSelesai->count() }})
                  </button>
                </li>
              </ul>
              
              <!-- Tab Content -->
              <div class="tab-content" id="reservasiTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                  @include('pelanggan.reservasi._table', ['reservasis' => $reservasi])
                </div>
                <div class="tab-pane fade" id="waiting" role="tabpanel">
                  @include('pelanggan.reservasi._table', ['reservasis' => $reservasiMenunggu])
                </div>
                <div class="tab-pane fade" id="paid" role="tabpanel">
                  @include('pelanggan.reservasi._table', ['reservasis' => $reservasiDibayar])
                </div>
                <div class="tab-pane fade" id="done" role="tabpanel">
                  @include('pelanggan.reservasi._table', ['reservasis' => $reservasiSelesai])
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
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Upload Bukti Pembayaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="uploadBuktiForm" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Pilih File</label>
              <input type="file" name="file_bukti_tf" class="form-control" accept="image/*,.pdf" required>
              <small class="text-muted">Format: JPG, PNG, PDF (Maks. 2MB)</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>