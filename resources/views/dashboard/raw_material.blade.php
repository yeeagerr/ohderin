@extends('layouts.dashboard_layout')

@section('title', 'Bahan Baku - Dashboard')

@section('content')
<main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Bahan Baku</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola data bahan baku untuk produksi</p>
            </div>
            <button onclick="openModal('addModal')" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/30 transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Bahan Baku
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
                    <p class="text-sm text-gray-500">Total Bahan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $rawMaterials->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Nilai</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format(App\Models\RawMaterial::sum('cost'), 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Jenis Satuan</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ App\Models\RawMaterial::distinct('unit')->count('unit') }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Rata-rata Biaya</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format(App\Models\RawMaterial::avg('cost'), 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
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
                    <input type="text" id="searchInput" placeholder="Cari bahan baku..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"/>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <select name="unit" onchange="this.form.submit()" 
                            class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition">
                        <option value="">Semua Satuan</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>
                                {{ ucfirst($unit) }}
                            </option>
                        @endforeach
                    </select>
                    <select name="sort" onchange="this.form.submit()" 
                            class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition">
                        <option value="">Urutkan</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                        <option value="cost_asc" {{ request('sort') == 'cost_asc' ? 'selected' : '' }}>Biaya Terendah</option>
                        <option value="cost_desc" {{ request('sort') == 'cost_desc' ? 'selected' : '' }}>Biaya Tertinggi</option>
                    </select>
                    @if(request()->hasAny(['unit', 'sort']))
                        <a href="{{ route('raw-materials.index') }}" class="px-4 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium transition">
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
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Satuan</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Minimal</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Biaya/Unit</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody id="rawMaterialsTableBody" class="divide-y divide-gray-100">
                    @forelse($rawMaterials as $index => $material)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-500">{{ $rawMaterials->firstItem() + $index }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $material->name }}</p>
                                    <p class="text-xs text-gray-400">ID: #{{ $material->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @php
                                $unitColors = [
                                    'kg' => 'bg-blue-100 text-blue-700',
                                    'gram' => 'bg-cyan-100 text-cyan-700',
                                    'liter' => 'bg-purple-100 text-purple-700',
                                    'ml' => 'bg-violet-100 text-violet-700',
                                    'pcs' => 'bg-green-100 text-green-700',
                                    'pack' => 'bg-amber-100 text-amber-700',
                                    'box' => 'bg-orange-100 text-orange-700',
                                ];
                                $colorClass = $unitColors[strtolower($material->unit)] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold {{ $colorClass }}">
                                {{ ucfirst($material->unit) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            @php
                                $isLowStock = $material->stock <= $material->minimal_stock;
                                $stockColor = $isLowStock ? 'text-red-600' : 'text-gray-700';
                            @endphp
                            <span class="font-bold {{ $stockColor }}">{{ number_format($material->stock, 2, ',', '.') }}</span>
                            <span class="text-gray-400 text-sm">{{ $material->unit }}</span>
                            @if($isLowStock)
                                <div class="text-xs text-red-500 mt-1">Stok Menipis!</div>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <span class="font-medium text-gray-700">{{ number_format($material->minimal_stock, 2, ',', '.') }}</span>
                            <span class="text-gray-400 text-sm">{{ $material->unit }}</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <span class="font-semibold text-gray-800">Rp {{ number_format($material->cost, 0, ',', '.') }}</span>
                            <span class="text-gray-400 text-xs">/{{ $material->unit }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ json_encode($material) }})" 
                                        class="w-9 h-9 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors"
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button onclick="openDeleteModal({{ $material->id }}, '{{ addslashes($material->name) }}')" 
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
                                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-semibold text-lg">Belum ada bahan baku</p>
                                <p class="text-sm text-gray-400 mt-1 mb-4">Klik tombol di bawah untuk menambahkan bahan baku baru</p>
                                <button onclick="openModal('addModal')" 
                                        class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Bahan Baku
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($rawMaterials->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold">{{ $rawMaterials->firstItem() }}</span> - 
                    <span class="font-semibold">{{ $rawMaterials->lastItem() }}</span> dari 
                    <span class="font-semibold">{{ $rawMaterials->total() }}</span> data
                </p>
                <div class="flex items-center gap-1">
                    @if($rawMaterials->onFirstPage())
                        <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <a href="{{ $rawMaterials->previousPageUrl() }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif
                    
                    @foreach($rawMaterials->getUrlRange(1, $rawMaterials->lastPage()) as $page => $url)
                        @if($page == $rawMaterials->currentPage())
                            <span class="px-3.5 py-2 rounded-lg bg-orange-500 text-white text-sm font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3.5 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 text-sm transition">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($rawMaterials->hasMorePages())
                        <a href="{{ $rawMaterials->nextPageUrl() }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 text-sm transition">
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
        @endif
    </div>
</main>

<datalist id="unitOptions">
    @foreach($units as $unit)
        <option value="{{ $unit }}">
    @endforeach
</datalist>

<!-- Modal Tambah -->
<div id="addModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('addModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white">Tambah Bahan Baku</h3>
                    <button onclick="closeModal('addModal')" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <form action="{{ route('raw-materials.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bahan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                               placeholder="Contoh: Beras Premium">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                            <input type="text" name="unit" id="addUnitName" required list="unitOptions" maxlength="20"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                                   placeholder="Contoh: kg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Minimal <span class="text-red-500">*</span></label>
                            <input type="number" name="minimal_stock" required min="0" step="0.01"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                                   placeholder="0.00">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Biaya per Unit <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="cost" required min="0" step="100"
                                   class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                                   placeholder="0">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Harga beli per satuan</p>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Daftar Satuan</label>
                                <p class="text-xs text-gray-400">Ratio dihitung terhadap satuan utama</p>
                            </div>
                            <button type="button" onclick="addUnitRow('add')" class="px-3 py-2 text-sm text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-xl font-semibold transition">
                                Tambah
                            </button>
                        </div>
                        <div class="overflow-hidden border border-gray-200 rounded-xl">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500">Nama</th>
                                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500">Ratio</th>
                                        <th class="w-16 px-3 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody id="addUnitsContainer" class="divide-y divide-gray-100"></tbody>
                            </table>
                        </div>
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
                    <h3 class="text-lg font-bold text-white">Edit Bahan Baku</h3>
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bahan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="editName" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                            <input type="text" name="unit" id="editUnit" required list="unitOptions" maxlength="20"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Minimal <span class="text-red-500">*</span></label>
                            <input type="number" name="minimal_stock" id="editMinimalStock" required min="0" step="0.01"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Biaya per Unit <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="cost" id="editCost" required min="0" step="100"
                                   class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Daftar Satuan</label>
                                <p class="text-xs text-gray-400">Ratio dihitung terhadap satuan utama</p>
                            </div>
                            <button type="button" onclick="addUnitRow('edit')" class="px-3 py-2 text-sm text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl font-semibold transition">
                                Tambah
                            </button>
                        </div>
                        <div class="overflow-hidden border border-gray-200 rounded-xl">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500">Nama</th>
                                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500">Ratio</th>
                                        <th class="w-16 px-3 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody id="editUnitsContainer" class="divide-y divide-gray-100"></tbody>
                            </table>
                        </div>
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
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Bahan Baku?</h3>
            <p class="text-gray-500 text-sm mb-6">Bahan baku "<span id="deleteName" class="font-semibold text-gray-700"></span>" akan dihapus permanen.</p>
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
    const unitCounters = { add: 1, edit: 1 };

    function getUnitInput(type) {
        return document.getElementById(type === 'add' ? 'addUnitName' : 'editUnit');
    }

    function getUnitsContainer(type) {
        return document.getElementById(type === 'add' ? 'addUnitsContainer' : 'editUnitsContainer');
    }

    function createUnitRow(type, index, unit = {}, isBase = false) {
        const color = type === 'add' ? 'orange' : 'blue';
        const name = unit.name || '';
        const ratio = unit.ratio || '';
        return `
            <tr ${isBase ? 'data-base-unit="true"' : ''}>
                <td class="px-3 py-2">
                    <input type="text" name="units[${index}][name]" value="${name}" ${isBase ? 'readonly' : ''}
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-${color}-400 ${isBase ? 'bg-gray-50 text-gray-500' : ''}"
                           placeholder="Nama satuan">
                </td>
                <td class="px-3 py-2">
                    <input type="number" name="units[${index}][ratio]" value="${ratio}" min="0.0001" step="0.0001" ${isBase ? 'readonly' : ''}
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-${color}-400 ${isBase ? 'bg-gray-50 text-gray-500' : ''}"
                           placeholder="1">
                </td>
                <td class="px-3 py-2 text-right">
                    ${isBase ? '<span class="text-xs text-gray-400">Utama</span>' : '<button type="button" onclick="this.closest(\'tr\').remove()" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm rounded-lg transition">Hapus</button>'}
                </td>
            </tr>
        `;
    }

    function syncBaseUnitRow(type) {
        const container = getUnitsContainer(type);
        const unitInput = getUnitInput(type);
        const baseName = unitInput.value.trim();
        let baseRow = container.querySelector('[data-base-unit="true"]');

        if (!baseRow) {
            container.insertAdjacentHTML('afterbegin', createUnitRow(type, 0, { name: baseName, ratio: 1 }, true));
            baseRow = container.querySelector('[data-base-unit="true"]');
        }

        baseRow.querySelector('input[name="units[0][name]"]').value = baseName;
        baseRow.querySelector('input[name="units[0][ratio]"]').value = 1;
    }

    function addUnitRow(type, unit = {}) {
        const container = getUnitsContainer(type);
        const index = unitCounters[type]++;
        container.insertAdjacentHTML('beforeend', createUnitRow(type, index, unit, false));
    }

    function populateUnits(type, mainUnit, units = []) {
        const container = getUnitsContainer(type);
        container.innerHTML = '';
        unitCounters[type] = 1;
        syncBaseUnitRow(type);

        units
            .filter(unit => unit.name && unit.name.toLowerCase() !== (mainUnit || '').toLowerCase())
            .forEach(unit => addUnitRow(type, unit));
    }

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        if (id === 'addModal') {
            syncBaseUnitRow('add');
        }
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openEditModal(material) {
        document.getElementById('editForm').action = `/dashboard/raw-materials/${material.id}`;
        document.getElementById('editName').value = material.name;
        document.getElementById('editUnit').value = material.unit;
        document.getElementById('editMinimalStock').value = material.minimal_stock;
        document.getElementById('editCost').value = material.cost;
        populateUnits('edit', material.unit, material.units || []);
        openModal('editModal');
    }

    function openDeleteModal(id, name) {
        document.getElementById('deleteForm').action = `/dashboard/raw-materials/${id}`;
        document.getElementById('deleteName').textContent = name;
        openModal('deleteModal');
    }

    // Search
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        document.querySelectorAll('#rawMaterialsTableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(search) ? '' : 'none';
        });
    });

    document.getElementById('addUnitName').addEventListener('input', () => syncBaseUnitRow('add'));
    document.getElementById('editUnit').addEventListener('input', () => syncBaseUnitRow('edit'));
    syncBaseUnitRow('add');

    // Auto close alert
    setTimeout(() => {
        const alert = document.getElementById('alertSuccess');
        if (alert) alert.remove();
    }, 5000);
</script>
@endsection
