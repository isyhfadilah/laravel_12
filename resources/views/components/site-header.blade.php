<header class="border-b border-slate-800 bg-slate-950 text-white">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4 sm:px-8">
        <a href="/" class="flex items-center gap-3 text-sm font-semibold tracking-wide" aria-label="Manajemen Data Mahasiswa, beranda">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-lg font-bold shadow-lg shadow-blue-950/40">M</span>
            <span>Data Mahasiswa</span>
        </a>
        @auth
        <div class="flex items-center gap-3">
        <a href="/mahasiswa" class="inline-flex items-center gap-2 rounded-lg border border-slate-700 px-3 py-2 text-xs font-semibold text-slate-100 transition hover:border-slate-500 hover:bg-slate-800 sm:text-sm">
            <span>Kelola data</span>
            <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
        </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg border border-slate-700 px-3 py-2 text-sm font-semibold hover:bg-slate-800">Keluar</button>
            </form>
        </div>
        @else
            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-100 hover:underline">Masuk</a>
        @endauth
    </div>
</header>
