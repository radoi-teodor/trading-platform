<aside class="sidebar fixed top-0 left-0 z-40 w-64 h-screen bg-[#0a0d14] border-r border-[#2a2d3a] overflow-y-auto">
    <div class="p-5 border-b border-[#2a2d3a]">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <span class="text-xl font-bold text-white">Trading <span class="text-[#3b82f6]">AI</span></span>
        </a>
    </div>

    <nav class="p-4 space-y-1">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->is('dashboard*') ? 'bg-[#1a1d29] text-white border-l-2 border-[#3b82f6]' : 'text-gray-400 hover:text-white hover:bg-[#1a1d29]' }}">
            <x-icons.home class="w-5 h-5" />
            <span>Dashboard</span>
        </a>

        {{-- Strategies --}}
        <a href="{{ route('strategies.index') }}"
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->is('strategies*') ? 'bg-[#1a1d29] text-white border-l-2 border-[#3b82f6]' : 'text-gray-400 hover:text-white hover:bg-[#1a1d29]' }}">
            <x-icons.sparkles class="w-5 h-5" />
            <span>Strategies</span>
        </a>

        {{-- Portfolio --}}
        <a href="{{ route('portfolio.index') }}"
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->is('portfolio*') ? 'bg-[#1a1d29] text-white border-l-2 border-[#3b82f6]' : 'text-gray-400 hover:text-white hover:bg-[#1a1d29]' }}">
            <x-icons.chart-bar class="w-5 h-5" />
            <span>Portfolio</span>
        </a>

        {{-- Scrapers --}}
        <a href="{{ route('scrapers.index') }}"
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->is('scrapers*') ? 'bg-[#1a1d29] text-white border-l-2 border-[#3b82f6]' : 'text-gray-400 hover:text-white hover:bg-[#1a1d29]' }}">
            <x-icons.globe class="w-5 h-5" />
            <span>Scrapers</span>
        </a>

        {{-- News --}}
        <a href="{{ route('news.index') }}"
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->is('news*') ? 'bg-[#1a1d29] text-white border-l-2 border-[#3b82f6]' : 'text-gray-400 hover:text-white hover:bg-[#1a1d29]' }}">
            <x-icons.newspaper class="w-5 h-5" />
            <span>News</span>
        </a>

        {{-- Watchlist --}}
        <a href="{{ route('watchlist.index') }}"
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->is('watchlist*') ? 'bg-[#1a1d29] text-white border-l-2 border-[#3b82f6]' : 'text-gray-400 hover:text-white hover:bg-[#1a1d29]' }}">
            <x-icons.eye class="w-5 h-5" />
            <span>Watchlist</span>
        </a>

        {{-- Logs --}}
        <a href="{{ route('logs.index') }}"
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->is('logs*') ? 'bg-[#1a1d29] text-white border-l-2 border-[#3b82f6]' : 'text-gray-400 hover:text-white hover:bg-[#1a1d29]' }}">
            <x-icons.document-text class="w-5 h-5" />
            <span>Logs</span>
        </a>

        {{-- Settings (collapsible via CSS checkbox) --}}
        <div class="pt-2">
            <input type="checkbox" id="settings-toggle" class="hidden peer" {{ request()->is('settings*') ? 'checked' : '' }}>
            <label for="settings-toggle"
                   class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium cursor-pointer transition-colors
                          {{ request()->is('settings*') ? 'bg-[#1a1d29] text-white border-l-2 border-[#3b82f6]' : 'text-gray-400 hover:text-white hover:bg-[#1a1d29]' }}">
                <span class="flex items-center space-x-3">
                    <x-icons.cog class="w-5 h-5" />
                    <span>Settings</span>
                </span>
                <x-icons.chevron-down class="w-4 h-4 transition-transform peer-checked:rotate-180" />
            </label>
            <div class="hidden peer-checked:block ml-8 mt-1 space-y-1">
                <a href="{{ route('settings.strategy') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors {{ request()->is('settings/strategy*') ? 'text-white bg-[#1a1d29]' : 'text-gray-500 hover:text-white' }}">
                    Strategy
                </a>
                <a href="{{ route('settings.broker') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors {{ request()->is('settings/broker*') ? 'text-white bg-[#1a1d29]' : 'text-gray-500 hover:text-white' }}">
                    Broker
                </a>
                <a href="{{ route('settings.notifications') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors {{ request()->is('settings/notifications*') ? 'text-white bg-[#1a1d29]' : 'text-gray-500 hover:text-white' }}">
                    Notifications
                </a>
                <a href="{{ route('settings.profile') }}"
                   class="block px-3 py-2 rounded-lg text-sm transition-colors {{ request()->is('settings/profile*') ? 'text-white bg-[#1a1d29]' : 'text-gray-500 hover:text-white' }}">
                    Profile
                </a>
            </div>
        </div>
    </nav>
</aside>
