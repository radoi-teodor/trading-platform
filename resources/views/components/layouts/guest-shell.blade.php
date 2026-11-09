@props(['title' => 'Trading X'])
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} · Trading X</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center p-4">
    <main class="w-full max-w-[360px] rounded-card border border-border bg-surface p-card">
        <div class="mb-1 font-semibold">Trading X</div>
        <h1 class="mb-3 text-2xs uppercase tracking-wider text-muted">{{ $title }}</h1>
        {{ $slot }}
    </main>
</body>
</html>
