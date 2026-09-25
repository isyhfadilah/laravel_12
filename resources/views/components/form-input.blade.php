@props(['id', 'name', 'label', 'value' => '', 'type' => 'text', 'placeholder' => null])

<div>
    <label for="{{ $id }}" class="mb-2 block text-sm font-medium text-slate-700">{{ $label }}</label>
    <input
        id="{{ $id }}"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100']) }}
    >
</div>
