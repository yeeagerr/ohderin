@extends('layouts.dashboard_layout')

@section('title', 'Dashboard')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 sm:p-6">
      <h1 class="text-2xl sm:text-3xl font-black text-gray-800 mb-5 tracking-tight">DASHBOARD</h1>

      <!-- STAT CARDS — 4 equal columns -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <!-- Pendapatan Hari Ini -->
        <div class="card p-4 flex flex-col justify-between" style="min-height:120px">
          <div class="flex items-start justify-between gap-1">
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pendapatan</p>
              <p class="text-[11px] text-gray-400">Hari Ini</p>
            </div>
            @if($revenueChange >= 0)
              <span class="text-[10px] font-bold text-green-500 bg-green-50 px-1.5 py-0.5 rounded-full">↗ {{ $revenueChange }}%</span>
            @else
              <span class="text-[10px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-full">↘ {{ abs($revenueChange) }}%</span>
            @endif
          </div>
          <div>
            @if($revenueChange >= 0)
              <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,28 25,20 50,14 75,8 100,2" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            @else
              <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,2 25,10 50,18 75,24 100,30" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            @endif
            <p class="text-xl font-black text-gray-800">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
          </div>
        </div>

        <!-- Transaksi Hari Ini -->
        <div class="card p-4 flex flex-col justify-between" style="min-height:120px">
          <div class="flex items-start justify-between gap-1">
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Transaksi</p>
              <p class="text-[11px] text-gray-400">Hari Ini</p>
            </div>
            @if($transactionChange >= 0)
              <span class="text-[10px] font-bold text-green-500 bg-green-50 px-1.5 py-0.5 rounded-full">↗ {{ $transactionChange }}%</span>
            @else
              <span class="text-[10px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-full">↘ {{ abs($transactionChange) }}%</span>
            @endif
          </div>
          <div>
            @if($transactionChange >= 0)
              <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,26 25,16 45,24 70,10 100,6" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            @else
              <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,12 30,16 50,10 75,22 100,28" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            @endif
            <p class="text-xl font-black text-gray-800">{{ number_format($todayTransactions, 0, ',', '.') }}</p>
          </div>
        </div>

        <!-- Produk Terjual Hari Ini -->
        <div class="card p-4 flex flex-col justify-between" style="min-height:120px">
          <div class="flex items-start justify-between gap-1">
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Produk Terjual</p>
              <p class="text-[11px] text-gray-400">Hari Ini</p>
            </div>
            @if($itemsSoldChange >= 0)
              <span class="text-[10px] font-bold text-green-500 bg-green-50 px-1.5 py-0.5 rounded-full">↗ {{ $itemsSoldChange }}%</span>
            @else
              <span class="text-[10px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-full">↘ {{ abs($itemsSoldChange) }}%</span>
            @endif
          </div>
          <div>
            @if($itemsSoldChange >= 0)
              <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,28 25,20 50,14 75,8 100,2" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            @else
              <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,2 25,10 50,18 75,24 100,30" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            @endif
            <p class="text-xl font-black text-gray-800">{{ number_format($todayItemsSold, 0, ',', '.') }}</p>
          </div>
        </div>

        <!-- Menu Aktif -->
        <div class="card p-4 flex flex-col justify-between" style="min-height:120px">
          <div class="flex items-start justify-between gap-1">
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Menu Aktif</p>
              <p class="text-[11px] text-gray-400">Total</p>
            </div>
            <span class="text-[10px] font-bold text-orange-500 bg-orange-50 px-1.5 py-0.5 rounded-full">📋 Aktif</span>
          </div>
          <div>
            <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,16 25,16 50,16 75,16 100,16" fill="none" stroke="#f97316" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <p class="text-xl font-black text-gray-800">{{ number_format($activeMenuCount, 0, ',', '.') }}</p>
          </div>
        </div>
      </div>

      <!-- MIDDLE ROW — 3 equal columns -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <!-- Ringkasan Bulan Ini -->
        <div class="card p-5 flex flex-col gap-4">
          <div class="flex items-center justify-between">
            <h2 class="text-base font-black text-gray-800">Ringkasan Bulan Ini</h2>
            @if($monthRevenueChange >= 0)
              <span class="text-[10px] font-bold text-green-500 bg-green-50 px-1.5 py-0.5 rounded-full">↗ {{ $monthRevenueChange }}%</span>
            @else
              <span class="text-[10px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-full">↘ {{ abs($monthRevenueChange) }}%</span>
            @endif
          </div>
          <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl p-4 text-white">
            <p class="text-xs opacity-75 mb-1">Total Pendapatan</p>
            <p class="text-2xl font-black tracking-tight">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
          </div>
          <div class="flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-500">Total Transaksi</p>
            <span class="text-lg font-black text-gray-800">{{ number_format($monthTransactions, 0, ',', '.') }}</span>
          </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="card p-5 flex flex-col gap-2">
          <div class="flex items-center justify-between mb-1">
            <h2 class="text-base font-black text-gray-800">Transaksi Terbaru</h2>
            <span class="text-xs text-orange-500 font-bold bg-orange-50 px-2 py-0.5 rounded-full">{{ $recentSales->count() }} tx</span>
          </div>
          <div class="divide-y divide-gray-50">
            @forelse($recentSales as $sale)
            <div class="flex justify-between items-center py-2.5">
              <div>
                <p class="text-xs font-bold text-gray-700">{{ $sale->order_number }}</p>
                <p class="text-[10px] text-gray-400">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
              </div>
              <div class="text-right">
                <p class="text-xs font-bold text-gray-800">Rp {{ number_format($sale->total, 0, ',', '.') }}</p>
                <p class="text-[10px] text-gray-400">{{ $sale->items_count }} item</p>
              </div>
            </div>
            @empty
            <div class="py-4 text-center">
              <p class="text-xs text-gray-400">Belum ada transaksi</p>
            </div>
            @endforelse
          </div>
        </div>

        <!-- Products Sold -->
        <div class="card p-5 flex flex-col items-center justify-center gap-4 text-center">
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Jumlah Produk Terjual</p>
          <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl w-20 h-20 flex items-center justify-center shadow-lg shadow-orange-200">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
            </svg>
          </div>
          <div>
            <p class="text-4xl font-black text-gray-800 tracking-tighter">{{ number_format($totalItemsSold, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total produk terjual hari ini</p>
          </div>
        </div>
      </div>

      <!-- BOTTOM ROW — chart (2/3) + right cards (1/3) -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="card p-5 lg:col-span-2 flex flex-col">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-black text-gray-800">Pemasukan 7 Hari Terakhir</h2>
          </div>
          <div class="flex-1" style="min-height:200px; position:relative;">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>

        <div class="flex flex-col gap-4">
          <!-- Menu Terlaris -->
          <div class="card p-5 flex-1">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-sm font-black text-gray-800">Menu Terlaris</h2>
              <span class="text-[10px] text-gray-400">Hari ini</span>
            </div>
            <div class="flex flex-col gap-3">
              @php
                $barColors = ['bg-orange-500', 'bg-orange-400', 'bg-orange-300', 'bg-orange-200', 'bg-orange-100'];
              @endphp
              @forelse($topProducts as $index => $product)
              <div>
                <div class="flex justify-between text-xs mb-1.5">
                  <span class="font-bold text-gray-700">{{ $product->name }}</span>
                  <span class="font-black text-gray-800">{{ $product->total_qty }}x</span>
                </div>
                <div class="bg-gray-100 rounded-full h-1.5">
                  <div class="{{ $barColors[$index] ?? 'bg-orange-100' }} h-1.5 rounded-full" style="width:{{ $maxQty > 0 ? round(($product->total_qty / $maxQty) * 100) : 0 }}%"></div>
                </div>
              </div>
              @empty
              <div class="py-4 text-center">
                <p class="text-xs text-gray-400">Belum ada data</p>
              </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

    </main>
@endsection

@section('scripts')
<script>
  const ctx = document.getElementById('revenueChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [{
        label: 'Pemasukan',
        data: {!! json_encode($chartData) !!},
        borderColor: '#f97316',
        backgroundColor: function(context) {
          const chart = context.chart;
          const {ctx: c, chartArea} = chart;
          if (!chartArea) return 'rgba(249,115,22,0.1)';
          const gradient = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
          gradient.addColorStop(0, 'rgba(249,115,22,0.18)');
          gradient.addColorStop(1, 'rgba(249,115,22,0.01)');
          return gradient;
        },
        borderWidth: 2.5,
        pointBackgroundColor: '#fff',
        pointBorderColor: '#f97316',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
        pointHoverBackgroundColor: '#f97316',
        fill: true,
        tension: 0.4,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#1f2937',
          padding: 10,
          titleFont: { family: 'Nunito', size: 11, weight: '600' },
          bodyFont: { family: 'Nunito', size: 12, weight: '800' },
          callbacks: { label: c => ' Rp ' + c.raw.toLocaleString('id-ID') }
        }
      },
      scales: {
        y: {
          grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
          border: { display: false },
          ticks: {
            font: { size: 10, family: 'Nunito', weight: '600' },
            color: '#9ca3af',
            callback: function(v) {
              if (v >= 1000000) return 'Rp ' + (v / 1000000).toFixed(0) + 'jt';
              if (v >= 1000) return 'Rp ' + (v / 1000).toFixed(0) + 'rb';
              return 'Rp ' + v;
            },
            maxTicksLimit: 5,
          }
        },
        x: {
          grid: { display: false },
          border: { display: false },
          ticks: { font: { size: 10, family: 'Nunito', weight: '600' }, color: '#9ca3af' }
        }
      }
    }
  });
</script>
@endsection