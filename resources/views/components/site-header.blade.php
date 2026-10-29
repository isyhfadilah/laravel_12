<header class="site-header">
    <nav class="site-nav" aria-label="Navigasi utama">
        <a href="/" class="brand" aria-label="Data Mahasiswa, beranda">
            <span class="brand-mark" aria-hidden="true">M<span>.</span></span>
            <span>DATA<br><span class="brand-subtitle">MAHASISWA</span></span>
        </a>
        <div class="nav-actions">
            @auth
                <a href="/mahasiswa" class="nav-link">Kelola data <span aria-hidden="true">↗</span></a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="neo-button nav-logout">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link" @if(request()->routeIs('login')) aria-current="page" @endif>Masuk</a>
                <a href="{{ route('register') }}" class="neo-button" @if(request()->routeIs('register')) aria-current="page" @endif>Daftar <span aria-hidden="true">↗</span></a>
            @endauth
        </div>
    </nav>
</header>