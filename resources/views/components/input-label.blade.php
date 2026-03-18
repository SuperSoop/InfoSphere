@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-surface-300 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
