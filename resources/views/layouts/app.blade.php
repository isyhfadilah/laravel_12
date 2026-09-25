<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portal Akademik' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <x-site-header />

    <x-container :class="$containerClass ?? 'mx-auto max-w-6xl px-5 py-10 sm:px-8 lg:py-14'">
        @yield('content')
    </x-container>
</body>
</html>
