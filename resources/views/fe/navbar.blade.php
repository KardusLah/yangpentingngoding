<div class="site-mobile-menu site-navbar-target">
		<div class="site-mobile-menu-header">
			<div class="site-mobile-menu-close">
				<span class="icofont-close js-menu-toggle"></span>
			</div>
		</div>
		<div class="site-mobile-menu-body"></div>
	</div>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ url('/') }}">Tour</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Beranda</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="reservasiDropdown" data-toggle="dropdown">Reservasi</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ url('/reservasi') }}">Pesan Paket Wisata</a>
            <a class="dropdown-item" href="{{ url('/riwayat') }}">Riwayat Reservasi</a>
            <a class="dropdown-item" href="{{ url('/pembayaran') }}">Unggah Bukti Pembayaran</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="informasiDropdown" data-toggle="dropdown">Informasi</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ url('/berita') }}">Berita & Promosi</a>
            <a class="dropdown-item" href="{{ url('/pencarian') }}">Pencarian Objek Wisata</a>
            <a class="dropdown-item" href="{{ url('/penginapan') }}">Informasi Penginapan</a>
          </div>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Kontak</a></li>
      </ul>
    </div>
  </nav>
