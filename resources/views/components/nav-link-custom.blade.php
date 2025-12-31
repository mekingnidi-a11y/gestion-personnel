@props(['active', 'href', 'icon'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 text-sm font-medium rounded-lg bg-indigo-800 text-white border-l-4 border-white'
            : 'flex items-center px-4 py-3 text-sm font-medium rounded-lg text-indigo-100 hover:bg-indigo-800 hover:text-white transition duration-150';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    <svg class="w-6 h-6 mr-3 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
    </svg>
    {{ $slot }}
</a>
