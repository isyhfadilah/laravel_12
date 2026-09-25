@extends('layouts.app', ['title' => 'Data Mahasiswa'])

@section('content')
        <x-page-heading
            title="Data Mahasiswa"
            description="Kelola daftar mahasiswa dengan cepat dan teratur."
            class="max-w-2xl"
        />

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-7 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] lg:items-start">
            <x-panel title="Tambah mahasiswa" description="Lengkapi informasi berikut untuk menyimpan data baru.">
                <x-validation-errors />

                <form action="/mahasiswa" method="POST" class="space-y-5">
                    @csrf
                    <x-form-input id="nama" name="nama" label="Nama lengkap" :value="old('nama')" placeholder="Contoh: Aisyah Putri" required />
                    <x-form-input id="nim" name="nim" label="NIM" :value="old('nim')" placeholder="Contoh: 20240001" required />
                    <x-form-input id="email" name="email" label="Email" type="email" :value="old('email')" placeholder="nama@kampus.ac.id" required />
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 active:bg-blue-800">Simpan data</button>
                </form>
            </x-panel>

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
                                <th class="px-4 py-3.5 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($mahasiswa as $mhs)
                                <tr class="transition hover:bg-blue-50/50">
                                    <td class="px-6 py-4 font-medium text-slate-900 sm:px-7">{{ $mhs->nama }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ $mhs->nim }}</td>
                                    <td class="px-4 py-4 text-slate-600">{{ $mhs->email }}</td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <a href="/mahasiswa/{{ $mhs->id }}/edit" class="inline-flex items-center rounded-lg border border-blue-200 px-3 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                        <td colspan="4" class="px-6 py-12 text-center sm:px-7">
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
@endsection