@extends('layouts.dashboard_layout')

@section('title', 'Stock Opname - Dashboard')

@section('content')
<main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Stock Opname</h1>
                <p class="text-sm text-gray-500 mt-1">Pencatatan dan pengecekan stok fisik bahan baku</p>
            </div>
            <button onclick="openAddModal()" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-orange-500/30 transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
                Buat Stock Opname
            </button>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div id="alertSuccess" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center justify-between">
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

    <!-- Alert Error -->
    @if(session('error'))
    <div id="alertError" class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        <button onclick="document.getElementById('alertError').remove()" class="text-red-500 hover:text-red-700">
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
                    <p class="text-sm text-gray-500">Total Opname</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalOpnames }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Bulan Ini</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $thisMonthOpnames }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $todayOpnames }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Bahan</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $totalMaterials }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
        <div class="p-4">
            <form method="GET" class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                <div class="relative w-full lg:w-64">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"/>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <select name="shift" onchange="this.form.submit()" 
                            class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition">
                        <option value="">Semua Shift</option>
                        <option value="Pagi" {{ request('shift') == 'Pagi' ? 'selected' : '' }}>🌅 Pagi</option>
                        <option value="Siang" {{ request('shift') == 'Siang' ? 'selected' : '' }}>☀️ Siang</option>
                        <option value="Malam" {{ request('shift') == 'Malam' ? 'selected' : '' }}>🌙 Malam</option>
                    </select>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()"
                           class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition"
                           title="Dari Tanggal">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()"
                           class="px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition"
                           title="Sampai Tanggal">
                    @if(request()->hasAny(['shift', 'start_date', 'end_date', 'user_id']))
                        <a href="{{ route('stock-opnames.index') }}" class="px-4 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium transition">
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
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Shift</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Petugas</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Item</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-center py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($stockOpnames as $index => $opname)
                    @php
                        $hasDifference = false;
                        $totalDiff = 0;
                        foreach($opname->items as $item) {
                            $systemQty = $systemStocks[$item->raw_material_id] ?? 0;
                            $diff = $item->qty - $systemQty;
                            $totalDiff += abs($diff);
                            if(abs($diff) > 0.01) {
                                $hasDifference = true;
                            }
                        }
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-500">{{ $stockOpnames->firstItem() + $index }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shrink-0">
                                    <span class="text-white font-bold text-sm">{{ $opname->opname_date->format('d') }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $opname->opname_date->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $opname->opname_date->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @php
                                $shiftConfig = [
                                    'Pagi' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => '🌅'],
                                    'Siang' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => '☀️'],
                                    'Malam' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'icon' => '🌙'],
                                ];
                                $shift = $shiftConfig[$opname->shift] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => '📋'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold {{ $shift['bg'] }} {{ $shift['text'] }}">
                                <span>{{ $shift['icon'] }}</span>
                                {{ $opname->shift }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">{{ strtoupper(substr($opname->user->name ?? 'U', 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ $opname->user->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $opname->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                {{ $opname->items->count() }} Bahan
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($opname->status == 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Menunggu
                                </span>
                            @elseif($opname->status == 'approved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="openViewModal({{ $opname->id }})" 
                                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition-colors"
                                        title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button onclick="openEditModal({{ $opname->id }})" 
                                        class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors"
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                @if($opname->status == 'pending')
                                    <button onclick="openApproveModal({{ $opname->id }}, '{{ $opname->opname_date->format('d M Y') }}')" 
                                            class="w-8 h-8 rounded-lg bg-green-50 hover:bg-green-100 text-green-600 flex items-center justify-center transition-colors"
                                            title="Setujui">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    <button onclick="openRejectModal({{ $opname->id }}, '{{ $opname->opname_date->format('d M Y') }}')" 
                                            class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors"
                                            title="Tolak">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                @endif
                                {{-- <a href="{{ route('stock-opnames.print', $opname) }}" target="_blank"
                                   class="w-8 h-8 rounded-lg bg-green-50 hover:bg-green-100 text-green-600 flex items-center justify-center transition-colors"
                                   title="Print">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </a> --}}
                                <button onclick="openDeleteModal({{ $opname->id }}, '{{ $opname->opname_date->format('d M Y') }}', '{{ $opname->shift }}')" 
                                        class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors"
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
                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-semibold text-lg">Belum ada stock opname</p>
                                <p class="text-sm text-gray-400 mt-1 mb-4">Klik tombol di bawah untuk membuat stock opname baru</p>
                                <button onclick="openAddModal()" 
                                        class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Buat Stock Opname
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($stockOpnames->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold">{{ $stockOpnames->firstItem() }}</span> - 
                    <span class="font-semibold">{{ $stockOpnames->lastItem() }}</span> dari 
                    <span class="font-semibold">{{ $stockOpnames->total() }}</span> data
                </p>
                <div class="flex items-center gap-1">
                    @if($stockOpnames->onFirstPage())
                        <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">‹</span>
                    @else
                        <a href="{{ $stockOpnames->previousPageUrl() }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:text-orange-600 text-sm transition">‹</a>
                    @endif
                    
                    @foreach($stockOpnames->getUrlRange(1, $stockOpnames->lastPage()) as $page => $url)
                        @if($page == $stockOpnames->currentPage())
                            <span class="px-3.5 py-2 rounded-lg bg-orange-500 text-white text-sm font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3.5 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:text-orange-600 text-sm transition">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($stockOpnames->hasMorePages())
                        <a href="{{ $stockOpnames->nextPageUrl() }}" class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-orange-50 hover:text-orange-600 text-sm transition">›</a>
                    @else
                        <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">›</span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</main>

<!-- Modal Tambah Stock Opname -->
<div id="addModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('addModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 flex flex-col max-h-[90vh]">
            <!-- Header - Fixed -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Buat Stock Opname</h3>
                        <p class="text-orange-100 text-sm">Catat stok fisik bahan baku</p>
                    </div>
                    <button onclick="closeModal('addModal')" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Body - Scrollable -->
            <form action="{{ route('stock-opnames.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="space-y-6">
                        <!-- Header Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Opname <span class="text-red-500">*</span></label>
                                <input type="date" name="opname_date" required value="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Shift <span class="text-red-500">*</span></label>
                                <select name="shift" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                                    <option value="">Pilih Shift</option>
                                    <option value="Pagi">🌅 Pagi (06:00 - 14:00)</option>
                                    <option value="Siang">☀️ Siang (14:00 - 22:00)</option>
                                    <option value="Malam">🌙 Malam (22:00 - 06:00)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Stock Items -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-semibold text-gray-700">Daftar Stok Bahan <span class="text-red-500">*</span></label>
                                <button type="button" onclick="loadAllMaterials('add')" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 text-sm font-medium transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Load Semua Bahan
                                </button>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl border border-gray-200">
                                <!-- Table Header -->
                                <div class="grid grid-cols-12 gap-2 px-4 py-3 bg-gray-100 rounded-t-xl text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <div class="col-span-3">Nama Bahan</div>
                                    <div class="col-span-2 text-center">UOM</div>
                                    <div class="col-span-1 text-center">Base</div>
                                    <div class="col-span-2 text-center">Stok Sistem</div>
                                    <div class="col-span-2 text-center">Stok Fisik</div>
                                    <div class="col-span-1 text-center">Selisih</div>
                                    <div class="col-span-1"></div>
                                </div>
                                
                                <!-- Items Container -->
                                <div id="addItemsContainer" class="p-3 space-y-2 max-h-60 overflow-y-auto">
                                    <!-- Items will be added here -->
                                </div>
                                
                                <!-- Add Button -->
                                <div class="px-3 pb-3">
                                    <button type="button" onclick="addStockItem('add')" 
                                            class="w-full py-2.5 rounded-lg border-2 border-dashed border-gray-300 hover:border-orange-400 text-gray-500 hover:text-orange-600 text-sm font-medium transition flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Tambah Bahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer - Fixed -->
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0">
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('addModal')" 
                                class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold hover:from-orange-600 hover:to-orange-700 transition shadow-lg shadow-orange-500/30">
                            Simpan Stock Opname
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 flex flex-col max-h-[90vh]">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Edit Stock Opname</h3>
                        <p id="editHeaderInfo" class="text-blue-100 text-sm"></p>
                    </div>
                    <button onclick="closeModal('editModal')" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <form id="editForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                @method('PUT')
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Opname <span class="text-red-500">*</span></label>
                                <input type="date" name="opname_date" id="editOpnameDate" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Shift <span class="text-red-500">*</span></label>
                                <select name="shift" id="editShift" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                                    <option value="Pagi">🌅 Pagi (06:00 - 14:00)</option>
                                    <option value="Siang">☀️ Siang (14:00 - 22:00)</option>
                                    <option value="Malam">🌙 Malam (22:00 - 06:00)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-semibold text-gray-700">Daftar Stok Bahan</label>
                                <button type="button" onclick="loadAllMaterials('edit')" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 text-sm font-medium transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Load Semua Bahan
                                </button>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl border border-gray-200">
                                <div class="grid grid-cols-12 gap-2 px-4 py-3 bg-gray-100 rounded-t-xl text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <div class="col-span-3">Nama Bahan</div>
                                    <div class="col-span-2 text-center">UOM</div>
                                    <div class="col-span-1 text-center">Base</div>
                                    <div class="col-span-2 text-center">Stok Sistem</div>
                                    <div class="col-span-2 text-center">Stok Fisik</div>
                                    <div class="col-span-1 text-center">Selisih</div>
                                    <div class="col-span-1"></div>
                                </div>
                                
                                <div id="editItemsContainer" class="p-3 space-y-2 max-h-60 overflow-y-auto">
                                    <!-- Items will be populated by JavaScript -->
                                </div>
                                
                                <div class="px-3 pb-3">
                                    <button type="button" onclick="addStockItem('edit')" 
                                            class="w-full py-2.5 rounded-lg border-2 border-dashed border-gray-300 hover:border-blue-400 text-gray-500 hover:text-blue-600 text-sm font-medium transition flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Tambah Bahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0">
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('editModal')" 
                                class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold hover:from-blue-600 hover:to-blue-700 transition shadow-lg shadow-blue-500/30">
                            Update Stock Opname
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal View Detail -->
<div id="viewModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('viewModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl max-h-[90vh] overflow-hidden">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 flex flex-col max-h-[90vh]">
            <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4 shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Detail Stock Opname</h3>
                        <p id="viewHeaderInfo" class="text-gray-300 text-sm"></p>
                    </div>
                    <button onclick="closeModal('viewModal')" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <!-- Info Cards -->
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-blue-600 font-medium">Tanggal</p>
                        <p id="viewDate" class="text-base font-bold text-blue-700 mt-1">-</p>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-amber-600 font-medium">Shift</p>
                        <p id="viewShift" class="text-base font-bold text-amber-700 mt-1">-</p>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-purple-600 font-medium">Petugas</p>
                        <p id="viewUser" class="text-base font-bold text-purple-700 mt-1 truncate">-</p>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                    <div class="grid grid-cols-12 gap-2 bg-gray-100 px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <div class="col-span-4">Nama Bahan</div>
                        <div class="col-span-2 text-center">Satuan</div>
                        <div class="col-span-2 text-right">Stok Sistem</div>
                        <div class="col-span-2 text-right">Stok Fisik</div>
                        <div class="col-span-2 text-right">Selisih</div>
                    </div>
                    <div id="viewItemsContainer" class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                        <!-- Items will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Summary -->
                <div id="viewSummary" class="mt-4 grid grid-cols-2 gap-3">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0">
                <button onclick="closeModal('viewModal')" 
                        class="w-full px-4 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold transition">
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
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Stock Opname?</h3>
            <p class="text-gray-500 text-sm mb-1">Tanggal: <span id="deleteDate" class="font-semibold text-gray-700"></span></p>
            <p class="text-gray-500 text-sm mb-6">Shift: <span id="deleteShift" class="font-semibold text-gray-700"></span></p>
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

<!-- Modal Approve -->
<div id="approveModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('approveModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 p-6 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Setujui Stock Opname?</h3>
            <p class="text-gray-500 text-sm mb-1">Tanggal: <span id="approveDate" class="font-semibold text-gray-700"></span></p>
            <p class="text-gray-500 text-sm mb-6">Aksi ini akan memperbarui stok sistem sesuai data stok opname ini secara permanen.</p>
            <form id="approveForm" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('approveModal')" 
                            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 rounded-xl bg-green-500 text-white font-semibold hover:bg-green-600 transition">
                        Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('rejectModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-2xl mx-4 p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Tolak Stock Opname?</h3>
            <p class="text-gray-500 text-sm mb-1">Tanggal: <span id="rejectDate" class="font-semibold text-gray-700"></span></p>
            <p class="text-gray-500 text-sm mb-6">Stock opname yang ditolak tidak akan mempengaruhi stok sistem.</p>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('rejectModal')" 
                            class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 rounded-xl bg-red-500 text-white font-semibold hover:bg-red-600 transition">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Data for JavaScript -->
<script>
    const rawMaterialsData = @json($rawMaterials);
    const systemStocksData = @json($systemStocks);
</script>
@endsection

@section('scripts')
<script>
    let addItemIndex = 0;
    let editItemIndex = 0;

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openAddModal() {
        document.getElementById('addItemsContainer').innerHTML = '';
        addItemIndex = 0;
        addStockItem('add');
        openModal('addModal');
    }

    function loadAllMaterials(prefix) {
        const container = document.getElementById(prefix + 'ItemsContainer');
        container.innerHTML = '';
        if (prefix === 'add') addItemIndex = 0;
        else editItemIndex = 0;
        
        rawMaterialsData.forEach(material => {
            addStockItemWithMaterial(prefix, material);
        });
    }

    function createItemRow(prefix, index, material = null, qty = '') {
        const systemStock = material ? (systemStocksData[material.id] || 0) : 0;
        const colorClass = prefix === 'add' ? 'orange' : 'blue';
        const units = getMaterialUnits(material);
        
        return `
            <div class="stock-item-row grid grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-200" data-index="${index}">
                <div class="col-span-3">
                    <select name="items[${index}][raw_material_id]" required
                            class="w-full px-2 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-${colorClass}-400 transition"
                            onchange="updateRowData(this, ${index}, '${prefix}')">
                        <option value="">Pilih Bahan</option>
                        ${rawMaterialsData.map(m => `<option value="${m.id}" data-unit="${m.unit}" ${material && m.id === material.id ? 'selected' : ''}>${m.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-span-2">
                    <select name="items[${index}][raw_material_unit_id]"
                            class="unit-select-${index} w-full px-2 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-${colorClass}-400 transition"
                            onchange="calculateDiff(${index}, '${prefix}')">
                        ${units.map(unit => `<option value="${unit.id || ''}" data-name="${unit.name}" data-ratio="${unit.ratio || 1}">${unit.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-span-1 text-center">
                    <span class="unit-${index} text-sm text-gray-500">${material?.unit || '-'}</span>
                </div>
                <div class="col-span-2 text-center">
                    <span class="system-stock-${index} text-sm font-medium text-gray-600">${parseFloat(systemStock).toFixed(2)}</span>
                </div>
                <div class="col-span-2">
                    <input type="number" name="items[${index}][qty]" required min="0" step="0.01"
                           class="w-full px-2 py-2 rounded-lg border border-gray-200 text-sm text-center focus:outline-none focus:ring-2 focus:ring-${colorClass}-400 transition"
                           placeholder="0.00"
                           value="${qty}"
                           oninput="calculateDiff(${index}, '${prefix}')">
                </div>
                <div class="col-span-1 text-center">
                    <span class="diff-${index} text-sm font-semibold ${qty ? (qty - systemStock >= 0 ? 'text-green-600' : 'text-red-600') : 'text-gray-400'}">${qty ? (qty - systemStock).toFixed(2) : '-'}</span>
                </div>
                <div class="col-span-1 text-center">
                    <button type="button" onclick="removeStockItem(this)" 
                            class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition mx-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;
    }

    function getMaterialUnits(material) {
        return (material?.units?.length ? material.units : [{ id: '', name: material?.unit || '-', ratio: 1 }])
            .slice()
            .sort((a, b) => (parseFloat(a.ratio) || 1) - (parseFloat(b.ratio) || 1));
    }

    function addStockItem(prefix) {
        const container = document.getElementById(prefix + 'ItemsContainer');
        const index = prefix === 'add' ? addItemIndex++ : editItemIndex++;
        container.insertAdjacentHTML('beforeend', createItemRow(prefix, index));
    }

    function addStockItemWithMaterial(prefix, material) {
        const container = document.getElementById(prefix + 'ItemsContainer');
        const index = prefix === 'add' ? addItemIndex++ : editItemIndex++;
        const systemStock = systemStocksData[material.id] || 0;
        container.insertAdjacentHTML('beforeend', createItemRow(prefix, index, material, systemStock.toFixed(2)));
    }

    function removeStockItem(button) {
        button.closest('.stock-item-row').remove();
    }

    function updateRowData(select, index, prefix) {
        const materialId = select.value;
        const material = rawMaterialsData.find(m => m.id == materialId);
        const systemStock = materialId ? (systemStocksData[materialId] || 0) : 0;
        const row = select.closest('.stock-item-row');
        const units = getMaterialUnits(material);
        const unitSelect = row.querySelector(`.unit-select-${index}`);
        
        if (unitSelect) {
            unitSelect.innerHTML = units.map(unit => `<option value="${unit.id || ''}" data-name="${unit.name}" data-ratio="${unit.ratio || 1}">${unit.name}</option>`).join('');
        }
        row.querySelector(`.unit-${index}`).textContent = material?.unit || '-';
        row.querySelector(`.system-stock-${index}`).textContent = parseFloat(systemStock).toFixed(2);
        calculateDiff(index, prefix);
    }

    function calculateDiff(index, prefix) {
        const container = document.getElementById(prefix + 'ItemsContainer');
        const row = container.querySelector(`[data-index="${index}"]`);
        if (!row) return;
        
        const systemStock = parseFloat(row.querySelector(`.system-stock-${index}`).textContent) || 0;
        const physicalStock = parseFloat(row.querySelector(`input[name*="[qty]"]`).value) || 0;
        const unitSelect = row.querySelector(`.unit-select-${index}`);
        const ratio = parseFloat(unitSelect?.options[unitSelect.selectedIndex]?.dataset.ratio) || 1;
        const basePhysicalStock = physicalStock * ratio;
        const diff = basePhysicalStock - systemStock;
        
        const diffElement = row.querySelector(`.diff-${index}`);
        diffElement.textContent = diff.toFixed(2);
        
        if (diff > 0) {
            diffElement.className = `diff-${index} text-sm font-semibold text-green-600`;
        } else if (diff < 0) {
            diffElement.className = `diff-${index} text-sm font-semibold text-red-600`;
        } else {
            diffElement.className = `diff-${index} text-sm font-semibold text-gray-600`;
        }
    }

    async function openViewModal(id) {
        try {
            const response = await fetch(`/dashboard/stock-opnames/${id}`);
            const data = await response.json();
            const opname = data.opname;
            const systemStocks = data.system_stocks;
            
            document.getElementById('viewHeaderInfo').textContent = `${opname.user?.name || '-'}`;
            document.getElementById('viewDate').textContent = new Date(opname.opname_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            document.getElementById('viewShift').textContent = opname.shift;
            document.getElementById('viewUser').textContent = opname.user?.name || '-';
            
            let itemsWithDiff = 0;
            
            const itemsHtml = opname.items.map(item => {
                const systemQty = systemStocks[item.raw_material_id] || 0;
                const diff = item.qty - systemQty;
                
                if (Math.abs(diff) > 0.01) itemsWithDiff++;
                
                let diffClass = 'text-gray-600';
                let diffPrefix = '';
                if (diff > 0) { diffClass = 'text-green-600'; diffPrefix = '+'; }
                else if (diff < 0) { diffClass = 'text-red-600'; }
                
                return `
                    <div class="grid grid-cols-12 gap-2 px-4 py-3 hover:bg-gray-50 text-sm">
                        <div class="col-span-4 font-medium text-gray-800">${item.raw_material?.name || '-'}</div>
                        <div class="col-span-2 text-center text-gray-500">${item.raw_material?.unit || '-'}</div>
                        <div class="col-span-2 text-right text-gray-600">${parseFloat(systemQty).toFixed(2)}</div>
                        <div class="col-span-2 text-right font-semibold text-gray-800">${parseFloat(item.qty).toFixed(2)}</div>
                        <div class="col-span-2 text-right font-semibold ${diffClass}">${diffPrefix}${diff.toFixed(2)}</div>
                    </div>
                `;
            }).join('');
            
            document.getElementById('viewItemsContainer').innerHTML = itemsHtml;
            
            document.getElementById('viewSummary').innerHTML = `
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-blue-600 font-medium">Total Item</p>
                    <p class="text-lg font-bold text-blue-700">${opname.items.length}</p>
                </div>
                <div class="${itemsWithDiff > 0 ? 'bg-red-50' : 'bg-green-50'} rounded-xl p-3 text-center">
                    <p class="text-xs ${itemsWithDiff > 0 ? 'text-red-600' : 'text-green-600'} font-medium">Ada Selisih</p>
                    <p class="text-lg font-bold ${itemsWithDiff > 0 ? 'text-red-700' : 'text-green-700'}">${itemsWithDiff}</p>
                </div>
            `;
            
            openModal('viewModal');
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal memuat data');
        }
    }

    async function openEditModal(id) {
        try {
            const response = await fetch(`/dashboard/stock-opnames/${id}`);
            const data = await response.json();
            const opname = data.opname;
            
            document.getElementById('editForm').action = `/stock-opnames/${id}`;
            document.getElementById('editHeaderInfo').textContent = `${new Date(opname.opname_date).toLocaleDateString('id-ID')} • ${opname.shift}`;
            document.getElementById('editOpnameDate').value = opname.opname_date;
            document.getElementById('editShift').value = opname.shift;
            
            const container = document.getElementById('editItemsContainer');
            container.innerHTML = '';
            editItemIndex = 0;
            
            opname.items.forEach(item => {
                const material = rawMaterialsData.find(m => m.id == item.raw_material_id);
                const index = editItemIndex++;
                container.insertAdjacentHTML('beforeend', createItemRow('edit', index, material, item.qty));
            });
            
            openModal('editModal');
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal memuat data');
        }
    }

    function openDeleteModal(id, date, shift) {
        document.getElementById('deleteForm').action = `/dashboard/stock-opnames/${id}`;
        document.getElementById('deleteDate').textContent = date;
        document.getElementById('deleteShift').textContent = shift;
        openModal('deleteModal');
    }

    function openApproveModal(id, date) {
        document.getElementById('approveForm').action = `/dashboard/stock-opnames/${id}/approve`;
        document.getElementById('approveDate').textContent = date;
        openModal('approveModal');
    }

    function openRejectModal(id, date) {
        document.getElementById('rejectForm').action = `/dashboard/stock-opnames/${id}/reject`;
        document.getElementById('rejectDate').textContent = date;
        openModal('rejectModal');
    }

    // Search
    document.getElementById('searchInput')?.addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(search) ? '' : 'none';
        });
    });

    // Auto close alert
    setTimeout(() => {
        ['alertSuccess', 'alertError'].forEach(id => {
            const alert = document.getElementById(id);
            if (alert) alert.remove();
        });
    }, 5000);
</script>
@endsection
