<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Portal Penagih - Sistem Penagihan Pajak PBB Digital</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- TailwindCSS / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; } /* slate-50 */
    </style>
</head>
<body class="text-slate-800 antialiased flex flex-col min-h-screen">
    
    <!-- Mobile Top Bar -->
    <header class="bg-indigo-600 text-white shadow-md sticky top-0 z-50">
        <div class="px-4 h-16 flex items-center justify-between max-w-lg mx-auto w-full">
            <div class="font-bold text-lg tracking-wide">PajakApp</div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-indigo-100 hidden sm:inline-block">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="p-2 -mr-2 text-indigo-100 hover:text-white transition-colors" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content Area (Mobile focused) -->
    <main class="flex-1 w-full max-w-lg mx-auto p-4 sm:p-6 flex flex-col pb-20">
        @if(session('error'))
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium shadow-sm flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Optional Bottom Nav or Footer for Mobile -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400 mt-auto">
        &copy; {{ date('Y') }} Sistem Penagihan Pajak PBB Digital
    </footer>

</body>
</html>
