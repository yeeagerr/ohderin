@extends('layouts.dashboard_layout')

@section('title', 'Resep - Dashboard')

@section('content')
<main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Resep Produk</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola komposisi bahan baku untuk setiap produk</p>
            </div>
            <button onclick="openAddModal()" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/30 transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
                Buat Resep
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
                    <p class="text-sm text-gray-500">Total Resep</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalRecipes }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Produk Punya Resep</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $productsWithRecipe }}</p>
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
                    <p class="text-sm text-gray-500">Belum Ada Resep</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $totalProducts - $productsWithRecipe }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8v4M12 16h.01"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Bahan</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $totalIngredients }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
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
                    <input type="text" id="searchInput" placeholder="Cari resep..." 
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
                    @if(request()->hasAny(['category', 'product']))
                        <a href="{{ route('recipes.index') }}" class="px-4 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Recipe Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($recipes as $recipe)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-5 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($recipe->product->name ?? 'R', 0, 1)) }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-lg">{{ $recipe->product->name ?? '-' }}</h3>
                            <p class="text-orange-100 text-sm">{{ $recipe->product->category->name ?? '-' }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-white/20 text-white text-xs font-medium">
                        {{ $recipe->items->count() }} Bahan
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-5">
                <!-- Ingredients List -->
                <div class="space-y-3 mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Komposisi Bahan:</p>
                    <div class="max-h-40 overflow-y-auto space-y-2">
                        @foreach($recipe->items as $item)
                        <div class="flex items-center justify-between py-2 px-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-amber-100 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-700">{{ $item->rawMaterial->name ?? '-' }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">
                                {{ number_format($item->qty, 4, ',', '.') }} 
                                <span class="text-gray-400 font-normal">{{ $item->rawMaterial->unit ?? '' }}</span>
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Cost Estimation -->
                @php
                    $totalCost = $recipe->items->sum(function($item) {
                        return $item->qty * ($item->rawMaterial->cost ?? 0);
                    });
                @endphp
                <div class="bg-green-50 rounded-xl p-3 border border-green-100 mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-green-700">Estimasi HPP:</span>
                        <span class="font-bold text-green-700">Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <button onclick="openViewModal({{ $recipe->id }})" 
                            class="flex-1 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </button>
                    <button onclick="openEditModal({{ $recipe->id }})" 
                            class="flex-1 px-4 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 text-sm font-medium transition inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit
                    </button>
                    <button onclick="openDeleteModal({{ $recipe->id }}, '{{ addslashes($recipe->product->name ?? '') }}')" 
                            class="w-10 h-10 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-semibold text-lg">Belum ada resep</p>
                    <p class="text-sm text-gray-400 mt-1 mb-4">Klik tombol di bawah untuk membuat resep baru</p>
                    <button onclick="openAddModal()" 
                            class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Resep
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($recipes->hasPages())
    <div class="mt-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold">{{ $recipes->firstItem() }}</span> - 
                    <span class="font-semibold">{{ $recipes->lastItem() }}</span> dari 
                    <span class="font-semibold">{{ $recipes->total() }}</span> resep
                </p>
                <div class="flex items-center gap-1">
                    @if($recipes->onFirstPage())
                        <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <a href="{{ $recipes->previousPageUrl() }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif
                    
                    @foreach($recipes->getUrlRange(1, $recipes->lastPage()) as $page => $url)
                        @if($page == $recipes->currentPage())
                            <span class="px-3.5 py-2 rounded-lg bg-orange-500 text-white text-sm font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3.5 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 text-sm transition">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($recipes->hasMorePages())
                        <a href="{{ $recipes->nextPageUrl() }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</main>

<!-- Modal Tambah Resep -->
<div id="addModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('addModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Buat Resep Baru</h3>
                        <p class="text-orange-100 text-sm">Tentukan komposisi bahan untuk produk</p>
                    </div>
                    <button onclick="closeModal('addModal')" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <form action="{{ route('recipes.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Select Product -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Produk <span class="text-red-500">*</span></label>
                        <select name="product_id" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($productsWithoutRecipe as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->category->name ?? '' }}</option>
                            @endforeach
                        </select>
                        @if($productsWithoutRecipe->isEmpty())
                            <p class="text-sm text-amber-600 mt-2">⚠️ Semua produk sudah memiliki resep</p>
                        @endif
                    </div>

                    <!-- Ingredients Section -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-semibold text-gray-700">Bahan-Bahan <span class="text-red-500">*</span></label>
                            <button type="button" onclick="addIngredient('add')" 
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-50 hover:bg-green-100 text-green-600 text-sm font-medium transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Bahan
                            </button>
                        </div>
                        
                        <div id="addIngredientsContainer" class="space-y-3">
                            <!-- Ingredient Row Template -->
                            <div class="ingredient-row flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                <div class="flex-1">
                                    <select name="items[0][raw_material_id]" required
                                            class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition"
                                            onchange="updateUnit(this)">
                                        <option value="">Pilih Bahan</option>
                                        @foreach($rawMaterials as $material)
                                            <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">
                                                {{ $material->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-32">
                                    <div class="relative">
                                        <input type="number" name="items[0][qty]" required min="0.0001" step="0.0001"
                                               class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition"
                                               placeholder="Qty">
                                        <span class="unit-label absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></span>
                                    </div>
                                </div>
                                <button type="button" onclick="removeIngredient(this)" 
                                        class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition opacity-50 cursor-not-allowed" disabled>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal('addModal')" 
                            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold hover:from-orange-600 hover:to-orange-700 transition shadow-lg shadow-orange-500/30">
                        Simpan Resep
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Resep -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Edit Resep</h3>
                        <p id="editProductName" class="text-blue-100 text-sm"></p>
                    </div>
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
                <div class="space-y-6">
                    <!-- Ingredients Section -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-semibold text-gray-700">Bahan-Bahan <span class="text-red-500">*</span></label>
                            <button type="button" onclick="addIngredient('edit')" 
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-50 hover:bg-green-100 text-green-600 text-sm font-medium transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Bahan
                            </button>
                        </div>
                        
                        <div id="editIngredientsContainer" class="space-y-3">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal('editModal')" 
                            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold hover:from-blue-600 hover:to-blue-700 transition shadow-lg shadow-blue-500/30">
                        Update Resep
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal View Detail -->
<div id="viewModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('viewModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Detail Resep</h3>
                        <p id="viewProductName" class="text-gray-300 text-sm"></p>
                    </div>
                    <button onclick="closeModal('viewModal')" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="viewIngredientsContainer" class="space-y-3 mb-6">
                    <!-- Will be populated by JavaScript -->
                </div>
                <div id="viewTotalCost" class="bg-green-50 rounded-xl p-4 border border-green-100">
                    <!-- Total cost will be populated by JavaScript -->
                </div>
                <button onclick="closeModal('viewModal')" 
                        class="w-full mt-4 px-4 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold transition">
                    Tutup
                </button>
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
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Resep?</h3>
            <p class="text-gray-500 text-sm mb-6">Resep untuk "<span id="deleteProductName" class="font-semibold text-gray-700"></span>" akan dihapus permanen.</p>
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

<!-- Raw Materials Data for JavaScript -->
<script>
    const rawMaterialsData = @json($rawMaterials);
</script>
@endsection

@section('scripts')
<script>
    let addIngredientIndex = 1;
    let editIngredientIndex = 0;

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openAddModal() {
        openModal('addModal');
    }

    function addIngredient(prefix) {
        const container = document.getElementById(prefix + 'IngredientsContainer');
        const index = prefix === 'add' ? addIngredientIndex++ : editIngredientIndex++;
        
        const row = document.createElement('div');
        row.className = 'ingredient-row flex items-center gap-3 p-3 bg-gray-50 rounded-xl';
        row.innerHTML = `
            <div class="flex-1">
                <select name="items[${index}][raw_material_id]" required
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-${prefix === 'add' ? 'orange' : 'blue'}-400 transition"
                        onchange="updateUnit(this)">
                    <option value="">Pilih Bahan</option>
                    ${rawMaterialsData.map(m => `<option value="${m.id}" data-unit="${m.unit}">${m.name}</option>`).join('')}
                </select>
            </div>
            <div class="w-32">
                <div class="relative">
                    <input type="number" name="items[${index}][qty]" required min="0.0001" step="0.0001"
                           class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-${prefix === 'add' ? 'orange' : 'blue'}-400 transition"
                           placeholder="Qty">
                    <span class="unit-label absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></span>
                </div>
            </div>
            <button type="button" onclick="removeIngredient(this)" 
                    class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        `;
        container.appendChild(row);
        updateRemoveButtons(container);
    }

    function removeIngredient(button) {
        const row = button.closest('.ingredient-row');
        const container = row.parentElement;
        row.remove();
        updateRemoveButtons(container);
    }

    function updateRemoveButtons(container) {
        const rows = container.querySelectorAll('.ingredient-row');
        rows.forEach((row, index) => {
            const button = row.querySelector('button[onclick*="removeIngredient"]');
            if (rows.length <= 1) {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }

    function updateUnit(select) {
        const unit = select.options[select.selectedIndex].dataset.unit || '';
        const row = select.closest('.ingredient-row');
        const unitLabel = row.querySelector('.unit-label');
        if (unitLabel) unitLabel.textContent = unit;
    }

    async function openEditModal(recipeId) {
        try {
            const response = await fetch(`/dashboard/recipes/${recipeId}`);
            const recipe = await response.json();
            
            document.getElementById('editForm').action = `/dashboard/recipes/${recipeId}`;
            document.getElementById('editProductName').textContent = recipe.product?.name || '';
            
            const container = document.getElementById('editIngredientsContainer');
            container.innerHTML = '';
            editIngredientIndex = 0;
            
            recipe.items.forEach((item, index) => {
                editIngredientIndex = index + 1;
                const row = document.createElement('div');
                row.className = 'ingredient-row flex items-center gap-3 p-3 bg-gray-50 rounded-xl';
                row.innerHTML = `
                    <div class="flex-1">        
                        <select name="items[${index}][raw_material_id]" required
                                class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                                onchange="updateUnit(this)">
                            <option value="">Pilih Bahan</option>
                            ${rawMaterialsData.map(m => `<option value="${m.id}" data-unit="${m.unit}" ${m.id == item.raw_material_id ? 'selected' : ''}>${m.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="w-32">
                        <div class="relative">
                            <input type="number" name="items[${index}][qty]" required min="0.0001" step="0.0001"
                                   value="${item.qty}"
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            <span class="unit-label absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">${item.raw_material?.unit || ''}</span>
                        </div>
                    </div>
                    <button type="button" onclick="removeIngredient(this)" 
                            class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                `;
                container.appendChild(row);
            });
            
            updateRemoveButtons(container);
            openModal('editModal');
        } catch (error) {
            console.error('Error fetching recipe:', error);
        }
    }

    async function openViewModal(recipeId) {
        try {
            const response = await fetch(`/dashboard/recipes/${recipeId}`);
            const recipe = await response.json();
            
            document.getElementById('viewProductName').textContent = recipe.product?.name || '';
            
            const container = document.getElementById('viewIngredientsContainer');
            let totalCost = 0;
            
            container.innerHTML = recipe.items.map(item => {
                const cost = item.qty * (item.raw_material?.cost || 0);
                totalCost += cost;
                return `
                    <div class="flex items-center justify-between py-3 px-4 rounded-xl bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">${item.raw_material?.name || '-'}</p>
                                <p class="text-xs text-gray-400">Rp ${(item.raw_material?.cost || 0).toLocaleString('id-ID')}/${item.raw_material?.unit || ''}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">${parseFloat(item.qty).toLocaleString('id-ID', {minimumFractionDigits: 4})} ${item.raw_material?.unit || ''}</p>
                            <p class="text-xs text-gray-400">Rp ${cost.toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                `;
            }).join('');
            
            document.getElementById('viewTotalCost').innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-green-700">Total HPP:</span>
                    <span class="text-xl font-bold text-green-700">Rp ${totalCost.toLocaleString('id-ID')}</span>
                </div>
            `;
            
            openModal('viewModal');
        } catch (error) {
            console.error('Error fetching recipe:', error);
        }
    }

    function openDeleteModal(id, productName) {
        document.getElementById('deleteForm').action = `/dashboard/recipes/${id}`;
        document.getElementById('deleteProductName').textContent = productName;
        openModal('deleteModal');
    }

    // Search
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        document.querySelectorAll('.grid > div:not(.col-span-full)').forEach(card => {
            card.style.display = card.textContent.toLowerCase().includes(search) ? '' : 'none';
        });
    });

    // Auto close alert
    setTimeout(() => {
        const alert = document.getElementById('alertSuccess');
        if (alert) alert.remove();
    }, 5000);
</script>
@endsection