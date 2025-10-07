<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    
    <!-- CSS Komponen -->
    <link rel="stylesheet" href="{{ asset('css/components-css/admin-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/admin-navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components-css/admin-footer.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin-css/admin-maps.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-css/admin-home.css') }}">
    
    <!-- CSS khusus halaman -->
    @yield('custom-css')
</head>
<body>
    @include('Admin.admin-components.admin-navbar')

    <main class="main-content">
        @yield('content')
    </main>

    @include('Admin.admin-components.admin-footer')
</body>
</html>
