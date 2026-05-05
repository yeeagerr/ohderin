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
                    <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}" 
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
                    @if(request()->hasAny(['category', 'status', 'type', 'search']))
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
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Resep</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Modifier</th>
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
                            @if(!$product->is_package)
                                @if($product->recipe && $product->recipe->quantity !== null)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Qty: {{ number_format($product->recipe->quantity, 2, '.', '') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Belum
                                    </span>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($product->modifiers->isNotEmpty())
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold">
                                        {{ $product->modifiers->count() }} modifier
                                    </span>
                                    <span class="text-xs text-gray-400 max-w-36 truncate" title="{{ $product->modifiers->pluck('name')->join(', ') }}">
                                        {{ $product->modifiers->pluck('name')->take(2)->join(', ') }}{{ $product->modifiers->count() > 2 ? ', ...' : '' }}
                                    </span>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">-</span>
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
                        <td colspan="9" class="py-16 text-center">
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

<!-- Store product data for dropdowns -->
<script>
    const allProductsData = {!! json_encode($allProducts) !!};
    const rawMaterialsData = {!! json_encode($rawMaterials ?? []) !!};
</script>

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
            <div class="h-[85vh] overflow-auto">
                <form action="{{ route('products.store') }}" method="POST" class="p-6" id="addProductForm" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-5">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-orange-400 hover:bg-orange-50/50 transition cursor-pointer" id="addImageDropZone">
                                <input type="file" name="image" id="addImageInput" class="hidden" accept="image/*" onchange="previewImage(event, 'addImagePreview')">
                                <div id="addImagePreview" class="hidden">
                                    <img src="" alt="Preview" class="max-w-full h-32 mx-auto mb-2 rounded">
                                    <button type="button" onclick="clearImage('add')" class="text-sm text-red-500 hover:text-red-600">Ganti Gambar</button>
                                </div>
                                <div class="flex flex-col items-center gap-2" id="addImagePlaceholder">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700">Drag & Drop atau <span class="text-orange-500">klik untuk upload</span></p>
                                    <p class="text-xs text-gray-500">(JPG, PNG, WebP - Max 5MB)</p>
                                </div>
                            </div>
                        </div>
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
                        <div class="border-t border-gray-200 pt-5">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Modifier Produk</label>
                                    <p class="text-xs text-gray-400">Pilih modifier yang tersedia untuk produk ini</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50">
                                @forelse($modifiers as $modifier)
                                    <label class="flex items-start gap-3 rounded-lg bg-white border border-gray-200 p-3 cursor-pointer hover:border-orange-300 transition">
                                        <input type="checkbox" name="modifier_ids[]" value="{{ $modifier->id }}" class="mt-1 h-4 w-4 rounded border-gray-300 text-orange-500 focus:ring-orange-400">
                                        <span class="min-w-0">
                                            <span class="block text-sm font-semibold text-gray-700 truncate">{{ $modifier->name }}</span>
                                            <span class="block text-xs text-gray-400">
                                                {{ $modifier->category ?: 'Umum' }}
                                                @if((float) $modifier->price_adjustment !== 0.0)
                                                    - {{ (float) $modifier->price_adjustment > 0 ? '+' : '' }}Rp {{ number_format($modifier->price_adjustment, 0, ',', '.') }}
                                                @endif
                                            </span>
                                        </span>
                                    </label>
                                @empty
                                    <div class="col-span-full text-sm text-gray-400 text-center py-4">Belum ada modifier aktif</div>
                                @endforelse
                            </div>
                        </div>
                        <!-- Recipe Section (Hidden for packages) -->
                        <div id="addRecipeSection" class="hidden border-t border-gray-200 pt-5">
                            <h4 class="text-sm font-bold text-gray-700 mb-4">📋 Resep Produk</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity Resep</label>
                                    <input type="number" name="recipe_quantity" min="0.0001" step="0.0001" 
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                                        placeholder="Masukkan quantity">
                                    <p class="text-xs text-gray-500 mt-1">⚠️ Jika kosong, produk tidak akan muncul di kasir</p>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-semibold text-gray-700">Bahan-Bahan</label>
                                        <button type="button" onclick="addRecipeItem('add')" class="text-sm text-orange-600 hover:text-orange-700 font-medium">+ Tambah</button>
                                    </div>
                                    <div id="addRecipeItemsContainer" class="space-y-2 max-h-48 overflow-y-auto"></div>
                                </div>
                            </div>
                        </div>
                        <!-- List Produk Paket -->
                        <div id="packageProductsList" class="mt-4 hidden">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Daftar Produk Paket</label>
                            <div class="space-y-2 max-h-56 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50" id="packageItemsContainer">
                            </div>
                            <button type="button" id="addPackageProductBtn" 
                                    class="mt-3 px-4 py-2 text-sm text-white bg-orange-500 hover:bg-orange-600 rounded-xl shadow">
                                Tambah Produk
                            </button>
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
            <div class="h-[85vh] overflow-auto">
                <form id="editForm" method="POST" class="p-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-5">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
                            <div id="editImagePreviewContainer" class="mb-3"></div>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-blue-50/50 transition cursor-pointer" id="editImageDropZone">
                                <input type="file" name="image" id="editImageInput" class="hidden" accept="image/*" onchange="previewImage(event, 'editImagePreview')">
                                <div id="editImagePreview" class="hidden">
                                    <img src="" alt="Preview" class="max-w-full h-32 mx-auto mb-2 rounded">
                                    <button type="button" onclick="clearImage('edit')" class="text-sm text-red-500 hover:text-red-600">Ganti Gambar</button>
                                </div>
                                <div class="flex flex-col items-center gap-2" id="editImagePlaceholder">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700">Drag & Drop atau <span class="text-blue-500">klik untuk upload</span></p>
                                    <p class="text-xs text-gray-500">(JPG, PNG, WebP - Max 5MB)</p>
                                </div>
                            </div>
                        </div>
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
                        <div class="border-t border-gray-200 pt-5">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Modifier Produk</label>
                                    <p class="text-xs text-gray-400">Pilih modifier yang tersedia untuk produk ini</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50">
                                @forelse($modifiers as $modifier)
                                    <label class="flex items-start gap-3 rounded-lg bg-white border border-gray-200 p-3 cursor-pointer hover:border-blue-300 transition">
                                        <input type="checkbox" name="modifier_ids[]" value="{{ $modifier->id }}" class="editModifierCheckbox mt-1 h-4 w-4 rounded border-gray-300 text-blue-500 focus:ring-blue-400">
                                        <span class="min-w-0">
                                            <span class="block text-sm font-semibold text-gray-700 truncate">{{ $modifier->name }}</span>
                                            <span class="block text-xs text-gray-400">
                                                {{ $modifier->category ?: 'Umum' }}
                                                @if((float) $modifier->price_adjustment !== 0.0)
                                                    - {{ (float) $modifier->price_adjustment > 0 ? '+' : '' }}Rp {{ number_format($modifier->price_adjustment, 0, ',', '.') }}
                                                @endif
                                            </span>
                                        </span>
                                    </label>
                                @empty
                                    <div class="col-span-full text-sm text-gray-400 text-center py-4">Belum ada modifier aktif</div>
                                @endforelse
                            </div>
                        </div>
                        <!-- Recipe Section (Hidden for packages) -->
                        <div id="editRecipeSection" class="hidden border-t border-gray-200 pt-5">
                            <h4 class="text-sm font-bold text-gray-700 mb-4">📋 Resep Produk</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity Resep</label>
                                    <input type="number" name="recipe_quantity" id="editRecipeQuantity" min="0.0001" step="0.0001" 
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                        placeholder="Masukkan quantity">
                                    <p class="text-xs text-gray-500 mt-1">⚠️ Jika kosong, produk tidak akan muncul di kasir</p>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-semibold text-gray-700">Bahan-Bahan</label>
                                        <button type="button" onclick="addRecipeItem('edit')" class="text-sm text-blue-600 hover:text-blue-700 font-medium">+ Tambah</button>
                                    </div>
                                    <div id="editRecipeItemsContainer" class="space-y-2 max-h-48 overflow-y-auto"></div>
                                </div>
                            </div>
                        </div>
                        <!-- List Produk Paket Edit -->
                        <div id="editPackageProductsList" class="mt-4 hidden">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Daftar Produk Paket</label>
                            <div class="space-y-2 max-h-56 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50" id="editPackageItemsContainer">
                            </div>
                            <button type="button" id="editAddPackageProductBtn" 
                                    class="mt-3 px-4 py-2 text-sm text-white bg-blue-500 hover:bg-blue-600 rounded-xl shadow">
                                Tambah Produk
                            </button>
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
        
        // Initialize recipe section visibility when opening Add Modal
        if (id === 'addModal') {
            const addIsPackageCheckbox = document.querySelector('#addModal input[name="is_package"]');
            const recipeSection = document.getElementById('addRecipeSection');
            const packageProductsList = document.getElementById('packageProductsList');
            
            // Show recipe by default, hide package list
            if (!addIsPackageCheckbox.checked) {
                recipeSection.classList.remove('hidden');
                packageProductsList.classList.add('hidden');
            }
        }
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Handle package/recipe visibility in Add Modal
    const addIsPackageCheckbox = document.querySelector('#addModal input[name="is_package"]');
    const packageProductsList = document.getElementById('packageProductsList');
    
    addIsPackageCheckbox.addEventListener('change', (e) => {
        const container = document.getElementById('packageItemsContainer');
        const recipeSection = document.getElementById('addRecipeSection');
        if (e.target.checked) {
            packageProductsList.classList.remove('hidden');
            recipeSection.classList.add('hidden');
            
            // Initialize with one empty row if container is empty
            if (container.children.length === 0) {
                const newRow = document.createElement('div');
                newRow.innerHTML = createProductRow(null, null, 'orange');
                container.appendChild(newRow);
                attachDeleteEvent(newRow.querySelector('.deleteProductBtn'));
                attachProductSearchEvent(newRow);
            }
        } else {
            packageProductsList.classList.add('hidden');
            recipeSection.classList.remove('hidden');
            // Optionally clear items when unchecking
            container.innerHTML = '';
        }
    });

    // Handle package/recipe visibility in Edit Modal
    const editIsPackageCheckbox = document.querySelector('#editModal input[name="is_package"]');
    const editPackageProductsList = document.getElementById('editPackageProductsList');
    
    editIsPackageCheckbox.addEventListener('change', (e) => {
        const editRecipeSection = document.getElementById('editRecipeSection');
        if (e.target.checked) {
            editPackageProductsList.classList.remove('hidden');
            editRecipeSection.classList.add('hidden');
        } else {
            editPackageProductsList.classList.add('hidden');
            editRecipeSection.classList.remove('hidden');
        }
    });

    // Create searchable product row
    let productRowCounter = 0;
    
    function createProductRow(selectedProductId = null, selectedProductName = null, color = 'orange') {
        const ringColor = color === 'blue' ? 'focus:ring-blue-400' : 'focus:ring-orange-400';
        const dataListId = 'products-list-' + Math.random().toString(36).substr(2, 9);
        const rowIndex = ++productRowCounter;
        
        let optionsHtml = '';
        allProductsData.forEach(prod => {
            optionsHtml += `<option value="${prod.name}" data-id="${prod.id}">`;
        });
        
        return `
            <div class="flex items-center gap-2 bg-white rounded-lg p-2 shadow-sm product-list-package">
                <div class="flex-1 relative">
                    <input type="text" name="package_products[${rowIndex}][product_name]" placeholder="Cari produk..." 
                        value="${selectedProductName || ''}"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 ${ringColor} text-sm product-search"
                        autocomplete="off" list="${dataListId}" required>
                    <input type="hidden" name="package_products[${rowIndex}][product_id]" class="product-id-field" value="${selectedProductId || ''}">
                    <datalist id="${dataListId}">
                        ${optionsHtml}
                    </datalist>
                </div>
                <input type="number" name="package_products[${rowIndex}][quantity]" min="1" value="1"
                    class="w-16 px-2 py-2 rounded border border-gray-300 text-sm focus:outline-none focus:ring-1 ${ringColor}" required>
                <button type="button" class="deleteProductBtn px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Hapus</button>
            </div>
        `;
    }

    // Handle product search and auto-fill ID
    function attachProductSearchEvent(row) {
        const searchInput = row.querySelector('.product-search');
        const idField = row.querySelector('.product-id-field');
        
        const findAndSetProduct = () => {
            const value = searchInput.value.trim();
            const product = allProductsData.find(p => p.name === value);
            if (product) {
                idField.value = product.id;
                searchInput.classList.remove('border-red-500');
            } else if (value) {
                idField.value = '';
                searchInput.classList.add('border-red-500');
            } else {
                idField.value = '';
                searchInput.classList.remove('border-red-500');
            }
        };
        
        // Handle input, change, and blur events
        searchInput.addEventListener('input', findAndSetProduct);
        searchInput.addEventListener('change', findAndSetProduct);
        searchInput.addEventListener('blur', findAndSetProduct);
        
        // Handle mousedown on datalist option (for better browser support)
        searchInput.addEventListener('click', (e) => {
            setTimeout(findAndSetProduct, 100);
        });
    }

    // Add new package product row in Add Modal
    document.getElementById('addPackageProductBtn').addEventListener('click', (e) => {
        e.preventDefault();
        const container = document.getElementById('packageItemsContainer');
        const newRow = document.createElement('div');
        newRow.innerHTML = createProductRow(null, null, 'orange');
        container.appendChild(newRow);
        attachDeleteEvent(newRow.querySelector('.deleteProductBtn'));
        attachProductSearchEvent(newRow);
    });

    // Add new package product row in Edit Modal
    document.getElementById('editAddPackageProductBtn').addEventListener('click', (e) => {
        e.preventDefault();
        const container = document.getElementById('editPackageItemsContainer');
        const newRow = document.createElement('div');
        newRow.innerHTML = createProductRow(null, null, 'blue');
        container.appendChild(newRow);
        attachDeleteEvent(newRow.querySelector('.deleteProductBtn'));
        attachProductSearchEvent(newRow);
    });

    // Helper function to attach delete event
    function attachDeleteEvent(button) {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const row = button.closest('.product-list-package');
            if (row) {
                row.remove();
            }
        });
    }

    // Attach delete events to initial buttons
    document.querySelectorAll('.deleteProductBtn').forEach(btn => {
        attachDeleteEvent(btn);
    });

    function openEditModal(product) {
        // Fetch full product data including recipe
        fetch(`/dashboard/products/${product.id}/data`)
            .then(res => res.json())
            .then(fullProduct => {
                document.getElementById('editForm').action = `/dashboard/products/${fullProduct.id}`;
                document.getElementById('editName').value = fullProduct.name;
                document.getElementById('editCategory').value = fullProduct.category_id;
                document.getElementById('editPrice').value = fullProduct.price;
                document.getElementById('editIsPackage').checked = fullProduct.is_package;
                document.getElementById('editIsActive').checked = fullProduct.is_active;
                const modifierIds = (fullProduct.modifiers || []).map(modifier => parseInt(modifier.id, 10));
                document.querySelectorAll('.editModifierCheckbox').forEach(checkbox => {
                    checkbox.checked = modifierIds.includes(parseInt(checkbox.value, 10));
                });
                
                // Handle image display
                if (fullProduct.image) {
                    const container = document.getElementById('editImagePreviewContainer');
                    container.innerHTML = `
                        <div class="mb-2 p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                            <img src="${fullProduct.image}" alt="Current" class="h-16 rounded">
                            <button type="button" onclick="clearImage('edit')" class="text-xs text-red-600 hover:text-red-700">Hapus</button>
                        </div>
                    `;
                }
                
                // Clear existing package items
                const editContainer = document.getElementById('editPackageItemsContainer');
                editContainer.innerHTML = '';
                
                // Reset product row counter when opening modal
                productRowCounter = 0;
                
                // Show/hide package products list and recipe section based on is_package
                if (fullProduct.is_package) {
                    document.getElementById('editPackageProductsList').classList.remove('hidden');
                    document.getElementById('editRecipeSection').classList.add('hidden');
                    
                    // Load existing package items if available
                    if (fullProduct.package_items && fullProduct.package_items.length > 0) {
                        fullProduct.package_items.forEach(item => {
                            const foundProduct = allProductsData.find(p => p.id === item.product_id);
                            const newRow = document.createElement('div');
                            newRow.innerHTML = createProductRow(item.product_id, foundProduct ? foundProduct.name : '', 'blue');
                            const qtyInput = newRow.querySelector('input[name*="[quantity]"]');
                            if (qtyInput) {
                                qtyInput.value = item.qty;
                            }
                            editContainer.appendChild(newRow);
                            attachDeleteEvent(newRow.querySelector('.deleteProductBtn'));
                            attachProductSearchEvent(newRow);
                        });
                    } else {
                        // Add one empty row
                        const newRow = document.createElement('div');
                        newRow.innerHTML = createProductRow(null, null, 'blue');
                        editContainer.appendChild(newRow);
                        attachDeleteEvent(newRow.querySelector('.deleteProductBtn'));
                        attachProductSearchEvent(newRow);
                    }
                } else {
                    document.getElementById('editPackageProductsList').classList.add('hidden');
                    document.getElementById('editRecipeSection').classList.remove('hidden');
                    
                    // Load recipe data
                    if (fullProduct.recipe) {
                        document.getElementById('editRecipeQuantity').value = fullProduct.recipe.quantity || '';
                        
                        // Load recipe items
                        const recipeContainer = document.getElementById('editRecipeItemsContainer');
                        recipeContainer.innerHTML = '';
                        if (fullProduct.recipe.items && fullProduct.recipe.items.length > 0) {
                            fullProduct.recipe.items.forEach((item, index) => {
                                recipeItemCounter.edit = index;
                                
                                // Build options from rawMaterialsData
                                let optionsHtml = '<option value="">Pilih Bahan</option>';
                                rawMaterialsData.forEach(material => {
                                    const selected = material.id == item.raw_material_id ? 'selected' : '';
                                    optionsHtml += `<option value="${material.id}" ${selected}>${material.name} (${material.unit})</option>`;
                                });
                                
                                const itemDiv = document.createElement('div');
                                itemDiv.className = 'flex items-center gap-2 bg-gray-50 p-3 rounded-lg';
                                itemDiv.innerHTML = `
                                    <select name="recipe_items[${index}][raw_material_id]" required
                                            class="flex-1 px-3 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        ${optionsHtml}
                                    </select>
                                    <input type="number" name="recipe_items[${index}][qty]" min="0.0001" step="0.0001" required
                                           value="${item.qty}"
                                           class="w-20 px-3 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                                           placeholder="Qty">
                                    <button type="button" onclick="this.parentElement.remove()" 
                                            class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm rounded transition">
                                        Hapus
                                    </button>
                                `;
                                recipeContainer.appendChild(itemDiv);
                            });
                        }
                    }
                }
                
                openModal('editModal');
            })
            .catch(error => {
                console.error('Error loading product:', error);
                alert('Error loading product data');
            });
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

    // Image handling
    function previewImage(event, previewId) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = document.getElementById(previewId);
                const img = preview.querySelector('img');
                img.src = e.target.result;
                preview.classList.remove('hidden');
                const placeholder = document.getElementById(previewId.includes('edit') ? 'editImagePlaceholder' : 'addImagePlaceholder');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function clearImage(type) {
        const input = document.getElementById(type + 'ImageInput');
        const preview = document.getElementById(type + 'ImagePreview');
        const placeholder = document.getElementById(type + 'ImagePlaceholder');
        input.value = '';
        preview.classList.add('hidden');
        if (placeholder) placeholder.classList.remove('hidden');
    }

    function handleImageDrop(event, inputId) {
        event.preventDefault();
        event.stopPropagation();
        const files = event.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById(inputId).files = files;
            const changeEvent = new Event('change', { bubbles: true });
            document.getElementById(inputId).dispatchEvent(changeEvent);
        }
    }

    // Recipe item management
    let recipeItemCounter = { add: 0, edit: 0 };

    function addRecipeItem(type) {
        const containerId = type + 'RecipeItemsContainer';
        const container = document.getElementById(containerId);
        const index = ++recipeItemCounter[type];
        
        // Build options from rawMaterialsData
        let optionsHtml = '<option value="">Pilih Bahan</option>';
        rawMaterialsData.forEach(material => {
            optionsHtml += `<option value="${material.id}">${material.name} (${material.unit})</option>`;
        });
        
        const itemDiv = document.createElement('div');
        itemDiv.className = 'flex items-center gap-2 bg-gray-50 p-3 rounded-lg';
        itemDiv.innerHTML = `
            <select name="recipe_items[${index}][raw_material_id]" required
                    class="flex-1 px-3 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-2 focus:ring-${type === 'add' ? 'orange' : 'blue'}-400">
                ${optionsHtml}
            </select>
            <input type="number" name="recipe_items[${index}][qty]" min="0.0001" step="0.0001" required
                   class="w-20 px-3 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-2 focus:ring-${type === 'add' ? 'orange' : 'blue'}-400"
                   placeholder="Qty">
            <button type="button" onclick="this.parentElement.remove()" 
                    class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm rounded transition">
                Hapus
            </button>
        `;
        container.appendChild(itemDiv);
    }

    // Handle drop zone click to open file dialog
    function setupDropZone(dropZoneId, inputId, color = 'orange') {
        const dropZone = document.getElementById(dropZoneId);
        const input = document.getElementById(inputId);
        const borderColor = color === 'blue' ? 'border-blue-400' : 'border-orange-400';
        const bgColor = color === 'blue' ? 'bg-blue-50' : 'bg-orange-50';

        // Click to open file dialog
        dropZone.addEventListener('click', () => {
            input.click();
        });

        // Drag and drop events
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add(borderColor, bgColor);
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove(borderColor, bgColor);
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove(borderColor, bgColor);
            handleImageDrop(e, inputId);
        });
    }

    // Initialize drop zones
    setupDropZone('addImageDropZone', 'addImageInput', 'orange');
    setupDropZone('editImageDropZone', 'editImageInput', 'blue');

    // Auto close alert
    setTimeout(() => {
        const alert = document.getElementById('alertSuccess');
        if (alert) alert.remove();
    }, 5000);
</script>
@endsection
