@extends('Components.layout')

@section('title', 'Beranda')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-css/home.css') }}">
<script src="{{ asset('js/home.js') }}"></script>
@endpush

@section('content')
    {{-- Hero Section --}}
    @include('components.hero', ['slides' => $slides])

    {{-- Statistik Laporan --}}
    <section class="stats">
        <h2>Laporan Banjir di setiap Kecamatan Kota Parepare 2025</h2>
        <div class="stats-container">
            <div class="stat-box"><h3>3</h3><p>Bacukiki</p></div>
            <div class="stat-box"><h3>0</h3><p>Bacukiki Barat</p></div>
            <div class="stat-box"><h3>9</h3><p>Soreang</p></div>
            <div class="stat-box"><h3>7</h3><p>Ujung</p></div>
        </div>
    </section>

    {{-- Maps Section --}}
    <section class="map-section">
        <h2>Sebaran titik banjir di Kota Parepare</h2>
        <div id="map"></div>
    </section>

    {{-- Pelaporan --}}
    <section class="report">
        <h2>Lapor di mana?</h2>
        <p>BNPB Kota Parepare telah menyiapkan layanan pengaduan banjir melalui sistem kami.</p>
        <a href="{{ url('/pelaporan') }}" class="btn">Laporkan Sekarang</a>
    </section>

    {{-- Info Section --}}
    <div id="slider-container">
        <div id="slides">
            @foreach($sections as $section)
            <div class="slide">
                <img src="{{ asset('storage/'.$section->image) }}" alt="{{ $section->title }}">
                <h1>{{ $section->subtitle }}</h1>
                <p>{{ $section->description }}</p>
            </div>
            @endforeach
        </div>

        <div class="nav-buttons">
            @foreach($sections as $index => $section)
            <button onclick="goToSlide({{ $index }})">{{ $section->title }}</button>
            @endforeach
        </div>
    </div>
    
@endsection

@push('scripts')
    {{-- JS Hero Slider khusus untuk halaman ini --}}
    <script src="{{ asset('js/hero.js') }}" defer></script>

    {{-- JS Peta --}}
    <script>
        var map = L.map('map').setView([-4.016, 119.623], 13); // Koordinat Parepare
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Marker contoh
        L.marker([-4.016, 119.623]).addTo(map)
            .bindPopup('Kota Parepare')
            .openPopup();
    </script>
@endpush
