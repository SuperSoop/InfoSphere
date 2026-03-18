@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-2.5 rounded-xl text-start text-sm font-medium bg-brand-500/15 text-brand-300 transition-all duration-200'
            : 'block w-full px-4 py-2.5 rounded-xl text-start text-sm font-medium text-surface-400 hover:text-surface-200 hover:bg-surface-700/50 transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
