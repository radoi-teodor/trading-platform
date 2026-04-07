@if(session('success'))
    <div class="mb-4 bg-[#1a1d29] border-l-4 border-[#22c55e] rounded-lg p-4">
        <p class="text-sm text-[#22c55e]">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-[#1a1d29] border-l-4 border-[#ef4444] rounded-lg p-4">
        <p class="text-sm text-[#ef4444]">{{ session('error') }}</p>
    </div>
@endif

@if(session('warning'))
    <div class="mb-4 bg-[#1a1d29] border-l-4 border-[#f59e0b] rounded-lg p-4">
        <p class="text-sm text-[#f59e0b]">{{ session('warning') }}</p>
    </div>
@endif
