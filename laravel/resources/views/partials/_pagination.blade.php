@if ($paginator->hasPages())
    <nav class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>
        <div class="flex items-center space-x-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-600 bg-[#1a1d29] border border-[#2a2d3a] rounded-lg cursor-not-allowed">&laquo; Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-300 bg-[#1a1d29] border border-[#2a2d3a] rounded-lg hover:bg-[#2a2d3a] transition-colors">&laquo; Prev</a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-2 text-sm text-gray-500 bg-[#1a1d29] border border-[#2a2d3a] rounded-lg">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-2 text-sm text-white bg-[#3b82f6] border border-[#3b82f6] rounded-lg font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-300 bg-[#1a1d29] border border-[#2a2d3a] rounded-lg hover:bg-[#2a2d3a] transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-300 bg-[#1a1d29] border border-[#2a2d3a] rounded-lg hover:bg-[#2a2d3a] transition-colors">Next &raquo;</a>
            @else
                <span class="px-3 py-2 text-sm text-gray-600 bg-[#1a1d29] border border-[#2a2d3a] rounded-lg cursor-not-allowed">Next &raquo;</span>
            @endif
        </div>
    </nav>
@endif
