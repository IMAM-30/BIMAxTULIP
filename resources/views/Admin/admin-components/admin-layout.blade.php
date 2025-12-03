<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    
    <link rel="stylesheet" href="{{ asset('css/admin-global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/admin-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/admin-navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/admin-footer.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin-css/admin-maps.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-css/admin-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-css/admin-kecamatans.css') }}">

    @yield('custom-css')
</head>
<body class="admin-body">
    @include('admin.admin-components.admin-navbar')

    <main class="main-content">
        @yield('content')
        @include('admin.admin-components.admin-footer')
    </main>

    <script src="{{ asset('js/admin-navbar.js') }}"></script>
    @yield('custom-js')
</body>
</html>
