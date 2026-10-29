@extends('layouts.app', ['title' => 'Masuk', 'containerClass' => 'mx-auto max-w-lg px-5 py-10 sm:px-8'])

@section('content')
    <x-panel title="Masuk" description="Gunakan NIM dan kata sandi untuk membuka data mahasiswa.">
        <x-validation-errors />
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <x-form-input id="nim" name="nim" label="NIM" :value="old('nim')" autocomplete="username" maxlength="20" required autofocus />
            <x-form-input id="password" name="password" label="Kata sandi" type="password" autocomplete="current-password" required />
            <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">Masuk</button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-600">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-blue-700 underline">Daftar sekarang</a></p>
    </x-panel>
@endsection