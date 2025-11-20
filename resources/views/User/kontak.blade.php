@extends('Components.layout')

@section('title', 'Kontak Kami')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user-css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-css/map-popup.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-css/kontak.css') }}">
@endpush

@section('content')
    {{-- Hero Section --}}
    @include('components.hero', ['slides' => $slides])

   @include('Components.kontak-section', ['kontaks' => $kontaks])
    
   @include('components.websitekontak-section', ['websitekontaks' => $websitekontaks])


@endsection

@push('scripts')
<script src="{{ asset('js/hero.js') }}" defer></script>
@endpush
