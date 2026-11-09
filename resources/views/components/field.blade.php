@props(['label', 'name', 'type' => 'text', 'autocomplete' => null])
<label class="mb-3 block">
    <span class="mb-1 block text-2xs uppercase tracking-wider text-muted">{{ $label }}</span>
    <input name="{{ $name }}" type="{{ $type }}" @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        {{ $attributes->merge(['class' => 'h-row w-full rounded-control border border-border bg-surface-2 px-2 font-mono text-sm text-text focus:border-focus focus:outline-none']) }}>
    @error($name)<span class="mt-1 block text-xs text-down">{{ $message }}</span>@enderror
</label>
