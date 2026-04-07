<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Trading AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; }

        /* Mobile sidebar toggle via CSS checkbox trick */
        #sidebar-toggle { display: none; }
        .sidebar-overlay { display: none; }

        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            #sidebar-toggle:checked ~ .sidebar { transform: translateX(0); }
            #sidebar-toggle:checked ~ .sidebar-overlay { display: block; }
        }

        @media (min-width: 1024px) {
            .sidebar { transform: translateX(0); }
            .main-content { margin-left: 16rem; }
        }
    </style>
</head>
<body class="bg-[#0f1117] text-gray-200 min-h-screen">
    <input type="checkbox" id="sidebar-toggle" class="hidden">

    @include('partials._sidebar')

    <label for="sidebar-toggle" class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-30 cursor-pointer"></label>

    <div class="main-content min-h-screen">
        @include('partials._topbar')

        <main class="p-4 lg:p-6">
            @include('partials._flash')
            @yield('content')
        </main>
    </div>
</body>
</html>
