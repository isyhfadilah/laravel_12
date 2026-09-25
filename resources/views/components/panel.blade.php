@props(['title', 'description'])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7']) }}>
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-950">{{ $title }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
    </div>

    {{ $slot }}
</div>
