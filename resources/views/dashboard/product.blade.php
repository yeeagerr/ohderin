@extends('layouts.dashboard_layout')

@section('title', 'Produk - Dashboard')

@section('content')
<main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola menu dan paket restoran Anda</p>
            </div>
            <button onclick="openModal('addModal')" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/30 transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
            </button>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
    <div id="alertSuccess" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center justify-between transition-all duration-300">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button onclick="document.getElementById('alertSuccess').remove()" class="text-green-500 hover:text-green-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $products->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Produk Aktif</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ App\Models\Product::where('is_active', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Paket</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ App\Models\Product::where('is_package', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Nonaktif</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ App\Models\Product::where('is_active', false)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M15 9l-6 6M9 9l6 6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
        <div class="p-4">
            <form method="GET" class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                <div class="relative w-full lg:w-80">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari produk..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"/>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <select name="category" onchange="this.form.submit()" 
                            class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" onchange="this.form.submit()" 
                            class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <select name="type" onchange="this.form.submit()" 
                            class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition">
                        <option value="">Semua Tipe</option>
                        <option value="regular" {{ request('type') == 'regular' ? 'selected' : '' }}>Reguler</option>
                        <option value="package" {{ request('type') == 'package' ? 'selected' : '' }}>Paket</option>
                    </select>
                    @if(request()->hasAny(['category', 'status', 'type']))
                        <a href="{{ route('products.index') }}" class="px-4 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $index => $product)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-500">{{ $products->firstItem() + $index }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $product->is_package ? 'from-purple-400 to-purple-600' : 'from-orange-400 to-orange-600' }} flex items-center justify-center shrink-0">
                                    @if($product->is_package)
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    @else
                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($product->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">ID: #{{ $product->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 text-xs font-medium">
                                <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <span class="font-semibold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($product->is_package)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    Paket
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                    Reguler
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button onclick="toggleStatus({{ $product->id }})" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition-colors cursor-pointer
                                    {{ $product->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $product->is_active ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 {{ $product->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                </span>
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                            <form id="toggleForm{{ $product->id }}" action="{{ route('products.toggle', $product) }}" method="POST" class="hidden">
                                @csrf
                                @method('PATCH')
                            </form>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ json_encode($product) }})" 
                                        class="w-9 h-9 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors"
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button onclick="openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')" 
                                        class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors"
                                        title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-semibold text-lg">Belum ada produk</p>
                                <p class="text-sm text-gray-400 mt-1 mb-4">Klik tombol di bawah untuk menambahkan produk baru</p>
                                <button onclick="openModal('addModal')" 
                                        class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Produk
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <p class="text-sm text-gray-500">
                    Menampilkan 
                    <span class="font-semibold">{{ $products->firstItem() }}</span> - 
                    <span class="font-semibold">{{ $products->lastItem() }}</span> dari 
                    <span class="font-semibold">{{ $products->total() }}</span> data
                </p>

                <div class="flex items-center gap-1">

                    {{-- Previous --}}
                    @if ($products->onFirstPage())
                        <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                            ‹
                        </span>
                    @else
                        <a href="{{ $products->appends(request()->query())->previousPageUrl() }}"
                        class="px-3 py-2 rounded-lg bg-white border border-gray-200 hover:bg-orange-50 hover:text-orange-600 transition">
                            ‹
                        </a>
                    @endif


                    @php
                        $current = $products->currentPage();
                        $last = $products->lastPage();
                        $start = max($current - 2, 1);
                        $end = min($current + 2, $last);
                    @endphp


                    {{-- First Page --}}
                    @if ($start > 1)
                        <a href="{{ $products->appends(request()->query())->url(1) }}"
                        class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-orange-50">
                            1
                        </a>

                        @if ($start > 2)
                            <span class="px-2 text-gray-400">...</span>
                        @endif
                    @endif


                    {{-- Page Numbers --}}
                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $current)
                            <span class="px-3 py-2 rounded-lg bg-orange-500 text-white font-medium">
                                {{ $i }}
                            </span>
                        @else
                            <a href="{{ $products->appends(request()->query())->url($i) }}"
                            class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-orange-50 hover:text-orange-600 transition">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor


                    {{-- Last Page --}}
                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="px-2 text-gray-400">...</span>
                        @endif

                        <a href="{{ $products->appends(request()->query())->url($last) }}"
                        class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-orange-50">
                            {{ $last }}
                        </a>
                    @endif


                    {{-- Next --}}
                    @if ($products->hasMorePages())
                        <a href="{{ $products->appends(request()->query())->nextPageUrl() }}"
                        class="px-3 py-2 rounded-lg bg-white border border-gray-200 hover:bg-orange-50 hover:text-orange-600 transition">
                            ›
                        </a>
                    @else
                        <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                            ›
                        </span>
                    @endif

                </div>
            </div>
        </div>
        @endif
    </div>
</main>

<!-- Modal Tambah -->
<div id="addModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('addModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white">Tambah Produk Baru</h3>
                    <button onclick="closeModal('addModal')" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <form action="{{ route('products.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                               placeholder="Contoh: Nasi Goreng Spesial">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input type="number" name="price" required min="0" step="500"
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                                       placeholder="0">
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="is_package" value="1" class="peer sr-only">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500"></div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Paket</span>
                                <p class="text-xs text-gray-400">Produk berupa paket/bundle</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1" checked class="peer sr-only">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Aktif</span>
                                <p class="text-xs text-gray-400">Tampilkan di kasir</p>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeModal('addModal')" 
                            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold hover:from-orange-600 hover:to-orange-700 transition shadow-lg shadow-orange-500/30">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white">Edit Produk</h3>
                    <button onclick="closeModal('editModal')" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="editName" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" id="editCategory" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input type="number" name="price" id="editPrice" required min="0" step="500"
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="is_package" id="editIsPackage" value="1" class="peer sr-only">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500"></div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Paket</span>
                                <p class="text-xs text-gray-400">Produk berupa paket/bundle</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="is_active" id="editIsActive" value="1" class="peer sr-only">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Aktif</span>
                                <p class="text-xs text-gray-400">Tampilkan di kasir</p>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeModal('editModal')" 
                            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold hover:from-blue-600 hover:to-blue-700 transition shadow-lg shadow-blue-500/30">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('deleteModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Produk?</h3>
            <p class="text-gray-500 text-sm mb-6">Produk "<span id="deleteName" class="font-semibold text-gray-700"></span>" akan dihapus permanen.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('deleteModal')" 
                            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 rounded-xl bg-red-500 text-white font-semibold hover:bg-red-600 transition">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openEditModal(product) {
        document.getElementById('editForm').action = `/dashboard/products/${product.id}`;
        document.getElementById('editName').value = product.name;
        document.getElementById('editCategory').value = product.category_id;
        document.getElementById('editPrice').value = product.price;
        document.getElementById('editIsPackage').checked = product.is_package;
        document.getElementById('editIsActive').checked = product.is_active;
        openModal('editModal');
    }

    function openDeleteModal(id, name) {
        document.getElementById('deleteForm').action = `/dashboard/products/${id}`;
        document.getElementById('deleteName').textContent = name;
        openModal('deleteModal');
    }

    function toggleStatus(id) {
        if (confirm('Yakin ingin mengubah status produk ini?')) {
            document.getElementById('toggleForm' + id).submit();
        }
    }

    // Search
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(search) ? '' : 'none';
        });
    });

    // Auto close alert
    setTimeout(() => {
        const alert = document.getElementById('alertSuccess');
        if (alert) alert.remove();
    }, 5000);
</script>
@endsection