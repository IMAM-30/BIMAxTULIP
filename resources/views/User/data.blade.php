@extends('Components.layout')

@section('title', 'Beranda')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-css/map-popup.css') }}">
@endpush

@section('content')
    {{-- Hero Section --}}
    @include('components.hero', ['slides' => $slides])


    {{-- Statistik Laporan --}}
    <section class="stats">
        <h2> DATA Laporan Banjir di setiap Kecamatan Kota Parepare 2025</h2>
        <div class="stats-container">
            <div class="stat-box"><h3>3</h3><p>Bacukiki</p></div>
            <div class="stat-box"><h3>0</h3><p>Bacukiki Barat</p></div>
            <div class="stat-box"><h3>9</h3><p>Soreang</p></div>
            <div class="stat-box"><h3>7</h3><p>Ujung</p></div>
        </div>
    </section>

    


@endsection

@push('scripts')
<script src="{{ asset('js/hero.js') }}" defer></script>
@endpush
