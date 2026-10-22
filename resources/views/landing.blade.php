@extends('layouts.app', ['title' => 'Manajemen Data Mahasiswa'])

@section('content')
    <section class="grid gap-12 py-4 sm:py-8 lg:min-h-[58vh] lg:grid-cols-[minmax(0,1.2fr)_minmax(18rem,0.8fr)] lg:items-center lg:gap-16">
        <div class="max-w-2xl">
            <p class="mb-5 inline-flex items-center gap-3 text-xs font-semibold uppercase tracking-wider text-blue-700">
                <span class="h-px w-8 bg-blue-600" aria-hidden="true"></span>
                Pengelolaan akademik
            </p>
            <h1 class="text-4xl font-bold leading-tight text-slate-950 sm:text-5xl">
                Data mahasiswa, terkelola dalam satu tempat.
            </h1>
            <p class="mt-5 max-w-xl text-base leading-7 text-slate-600">
                Kelola informasi mahasiswa dengan alur yang sederhana, dari pencatatan hingga pencarian data.
            </p>
            <a href="/mahasiswa" class="mt-8 inline-flex items-center gap-3 rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                Buka data mahasiswa
                <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
            </a>
        </div>

        <aside aria-label="Ruang lingkup aplikasi" class="border-t border-slate-200 pt-2 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
            <div class="grid grid-cols-[2.5rem_1fr] gap-x-4 border-b border-slate-200 py-5">
                <span class="pt-0.5 text-xs font-semibold tabular-nums text-blue-700">01</span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Data terstruktur</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-600">Nama, NIM, dan email tersusun dalam satu daftar.</p>
                </div>
            </div>
            <div class="grid grid-cols-[2.5rem_1fr] gap-x-4 border-b border-slate-200 py-5">
                <span class="pt-0.5 text-xs font-semibold tabular-nums text-blue-700">02</span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Pencarian praktis</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-600">Temukan data berdasarkan nama, NIM, atau email.</p>
                </div>
            </div>
            <div class="grid grid-cols-[2.5rem_1fr] gap-x-4 border-b border-slate-200 py-5">
                <span class="pt-0.5 text-xs font-semibold tabular-nums text-blue-700">03</span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Pengelolaan langsung</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-600">Perbarui atau hapus data dari halaman yang sama.</p>
                </div>
            </div>
        </aside>
    </section>
@endsection