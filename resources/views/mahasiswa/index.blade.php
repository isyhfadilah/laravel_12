<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-800 bg-slate-950 text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4 sm:px-8">
            <a href="/mahasiswa" class="flex items-center gap-3 text-sm font-semibold tracking-wide">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-lg font-bold shadow-lg shadow-blue-950/40">M</span>
                <span>Portal Akademik</span>
            </a>
            <span class="hidden text-sm text-slate-400 sm:block">Data Mahasiswa</span>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-5 py-10 sm:px-8 lg:py-14">
        <div class="mb-8 max-w-2xl">
            <p class="mb-3 text-sm font-semibold uppercase tracking-wider text-blue-600">Administrasi akademik</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Data Mahasiswa</h1>
            <p class="mt-3 text-base leading-7 text-slate-600">Kelola daftar mahasiswa dengan cepat dan teratur.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-7 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] lg:items-start">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-slate-950">Tambah mahasiswa</h2>
                    <p class="mt-1 text-sm text-slate-500">Lengkapi informasi berikut untuk menyimpan data baru.</p>
                </div>

                @if($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                        <p class="font-semibold">Periksa kembali data Anda:</p>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/mahasiswa" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="nama" class="mb-2 block text-sm font-medium text-slate-700">Nama lengkap</label>
                        <input id="nama" type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Aisyah Putri" required class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100">
                    </div>
                    <div>
                        <label for="nim" class="mb-2 block text-sm font-medium text-slate-700">NIM</label>
                        <input id="nim" type="text" name="nim" value="{{ old('nim') }}" placeholder="Contoh: 20240001" required class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100">
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@kampus.ac.id" required class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100">
                    </div>
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 active:bg-blue-800">Simpan data</button>
                </form>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5 sm:px-7">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">Daftar mahasiswa</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $mahasiswa->count() }} data terdaftar</p>
                    </div>
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Aktif</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-3.5 font-semibold sm:px-7">Mahasiswa</th>
                                <th class="px-4 py-3.5 font-semibold">NIM</th>
                                <th class="px-4 py-3.5 font-semibold">Email</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($mahasiswa as $mhs)
                                <tr class="transition hover:bg-blue-50/50">
                                    <td class="px-6 py-4 font-medium text-slate-900 sm:px-7">{{ $mhs->nama }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ $mhs->nim }}</td>
                                    <td class="px-4 py-4 text-slate-600">{{ $mhs->email }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center sm:px-7">
                                        <p class="font-medium text-slate-700">Belum ada data mahasiswa</p>
                                        <p class="mt-1 text-sm text-slate-500">Data yang ditambahkan akan muncul di sini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</body>
</html>