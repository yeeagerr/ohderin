<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Ohderin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Nunito:wght@400;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('properties/logo_1.png') }}">
</head>

<body class="flex h-screen overflow-hidden">

    @include('components.loading-overlay')

    <div id="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar-bg overflow-auto w-56 shrink-0 flex flex-col py-6 px-4 text-white h-full">
        <div class="flex items-center gap-2 px-2 mb-8">
            <img src="{{ asset('properties/logo_2.png') }}" draggable="false" class="mx-auto" alt="LOGO 1">
        </div>

        <div class="mb-6">
            <p class="text-[10px]  uppercase tracking-[0.15em] opacity-60 mb-2 px-2">Platform</p>
            <nav class="flex flex-col gap-0.5">
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('dashboard'))
                <a href="{{ url('/dashboard') }}"
                    class="nav-link {{ request()->is('dashboard') ? 'active-nav' : '' }} flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 9.5L12 3l9 6.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">DASHBOARD</span>
                </a>
                @endif
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('kasir'))
                <a href="{{ route('kasir.registers.index') }}"
                    class="nav-link {{ request()->is('kasir*') ? 'active-nav' : '' }} flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2" />
                        <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">KASIR</span>
                </a>
                @endif
            </nav>
        </div>

        <div class="mb-6">
            <p class="text-[10px]  uppercase tracking-[0.15em] opacity-60 mb-2 px-2">Master Data</p>
            <nav class="flex flex-col gap-0.5">
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('categories'))
                <a href="{{ route('categories.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">KATEGORI</span>
                </a>
                @endif
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('products'))
                <a href="{{ route('products.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">PRODUK</span>
                </a>
                @endif
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('modifiers'))
                <a href="{{ route('modifiers.index') }}" class="nav-link {{ request()->is('dashboard/modifiers*') ? 'active-nav' : '' }} flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">MODIFIER</span>
                </a>
                @endif
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('raw_materials'))
                <a href="{{ route('raw-materials.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="3" y="7" width="18" height="13" rx="2" />
                        <path d="M8 7V5a1 1 0 011-1h6a1 1 0 011 1v2" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">BAHAN MENTAH</span>
                </a>
                @endif
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('recipes'))
                <a href="{{ route('recipes.index') }}"
                    class="nav-link {{ request()->is('recipes*') ? 'active-nav' : '' }} flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">RESEP</span>
                </a>
                @endif
            </nav>
        </div>

        <div class="mb-6">
            <p class="text-[10px]  uppercase tracking-[0.15em] opacity-60 mb-2 px-2">Inventory</p>
            <nav class="flex flex-col gap-0.5">
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('purchases'))
                <a href="{{ route('purchases.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3" />
                        <path
                            d="M19.07 4.93A10 10 0 0012 2v2M4.93 4.93A10 10 0 002 12h2M19.07 19.07A10 10 0 0112 22v-2M4.93 19.07A10 10 0 012 12h2" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">PURCHASE</span>
                </a>
                @endif
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('stock_opnames'))
                <a href="{{ route('stock-opnames.index') }}"
                    class="nav-link {{ request()->is('stock-opnames*') ? 'active-nav' : '' }} flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">STOCK OPNAME</span>
                </a>
                @endif
            </nav>
        </div>

        <div class="mb-6">
            <p class="text-[10px]  uppercase tracking-[0.15em] opacity-60 mb-2 px-2">Laporan</p>
            <nav class="flex flex-col gap-0.5">
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('transactions_report'))
                <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->is('dashboard/reports/transactions*') ? 'active-nav' : '' }} flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9h6m-6 4h6m-10-5h.01M7 16h.01" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">TRANSAKSI</span>
                </a>
                @endif
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('sales_report'))
                <a href="{{ route('sales.index') }}" class="nav-link {{ request()->is('dashboard/reports/sales*') ? 'active-nav' : '' }} flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 3v18a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2zm9 4v10m-4-6v6m8-2v4" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">LAPORAN PENJUALAN</span>
                </a>
                @endif
                {{-- <a href="{{ route('daily-summary.index') }}" class="nav-link {{ request()->is('dashboard/reports/daily-summary*') ? 'active-nav' : '' }} flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2M8 7h8m-8 0H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3M9 11h6m-6 4h6" />
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">RINGKASAN HARIAN</span>
                </a> --}}
            </nav>
        </div>

        <div class="mb-6">
            <p class="text-[10px]  uppercase tracking-[0.15em] opacity-60 mb-2 px-2">Sistem</p>
            <nav class="flex flex-col gap-0.5">
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('settings'))
                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->is('dashboard/settings*') ? 'active-nav' : '' }} flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">PENGATURAN</span>
                </a>
                @endif
                
                @if(config('app.debug') || auth()->user()?->hasPermissionTo('users'))
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('dashboard/users*') || request()->is('dashboard/roles*') ? 'active-nav' : '' }} flex items-center gap-3 px-3 py-2.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="nav-title text-sm tracking-[2px]">PENGGUNA & ROLE</span>
                </a>
                @endif
            </nav>
        </div>

        <div class="mt-auto relative z-10">
            <div class="h-px bg-white/20 mb-4"></div>
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5">
                <div class="w-8 h-8 rounded-full bg-white/25 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </div>
                <div>
                    <p class="nav-title text-sm leading-none">{{ auth()->user() ? strtoupper(auth()->user()->name) : 'ADMIN' }}</p>
                    <p class="text-[10px] opacity-60 mt-0.5">{{ auth()->user() && auth()->user()->role ? auth()->user()->role->name : 'Super Admin' }}</p>
                </div>
            </a>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Topbar -->
        <header
            class="bg-white border-b border-gray-100 px-4 sm:px-6 py-3 flex items-center justify-between shrink-0 gap-3">
            <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-orange-500 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <div class="relative flex-1 max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.35-4.35" />
                </svg>
                <input type="text" placeholder="Cari sesuatu..."
                    class="w-full pl-9 pr-3 py-2 rounded-lg bg-gray-100 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 transition" />
            </div>
            <div class="flex items-center gap-3">
                <button class="relative text-gray-500 hover:text-orange-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 01-3.46 0" />
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-orange-500 rounded-full"></span>
                </button>
                <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center shrink-0">
                    <span class="text-white text-xs ">A</span>
                </div>
            </div>
        </header>

        <!-- Content -->
        @yield('content')
    </div>

    <script src="{{ asset('js/prevent-double-click.js') }}"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('show');
        }
    </script>

    @yield('scripts')
</body>

</html>
