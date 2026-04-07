<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - Trading AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; }
    </style>
</head>
<body class="bg-[#0f1117] text-gray-200 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white">Trading <span class="text-[#3b82f6]">AI</span></h1>
            <p class="text-gray-500 mt-2">AI-Powered Trading Platform</p>
        </div>

        @include('partials._flash')

        <div class="bg-[#1a1d29] border border-[#2a2d3a] rounded-lg p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>
