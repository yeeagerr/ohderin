<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Dashboard Ohderin')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="flex h-screen overflow-hidden">

  <div id="overlay" onclick="toggleSidebar()"></div>

  <!-- Sidebar -->
  <aside id="sidebar" class="sidebar-bg w-56 shrink-0 flex flex-col py-6 px-4 text-white h-full">
    <div class="flex items-center gap-2 px-2 mb-8">
      <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/></svg>
      </div>
      <span class="nav-title text-base font-bold">RESTO APP</span>
    </div>

    <div class="mb-6">
      <p class="text-[10px] font-bold uppercase tracking-[0.15em] opacity-60 mb-2 px-2">Platform</p>
      <nav class="flex flex-col gap-0.5">
        <a href="#" class="nav-link active-nav flex items-center gap-3 px-3 py-2.5">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/></svg>
          <span class="nav-title text-sm">DASHBOARD</span>
        </a>
        <a href="{{ route('kasir.order') }}" class="nav-link flex items-center gap-3 px-3 py-2.5">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
          <span class="nav-title text-sm">KASIR</span>
        </a>
      </nav>
    </div>

    <div class="mb-6">
      <p class="text-[10px] font-bold uppercase tracking-[0.15em] opacity-60 mb-2 px-2">Manajemen</p>
      <nav class="flex flex-col gap-0.5">
        <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
          <span class="nav-title text-sm">KATEGORI</span>
        </a>
        <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
          <span class="nav-title text-sm">MENU</span>
        </a>
        <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a1 1 0 011-1h6a1 1 0 011 1v2"/></svg>
          <span class="nav-title text-sm">MEJA</span>
        </a>
        <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93A10 10 0 0012 2v2M4.93 4.93A10 10 0 002 12h2M19.07 19.07A10 10 0 0112 22v-2M4.93 19.07A10 10 0 012 12h2"/></svg>
          <span class="nav-title text-sm">STATUS MEJA</span>
        </a>
      </nav>
    </div>

    <div class="mb-6">
      <p class="text-[10px] font-bold uppercase tracking-[0.15em] opacity-60 mb-2 px-2">Laporan</p>
      <nav class="flex flex-col gap-0.5">
        <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
          <span class="nav-title text-sm">TRANSAKSI</span>
        </a>
        <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
          <span class="nav-title text-sm">LAPORAN PENJUALAN</span>
        </a>
      </nav>
    </div>

    <div class="mt-auto relative z-10">
      <div class="h-px bg-white/20 mb-4"></div>
      <a href="#" class="nav-link flex items-center gap-3 px-3 py-2.5">
        <div class="w-8 h-8 rounded-full bg-white/25 flex items-center justify-center shrink-0">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div>
          <p class="nav-title text-sm leading-none">ADMIN</p>
          <p class="text-[10px] opacity-60 mt-0.5">Super Admin</p>
        </div>
      </a>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
    <!-- Topbar -->
    <header class="bg-white border-b border-gray-100 px-4 sm:px-6 py-3 flex items-center justify-between shrink-0 gap-3">
      <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-orange-500 shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <div class="relative flex-1 max-w-xs">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" placeholder="Cari sesuatu..." class="w-full pl-9 pr-3 py-2 rounded-lg bg-gray-100 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 transition"/>
      </div>
      <div class="flex items-center gap-3">
        <button class="relative text-gray-500 hover:text-orange-500 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
          <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-orange-500 rounded-full"></span>
        </button>
        <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center shrink-0">
          <span class="text-white text-xs font-bold">A</span>
        </div>
      </div>
    </header>

    <!-- Content -->
    @yield('content')
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('open');
      document.getElementById('overlay').classList.toggle('show');
    }

    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        datasets: [{
          label: 'Pemasukan',
          data: [1500000, 3000000, 5900000, 5000000, 3000000, 1900000, 4900000],
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
            callbacks: { label: c => ' Rp ' + (c.raw / 1000000).toFixed(1) + ' jt' }
          }
        },
        scales: {
          y: {
            grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
            border: { display: false },
            ticks: {
              font: { size: 10, family: 'Nunito', weight: '600' },
              color: '#9ca3af',
              callback: v => 'Rp ' + (v / 1000000).toFixed(0) + 'jt',
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
</body>
</html>