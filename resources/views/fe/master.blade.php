<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Untree.co">
	<link rel="shortcut icon" href="favicon.png">

	<meta name="description" content="" />
	<meta name="keywords" content="bootstrap, bootstrap4" />

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Source+Serif+Pro:wght@400;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('fe/assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('fe/assets/css/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('fe/assets/css/owl.theme.default.min.css') }}">
  <link rel="stylesheet" href="{{ asset('fe/assets/css/jquery.fancybox.min.css') }}">
  <link rel="stylesheet" href="{{ asset('fe/assets/fonts/icomoon/style.css') }}">
  <link rel="stylesheet" href="{{ asset('fe/assets/fonts/flaticon/font/flaticon.css') }}">
  <link rel="stylesheet" href="{{ asset('fe/assets/css/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('fe/assets/css/aos.css') }}">
  <link rel="stylesheet" href="{{ asset('fe/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('be/assets/extensions/flatpickr/flatpickr.min.js') }}">

	<title>Tour Free Bootstrap Template for Travel Agency by Untree.co</title>
</head>

<body>

  {{-- Mobile Menu --}}
  <div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
      <div class="site-mobile-menu-close">
        <span class="icofont-close js-menu-toggle"></span>
      </div>
    </div>
    <div class="site-mobile-menu-body"></div>
  </div>

  {{-- Navbar --}}
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

  {{-- Main Content --}}
  <main>
    @yield('content')
  </main>

  {{-- Footer --}}
  @yield('footer')

  {{-- Scripts --}}
  <script src="{{ asset('fe/assets/js/jquery-3.4.1.min.js') }}"></script>
  <script src="{{ asset('fe/assets/js/popper.min.js') }}"></script>
  <script src="{{ asset('fe/assets/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('fe/assets/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('fe/assets/js/jquery.fancybox.min.js') }}"></script>
  <script src="{{ asset('fe/assets/js/aos.js') }}"></script>
  <script src="{{ asset('fe/assets/js/typed.js') }}"></script>
  <script src="{{ asset('fe/assets/js/custom.js') }}"></script>
  @stack('scripts')
  @stack('notifications')
</body>
</html>
