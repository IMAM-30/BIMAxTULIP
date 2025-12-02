<nav class="navbar">
    <div class="navbar-container">
        <div class="logo">T U L I P</div>
        <ul class="navbar-menu">
            <li><a href="{{ url('/home') }}">Home</a></li>
            <li><a href="{{ url('/data') }}">Data</a></li>
            <li><a href="{{ url('/maps') }}">Peta</a></li>
            <li><a href="{{ url('/pelaporan') }}">Pelaporan</a></li>
            <li><a href="{{ url('/faq') }}">FAQ</a></li>
            <li><a href="{{ url('/kontak') }}">Kontak</a></li>
            <li><a href="{{ url('/sistemcerdas') }}">Sistem Cerdas</a></li>
        </ul>
     
        <div class="navbar-auth">
            @if(!session()->has('admin_id'))
                <a href="{{ route('admin.login') }}" style="
                    padding:8px 14px;
                    border-radius:999px;
                    border:1px solid #cbd5f5;
                    font-size:13px;
                    color:#475569;
                    text-decoration:none;
                    background:#eef2ff;
                    margin-left:20px;
                ">
                    Login Admin
                </a>
            @else
                <a href="{{ route('admin.home') }}" style="
                    padding:8px 14px;
                    border-radius:999px;
                    border:1px solid #cbd5f5;
                    font-size:13px;
                    color:#1d4ed8;
                    text-decoration:none;
                    background:#dbeafe;
                    margin-left:20px;
                ">
                    Admin Panel
                </a>

                <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="
                        padding:8px 14px;
                        border-radius:999px;
                        border:none;
                        font-size:13px;
                        color:white;
                        background:#ef4444;
                        margin-left:10px;
                        cursor:pointer;
                    ">
                        Logout
                    </button>
                </form>
            @endif
        </div>

    </div>
</nav>
