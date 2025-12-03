<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Website Banjir Parepare')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    <link rel="stylesheet" href="{{ asset('css/components-css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-css/maps.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-css/map-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-css/whatsapp-bubble.css') }}">

    @stack('styles')

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    <div class="bg-circle big-left"></div>
    <div class="bg-circle right-outline"></div>
    <div class="bg-circle top-left"></div>
    <div class="bg-circle small-soft"></div>

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="{{ asset('js/whatsapp-bubble.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/maps.js') }}"></script>
    <script src="{{ asset('js/hero.js') }}"></script>
    <script src="{{ asset('js/faq.js') }}"></script>
    


    @stack('scripts')
</body>
</html>
