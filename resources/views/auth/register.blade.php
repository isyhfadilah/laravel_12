@extends('layouts.app', ['title' => 'Daftar akun', 'containerClass' => 'mx-auto max-w-6xl px-5 py-10 sm:px-8 lg:py-14'])

@section('content')
    <div class="auth-shell">
        <x-auth-intro :register="true" />
    <x-panel class="auth-panel" title="Daftar akun" description="Lengkapi data berikut untuk membuat akun mahasiswa.">
        <x-validation-errors />
        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf
            <x-form-input id="nama" name="nama" label="Nama lengkap" :value="old('nama')" autocomplete="name" maxlength="100" required autofocus />
            <x-form-input id="nim" name="nim" label="NIM" :value="old('nim')" autocomplete="username" maxlength="20" required />
            <x-form-input id="email" name="email" label="Email" type="email" :value="old('email')" autocomplete="email" maxlength="255" required />
            <x-form-input id="password" name="password" label="Kata sandi (minimal 8 karakter)" type="password" autocomplete="new-password" minlength="8" required />
            <x-form-input id="password_confirmation" name="password_confirmation" label="Konfirmasi kata sandi" type="password" autocomplete="new-password" minlength="8" required />
            <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">Daftar</button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-600">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-blue-700 underline">Masuk</a></p>
    </x-panel>
    </div>
@endsection