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
            <li><a href="{{ route('admin.whatsapp.index') }}">WhatsApp Links</a></li>
            <li><a href="{{ route('admin.kecamatans.index') }}">Kecamatan</a></li>
            <li><a href="{{ route('admin.accounts.index') }}">Admin Accounts</a></li>
        </ul>

        <div style="display:flex;align-items:center;gap:12px;">
            <span style="font-size:14px;color:#64748b;">
                Halo, {{ session('admin_username') }}
            </span>

            <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;color:#f97373;cursor:pointer;">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>
