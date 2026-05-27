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
<body class="bg-slate-50 text-slate-800 antialiased font-sans" x-data="{ mobileSidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar (Desktop) -->
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
                <a href="{{ route('admin.settings.edit') }}" class="block px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    Pengaturan WA
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
            <!-- Mobile Header with Hamburger Menu -->
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 md:hidden">
                <div class="flex items-center">
                    <button @click="mobileSidebarOpen = true" class="text-slate-500 hover:text-slate-700 focus:outline-none p-1.5 -ml-2 mr-2 rounded-lg hover:bg-slate-100 transition-colors" title="Buka Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="font-bold text-slate-900 text-sm">Admin PBB</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="text-slate-500 hover:text-red-600 text-xs font-semibold px-2.5 py-1.5 rounded-lg hover:bg-red-50 transition-colors">Logout</button>
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

    <!-- Mobile Sidebar Drawer -->
    <div x-show="mobileSidebarOpen" class="fixed inset-0 z-50 flex md:hidden" style="display: none;">
        <!-- Backdrop overlay -->
        <div x-show="mobileSidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             @click="mobileSidebarOpen = false"></div>

        <!-- Drawer Menu -->
        <div x-show="mobileSidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-900 text-slate-300 shadow-2xl h-full">
            
            <!-- Close Button Inside Drawer -->
            <div class="absolute top-0 right-0 -mr-12 pt-4">
                <button type="button" @click="mobileSidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white text-white bg-slate-900/40 hover:bg-slate-900/60 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Drawer Sidebar Header -->
            <div class="h-16 flex items-center px-6 bg-slate-950 text-white font-bold text-lg tracking-wide border-b border-slate-800">
                Admin Panel PBB
            </div>

            <!-- Drawer Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" @click="mobileSidebarOpen = false" class="block px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.pajak.index') }}" @click="mobileSidebarOpen = false" class="block px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.pajak.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    Data Pajak
                </a>
                <a href="{{ route('admin.users.index') }}" @click="mobileSidebarOpen = false" class="block px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    Manajemen Pengguna
                </a>
                <a href="{{ route('admin.settings.edit') }}" @click="mobileSidebarOpen = false" class="block px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    Pengaturan WA
                </a>
            </nav>

            <!-- Drawer Footer / Logout -->
            <div class="p-4 bg-slate-950">
                <div class="text-xs text-slate-400 mb-2 truncate">Halo, {{ auth()->user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-red-650 hover:text-white transition-colors text-sm">
                        Logout
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Dummy block to force focus trap / alignment -->
        <div class="flex-shrink-0 w-14" aria-hidden="true"></div>
    </div>

    <!-- Alpine.js logic globally for layout transitions and toggles -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
