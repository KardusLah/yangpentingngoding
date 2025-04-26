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
  @yield('navbar')

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
