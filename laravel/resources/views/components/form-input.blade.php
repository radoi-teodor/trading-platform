@props(['name', 'label', 'type' => 'text', 'value' => null, 'placeholder' => '', 'required' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-300 mb-1.5">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2a2d3a] rounded-lg text-gray-200 placeholder-gray-600 focus:outline-none focus:border-[#3b82f6] focus:ring-1 focus:ring-[#3b82f6] transition-colors"
    >
    @error($name)
        <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
    @enderror
</div>
