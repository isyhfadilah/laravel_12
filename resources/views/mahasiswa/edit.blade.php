@extends('layouts.app', [
    'title' => 'Edit Mahasiswa',
    'containerClass' => 'mx-auto max-w-xl px-5 py-10 sm:px-8 lg:px-14 lg:py-14',
])

@section('content')
        <x-page-heading
            title="Edit Mahasiswa"
            description="Perbarui informasi mahasiswa dengan data terbaru."
        />

        <x-panel title="Informasi mahasiswa" description="Pastikan semua informasi sudah benar sebelum menyimpan perubahan.">
            <x-validation-errors />

            <form action="/mahasiswa/{{ $mahasiswa->id }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <x-form-input id="nama" name="nama" label="Nama lengkap" :value="old('nama', $mahasiswa->nama)" required />
                <x-form-input id="nim" name="nim" label="NIM" :value="old('nim', $mahasiswa->nim)" required />
                <x-form-input id="email" name="email" label="Email" type="email" :value="old('email', $mahasiswa->email)" required />

                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <a href="/mahasiswa" class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100">Batal</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 active:bg-blue-800">Simpan perubahan</button>
                </div>
            </form>
        </x-panel>
@endsection