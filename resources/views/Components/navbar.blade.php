<nav class="navbar">
    <div class="navbar-container">
        {{-- Logo kiri --}}
        <div class="navbar-left">
            <a href="{{ url('/home') }}" class="logo">
                T U L I P
            </a>
        </div>

        {{-- Tombol hamburger (mobile) --}}
        <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>

        {{-- Menu utama (desktop) --}}
        <ul class="navbar-menu">
            <li><a href="{{ url('/home') }}">Home</a></li>
            <li><a href="{{ url('/data') }}">Data</a></li>
            <li><a href="{{ url('/maps') }}">Peta</a></li>
            <li><a href="{{ url('/pelaporan') }}">Pelaporan</a></li>
            <li><a href="{{ url('/faq') }}">FAQ</a></li>
            <li><a href="{{ url('/kontak') }}">Kontak</a></li>
            <li><a href="{{ url('/sistemcerdas') }}">Sistem Cerdas</a></li>
        </ul>

        {{-- Auth kanan (desktop) --}}
        <div class="navbar-auth">
            @if(!session()->has('admin_id'))
                <a href="{{ route('admin.login') }}" class="btn-pill btn-soft">
                    Login Admin
                </a>
            @else
                <a href="{{ route('admin.home') }}" class="btn-pill btn-primary">
                    Admin Panel
                </a>

                <form action="{{ route('admin.logout') }}" method="POST" class="inline-form">
                    @csrf
                    <button type="submit" class="btn-pill btn-danger">
                        Logout
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Backdrop untuk sidebar (mobile) --}}
    <div class="navbar-backdrop" id="navbarBackdrop"></div>

    {{-- Sidebar (mobile) --}}
    <div class="navbar-sidebar" id="navbarSidebar">
        <div class="sidebar-header">
            <div class="logo">
                T U L I P
            </div>
            <button class="sidebar-close" id="sidebarClose" aria-label="Close navigation">
                &times;
            </button>
        </div>

        <ul class="sidebar-menu">
            <li><a href="{{ url('/home') }}">Home</a></li>
            <li><a href="{{ url('/data') }}">Data</a></li>
            <li><a href="{{ url('/maps') }}">Peta</a></li>
            <li><a href="{{ url('/pelaporan') }}">Pelaporan</a></li>
            <li><a href="{{ url('/faq') }}">FAQ</a></li>
            <li><a href="{{ url('/kontak') }}">Kontak</a></li>
            <li><a href="{{ url('/sistemcerdas') }}">Sistem Cerdas</a></li>
        </ul>

        <div class="sidebar-auth">
            @if(!session()->has('admin_id'))
                <a href="{{ route('admin.login') }}" class="btn-pill btn-soft full-width">
                    Login Admin
                </a>
            @else
                <a href="{{ route('admin.home') }}" class="btn-pill btn-primary full-width">
                    Admin Panel
                </a>

                <form action="{{ route('admin.logout') }}" method="POST" class="inline-form full-width">
                    @csrf
                    <button type="submit" class="btn-pill btn-danger full-width">
                        Logout
                    </button>
                </form>
            @endif
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navbar = document.querySelector('.navbar');
        const toggle = document.getElementById('navbarToggle');
        const sidebar = document.getElementById('navbarSidebar');
        const closeBtn = document.getElementById('sidebarClose');
        const backdrop = document.getElementById('navbarBackdrop');

        function handleScroll() {
            if (window.scrollY > 10) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        }

        function openSidebar() {
            sidebar.classList.add('open');
            backdrop.classList.add('show');
            document.body.classList.add('no-scroll');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
            document.body.classList.remove('no-scroll');
        }

        toggle.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        backdrop.addEventListener('click', closeSidebar);
        window.addEventListener('scroll', handleScroll);

        handleScroll();
    });
</script>
