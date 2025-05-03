<nav class="site-nav">
  <div class="container">
    <div class="site-navigation">
      <a href="{{ route('home') }}" class="logo m-0">Tour <span class="text-primary">.</span></a>
      <ul class="js-clone-nav d-none d-lg-inline-block text-left site-menu float-right align-items-center">
        <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('fe.paket.index') }}">Paket Wisata</a></li>
        <li><a href="">Obyek Wisata</a></li>
        {{-- {{ route('obyek.index') }} --}}
        <li><a href="{{ route('fe.penginapan.index') }}">Penginapan</a></li>
        <li><a href="{{ route('fe.berita.index') }}">Berita</a></li>
        @guest
          <li>
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3 mr-2">Login</a>
            <a href="{{ route('registration') }}" class="btn btn-primary btn-sm px-3">Sign In</a>
          </li>
        @else
          <li>
            <a href="{{ route('home') }}" class="d-inline-block align-middle">
              <img src="{{ Auth::user()->foto ?? asset('fe/assets/images/user.png') }}" alt="Profil" class="rounded-circle" width="32" height="32">
            </a>
          </li>
        @endguest
      </ul>
      {{-- Tombol login/sign in untuk mobile --}}
      <div class="d-inline-block d-lg-none float-right">
        @guest
          <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3 mr-2">Login</a>
          <a href="{{ route('registration') }}" class="btn btn-primary btn-sm px-3">Sign In</a>
        @else
          <a href="{{ route('profile') }}" class="d-inline-block align-middle">
            <img src="{{ Auth::user()->foto ?? asset('fe/assets/images/user.png') }}" alt="Profil" class="rounded-circle" width="32" height="32">
          </a>
        @endguest
      </div>
      <a href="#" class="burger ml-auto float-right site-menu-toggle js-menu-toggle d-inline-block d-lg-none light" data-toggle="collapse" data-target="#main-navbar">
        <span></span>
      </a>
    </div>
  </div>
</nav>