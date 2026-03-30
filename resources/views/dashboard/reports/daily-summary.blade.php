@extends('layouts.dashboard_layout')

@section('title', 'Laporan Harian Penjualan')

@section('content')
<div class="flex-1 flex flex-col bg-gray-50 min-h-screen">
    <div class="bg-white border-b border-gray-200 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Laporan Harian {{ $date->translatedFormat('l, d F Y') }}</h1>
                <p class="text-gray-600 mt-1">Ringkasan lengkap penjualan harian</p>
            </div>
            <div class="flex gap-3">
                <input type="date" id="dateSelect" value="{{ $date->format('Y-m-d') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                <button onclick="changeDate()" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                    Lihat
                </button>
            </div>
        </div>
    </div>

    <div class="p-6 overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-linear-to-br from-orange-400 to-orange-600 rounded-lg shadow-md p-6 text-white">
                <p class="text-orange-100 text-sm">Total Penjualan</p>
                <p class="text-3xl font-bold mt-2">Rp {{ number_format($summary->total_revenue, 0, ',', '.') }}</p>
                <p class="text-orange-100 text-sm mt-2">{{ $summary->total_transactions }} transaksi</p>
            </div>

            <div class="bg-linear-to-br from-green-400 to-green-600 rounded-lg shadow-md p-6 text-white">
                <p class="text-green-100 text-sm">Avg Per Transaksi</p>
                <p class="text-3xl font-bold mt-2">Rp {{ number_format($summary->total_revenue / max($summary->total_transactions, 1), 0, ',', '.') }}</p>
                <p class="text-green-100 text-sm mt-2">Rata-rata</p>
            </div>

            <div class="bg-linear-to-br from-blue-400 to-blue-600 rounded-lg shadow-md p-6 text-white">
                <p class="text-blue-100 text-sm">Jumlah Kasir</p>
                <p class="text-3xl font-bold mt-2">{{ $summary->cashiers_count }}</p>
                <p class="text-blue-100 text-sm mt-2">Orang</p>
            </div>

            <div class="bg-linear-to-br from-purple-400 to-purple-600 rounded-lg shadow-md p-6 text-white">
                <p class="text-purple-100 text-sm">Tipe Order</p>
                <p class="text-xl font-bold mt-2">{{ $summary->dine_in_count }} + {{ $summary->takeaway_count }}</p>
                <p class="text-purple-100 text-sm mt-2">Dine In + Take Away</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h3>
                <div class="space-y-4">
                    @php
                        $colors = ['#F97316', '#10B981', '#3B82F6', '#8B5CF6'];
                        $colorIndex = 0;
                    @endphp
                    @foreach($paymentSummary as $payment)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $colors[$colorIndex % count($colors)] }}"></div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 capitalize">{{ $payment->payment_method }}</p>
                                <p class="text-sm text-gray-600">{{ $payment->count }} transaksi</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">Rp {{ number_format($payment->total, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600">{{ round(($payment->total / $summary->total_revenue) * 100) }}%</p>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r h-2 rounded-full" 
                             style="width: {{ round(($payment->total / $summary->total_revenue) * 100) }}%; background-color: {{ $colors[$colorIndex % count($colors)] }}"></div>
                    </div>
                    @php $colorIndex++; @endphp
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tipe Order</h3>
                <div class="space-y-4">
                    @foreach($orderTypeSummary as $type)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $type->order_type === 'dine_in' ? '#3B82F6' : '#8B5CF6' }}"></div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $type->order_type === 'dine_in' ? 'Dine In' : 'Take Away' }}</p>
                                <p class="text-sm text-gray-600">{{ $type->count }} transaksi</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">Rp {{ number_format($type->total, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600">{{ round(($type->total / $summary->total_revenue) * 100) }}%</p>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-linear-to-r h-2 rounded-full" 
                             style="width: {{ round(($type->total / $summary->total_revenue) * 100) }}%; background-color: {{ $type->order_type === 'dine_in' ? '#3B82F6' : '#8B5CF6' }}"></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk Terlaris</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Nama Produk</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-900">Qty</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Revenue</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($topProducts as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-600">{{ $product->qty }} unit</td>
                            <td class="px-4 py-3 text-sm font-semibold text-right text-orange-600">Rp {{ number_format($product->revenue, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-right text-gray-600">{{ round(($product->revenue / $summary->total_revenue) * 100) }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-600">Belum ada penjualan produk</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performa Kasir</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Nama Kasir</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-900">Transaksi</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Total</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($cashierSummary as $cashier)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $cashier->name }}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-600">{{ $cashier->count }}x</td>
                            <td class="px-4 py-3 text-sm font-semibold text-right text-gray-900">Rp {{ number_format($cashier->total, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-right text-gray-600">Rp {{ number_format($cashier->total / $cashier->count, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Semua Transaksi ({{ $sales->count() }} transaksi)</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">No. Order</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Waktu</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kasir</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tipe</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Pembayaran</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Item</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-orange-600">{{ $sale->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->created_at->format('H:i:s') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->cashier->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $sale->order_type === 'dine_in' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $sale->order_type === 'dine_in' ? 'DI' : 'TA' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ strtoupper(substr($sale->payment_method, 0, 3)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-600">{{ $sale->items->count() }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-right text-gray-900">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-600">Tidak ada transaksi hari ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function changeDate() {
        const date = document.getElementById('dateSelect').value;
        window.location.href = '{{ route("daily-summary.index") }}?date=' + date;
    }

    document.getElementById('dateSelect').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            changeDate();
        }
    });
</script>
@endpush

@endsection
