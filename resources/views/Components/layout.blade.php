<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Website Banjir Parepare')</title>

    {{-- CSS per component --}}
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    <link rel="stylesheet" href="{{ asset('css/components-css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-css/map-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-css/whatsapp-bubble.css') }}">
    

    
    {{-- CSS khusus per halaman --}}
    @stack('styles')
    

    {{-- Leaflet CSS untuk peta --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    {{-- JS Leaflet --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="{{ asset('js/whatsapp-bubble.js') }}" defer></script>
    {{-- JS khusus per halaman --}}

    @stack('scripts')
</body>
</html>
