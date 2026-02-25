@extends('layouts.dashboard_layout')

@section('title', 'Dashboard')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 sm:p-6">
      <h1 class="text-2xl sm:text-3xl font-black text-gray-800 mb-5 tracking-tight">DASHBOARD</h1>

      <!-- STAT CARDS — 4 equal columns -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div class="card p-4 flex flex-col justify-between" style="min-height:120px">
          <div class="flex items-start justify-between gap-1">
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Persentase</p>
              <p class="text-[11px] text-gray-400">NAIK jan</p>
            </div>
            <span class="text-[10px] font-bold text-green-500 bg-green-50 px-1.5 py-0.5 rounded-full">↗ 62.8%</span>
          </div>
          <div>
            <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,28 25,20 50,14 75,8 100,2" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <p class="text-xl font-black text-gray-800">100.000</p>
          </div>
        </div>
        <div class="card p-4 flex flex-col justify-between" style="min-height:120px">
          <div class="flex items-start justify-between gap-1">
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Persentase</p>
              <p class="text-[11px] text-gray-400">NAIK feb</p>
            </div>
            <span class="text-[10px] font-bold text-green-500 bg-green-50 px-1.5 py-0.5 rounded-full">↗ 62.8%</span>
          </div>
          <div>
            <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,26 25,16 45,24 70,10 100,6" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <p class="text-xl font-black text-gray-800">72.000</p>
          </div>
        </div>
        <div class="card p-4 flex flex-col justify-between" style="min-height:120px">
          <div class="flex items-start justify-between gap-1">
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Persentase</p>
              <p class="text-[11px] text-gray-400">TURUN mar</p>
            </div>
            <span class="text-[10px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-full">↘ 78.8%</span>
          </div>
          <div>
            <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,2 25,10 50,18 75,24 100,30" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <p class="text-xl font-black text-gray-800">-72.000</p>
          </div>
        </div>
        <div class="card p-4 flex flex-col justify-between" style="min-height:120px">
          <div class="flex items-start justify-between gap-1">
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Persentase</p>
              <p class="text-[11px] text-gray-400">TURUN april</p>
            </div>
            <span class="text-[10px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-full">↘ 8.6%</span>
          </div>
          <div>
            <svg viewBox="0 0 100 32" class="w-full mb-1"><polyline points="0,12 30,16 50,10 75,22 100,28" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <p class="text-xl font-black text-gray-800">-2.000</p>
          </div>
        </div>
      </div>

      <!-- MIDDLE ROW — 3 equal columns -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <!-- Profile -->
        <div class="card p-5 flex flex-col gap-4">
          <div class="flex items-center justify-between">
            <h2 class="text-base font-black text-gray-800">Profil</h2>
            <button class="relative text-gray-400 hover:text-orange-500 transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
              <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-orange-400 rounded-full"></span>
            </button>
          </div>
          <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl p-4 text-center text-white">
            <div class="w-10 h-10 rounded-full bg-white/25 mx-auto mb-2 flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <p class="font-bold text-sm">alexanderrrr</p>
            <p class="text-xs opacity-75 mt-0.5">iwkdsama@gmail.com</p>
          </div>
          <div class="flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-500">Tayangan Profil</p>
            <span class="text-xs text-orange-500 font-bold">Lihat →</span>
          </div>
        </div>

        <!-- Transactions -->
        <div class="card p-5 flex flex-col gap-2">
          <div class="flex items-center justify-between mb-1">
            <h2 class="text-base font-black text-gray-800">Transaksi Hari Ini</h2>
            <span class="text-xs text-orange-500 font-bold bg-orange-50 px-2 py-0.5 rounded-full">4 tx</span>
          </div>
          <div class="divide-y divide-gray-50">
            <div class="flex justify-between items-center py-2.5">
              <div><p class="text-xs font-bold text-gray-700">BRI128293890</p><p class="text-[10px] text-gray-400">11/02/2026 06:00</p></div>
              <div class="text-right"><p class="text-xs font-bold text-gray-800">Rp 200.000</p><p class="text-[10px] text-gray-400">4 item</p></div>
            </div>
            <div class="flex justify-between items-center py-2.5">
              <div><p class="text-xs font-bold text-gray-700">BCA738239139</p><p class="text-[10px] text-gray-400">11/02/2026 06:00</p></div>
              <div class="text-right"><p class="text-xs font-bold text-gray-800">Rp 70.000</p><p class="text-[10px] text-gray-400">2 item</p></div>
            </div>
            <div class="flex justify-between items-center py-2.5">
              <div><p class="text-xs font-bold text-gray-700">DANA0823237236</p><p class="text-[10px] text-gray-400">11/02/2026 06:00</p></div>
              <div class="text-right"><p class="text-xs font-bold text-gray-800">Rp 20.000</p><p class="text-[10px] text-gray-400">1 item</p></div>
            </div>
            <div class="flex justify-between items-center py-2.5">
              <div><p class="text-xs font-bold text-gray-700">SEA BANK32783612</p><p class="text-[10px] text-gray-400">11/02/2026 08:00</p></div>
              <div class="text-right"><p class="text-xs font-bold text-gray-800">Rp 90.000</p><p class="text-[10px] text-gray-400">3 item</p></div>
            </div>
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
            <p class="text-4xl font-black text-gray-800 tracking-tighter">19.000</p>
            <p class="text-xs text-gray-400 mt-1">Total produk terjual hari ini</p>
          </div>
        </div>
      </div>

      <!-- BOTTOM ROW — chart (2/3) + right cards (1/3) -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="card p-5 lg:col-span-2 flex flex-col">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-black text-gray-800">Pemasukan 1 Week</h2>
            <div class="flex gap-2">
              <button class="text-xs font-bold text-white bg-orange-500 px-3 py-1 rounded-full">Minggu</button>
              <button class="text-xs font-bold text-gray-400 hover:text-orange-500 px-3 py-1 rounded-full hover:bg-orange-50 transition">Bulan</button>
            </div>
          </div>
          <div class="flex-1" style="min-height:200px; position:relative;">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>

        <div class="flex flex-col gap-4">
          <!-- Table occupancy -->
          <div class="card p-5 flex flex-col items-center justify-center text-center flex-1">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em] mb-2">Meja Terisi</p>
            <p class="text-5xl font-black text-gray-800 tracking-tighter">20<span class="text-gray-300">/</span>25</p>
            <div class="w-full bg-gray-100 rounded-full h-2 mt-3">
              <div class="bg-orange-500 h-2 rounded-full transition-all" style="width:80%"></div>
            </div>
            <p class="text-[11px] text-gray-400 mt-1.5">80% kapasitas terisi</p>
          </div>

          <!-- Best menu -->
          <div class="card p-5 flex-1">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-sm font-black text-gray-800">Menu Terlaris</h2>
              <span class="text-[10px] text-gray-400">Hari ini</span>
            </div>
            <div class="flex flex-col gap-3">
              <div>
                <div class="flex justify-between text-xs mb-1.5">
                  <span class="font-bold text-gray-700">🍳 Nasi Goreng</span>
                  <span class="font-black text-gray-800">42x</span>
                </div>
                <div class="bg-gray-100 rounded-full h-1.5"><div class="bg-orange-500 h-1.5 rounded-full" style="width:84%"></div></div>
              </div>
              <div>
                <div class="flex justify-between text-xs mb-1.5">
                  <span class="font-bold text-gray-700">🍜 Mie Ayam</span>
                  <span class="font-black text-gray-800">35x</span>
                </div>
                <div class="bg-gray-100 rounded-full h-1.5"><div class="bg-orange-400 h-1.5 rounded-full" style="width:70%"></div></div>
              </div>
              <div>
                <div class="flex justify-between text-xs mb-1.5">
                  <span class="font-bold text-gray-700">🧋 Es Teh Manis</span>
                  <span class="font-black text-gray-800">28x</span>
                </div>
                <div class="bg-gray-100 rounded-full h-1.5"><div class="bg-orange-300 h-1.5 rounded-full" style="width:56%"></div></div>
              </div>
              <div>
                <div class="flex justify-between text-xs mb-1.5">
                  <span class="font-bold text-gray-700">☕ Kopi Susu</span>
                  <span class="font-black text-gray-800">19x</span>
                </div>
                <div class="bg-gray-100 rounded-full h-1.5"><div class="bg-orange-200 h-1.5 rounded-full" style="width:38%"></div></div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>
@endsection