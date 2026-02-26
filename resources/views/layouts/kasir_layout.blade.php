<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/kasir.css') }}">
    @yield('styles')
</head>
<body class="bg-gray-50">
    @include('components.loading-overlay')

    <!-- Unsupported Device Popup -->
    <div id="unsupportedPopup" class="fixed inset-0 bg-gradient-to-br from-indigo-600 to-purple-700 z-50 hidden items-center justify-center p-6">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center">
            <!-- Icon -->
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Ukuran Perangkat Tidak Didukung</h2>
            
            <!-- Description -->
            <p class="text-gray-600 mb-6">
                Maaf, aplikasi POS ini belum mendukung perangkat dengan ukuran layar Anda. Silakan gunakan tablet atau perangkat dengan layar lebih besar.
            </p>
            
            <!-- Device Requirements -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <p class="text-sm text-gray-500 mb-2">Ukuran minimum yang didukung:</p>
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.5 0h-14A2.5 2.5 0 002 2.5v19A2.5 2.5 0 004.5 24h14a2.5 2.5 0 002.5-2.5v-19A2.5 2.5 0 0018.5 0zm-7 23c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm7.5-4H4V3h15v16z"/>
                    </svg>
                    <span class="text-lg font-semibold text-indigo-600">Tablet (768px+)</span>
                </div>
            </div>
            
            <!-- Rotate Suggestion -->
            <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7.11 8.53L5.7 7.11C4.8 8.27 4.24 9.61 4.07 11h2.02c.14-.87.49-1.72 1.02-2.47zM6.09 13H4.07c.17 1.39.72 2.73 1.62 3.89l1.41-1.42c-.52-.75-.87-1.59-1.01-2.47zm1.01 5.32c1.16.9 2.51 1.44 3.9 1.61V17.9c-.87-.15-1.71-.49-2.46-1.03L7.1 18.32zM13 4.07V1L8.45 5.55 13 10V6.09c2.84.48 5 2.94 5 5.91s-2.16 5.43-5 5.91v2.02c3.95-.49 7-3.85 7-7.93s-3.05-7.44-7-7.93z"/>
                </svg>
                <span>Coba putar perangkat Anda ke mode landscape</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-20 bg-indigo-600 text-white flex flex-col items-center py-6 space-y-4 flex-shrink-0">
            <div class="text-xl font-bold mb-4">
                <svg class="w-10 h-10" fill="white" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12z"/>
                </svg>
            </div>
            
            <a href="{{ route('kasir.pos') }}" class="flex flex-col items-center p-3 bg-indigo-500 rounded-xl w-14">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                </svg>
                <span class="text-[10px]">POS</span>
            </a>
            
            <a href="{{ route('kasir.order') }}" class="flex flex-col items-center p-3 hover:bg-indigo-500 rounded-xl w-14">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
                <span class="text-[10px]">Orders</span>
            </a>
            
            <a href="#" class="flex flex-col items-center p-3 hover:bg-indigo-500 rounded-xl w-14">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <span class="text-[10px]">Products</span>
            </a>
            
            <a href="#" class="flex flex-col items-center p-3 hover:bg-indigo-500 rounded-xl w-14">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9h-4v4h-2v-4H9V9h4V5h2v4h4v2z"/>
                </svg>
                <span class="text-[10px]">Barcode</span>
            </a>
            
            <a href="#" class="flex flex-col items-center p-3 hover:bg-indigo-500 rounded-xl w-14">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                </svg>
                <span class="text-[10px]">Customers</span>
            </a>
            
            <a href="#" class="flex flex-col items-center p-3 hover:bg-indigo-500 rounded-xl w-14">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                </svg>
                <span class="text-[10px]">Stock</span>
            </a>
        </div>

        @yield('content')
    </div>


        <script>
        function checkScreenSize() {
            const popup = document.getElementById('unsupportedPopup');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth < 768) {
                popup.classList.remove('hidden');
                popup.classList.add('flex');
                mainContent.classList.add('hidden');
            } else {
                popup.classList.add('hidden');
                popup.classList.remove('flex');
                mainContent.classList.remove('hidden');
            }
        }
        
        window.addEventListener('load', checkScreenSize);
        window.addEventListener('resize', checkScreenSize);
    </script>
    @yield('scripts')
</body>
</html>