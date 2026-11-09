@props(['variant' => 'primary'])
<button {{ $attributes->merge(['type' => 'submit', 'class' => match ($variant) {
    'primary' => 'h-row rounded-control bg-accent px-3 text-sm font-medium text-accent-contrast hover:opacity-90 disabled:opacity-50',
    'danger' => 'h-row rounded-control border border-down px-3 text-sm font-medium text-down hover:bg-surface-2 disabled:opacity-50',
    default => 'h-row rounded-control border border-border px-3 text-sm text-text hover:bg-surface-2 disabled:opacity-50',
}]) }}>{{ $slot }}</button>
