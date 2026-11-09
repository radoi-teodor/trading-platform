<div class="flex items-center gap-2">
    @if ($message !== '')<span class="hidden text-xs text-muted sm:inline" role="status">{{ $message }}</span>@endif
    <button type="button" wire:click="halt" class="h-row rounded-control border border-down px-3 text-sm font-semibold text-down hover:bg-surface-2">Halt</button>
</div>
