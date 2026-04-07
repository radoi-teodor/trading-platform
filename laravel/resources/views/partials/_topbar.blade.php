<header class="bg-[#0a0d14] border-b border-[#2a2d3a] px-4 lg:px-6 py-4 flex items-center justify-between">
    <div class="flex items-center space-x-4">
        {{-- Mobile menu toggle --}}
        <label for="sidebar-toggle" class="lg:hidden cursor-pointer text-gray-400 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </label>

        <h1 class="text-lg font-semibold text-white">@yield('title', 'Dashboard')</h1>
    </div>

    <div class="flex items-center space-x-4">
        @auth
            <span class="text-sm text-gray-400">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-1 text-sm text-gray-400 hover:text-white transition-colors">
                    <x-icons.logout class="w-4 h-4" />
                    <span>Logout</span>
                </button>
            </form>
        @endauth
    </div>
</header>
