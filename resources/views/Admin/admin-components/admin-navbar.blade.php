<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">Admin Panel</div>
        <ul class="navbar-menu">
            <li><a href="{{ route('admin.home') }}">Home</a></li>
            <li><a href="{{ route('admin.sections') }}">Sections</a></li>
            <li><a href="{{ route('admin.maps') }}">Maps</a></li>
            <li><a href="{{ route('faq.index') }}">FAQ</a></li>
            <li><a href="{{ route('admin.laporan') }}">Laporan</a></li>
            <li><a href="{{ route('admin.kontak.index') }}">Kontak</a></li>
            <li><a href="{{ route('admin.websitekontak.index') }}">WebsiteKontak</a></li>
            <li><a href="{{ route('admin.news.index') }}">News</a></li>
            <li><a href="{{ route('admin.monthly-stats.index') }}">Monthly Stats</a></li>
            <li><a href="{{ route('admin.kecamatans.index') }}">Kecamatan</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>
</nav>
