@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection
@section('content')
<div class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="intro-wrap">
          <h1 class="mb-5"><span class="d-block">Let's Enjoy Your</span> Trip In <span class="typed-words"></span></h1>

          <div class="row">
            <div class="col-12">
              <form class="form">
                <div class="row mb-2">
                  <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-4">
                    <select name="" id="" class="form-control custom-select">
                      <option value="">Destination</option>
                      <option value="">Peru</option>
                      <option value="">Japan</option>
                      <option value="">Thailand</option>
                      <option value="">Brazil</option>
                      <option value="">United States</option>
                      <option value="">Israel</option>
                      <option value="">China</option>
                      <option value="">Russia</option>
                    </select>
                  </div>
                  <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-5">
                    <input type="text" class="form-control" name="daterange">
                  </div>
                  <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-3">
                    <input type="text" class="form-control" placeholder="# of People">
                  </div>

                </div>    
                <div class="row align-items-center">
                  <div class="col-sm-12 col-md-6 mb-3 mb-lg-0 col-lg-4">
                    <input type="submit" class="btn btn-primary btn-block" value="Search">
                  </div>
                  <div class="col-lg-8">
                    <label class="control control--checkbox mt-3">
                      <span class="caption">Save this search</span>
                      <input type="checkbox" checked="checked" />
                      <div class="control__indicator"></div>
                    </label>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="slides">
          <img src="{{ asset('fe/assets/images/hero-slider-1.jpg') }}" alt="Image" class="img-fluid active">
          <img src="{{ asset('fe/assets/images/hero-slider-2.jpg') }}" alt="Image" class="img-fluid">
          <img src="{{ asset('fe/assets/images/hero-slider-3.jpg') }}" alt="Image" class="img-fluid">
          <img src="{{ asset('fe/assets/images/hero-slider-4.jpg') }}" alt="Image" class="img-fluid">
          <img src="{{ asset('fe/assets/images/hero-slider-5.jpg') }}" alt="Image" class="img-fluid">
        </div>
      </div>
    </div>
  </div>
</div>


<div class="untree_co-section">
  <div class="container">
    <div class="row mb-5 justify-content-center">
      <div class="col-lg-6 text-center">
        <h2 class="section-title text-center mb-3">Our Services</h2>
        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
      </div>
    </div>
    <div class="row align-items-stretch">
      <div class="col-lg-4 order-lg-1">
        <div class="h-100"><div class="frame h-100"><div class="feature-img-bg h-100" style="background-image: url('{{ asset('fe/assets/images/hero-slider-1.jpg') }}');"></div></div></div>
      </div>

      <div class="col-6 col-sm-6 col-lg-4 feature-1-wrap d-md-flex flex-md-column order-lg-1" >

        <div class="feature-1 d-md-flex">
          <div class="align-self-center">
            <span class="flaticon-house display-4 text-primary"></span>
            <h3>Beautiful Condo</h3>
            <p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
          </div>
        </div>

        <div class="feature-1 ">
          <div class="align-self-center">
            <span class="flaticon-restaurant display-4 text-primary"></span>
            <h3>Restaurants & Cafe</h3>
            <p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
          </div>
        </div>

      </div>

      <div class="col-6 col-sm-6 col-lg-4 feature-1-wrap d-md-flex flex-md-column order-lg-3" >

        <div class="feature-1 d-md-flex">
          <div class="align-self-center">
            <span class="flaticon-mail display-4 text-primary"></span>
            <h3>Easy to Connect</h3>
            <p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
          </div>
        </div>

        <div class="feature-1 d-md-flex">
          <div class="align-self-center">
            <span class="flaticon-phone-call display-4 text-primary"></span>
            <h3>24/7 Support</h3>
            <p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<div class="untree_co-section">
  <div class="container">
    <h2 class="section-title text-center mb-3">Reservasi Paket Wisata</h2>
    {{-- <form action="{{ route('reservasi.store') }}" method="POST"> --}}
      @csrf
      <div class="row mb-3">
        <div class="col-lg-6">
          <label for="paket">Pilih Paket Wisata</label>
          <select name="paket_id" id="paket" class="form-control">
            <option value="">-- Pilih Paket --</option>
            {{-- @foreach($paketWisata as $paket)
              <option value="{{ $paket->id }}">{{ $paket->nama }} - Rp{{ number_format($paket->harga, 0, ',', '.') }}</option>
            @endforeach --}}
          </select>
        </div>
        <div class="col-lg-3">
          <label for="jumlah">Jumlah Peserta</label>
          <input type="number" name="jumlah_peserta" id="jumlah" class="form-control" min="1" required>
        </div>
        <div class="col-lg-3">
          <label for="tanggal">Tanggal Wisata</label>
          <input type="date" name="tanggal_wisata" id="tanggal" class="form-control" required>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 text-center">
          <button type="submit" class="btn btn-primary">Reservasi</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="untree_co-section">
  <div class="container">
    <h2 class="section-title text-center mb-3">Riwayat Reservasi</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Tanggal Reservasi</th>
          <th>Paket Wisata</th>
          <th>Jumlah Peserta</th>
          <th>Total Harga</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        {{-- @foreach($riwayatReservasi as $reservasi)
          <tr>
            <td>{{ $reservasi->tanggal_reservasi }}</td>
            <td>{{ $reservasi->paket->nama }}</td>
            <td>{{ $reservasi->jumlah_peserta }}</td>
            <td>Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
            <td>{{ $reservasi->status }}</td>
          </tr>
        @endforeach
      </tbody> --}}
    </table>
  </div>
</div>
@endsection
@section('footer')
    @include('fe.footer')
@endsection