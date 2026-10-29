<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portal Akademik' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="neo-app min-h-screen antialiased">
    <a href="#main-content" class="skip-link">Langsung ke konten</a>
    <x-site-header />

    <x-container id="main-content" :class="$containerClass ?? 'mx-auto max-w-6xl px-5 py-10 sm:px-8 lg:py-14'">
        @yield('content')
    </x-container>
</body>
</html>
