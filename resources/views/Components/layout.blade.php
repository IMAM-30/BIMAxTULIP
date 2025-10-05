<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Website Banjir Parepare')</title>

    {{-- CSS per component --}}
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    <link rel="stylesheet" href="{{ asset('css/components-css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/hero.css') }}">
    
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

    {{-- JS khusus per halaman --}}
    @yield('scripts')
    @stack('scripts')
</body>
</html>
