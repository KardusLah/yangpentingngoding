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
  <link rel="stylesheet" href="{{ asset('be/assets/extensions/flatpickr/flatpickr.min.css') }}">

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
  <nav class="site-nav" id="main-navbar">
    <div class="container">
        <div class="site-navigation">
            <a href="{{ route('home') }}" class="logo m-0">Tour<span class="text-primary">.</span></a>
            
            <ul class="js-clone-nav d-none d-lg-inline-block text-left site-menu float-right align-items-center">
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                <li class="{{ request()->routeIs('fe.paket.index') ? 'active' : '' }}"><a href="{{ route('fe.paket.index') }}">Paket Wisata</a></li>
                <li class="{{ request()->routeIs('fe.wisata.index') ? 'active' : '' }}">
                    <a href="{{ route('fe.wisata.index') }}">Obyek Wisata</a>
                </li>
                <li class="{{ request()->routeIs('fe.penginapan.index') ? 'active' : '' }}">
                    <a href="{{ route('fe.penginapan.index') }}">Penginapan</a>
                </li>
                <li class="{{ request()->routeIs('fe.berita.index') ? 'active' : '' }}"><a href="{{ route('fe.berita.index') }}">Berita</a></li>
                
                @guest
                    <li class="ml-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3 mr-2">Login</a>
                        <a href="{{ route('registration') }}" class="btn btn-primary btn-sm px-3">Sign In</a>
                    </li>
                @else
                    <li class="has-children ml-3">
                        <a href="#" class="d-inline-flex align-items-center">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown arrow-top">
                            <li><a href="{{ route('profile') }}">Profil Saya</a></li>
                            <li><a href="{{ route('profile') }}#riwayat-reservasi">Pesanan</a></li>
                            <li><a href="{{ route('logout') }}" 
                                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                  Logout
                              </a>
                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                  @csrf
                              </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>

            {{-- Mobile menu toggle and buttons --}}
            <div class="d-inline-block d-lg-none ml-auto">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3 mr-2">Login</a>
                    <a href="{{ route('registration') }}" class="btn btn-primary btn-sm px-3">Sign In</a>
                @else
                    <a href="{{ route('profile') }}" class="d-inline-block align-middle mr-3"></a>
                @endguest
                <a href="#" class="burger site-menu-toggle js-menu-toggle d-inline-block d-lg-none light">
                    <span></span>
                </a>
            </div>
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
    <script src="{{ asset('be/assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.site-nav');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.js-menu-toggle');
            const mobileMenu = document.querySelector('.site-mobile-menu');
            
            if (menuToggle && mobileMenu) {
                menuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.body.classList.toggle('offcanvas-menu');
                });
            }

            // Close mobile menu when clicking a link
            const mobileLinks = document.querySelectorAll('.site-mobile-menu .site-nav-wrap a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    document.body.classList.remove('offcanvas-menu');
                });
            });
        });
    </script>
    
    @stack('scripts')
    @stack('notifications')
</body>
</html>
