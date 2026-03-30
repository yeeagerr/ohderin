@extends('layouts.dashboard_layout')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="flex-1 flex flex-col bg-gray-50 min-h-screen">
    <div class="bg-white border-b border-gray-200 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Laporan Penjualan</h1>
                <p class="text-gray-600 mt-1">Analisis mendalam data penjualan Anda</p>
            </div>
            <div class="flex gap-3">
                <a href="#" onclick="handleExport(event, 'xlsx')" 
                   class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center gap-2 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>
    </div>

    <div class="p-6 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Laporan</h3>
            <form method="GET" class="grid grid-cols-2 lg:grid-cols-6 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pembayaran</label>
                    <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="all">Semua Metode</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="debit" {{ request('payment_method') === 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="credit" {{ request('payment_method') === 'credit' ? 'selected' : '' }}>Kredit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Order</label>
                    <select name="order_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="all">Semua Tipe</option>
                        <option value="dine_in" {{ request('order_type') === 'dine_in' ? 'selected' : '' }}>Dine In</option>
                        <option value="take_away" {{ request('order_type') === 'take_away' ? 'selected' : '' }}>Take Away</option>
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

                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-medium">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-5">
                <p class="text-gray-600 text-sm">Total Transaksi</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $summary->total_transactions }}</p>
                <p class="text-xs text-gray-500 mt-1">Transaksi</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-5">
                <p class="text-gray-600 text-sm">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($summary->total_revenue, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-1">Semua</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-5">
                <p class="text-gray-600 text-sm">Rata-rata</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($summary->average_transaction, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-1">Per transaksi</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-5">
                <p class="text-gray-600 text-sm">Minimum</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($summary->min_transaction, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-1">Nilai terendah</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-5">
                <p class="text-gray-600 text-sm">Maximum</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($summary->max_transaction, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-1">Nilai tertinggi</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Penjualan Harian</h3>
                <canvas id="dailyChart"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h3>
                <div class="space-y-4">
                    @foreach($paymentBreakdown as $payment)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $loop->index % 2 === 0 ? '#F97316' : '#10B981' }}"></div>
                            <span class="text-gray-700 font-medium capitalize">{{ $payment->payment_method }}</span>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ $payment->count }}x</p>
                            <p class="text-sm text-gray-600">Rp {{ number_format($payment->total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tipe Order</h3>
                <div class="space-y-4">
                    @foreach($orderTypeBreakdown as $type)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $type->order_type === 'dine_in' ? '#3B82F6' : '#8B5CF6' }}"></div>
                            <span class="text-gray-700 font-medium">{{ $type->order_type === 'dine_in' ? 'Dine In' : 'Take Away' }}</span>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ $type->count }}x</p>
                            <p class="text-sm text-gray-600">Rp {{ number_format($type->total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk Terlaris</h3>
                <div class="space-y-3">
                    @forelse($topProducts as $product)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-900 font-medium text-sm">{{ $product->name }}</p>
                            <p class="text-xs text-gray-600">{{ $product->total_qty }} unit</p>
                        </div>
                        <p class="font-semibold text-orange-600 text-sm">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Penjualan</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">No. Order</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Waktu</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kasir</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tipe</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Metode</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Total</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $sale->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->created_at->translatedFormat('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->cashier->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $sale->order_type === 'dine_in' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $sale->order_type === 'dine_in' ? 'Dine In' : 'Take Away' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ $sale->payment_method }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-right text-gray-900">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-600">Tidak ada data penjualan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function handleExport(e, format) {
        e.preventDefault();
        // document.getElementById('loading').style.display = 'flex';
        
        const params = new URLSearchParams({
            start_date: document.querySelector('input[name="start_date"]').value,
            end_date: document.querySelector('input[name="end_date"]').value,
            payment_method: document.querySelector('select[name="payment_method"]').value,
            order_type: document.querySelector('select[name="order_type"]').value,
            status: document.querySelector('select[name="status"]').value,
            format: format
        });
        
        const link = document.createElement('a');
        link.href = '{{ route("sales.export") }}?' + params.toString();
        link.click();
        
        // setTimeout(() => {
        //     document.getElementById('loading').style.display = 'none';
        // }, 1000);
    }

    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_map(fn($d) => $d['label'], $dailySales)) !!},
            datasets: [
                {
                    label: 'Total Penjualan (Rp)',
                    data: {!! json_encode(array_map(fn($d) => $d['total'], $dailySales)) !!},
                    borderColor: '#F97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#F97316',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Jumlah Transaksi',
                    data: {!! json_encode(array_map(fn($d) => $d['count'], $dailySales)) !!},
                    borderColor: '#3B82F6',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Total Penjualan (Rp)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Jumlah Transaksi'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>

@endsection
