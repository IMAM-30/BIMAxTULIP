<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-inner">
        <div class="sidebar-header">
            <h2 class="sidebar-title">Admin Panel</h2>

            <button class="sidebar-toggle" id="sidebarToggle" type="button">
                ☰
            </button>

            <span class="sidebar-username">
                👋 Halo, {{ session('admin_username') }}
            </span>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.home') }}"
                   class="{{ request()->routeIs('admin.home') ? 'active' : '' }}">
                    <span class="icon">🏠</span><span class="label">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.maps') }}"
                   class="{{ request()->routeIs('admin.maps*') ? 'active' : '' }}">
                    <span class="icon">🗺️</span><span class="label">Maps</span>
                </a>
            </li>
            <li>
                <a href="{{ route('faq.index') }}"
                   class="{{ request()->routeIs('faq.*') ? 'active' : '' }}">
                    <span class="icon">❓</span><span class="label">FAQ</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.laporan') }}"
                   class="{{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                    <span class="icon">📨</span><span class="label">Laporan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kontak.index') }}"
                   class="{{ request()->routeIs('admin.kontak.*') ? 'active' : '' }}">
                    <span class="icon">📞</span><span class="label">Kontak</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.websitekontak.index') }}"
                   class="{{ request()->routeIs('admin.websitekontak.*') ? 'active' : '' }}">
                    <span class="icon">🌐</span><span class="label">Website Kontak</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.news.index') }}"
                   class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <span class="icon">📰</span><span class="label">News</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.monthly-stats.index') }}"
                   class="{{ request()->routeIs('admin.monthly-stats.*') ? 'active' : '' }}">
                    <span class="icon">📊</span><span class="label">Monthly Stats</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.whatsapp.index') }}"
                   class="{{ request()->routeIs('admin.whatsapp.*') ? 'active' : '' }}">
                    <span class="icon">💬</span><span class="label">WhatsApp</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kecamatans.index') }}"
                   class="{{ request()->routeIs('admin.kecamatans.*') ? 'active' : '' }}">
                    <span class="icon">📍</span><span class="label">Kecamatan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.accounts.index') }}"
                   class="{{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}">
                    <span class="icon">👤</span><span class="label">Admin Accounts</span>
                </a>
            </li>
        </ul>

        <form action="{{ route('admin.logout') }}" method="POST" class="sidebar-logout">
            @csrf
            <button type="submit">
                <span class="icon">🚪</span><span class="label">Logout</span>
            </button>
        </form>
    </div>
</aside>
