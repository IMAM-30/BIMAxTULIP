@extends('Components.layout')

@section('title', 'Sistem Cerdas')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-css/home.css') }}">

@endpush

@section('content')
    {{-- Hero Section --}}
    @include('components.hero', ['slides' => $slides])

    {{-- Statistik Laporan --}}
    <section class="stats">
        <h2>Sistem Cerdas TULIP</h2>
        <div class="stats-container">
        </div>
    </section>

@endsection

@push('scripts')
<script src="{{ asset('js/hero.js') }}" defer></script>
@endpush
