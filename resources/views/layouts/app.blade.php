<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Trading X' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen">
    <div class="flex min-h-screen">
        <nav class="hidden w-[232px] shrink-0 border-r border-border bg-surface lg:block" aria-label="Main">
            <div class="flex h-12 items-center border-b border-border px-3 font-semibold">Trading X</div>
            @foreach (['chart' => 'Chart', 'strategies' => 'Strategies', 'account' => 'Account'] as $route => $label)
                <a href="{{ route($route) }}" @class([
                    'flex h-9 items-center border-l-2 px-3 text-sm',
                    'border-accent text-text' => request()->routeIs($route),
                    'border-transparent text-muted hover:text-text' => ! request()->routeIs($route),
                ])>{{ $label }}</a>
            @endforeach
        </nav>
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex min-h-12 flex-wrap items-center gap-x-3 gap-y-1 border-b border-border bg-surface px-3 py-1">
                <span class="hidden font-semibold sm:inline lg:hidden">Trading X</span>
                {{-- On a phone: the account, its alerts and Halt (docs/brand.md, A6). --}}
                <nav class="flex gap-3 text-sm lg:hidden" aria-label="Main">
                    <a href="{{ route('chart') }}" class="hidden text-muted hover:text-text sm:inline">Chart</a>
                    <a href="{{ route('strategies') }}" class="hidden text-muted hover:text-text sm:inline">Strategies</a>
                    <a href="{{ route('account') }}" class="text-muted hover:text-text">Account</a>
                </nav>
                <livewire:mode-badge />
                <div class="ml-auto flex items-center gap-3">
                    <livewire:halt-button />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="whitespace-nowrap text-sm text-muted hover:text-text">Log out</button>
                    </form>
                </div>
            </header>
            <main class="min-w-0 flex-1 p-3">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
