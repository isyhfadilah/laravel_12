@props(['class' => 'mx-auto max-w-6xl px-5 py-10 sm:px-8 lg:py-14'])

<main {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</main>
