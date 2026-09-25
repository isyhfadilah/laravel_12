@props(['eyebrow' => 'Administrasi akademik', 'title', 'description'])

<div class="mb-8 {{ $attributes->get('class') }}">
    <p class="mb-3 text-sm font-semibold uppercase tracking-wider text-blue-600">{{ $eyebrow }}</p>
    <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ $title }}</h1>
    <p class="mt-3 text-base leading-7 text-slate-600">{{ $description }}</p>
</div>
