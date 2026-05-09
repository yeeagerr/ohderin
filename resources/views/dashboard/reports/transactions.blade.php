@extends('layouts.dashboard_layout')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="flex-1 flex flex-col bg-gray-50 min-h-screen">
    <div class="bg-white border-b border-gray-200 p-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan Transaksi</h1>
            <p class="text-gray-600 mt-1">Lihat detail setiap transaksi penjualan</p>
        </div>
    </div>

    <div class="p-6 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Transaksi</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="all">Semua Metode</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="debit" {{ request('payment_method') === 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="credit" {{ request('payment_method') === 'credit' ? 'selected' : '' }}>Kredit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="all">Semua Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari No. Order</label>
                    <input type="text" name="search" placeholder="Masukkan no. order..." value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Jumlah</label>
                    <input type="number" name="min_amount" placeholder="Rp" value="{{ request('min_amount') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maksimum Jumlah</label>
                    <input type="number" name="max_amount" placeholder="Rp" value="{{ request('max_amount') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-medium">
                        Cari
                    </button>
                    <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-4">
                <p class="text-gray-600 text-sm">Total Transaksi</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats->total_count }}</p>
                <p class="text-xs text-gray-500 mt-1">Transaksi</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <p class="text-gray-600 text-sm">Total Jumlah</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($stats->total_amount, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-1">Semua transaksi</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <p class="text-gray-600 text-sm">Rata-rata</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($stats->avg_amount, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-1">Per transaksi</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <p class="text-gray-600 text-sm">Selesai</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats->completed_count }}</p>
                <p class="text-xs text-gray-500 mt-1">Transaksi</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <p class="text-gray-600 text-sm">Draft</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats->draft_count }}</p>
                <p class="text-xs text-gray-500 mt-1">Transaksi</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Transaksi</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">No. Order</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal & Waktu</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">POS</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Metode Pembayaran</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Item</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Total</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Dibayar</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Kembalian</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <a href="#" onclick="showDetail(event, {{ $transaction->id }})" 
                                   class="text-orange-600 font-medium hover:underline cursor-pointer">
                                    {{ $transaction->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $transaction->created_at->translatedFormat('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $transaction->register->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-600">
                                {{ $transaction->items_count ?? $transaction->items->count() }} item
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-right text-gray-900">
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-gray-600">
                                Rp {{ number_format($transaction->paid_amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-gray-600">
                                Rp {{ number_format($transaction->change_amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                @if($transaction->status === 'completed')
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✓ Selesai
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        ⎯ Draft
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-600">Tidak ada transaksi ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

<div id="detailModal" class="hidden fixed inset-0 z-50 p-4 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen">
        <div onclick="closeDetail()" class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto relative z-10">

@push('scripts')
<script>
    function showDetail(e, id) {
        e.preventDefault();
        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        
        const row = event.target.closest('tr');
        const cells = row.querySelectorAll('td');
        
        let html = `
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Detail Transaksi</h3>
                <button onclick="closeDetail()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">No. Order</p>
                        <p class="font-semibold text-gray-900">${cells[0].textContent.trim()}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Waktu</p>
                        <p class="font-semibold text-gray-900">${cells[1].textContent.trim()}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Total</p>
                        <p class="font-semibold text-gray-900">${cells[5].textContent.trim()}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Dibayar</p>
                        <p class="font-semibold text-gray-900">${cells[6].textContent.trim()}</p>
                    </div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-900">
                        <strong>Kembalian:</strong> ${cells[7].textContent.trim()}
                    </p>
                </div>
            </div>
        `;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'p-6 border-b border-gray-200';
        contentDiv.innerHTML = html;
        
        const modalContent = modal.querySelector('.bg-white');
        modalContent.innerHTML = '';
        modalContent.appendChild(contentDiv);
    }

    function closeDetail() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target.classList.contains('bg-black')) {
            closeDetail();
        }
    });
</script>
@endpush

@endsection
