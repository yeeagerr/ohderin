@extends('layouts.dashboard_layout')

@section('title', 'Pembelian Bahan Baku - Dashboard')

@section('content')
<main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pembelian Bahan Baku</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola riwayat pembelian dan stok masuk</p>
            </div>
            <button onclick="openModal('addModal')" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/30 transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pembelian
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
                    <p class="text-sm text-gray-500">Total Pembelian</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalPurchases) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Pengeluaran</p>
                    <p class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
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
                    <p class="text-sm text-gray-500">Bulan Ini</p>
                    <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($thisMonthSpent, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Hari Ini</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $todayPurchases }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
        <div class="p-4">
            <form method="GET" class="space-y-4">
                <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                    <div class="relative w-full lg:w-80">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Cari pembelian..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"/>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <select name="raw_material" onchange="this.form.submit()" 
                                class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition">
                            <option value="">Semua Bahan</option>
                            @foreach($rawMaterials as $material)
                                <option value="{{ $material->id }}" {{ request('raw_material') == $material->id ? 'selected' : '' }}>
                                    {{ $material->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()"
                               class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition"
                               placeholder="Dari Tanggal">
                        <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()"
                               class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition"
                               placeholder="Sampai Tanggal">
                        @if(request()->hasAny(['raw_material', 'start_date', 'end_date']))
                            <a href="{{ route('purchases.index') }}" class="px-4 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium transition">
                                Reset
                            </a>
                        @endif
                    </div>
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
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bahan Baku</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga/Unit</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($purchases as $index => $purchase)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-500">{{ $purchases->firstItem() + $index }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <path d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($purchase->purchase_date)->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $purchase->rawMaterial->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $purchase->rawMaterial->unit ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-green-50 text-green-700 font-semibold text-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ number_format($purchase->qty, 2, ',', '.') }}
                                <span class="text-green-500 font-normal">{{ $purchase->rawMaterial->unit ?? '' }}</span>
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <span class="text-gray-700">Rp {{ number_format($purchase->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <span class="font-bold text-gray-800">Rp {{ number_format($purchase->qty * $purchase->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ json_encode($purchase->load('rawMaterial')) }})" 
                                        class="w-9 h-9 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors"
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button onclick="openDeleteModal({{ $purchase->id }}, '{{ $purchase->rawMaterial->name ?? '' }}', '{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}')" 
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
                                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-semibold text-lg">Belum ada pembelian</p>
                                <p class="text-sm text-gray-400 mt-1 mb-4">Klik tombol di bawah untuk menambahkan pembelian baru</p>
                                <button onclick="openModal('addModal')" 
                                        class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Pembelian
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($purchases->count() > 0)
                <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                    <tr>
                        <td colspan="5" class="py-4 px-6 text-right font-semibold text-gray-700">Total Halaman Ini:</td>
                        <td class="py-4 px-6 text-right font-bold text-lg text-orange-600">
                            Rp {{ number_format($purchases->sum(function($p) { return $p->qty * $p->price; }), 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- Pagination -->
        @if($purchases->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold">{{ $purchases->firstItem() }}</span> - 
                    <span class="font-semibold">{{ $purchases->lastItem() }}</span> dari 
                    <span class="font-semibold">{{ $purchases->total() }}</span> data
                </p>
                <div class="flex items-center gap-1">
                    @if($purchases->onFirstPage())
                        <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <a href="{{ $purchases->previousPageUrl() }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif
                    
                    @foreach($purchases->getUrlRange(1, $purchases->lastPage()) as $page => $url)
                        @if($page == $purchases->currentPage())
                            <span class="px-3.5 py-2 rounded-lg bg-orange-500 text-white text-sm font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3.5 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 text-sm transition">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($purchases->hasMorePages())
                        <a href="{{ $purchases->nextPageUrl() }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 text-sm transition">
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

<!-- Modal Tambah -->
<div id="addModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('addModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Tambah Pembelian</h3>
                        <p class="text-orange-100 text-sm">Catat pembelian bahan baku baru</p>
                    </div>
                    <button onclick="closeModal('addModal')" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <form action="{{ route('purchases.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bahan Baku <span class="text-red-500">*</span></label>
                        <select name="raw_material_id" id="addRawMaterial" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                                onchange="updateUnitLabel(this, 'addUnit')">
                            <option value="">Pilih Bahan Baku</option>
                            @foreach($rawMaterials as $material)
                                <option value="{{ $material->id }}" data-unit="{{ $material->unit }}" data-cost="{{ $material->cost }}">
                                    {{ $material->name }} ({{ $material->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pembelian <span class="text-red-500">*</span></label>
                        <input type="date" name="purchase_date" required value="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="qty" required min="0.01" step="0.01"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                                       placeholder="0.00"
                                       oninput="calculateTotal('add')">
                                <span id="addUnit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">unit</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga/Unit <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input type="number" name="price" id="addPrice" required min="0" step="100"
                                       class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                                       placeholder="0"
                                       oninput="calculateTotal('add')">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Preview -->
                    <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-orange-700">Total Pembelian:</span>
                            <span id="addTotal" class="text-xl font-bold text-orange-600">Rp 0</span>
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
                    <div>
                        <h3 class="text-lg font-bold text-white">Edit Pembelian</h3>
                        <p class="text-blue-100 text-sm">Ubah data pembelian bahan baku</p>
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
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bahan Baku <span class="text-red-500">*</span></label>
                        <select name="raw_material_id" id="editRawMaterial" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                onchange="updateUnitLabel(this, 'editUnit')">
                            @foreach($rawMaterials as $material)
                                <option value="{{ $material->id }}" data-unit="{{ $material->unit }}" data-cost="{{ $material->cost }}">
                                    {{ $material->name }} ({{ $material->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pembelian <span class="text-red-500">*</span></label>
                        <input type="date" name="purchase_date" id="editPurchaseDate" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="qty" id="editQty" required min="0.01" step="0.01"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                       oninput="calculateTotal('edit')">
                                <span id="editUnit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">unit</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga/Unit <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input type="number" name="price" id="editPrice" required min="0" step="100"
                                       class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                       oninput="calculateTotal('edit')">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Preview -->
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-blue-700">Total Pembelian:</span>
                            <span id="editTotal" class="text-xl font-bold text-blue-600">Rp 0</span>
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
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Pembelian?</h3>
            <p class="text-gray-500 text-sm mb-1">Pembelian bahan "<span id="deleteMaterial" class="font-semibold text-gray-700"></span>"</p>
            <p class="text-gray-500 text-sm mb-6">pada tanggal <span id="deleteDate" class="font-semibold text-gray-700"></span> akan dihapus.</p>
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

    function updateUnitLabel(select, labelId) {
        console.log("SELECT OPTION = = ", select.options, select.selectedIndex)
        const selectedOption = select.options[select.selectedIndex];
        const unit = selectedOption.dataset.unit || 'unit';
        const cost = selectedOption.dataset.cost || 0;
        document.getElementById(labelId).textContent = unit;
        
        // Auto-fill price with cost from raw material
        const prefix = labelId === 'addUnit' ? 'add' : 'edit';
        document.getElementById(prefix + 'Price').value = cost;
        calculateTotal(prefix);
    }

    function calculateTotal(prefix) {
        const qty = parseFloat(document.querySelector(`#${prefix}Modal input[name="qty"]`)?.value) || 0;
        const price = parseFloat(document.getElementById(prefix + 'Price')?.value) || 0;
        const total = qty * price;
        document.getElementById(prefix + 'Total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function openEditModal(purchase) {
        document.getElementById('editForm').action = `/dashboard/purchases/${purchase.id}`;
        document.getElementById('editRawMaterial').value = purchase.raw_material_id;
        document.getElementById('editPurchaseDate').value = purchase.purchase_date;
        document.getElementById('editQty').value = purchase.qty;
        document.getElementById('editPrice').value = purchase.price;
        
        // Update unit label
        const select = document.getElementById('editRawMaterial');
        const selectedOption = select.options[select.selectedIndex];
        document.getElementById('editUnit').textContent = selectedOption.dataset.unit || 'unit';
        
        calculateTotal('edit');
        openModal('editModal');
    }

    function openDeleteModal(id, material, date) {
        document.getElementById('deleteForm').action = `/dashboard/purchases/${id}`;
        document.getElementById('deleteMaterial').textContent = material;
        document.getElementById('deleteDate').textContent = date;
        openModal('deleteModal');
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