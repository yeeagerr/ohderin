<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kasir & Inventory - OH!DERIN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|dm-serif-display:400i" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --orange-primary: #ea580c;
            --orange-light: #f97316;
            --orange-pale: #fff7ed;
            --text-dark: #1a1410;
            --text-mid: #4b3f35;
            --text-soft: #78716c;
            --border: #e7e2dd;
            --surface: #faf9f7;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: var(--surface);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
        }

        .display-font { font-family: 'DM Serif Display', Georgia, serif; font-style: italic; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.96); }
            to   { opacity: 1; transform: scale(1); }
        }
        @keyframes countUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .anim-fade-up  { animation: fadeUp  0.7s cubic-bezier(.22,.68,0,1.2) both; }
        .anim-fade-in  { animation: fadeIn  0.6s ease both; }
        .anim-scale-in { animation: scaleIn 0.6s cubic-bezier(.22,.68,0,1.2) both; }

        .delay-1 { animation-delay: .10s; }
        .delay-2 { animation-delay: .20s; }
        .delay-3 { animation-delay: .32s; }
        .delay-4 { animation-delay: .44s; }
        .delay-5 { animation-delay: .56s; }
        .delay-6 { animation-delay: .68s; }

        .hero-bg {
            background: radial-gradient(ellipse 80% 60% at 50% -10%, #fde8d8 0%, transparent 70%),
                        linear-gradient(180deg, #fff7f0 0%, #faf9f7 100%);
        }

        .orange-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fde8d8;
            color: var(--orange-primary);
            border: 1px solid #fed7aa;
            padding: 5px 14px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .03em;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--orange-primary);
            color: #fff;
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 20px rgba(234,88,12,.30);
        }
        .btn-primary:hover {
            background: #c2410c;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(234,88,12,.38);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #fff;
            color: var(--text-dark);
            border: 1.5px solid var(--border);
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: border-color .2s, background .2s, transform .15s;
        }
        .btn-secondary:hover {
            border-color: var(--orange-primary);
            color: var(--orange-primary);
            transform: translateY(-1px);
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px 32px;
            text-align: center;
            transition: box-shadow .25s, transform .25s;
        }
        .stat-card:hover {
            box-shadow: 0 8px 40px rgba(0,0,0,.07);
            transform: translateY(-2px);
        }

        .feature-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 36px 32px;
            transition: box-shadow .25s, transform .25s, border-color .25s;
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #fff7f0 0%, transparent 60%);
            opacity: 0;
            transition: opacity .3s;
        }
        .feature-card:hover {
            box-shadow: 0 12px 48px rgba(0,0,0,.09);
            transform: translateY(-3px);
            border-color: #fed7aa;
        }
        .feature-card:hover::before { opacity: 1; }
        .feature-card > * { position: relative; z-index: 1; }

        .icon-wrap {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #fff7ed 0%, #fde8d8 100%);
            border: 1px solid #fed7aa;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
        }

        .step-number {
            width: 44px;
            height: 44px;
            background: var(--orange-primary);
            color: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .step-connector {
            position: absolute;
            left: 22px;
            top: 44px;
            width: 2px;
            height: calc(100% + 24px);
            background: linear-gradient(to bottom, #fed7aa, transparent);
        }

        .testimonial-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 32px;
            transition: box-shadow .25s;
        }
        .testimonial-card:hover { box-shadow: 0 8px 40px rgba(0,0,0,.07); }

        .stars { color: #f97316; letter-spacing: 2px; }

        .section-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--orange-primary);
            margin-bottom: 14px;
        }

        .divider {
            width: 48px;
            height: 3px;
            background: linear-gradient(90deg, var(--orange-primary), var(--orange-light));
            border-radius: 4px;
            margin: 18px auto 0;
        }

        .pricing-card {
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 20px;
            padding: 40px 36px;
            transition: box-shadow .25s, transform .25s;
        }
        .pricing-card.featured {
            border-color: var(--orange-primary);
            background: linear-gradient(160deg, #fff7f0 0%, #fff 50%);
            box-shadow: 0 8px 40px rgba(234,88,12,.15);
        }
        .pricing-card:hover:not(.featured) {
            box-shadow: 0 8px 40px rgba(0,0,0,.07);
            transform: translateY(-2px);
        }

        .check-icon {
            width: 20px;
            height: 20px;
            background: #fde8d8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(250,249,247,.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }

        .nav-link {
            color: var(--text-mid);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 8px;
            transition: color .2s, background .2s;
        }
        .nav-link:hover { color: var(--orange-primary); background: #fff7ed; }

        .cta-section {
            background: var(--text-dark);
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 60% 70% at 70% 50%, rgba(234,88,12,.25) 0%, transparent 70%);
        }

        .faq-item {
            border-bottom: 1px solid var(--border);
            padding: 22px 0;
        }
        .faq-item:last-child { border-bottom: none; }

        .badge-pill {
            display: inline-block;
            background: var(--orange-pale);
            color: var(--orange-primary);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            padding: 3px 10px;
            border-radius: 100px;
            text-transform: uppercase;
        }

        .footer-link {
            color: #a8a29e;
            text-decoration: none;
            font-size: 14px;
            transition: color .2s;
        }
        .footer-link:hover { color: #e7e2dd; }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .6s ease, transform .6s cubic-bezier(.22,.68,0,1.2);
        }
        .scroll-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>

<header>
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-2.5">
                <div style="width:32px;height:32px;background:var(--orange-primary);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
            </div>
            <nav class="hidden md:flex items-center gap-1">
                <a href="#fitur" class="nav-link">Fitur</a>
                <a href="#cara-kerja" class="nav-link">Cara Kerja</a>
                <a href="#harga" class="nav-link">Harga</a>
                <a href="#faq" class="nav-link">FAQ</a>
            </nav>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary" style="padding:9px 20px;font-size:14px;">Dashboard →</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary" style="padding:9px 20px;font-size:14px;">Mulai Gratis →</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</header>

<main>

    <section class="hero-bg" style="padding: 96px 0 80px;">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <div class="anim-fade-up delay-1" style="margin-bottom:28px;">
                <span class="orange-badge">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
                    Dipercaya 2.000+ Restoran di Indonesia
                </span>
            </div>
            <h1 class="display-font anim-fade-up delay-2" style="font-size:clamp(2.6rem,6vw,4.5rem);line-height:1.1;color:var(--text-dark);margin-bottom:24px;max-width:820px;margin-left:auto;margin-right:auto;">
                Kelola Restoran Anda<br>dengan Satu Platform
            </h1>
            <p class="anim-fade-up delay-3" style="font-size:1.15rem;color:var(--text-soft);max-width:600px;margin:0 auto 44px;line-height:1.7;">
                Sistem Point of Sale dan manajemen inventori terintegrasi yang dirancang khusus untuk restoran modern. Dari kasir hingga laporan keuangan, semua dalam satu genggaman.
            </p>
            <div class="anim-fade-up delay-4" style="display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary">Akses Dashboard →</a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary">Mulai Gratis 14 Hari →</a>
                    <a href="{{ route('login') }}" class="btn-secondary">Sudah punya akun</a>
                @endauth
            </div>
            <p class="anim-fade-in delay-5" style="font-size:13px;color:var(--text-soft);margin-top:18px;">Tidak perlu kartu kredit &nbsp;·&nbsp; Setup 5 menit &nbsp;·&nbsp; Batalkan kapan saja</p>
        </div>

        <div class="max-w-5xl mx-auto px-6 lg:px-8" style="margin-top:72px;">
            <div class="anim-scale-in delay-5" style="background:#fff;border:1px solid var(--border);border-radius:20px;padding:40px;box-shadow:0 20px 80px rgba(0,0,0,.07);">
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--border);border-radius:12px;overflow:hidden;">
                    <div style="background:#fff;padding:28px 20px;text-align:center;">
                        <p class="display-font" style="font-size:2rem;color:var(--orange-primary);">2.000+</p>
                        <p style="font-size:13px;color:var(--text-soft);margin-top:4px;font-weight:500;">Restoran Aktif</p>
                    </div>
                    <div style="background:#fff;padding:28px 20px;text-align:center;">
                        <p class="display-font" style="font-size:2rem;color:var(--orange-primary);">500rb+</p>
                        <p style="font-size:13px;color:var(--text-soft);margin-top:4px;font-weight:500;">Transaksi / Bulan</p>
                    </div>
                    <div style="background:#fff;padding:28px 20px;text-align:center;">
                        <p class="display-font" style="font-size:2rem;color:var(--orange-primary);">99.9%</p>
                        <p style="font-size:13px;color:var(--text-soft);margin-top:4px;font-weight:500;">Uptime Server</p>
                    </div>
                    <div style="background:#fff;padding:28px 20px;text-align:center;">
                        <p class="display-font" style="font-size:2rem;color:var(--orange-primary);">4.9★</p>
                        <p style="font-size:13px;color:var(--text-soft);margin-top:4px;font-weight:500;">Rating Pengguna</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" style="padding:96px 0;background:#fff;">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center scroll-reveal" style="margin-bottom:64px;">
                <p class="section-label">Fitur Lengkap</p>
                <h2 class="display-font" style="font-size:clamp(2rem,4vw,3rem);color:var(--text-dark);margin-bottom:16px;">
                    Semua yang Anda Butuhkan
                </h2>
                <p style="color:var(--text-soft);font-size:1.05rem;max-width:540px;margin:0 auto;line-height:1.7;">
                    Platform kami mencakup seluruh operasional restoran dari depan hingga dapur, tanpa perlu software tambahan.
                </p>
                <div class="divider"></div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;" class="scroll-reveal">
                <div class="feature-card">
                    <div class="icon-wrap">
                        <svg width="22" height="22" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-dark);margin-bottom:12px;">Manajemen Menu</h3>
                    <p style="color:var(--text-soft);font-size:14px;line-height:1.7;">Kelola ratusan produk, paket bundling, dan variasi menu dengan antarmuka drag-and-drop yang intuitif. Atur kategori, harga, dan stok bahan baku secara otomatis berdasarkan resep.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap">
                        <svg width="22" height="22" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-dark);margin-bottom:12px;">Point of Sale Kasir</h3>
                    <p style="color:var(--text-soft);font-size:14px;line-height:1.7;">Antarmuka kasir yang bersih dan responsif untuk memproses pesanan dalam hitungan detik. Dukung berbagai metode pembayaran: tunai, QRIS, kartu debit, dan dompet digital populer.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap">
                        <svg width="22" height="22" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-dark);margin-bottom:12px;">Laporan & Analitik</h3>
                    <p style="color:var(--text-soft);font-size:14px;line-height:1.7;">Dapatkan insight mendalam tentang performa bisnis melalui dashboard visual. Lacak pendapatan harian, produk terlaris, jam sibuk, dan tren penjualan mingguan maupun bulanan.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap">
                        <svg width="22" height="22" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-dark);margin-bottom:12px;">Manajemen Inventori</h3>
                    <p style="color:var(--text-soft);font-size:14px;line-height:1.7;">Pantau stok bahan baku secara real-time dengan sistem stock opname digital. Terima notifikasi otomatis saat stok menipis dan catat riwayat penggunaan bahan untuk menghindari pemborosan.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap">
                        <svg width="22" height="22" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/></svg>
                    </div>
                    <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-dark);margin-bottom:12px;">Manajemen Karyawan</h3>
                    <p style="color:var(--text-soft);font-size:14px;line-height:1.7;">Atur hak akses berbasis peran untuk manajer, kasir, dan staf dapur. Pantau performa kasir, jadwal shift, dan rekap absensi langsung dari satu panel administrasi yang terpusat.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap">
                        <svg width="22" height="22" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-dark);margin-bottom:12px;">Manajemen Pelanggan</h3>
                    <p style="color:var(--text-soft);font-size:14px;line-height:1.7;">Bangun database pelanggan setia dengan sistem poin loyalitas terintegrasi. Kirim promo khusus, lacak riwayat pembelian, dan tingkatkan retensi pelanggan dengan program membership digital.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="cara-kerja" style="padding:96px 0;background:var(--surface);">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;">
                <div class="scroll-reveal">
                    <p class="section-label">Cara Kerja</p>
                    <h2 class="display-font" style="font-size:clamp(2rem,3.5vw,2.8rem);color:var(--text-dark);margin-bottom:16px;line-height:1.15;">
                        Mulai Berjalan dalam Hitungan Menit
                    </h2>
                    <p style="color:var(--text-soft);font-size:1rem;line-height:1.75;margin-bottom:40px;">
                        Tidak diperlukan keahlian teknis. Platform kami dirancang agar siapa pun dapat langsung mengoperasikannya tanpa pelatihan panjang.
                    </p>
                    <div style="display:flex;flex-direction:column;gap:0;">
                        <div style="display:flex;gap:20px;position:relative;padding-bottom:32px;">
                            <div style="display:flex;flex-direction:column;align-items:center;">
                                <div class="step-number">1</div>
                                <div style="width:2px;flex:1;background:linear-gradient(to bottom,#fed7aa,transparent);margin-top:8px;"></div>
                            </div>
                            <div style="padding-top:8px;">
                                <h4 style="font-weight:700;color:var(--text-dark);margin-bottom:6px;">Daftar & Atur Profil Restoran</h4>
                                <p style="color:var(--text-soft);font-size:14px;line-height:1.7;">Buat akun gratis, masukkan informasi restoran Anda, dan tentukan zona waktu serta mata uang operasional. Proses onboarding selesai dalam kurang dari 5 menit.</p>
                            </div>
                        </div>
                        <div style="display:flex;gap:20px;position:relative;padding-bottom:32px;">
                            <div style="display:flex;flex-direction:column;align-items:center;">
                                <div class="step-number">2</div>
                                <div style="width:2px;flex:1;background:linear-gradient(to bottom,#fed7aa,transparent);margin-top:8px;"></div>
                            </div>
                            <div style="padding-top:8px;">
                                <h4 style="font-weight:700;color:var(--text-dark);margin-bottom:6px;">Input Menu & Bahan Baku</h4>
                                <p style="color:var(--text-soft);font-size:14px;line-height:1.7;">Tambahkan produk menu beserta foto, harga, dan resep. Sistem akan otomatis menghitung pemakaian bahan baku setiap kali terjadi transaksi penjualan.</p>
                            </div>
                        </div>
                        <div style="display:flex;gap:20px;position:relative;padding-bottom:32px;">
                            <div style="display:flex;flex-direction:column;align-items:center;">
                                <div class="step-number">3</div>
                                <div style="width:2px;flex:1;background:linear-gradient(to bottom,#fed7aa,transparent);margin-top:8px;"></div>
                            </div>
                            <div style="padding-top:8px;">
                                <h4 style="font-weight:700;color:var(--text-dark);margin-bottom:6px;">Tambahkan Akun Staf</h4>
                                <p style="color:var(--text-soft);font-size:14px;line-height:1.7;">Undang kasir dan manajer dengan role yang berbeda. Setiap staf hanya dapat mengakses fitur sesuai dengan wewenangnya untuk keamanan data yang maksimal.</p>
                            </div>
                        </div>
                        <div style="display:flex;gap:20px;">
                            <div class="step-number">4</div>
                            <div style="padding-top:8px;">
                                <h4 style="font-weight:700;color:var(--text-dark);margin-bottom:6px;">Mulai Transaksi & Pantau Bisnis</h4>
                                <p style="color:var(--text-soft);font-size:14px;line-height:1.7;">Sistem kasir siap digunakan. Pantau pendapatan, stok, dan performa bisnis Anda secara real-time dari perangkat apa pun, kapan pun dan di mana pun Anda berada.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="scroll-reveal" style="background:#fff;border:1px solid var(--border);border-radius:24px;padding:40px;box-shadow:0 16px 64px rgba(0,0,0,.07);">
                    <div style="display:flex;gap:16px;align-items:center;margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid var(--border);">
                        <div style="width:42px;height:42px;background:#fde8d8;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <svg width="20" height="20" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                        </div>
                        <div>
                            <p style="font-weight:700;font-size:15px;color:var(--text-dark);">Dashboard Ringkasan Hari Ini</p>
                            <p style="font-size:12px;color:var(--text-soft);">Selasa, 28 April 2026</p>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px;">
                        <div style="background:var(--surface);border-radius:12px;padding:18px;">
                            <p style="font-size:12px;color:var(--text-soft);margin-bottom:4px;">Pendapatan Hari Ini</p>
                            <p class="display-font" style="font-size:1.6rem;color:var(--orange-primary);">Rp 4,8jt</p>
                            <p style="font-size:11px;color:#22c55e;margin-top:2px;">↑ 12% vs kemarin</p>
                        </div>
                        <div style="background:var(--surface);border-radius:12px;padding:18px;">
                            <p style="font-size:12px;color:var(--text-soft);margin-bottom:4px;">Total Transaksi</p>
                            <p class="display-font" style="font-size:1.6rem;color:var(--text-dark);">128</p>
                            <p style="font-size:11px;color:#22c55e;margin-top:2px;">↑ 8 lebih banyak</p>
                        </div>
                        <div style="background:var(--surface);border-radius:12px;padding:18px;">
                            <p style="font-size:12px;color:var(--text-soft);margin-bottom:4px;">Stok Hampir Habis</p>
                            <p class="display-font" style="font-size:1.6rem;color:#ef4444;">3</p>
                            <p style="font-size:11px;color:#ef4444;margin-top:2px;">Perlu restock segera</p>
                        </div>
                        <div style="background:var(--surface);border-radius:12px;padding:18px;">
                            <p style="font-size:12px;color:var(--text-soft);margin-bottom:4px;">Rating Pelanggan</p>
                            <p class="display-font" style="font-size:1.6rem;color:var(--text-dark);">4.9</p>
                            <p style="font-size:11px;color:#22c55e;margin-top:2px;">★ Sangat Baik</p>
                        </div>
                    </div>
                    <div style="background:var(--surface);border-radius:12px;padding:16px;">
                        <p style="font-size:12px;font-weight:600;color:var(--text-mid);margin-bottom:12px;">Menu Terlaris Hari Ini</p>
                        <div style="display:flex;flex-direction:column;gap:10px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:13px;color:var(--text-dark);">Nasi Goreng Spesial</span>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:80px;height:6px;background:#e7e2dd;border-radius:3px;overflow:hidden;"><div style="width:90%;height:100%;background:var(--orange-primary);border-radius:3px;"></div></div>
                                    <span style="font-size:12px;color:var(--text-soft);width:24px;text-align:right;">45x</span>
                                </div>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:13px;color:var(--text-dark);">Ayam Bakar Madu</span>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:80px;height:6px;background:#e7e2dd;border-radius:3px;overflow:hidden;"><div style="width:65%;height:100%;background:var(--orange-primary);border-radius:3px;"></div></div>
                                    <span style="font-size:12px;color:var(--text-soft);width:24px;text-align:right;">32x</span>
                                </div>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:13px;color:var(--text-dark);">Es Teh Tarik</span>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:80px;height:6px;background:#e7e2dd;border-radius:3px;overflow:hidden;"><div style="width:48%;height:100%;background:var(--orange-primary);border-radius:3px;"></div></div>
                                    <span style="font-size:12px;color:var(--text-soft);width:24px;text-align:right;">24x</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section style="padding:64px 0;background:#fff;border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="scroll-reveal" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--border);border-radius:16px;overflow:hidden;">
                <div style="background:#fff;padding:36px 24px;text-align:center;">
                    <div style="width:44px;height:44px;background:#fde8d8;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                        <svg width="20" height="20" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <p style="font-weight:700;font-size:15px;color:var(--text-dark);margin-bottom:6px;">Setup Instan</p>
                    <p style="font-size:13px;color:var(--text-soft);line-height:1.6;">Tidak butuh instalasi atau server khusus. Langsung gunakan dari browser.</p>
                </div>
                <div style="background:#fff;padding:36px 24px;text-align:center;">
                    <div style="width:44px;height:44px;background:#fde8d8;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                        <svg width="20" height="20" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <p style="font-weight:700;font-size:15px;color:var(--text-dark);margin-bottom:6px;">Keamanan Data</p>
                    <p style="font-size:13px;color:var(--text-soft);line-height:1.6;">Enkripsi SSL dan backup otomatis harian. Data Anda aman bersama kami.</p>
                </div>
                <div style="background:#fff;padding:36px 24px;text-align:center;">
                    <div style="width:44px;height:44px;background:#fde8d8;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                        <svg width="20" height="20" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <p style="font-weight:700;font-size:15px;color:var(--text-dark);margin-bottom:6px;">Dukungan 24/7</p>
                    <p style="font-size:13px;color:var(--text-soft);line-height:1.6;">Tim support siap membantu via live chat, WhatsApp, dan email setiap saat.</p>
                </div>
                <div style="background:#fff;padding:36px 24px;text-align:center;">
                    <div style="width:44px;height:44px;background:#fde8d8;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                        <svg width="20" height="20" fill="none" stroke="var(--orange-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <p style="font-weight:700;font-size:15px;color:var(--text-dark);margin-bottom:6px;">Update Rutin</p>
                    <p style="font-size:13px;color:var(--text-soft);line-height:1.6;">Fitur baru dan peningkatan kinerja dirilis setiap bulan tanpa biaya tambahan.</p>
                </div>
            </div>
        </div>
    </section>

    <section style="padding:96px 0;background:var(--surface);">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center scroll-reveal" style="margin-bottom:64px;">
                <p class="section-label">Testimoni</p>
                <h2 class="display-font" style="font-size:clamp(2rem,4vw,3rem);color:var(--text-dark);margin-bottom:16px;">Kata Mereka yang Sudah Merasakan</h2>
                <div class="divider"></div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;" class="scroll-reveal">
                <div class="testimonial-card">
                    <div class="stars" style="margin-bottom:14px;">★★★★★</div>
                    <p style="color:var(--text-mid);font-size:14px;line-height:1.75;margin-bottom:20px;">"Sejak pakai sistem ini, waktu kasir kami berkurang 40%. Fitur laporan penjualannya sangat detail sehingga saya bisa tahu persis produk mana yang paling menguntungkan setiap harinya."</p>
                    <div style="display:flex;align-items:center;gap:12px;padding-top:16px;border-top:1px solid var(--border);">
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,#fde8d8,#fed7aa);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;color:var(--orange-primary);">R</div>
                        <div>
                            <p style="font-weight:700;font-size:14px;color:var(--text-dark);">Rudi Hartono</p>
                            <p style="font-size:12px;color:var(--text-soft);">Pemilik Warung Makan Sederhana, Jakarta</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="stars" style="margin-bottom:14px;">★★★★★</div>
                    <p style="color:var(--text-mid);font-size:14px;line-height:1.75;margin-bottom:20px;">"Stok bahan baku kami dulu sering misterius habis entah kemana. Setelah pakai sistem ini, setiap bahan terlacak otomatis. Pemborosan turun drastis dan margin keuntungan naik signifikan."</p>
                    <div style="display:flex;align-items:center;gap:12px;padding-top:16px;border-top:1px solid var(--border);">
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,#fde8d8,#fed7aa);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;color:var(--orange-primary);">S</div>
                        <div>
                            <p style="font-weight:700;font-size:14px;color:var(--text-dark);">Sari Dewi Putri</p>
                            <p style="font-size:12px;color:var(--text-soft);">Manajer Café Kopi Nusantara, Surabaya</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="stars" style="margin-bottom:14px;">★★★★★</div>
                    <p style="color:var(--text-mid);font-size:14px;line-height:1.75;margin-bottom:20px;">"Saya bisa memantau 3 cabang restoran sekaligus dari smartphone. Laporan konsolidasi langsung tersaji tanpa perlu kumpulkan data manual. Ini mengubah cara saya mengelola bisnis sepenuhnya."</p>
                    <div style="display:flex;align-items:center;gap:12px;padding-top:16px;border-top:1px solid var(--border);">
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,#fde8d8,#fed7aa);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;color:var(--orange-primary);">B</div>
                        <div>
                            <p style="font-weight:700;font-size:14px;color:var(--text-dark);">Budi Setiawan</p>
                            <p style="font-size:12px;color:var(--text-soft);">Direktur Restoran Padang Jaya, Bandung</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="harga" style="padding:96px 0;background:#fff;">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center scroll-reveal" style="margin-bottom:64px;">
                <p class="section-label">Harga Transparan</p>
                <h2 class="display-font" style="font-size:clamp(2rem,4vw,3rem);color:var(--text-dark);margin-bottom:16px;">Pilih Paket yang Sesuai</h2>
                <p style="color:var(--text-soft);font-size:1.05rem;max-width:500px;margin:0 auto;">Harga tetap tanpa biaya tersembunyi. Upgrade atau downgrade kapan saja.</p>
                <div class="divider"></div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:960px;margin:0 auto;" class="scroll-reveal">
                <div class="pricing-card">
                    <p style="font-weight:700;font-size:13px;letter-spacing:.06em;color:var(--text-soft);text-transform:uppercase;margin-bottom:20px;">Starter</p>
                    <p class="display-font" style="font-size:2.4rem;color:var(--text-dark);margin-bottom:4px;">Rp 299rb</p>
                    <p style="font-size:13px;color:var(--text-soft);margin-bottom:28px;">per bulan</p>
                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:32px;">
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">1 outlet / kasir</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Hingga 100 produk menu</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Laporan harian & mingguan</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Manajemen inventori dasar</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Support via email</span></div>
                    </div>
                    <a href="{{ route('register') }}" class="btn-secondary" style="width:100%;justify-content:center;">Mulai Gratis</a>
                </div>
                <div class="pricing-card featured" style="position:relative;">
                    <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);">
                        <span class="badge-pill">Paling Populer</span>
                    </div>
                    <p style="font-weight:700;font-size:13px;letter-spacing:.06em;color:var(--orange-primary);text-transform:uppercase;margin-bottom:20px;">Professional</p>
                    <p class="display-font" style="font-size:2.4rem;color:var(--text-dark);margin-bottom:4px;">Rp 699rb</p>
                    <p style="font-size:13px;color:var(--text-soft);margin-bottom:28px;">per bulan</p>
                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:32px;">
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Hingga 3 outlet / kasir</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Produk menu tidak terbatas</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Laporan & analitik lengkap</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Manajemen inventori + resep</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Program loyalitas pelanggan</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Support prioritas 24/7</span></div>
                    </div>
                    <a href="{{ route('register') }}" class="btn-primary" style="width:100%;justify-content:center;">Mulai 14 Hari Gratis →</a>
                </div>
                <div class="pricing-card">
                    <p style="font-weight:700;font-size:13px;letter-spacing:.06em;color:var(--text-soft);text-transform:uppercase;margin-bottom:20px;">Enterprise</p>
                    <p class="display-font" style="font-size:2.4rem;color:var(--text-dark);margin-bottom:4px;">Custom</p>
                    <p style="font-size:13px;color:var(--text-soft);margin-bottom:28px;">Hubungi kami</p>
                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:32px;">
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Outlet tidak terbatas</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Laporan multi-cabang konsolidasi</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Integrasi API & sistem POS lama</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Onboarding & pelatihan staf</span></div>
                        <div style="display:flex;align-items:center;gap:10px;"><div class="check-icon"><svg width="10" height="10" fill="none" stroke="var(--orange-primary)" stroke-width="2.5" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5"/></svg></div><span style="font-size:14px;color:var(--text-mid);">Dedicated account manager</span></div>
                    </div>
                    <a href="mailto:sales@restoran-pos.id" class="btn-secondary" style="width:100%;justify-content:center;">Hubungi Sales</a>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" style="padding:96px 0;background:var(--surface);">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <div class="text-center scroll-reveal" style="margin-bottom:64px;">
                <p class="section-label">FAQ</p>
                <h2 class="display-font" style="font-size:clamp(2rem,4vw,3rem);color:var(--text-dark);margin-bottom:16px;">Pertanyaan yang Sering Ditanyakan</h2>
                <div class="divider"></div>
            </div>
            <div class="scroll-reveal" style="background:#fff;border:1px solid var(--border);border-radius:20px;padding:8px 40px;">
                <div class="faq-item">
                    <p style="font-weight:700;font-size:15px;color:var(--text-dark);margin-bottom:8px;">Apakah saya perlu menginstal aplikasi khusus?</p>
                    <p style="font-size:14px;color:var(--text-soft);line-height:1.7;">Tidak perlu. Restoran POS berjalan sepenuhnya di browser modern seperti Chrome, Safari, atau Firefox. Tidak diperlukan instalasi apapun, cukup buka dan langsung gunakan dari perangkat apa pun.</p>
                </div>
                <div class="faq-item">
                    <p style="font-weight:700;font-size:15px;color:var(--text-dark);margin-bottom:8px;">Bagaimana jika internet sedang bermasalah saat transaksi?</p>
                    <p style="font-size:14px;color:var(--text-soft);line-height:1.7;">Sistem kami memiliki mode offline yang memungkinkan kasir tetap memproses transaksi meski tanpa koneksi internet. Data akan disinkronkan otomatis begitu koneksi kembali tersedia.</p>
                </div>
                <div class="faq-item">
                    <p style="font-weight:700;font-size:15px;color:var(--text-dark);margin-bottom:8px;">Apakah data saya aman dan terlindungi?</p>
                    <p style="font-size:14px;color:var(--text-soft);line-height:1.7;">Ya. Semua data dienkripsi dengan standar SSL/TLS industri dan disimpan di server dengan backup otomatis setiap hari. Kami tidak pernah membagikan data Anda kepada pihak ketiga.</p>
                </div>
                <div class="faq-item">
                    <p style="font-weight:700;font-size:15px;color:var(--text-dark);margin-bottom:8px;">Bisakah saya migrasi data dari sistem lama?</p>
                    <p style="font-size:14px;color:var(--text-soft);line-height:1.7;">Bisa. Tim kami akan membantu proses migrasi data dari sistem POS lama Anda, termasuk data menu, pelanggan, dan riwayat transaksi. Layanan migrasi ini tersedia gratis untuk paket Professional dan Enterprise.</p>
                </div>
                <div class="faq-item">
                    <p style="font-weight:700;font-size:15px;color:var(--text-dark);margin-bottom:8px;">Apakah ada kontrak jangka panjang yang harus ditandatangani?</p>
                    <p style="font-size:14px;color:var(--text-soft);line-height:1.7;">Tidak ada. Semua paket berjalan secara bulanan tanpa komitmen jangka panjang. Anda bebas membatalkan, upgrade, atau downgrade paket kapan saja tanpa biaya penalti.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section" style="padding:96px 0;">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center" style="position:relative;z-index:1;">
            <div class="scroll-reveal">
                <p class="section-label" style="color:#fb923c;">Mulai Sekarang</p>
                <h2 class="display-font" style="font-size:clamp(2rem,4.5vw,3.5rem);color:#fff;margin-bottom:20px;line-height:1.15;">
                    Siap Membawa Restoran Anda ke Level Berikutnya?
                </h2>
                <p style="color:#a8a29e;font-size:1.05rem;max-width:520px;margin:0 auto 44px;line-height:1.7;">
                    Bergabunglah dengan ribuan pemilik restoran yang sudah membuktikan efisiensi dan pertumbuhan bisnis bersama platform kami.
                </p>
                <div style="display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary">Masuk ke Dashboard →</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary">Coba Gratis 14 Hari →</a>
                        <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;justify-content:center;color:#e7e2dd;font-weight:600;font-size:15px;text-decoration:none;padding:14px 30px;border:1.5px solid #44403c;border-radius:10px;transition:border-color .2s,color .2s;">Sudah Punya Akun</a>
                    @endauth
                </div>
                <p style="font-size:13px;color:#78716c;margin-top:18px;">Tidak perlu kartu kredit &nbsp;·&nbsp; Batalkan kapan saja &nbsp;·&nbsp; Support 24/7</p>
            </div>
        </div>
    </section>

</main>

<footer style="background:#0f0a08;padding:60px 0 32px;">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:48px;margin-bottom:48px;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                    <div style="width:32px;height:32px;background:var(--orange-primary);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </div>
                    <span style="font-weight:700;font-size:16px;color:#faf9f7;">Restoran POS</span>
                </div>
                <p style="font-size:14px;color:#78716c;line-height:1.7;max-width:280px;">Platform manajemen restoran terpercaya untuk bisnis kuliner Indonesia yang ingin tumbuh lebih cepat dan lebih efisien.</p>
            </div>
            <div>
                <p style="font-size:12px;font-weight:700;letter-spacing:.1em;color:#a8a29e;text-transform:uppercase;margin-bottom:16px;">Produk</p>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a href="#fitur" class="footer-link">Fitur</a>
                    <a href="#harga" class="footer-link">Harga</a>
                    <a href="#cara-kerja" class="footer-link">Cara Kerja</a>
                    <a href="#faq" class="footer-link">FAQ</a>
                </div>
            </div>
            <div>
                <p style="font-size:12px;font-weight:700;letter-spacing:.1em;color:#a8a29e;text-transform:uppercase;margin-bottom:16px;">Perusahaan</p>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a href="#" class="footer-link">Tentang Kami</a>
                    <a href="#" class="footer-link">Blog</a>
                    <a href="#" class="footer-link">Karir</a>
                    <a href="#" class="footer-link">Hubungi Kami</a>
                </div>
            </div>
            <div>
                <p style="font-size:12px;font-weight:700;letter-spacing:.1em;color:#a8a29e;text-transform:uppercase;margin-bottom:16px;">Legal</p>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a href="#" class="footer-link">Kebijakan Privasi</a>
                    <a href="#" class="footer-link">Syarat & Ketentuan</a>
                    <a href="#" class="footer-link">Keamanan Data</a>
                </div>
            </div>
        </div>
        <div style="border-top:1px solid #1c1917;padding-top:28px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <p style="font-size:13px;color:#57534e;">&copy; 2026 Sistem Manajemen Restoran. Seluruh hak cipta dilindungi.</p>
            <p style="font-size:13px;color:#57534e;">Dibuat untuk kemajuan bisnis kuliner Indonesia 🇮🇩</p>
        </div>
    </div>
</footer>

<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>

</body>
</html>