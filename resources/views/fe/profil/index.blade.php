<div class="untree_co-section bg-light">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card shadow-sm rounded-20">
            <div class="card-body p-5">
              <div class="section-title text-center mb-5">
                <h2>Profil Saya</h2>
              </div>
              
              <div class="row align-items-center mb-5">
                <div class="col-md-4 text-center">
                  <div class="position-relative">
                    <img src="{{ asset('storage/' . $pelanggan->foto) }}" 
                         alt="Foto Profil" 
                         class="img-fluid rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <button class="btn btn-outline-third btn-sm position-absolute bottom-0 start-50 translate-middle-x">
                      <i class="fas fa-camera"></i> Ganti Foto
                    </button>
                  </div>
                </div>
                <div class="col-md-8">
                  <h3 class="mb-3">{{ $pelanggan->nama_lengkap }}</h3>
                  <p class="text-muted mb-2">
                    <i class="fas fa-envelope mr-2"></i> {{ Auth::user()->email }}
                  </p>
                  <p class="text-muted mb-2">
                    <i class="fas fa-phone mr-2"></i> {{ $pelanggan->no_hp }}
                  </p>
                  <p class="text-muted mb-0">
                    <i class="fas fa-map-marker-alt mr-2"></i> {{ $pelanggan->alamat }}
                  </p>
                </div>
              </div>
              
              <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-4">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" name="nama_lengkap" class="form-control" 
                         value="{{ $pelanggan->nama_lengkap }}" required>
                </div>
                
                <div class="form-group mb-4">
                  <label class="form-label">Nomor HP</label>
                  <input type="text" name="no_hp" class="form-control" 
                         value="{{ $pelanggan->no_hp }}" required>
                </div>
                
                <div class="form-group mb-4">
                  <label class="form-label">Alamat</label>
                  <textarea name="alamat" class="form-control" rows="3" required>{{ $pelanggan->alamat }}</textarea>
                </div>
                
                <div class="form-group mb-4">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" 
                         value="{{ Auth::user()->email }}" disabled>
                  <small class="text-muted">Hubungi admin untuk mengubah email</small>
                </div>
                
                <div class="form-group mb-4">
                  <label class="form-label">Ganti Password</label>
                  <input type="password" name="password" class="form-control" 
                         placeholder="Kosongkan jika tidak ingin mengubah">
                </div>
                
                <div class="form-group mb-4">
                  <label class="form-label">Konfirmasi Password</label>
                  <input type="password" name="password_confirmation" class="form-control">
                </div>
                
                <div class="text-center mt-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>