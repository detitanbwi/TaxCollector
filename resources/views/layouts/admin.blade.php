<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sistem Penagihan Pajak PBB Digital</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- TailwindCSS / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col hidden md:flex flex-shrink-0">
            <div class="h-16 flex items-center px-6 bg-slate-950 text-white font-bold text-lg tracking-wide">
                Admin Panel PBB
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.pajak.index') }}" class="block px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.pajak.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    Data Pajak
                </a>
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    Manajemen Pengguna
                </a>
            </nav>
            <div class="p-4 bg-slate-950">
                <div class="text-sm text-slate-400 mb-2 truncate">Halo, {{ auth()->user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-red-600 hover:text-white transition-colors text-sm">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 md:hidden">
                <div class="font-bold text-slate-900">Admin PBB</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-500 hover:text-red-600 text-sm font-medium">Logout</button>
                </form>
            </header>
            
            <div class="flex-1 overflow-y-auto p-6 md:p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm font-medium shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
