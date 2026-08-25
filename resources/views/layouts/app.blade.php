<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sistem Keuangan') — Pesantren</title>
    
    <!-- Font Inter & Lucide Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- Alpine.js Collapse Plugin & Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 selection:bg-emerald-500 selection:text-white" x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay -->
    <div 
        x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false" 
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden"
        x-cloak>
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col justify-between transition-transform duration-200 ease-in-out lg:static lg:translate-x-0 border-r border-slate-800">
            
            <!-- Logo & Brand Header -->
            <div>
                <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800 bg-slate-900/50">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-sm tracking-wide">
                            KP
                        </div>
                        <div class="flex flex-col">
                            <span class="font-semibold text-white text-sm tracking-tight leading-tight group-hover:text-emerald-400 transition-colors">Keuangan</span>
                            <span class="text-[11px] text-slate-400 font-normal">Pesantren System</span>
                        </div>
                    </a>
                    
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white focus:outline-none p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Navigation List -->
                <nav class="px-3 py-4 space-y-1 overflow-y-auto">
                    <div class="px-3 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        Menu Utama
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('dashboard*') ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </a>

                    @if(in_array(auth()->user()->role, ['admin', 'bendahara']))
                        <!-- Data Santri -->
                        <a href="{{ route('santri.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('santri*') ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Data Santri</span>
                        </a>

                        <!-- Transaksi Menu Dropdown -->
                        <div x-data="{ open: {{ request()->routeIs('transaksi*') || request()->routeIs('uang-jajan*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" 
                                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-colors hover:bg-slate-800 hover:text-white {{ request()->routeIs('transaksi*') || request()->routeIs('uang-jajan*') ? 'text-white' : 'text-slate-400' }}">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span>Transaksi</span>
                                </div>
                                <svg class="w-3.5 h-3.5 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" x-collapse class="pl-7 pr-1 py-1 space-y-1">
                                <a href="{{ route('transaksi.index', ['tipe' => 'umum']) }}" 
                                   class="block px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ request()->fullUrlIs('*tipe=umum*') ? 'text-emerald-400 font-semibold bg-slate-800/80' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                                    Kas Umum
                                </a>
                                <a href="{{ route('transaksi.index', ['tipe' => 'uang_jajan']) }}" 
                                   class="block px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ request()->fullUrlIs('*tipe=uang_jajan*') ? 'text-emerald-400 font-semibold bg-slate-800/80' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                                    Uang Jajan Santri
                                </a>
                                <a href="{{ route('transaksi.create') }}" 
                                   class="block px-3 py-1.5 rounded-md text-xs font-medium text-emerald-400 hover:text-emerald-300 hover:bg-slate-800/40 transition-colors">
                                    + Catat Baru
                                </a>
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->role === 'pimpinan')
                        <div class="px-3 pt-3 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            Laporan
                        </div>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Laporan Keuangan</span>
                        </a>
                    @endif
                </nav>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- TOP NAVBAR -->
            <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0">
                <!-- Mobile Trigger -->
                <button @click="sidebarOpen = true" class="lg:hidden p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <!-- Context Header Info -->
                <div class="hidden sm:flex items-center space-x-2 text-xs text-slate-500">
                    <span class="font-semibold text-slate-800">Pesantren Digital</span>
                    <span>/</span>
                    <span class="px-2 py-0.5 rounded bg-slate-100 font-medium text-slate-700">
                        {{ auth()->user()->tingkat ? auth()->user()->tingkat->nama : 'Akses Penuh' }}
                    </span>
                </div>

                <!-- Profile Info & Logout -->
                <div class="flex items-center space-x-4 ml-auto">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        </div>
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-semibold text-slate-800 leading-tight">{{ auth()->user()->nama }}</span>
                            <span class="text-[11px] text-slate-500 capitalize">
                                {{ auth()->user()->role }} 
                                @if(auth()->user()->tingkat)
                                    ({{ auth()->user()->tingkat->nama }})
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="h-4 w-px bg-slate-200"></div>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="flex items-center">
                        @csrf
                        <button type="submit" 
                                title="Keluar dari akun"
                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-colors focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- MAIN CONTENT CONTAINER -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <div class="max-w-6xl mx-auto space-y-5">
                    
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-lg flex justify-between items-center text-xs">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-emerald-700 hover:text-emerald-900"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-lg flex justify-between items-center text-xs">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                            <button @click="show = false" class="text-rose-700 hover:text-rose-900"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                    @endif

                    <!-- Dynamic Page Content -->
                    @yield('content')
                    
                </div>
            </main>
        </div>
    </div>

</body>
</html>