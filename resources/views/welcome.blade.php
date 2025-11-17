<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Do-Account - Sistem Akuntansi Modern & Terpercaya</title>
    <meta name="description" content="Kelola keuangan bisnis Anda lebih mudah & akurat. Jurnal, laporan keuangan, invoice, dan dashboard analitik dalam satu platform monokrom yang modern.">
    <link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
    <meta name="theme-color" content="#000000">
    <!-- Open Graph / Facebook -->
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Do-Account">
    <meta property="og:title" content="Do-Account — Sistem Akuntansi Modern & Terpercaya">
    <meta property="og:description" content="Jurnal, laporan keuangan, invoice, dan dashboard analitik. Monokrom, modern, dan cepat.">
    <meta property="og:url" content="http://localhost/seb/gocount/">
    <meta property="og:image" content="https://placehold.co/1200x630/000/FFF?text=Do-Account%20%E2%80%94%20Akuntansi%20UMKM">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Do-Account — Sistem Akuntansi Modern & Terpercaya">
    <meta name="twitter:description" content="Jurnal, laporan keuangan, invoice, dan dashboard analitik. Monokrom, modern, dan cepat.">
    <meta name="twitter:image" content="https://placehold.co/1200x630/000/FFF?text=Do-Account%20%E2%80%94%20Akuntansi%20UMKM">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        @keyframes pulse { 0%,100%{ transform: scale(1);} 50%{ transform: scale(1.05);} }
        @keyframes blink { 0%,100%{ opacity: 1;} 50%{ opacity: .3;} }
        @keyframes float { 0%,100%{ transform: translateY(0) rotate(0deg);} 50%{ transform: translateY(-20px) rotate(180deg);} }
        @keyframes gridMove { 0%{ transform: translate(0,0);} 100%{ transform: translate(50px,50px);} }
        @keyframes scroll { 0%{ opacity: 1; transform: translateX(-50%) translateY(0);} 100%{ opacity: 0; transform: translateX(-50%) translateY(15px);} }
        @keyframes fadeIn { from{opacity:0;} to{opacity:1;} }
        @keyframes slideUp { from{opacity:0; transform: translateY(30px);} to{opacity:1; transform: translateY(0);} }
        @keyframes ripple-animation { to { transform: scale(4); opacity: 0; } }
        .fade-in { animation: fadeIn 1s ease-out; }
        .fade-in-delay { animation: fadeIn 1s ease-out .3s both; }
        .fade-in-delay-2 { animation: fadeIn 1s ease-out .6s both; }
        .fade-in-delay-3 { animation: fadeIn 1s ease-out .9s both; }
        .slide-up { animation: slideUp 1s ease-out; }
        .logo-circle { animation: pulse 2s infinite; }
        .badge-dot { animation: blink 2s infinite; }
        .grid-pattern { animation: gridMove 20s linear infinite; }
        .shape-1 { animation: float 15s ease-in-out infinite; }
        .shape-2 { animation: float 20s ease-in-out infinite reverse; }
        .shape-3 { animation: float 12s ease-in-out infinite; }
        .mouse::before { animation: scroll 2s infinite; }
        .ripple { position: absolute; border-radius: 50%; background: rgba(255,255,255,.3); transform: scale(0); animation: ripple-animation .6s ease-out; pointer-events: none; }
        .nav-menu a.active { color: #000; font-weight: 600; }
        .nav-menu a.active::after { width: 100%; }
        .modal-backdrop { background-color: rgba(0, 0, 0, 0.5); }
        .modal { display: none; }
        .modal.show { display: flex; }
    </style>
</head>
<body class="font-sans text-black bg-white overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-lg border-b border-gray-200 z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-5 flex justify-between items-center py-4">
            <div class="flex items-center gap-3 font-bold text-2xl text-black">
                <span class="text-2xl font-extrabold">Do-Account</span>
            </div>
            <ul class="hidden md:flex list-none gap-8" id="navMenu">
                <li><a href="#home" class="text-black font-medium transition-colors duration-300 relative hover:text-black after:content-[''] after:absolute after:bottom-[-5px] after:left-0 after:w-0 after:h-0.5 after:bg-black after:transition-all after:duration-300 hover:after:w-full">Beranda</a></li>
                <li><a href="#features" class="text-black font-medium transition-colors duration-300 relative hover:text-black after:content-[''] after:absolute after:bottom-[-5px] after:left-0 after:w-0 after:h-0.5 after:bg-black after:transition-all after:duration-300 hover:after:w-full">Fitur</a></li>
                <li><a href="#pricing" class="text-black font-medium transition-colors duration-300 relative hover:text-black after:content-[''] after:absolute after:bottom-[-5px] after:left-0 after:w-0 after:h-0.5 after:bg-black after:transition-all after:duration-300 hover:after:w-full">Paket</a></li>
                <li><a href="#contact" class="text-black font-medium transition-colors duration-300 relative hover:text-black after:content-[''] after:absolute after:bottom-[-5px] after:left-0 after:w-0 after:h-0.5 after:bg-black after:transition-all after:duration-300 hover:after:w-full">Kontak</a></li>
            </ul>
            <button class="md:hidden flex flex-col gap-1.5 bg-transparent border-none cursor-pointer p-1.5" id="navToggle">
                <span class="w-6 h-0.5 bg-black transition-all duration-300"></span>
                <span class="w-6 h-0.5 bg-black transition-all duration-300"></span>
                <span class="w-6 h-0.5 bg-black transition-all duration-300"></span>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center relative pt-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 grid-pattern bg-[linear-gradient(rgba(0,0,0,.03)_1px,transparent_1px),linear-gradient(90deg,rgba(0,0,0,.03)_1px,transparent_1px)] bg-[length:50px_50px]"></div>
            <div class="absolute w-full h-full">
                <div class="absolute w-[300px] h-[300px] top-[10%] right-[10%] border border-black/5 rounded-full shape-1"></div>
                <div class="absolute w-[200px] h-[200px] bottom-[20%] left-[5%] border border-black/5 rounded-full shape-2"></div>
                <div class="absolute w-[150px] h-[150px] top-1/2 right-[20%] border border-black/5 rounded-full shape-3"></div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-5 w-full">
            <div class="relative z-10 text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-full text-sm mb-8 font-medium fade-in">
                    <span class="w-2 h-2 bg-black rounded-full badge-dot"></span>
                    Sistem Akuntansi Modern & Terpercaya
                </div>
                <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-6 text-black slide-up">
                    Kelola Keuangan Bisnis Anda
                    <span class="block bg-gradient-to-br from-black to-gray-600 bg-clip-text text-transparent">Lebih Mudah & Akurat</span>
                </h1>
                <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto fade-in-delay">
                    Solusi akuntansi lengkap untuk bisnis Anda. Dari jurnal hingga laporan keuangan, 
                    semua dalam satu platform yang mudah digunakan.
                </p>
                <div class="flex gap-4 justify-center mb-16 flex-wrap fade-in-delay-2">
                    <a href="#" class="px-8 py-3.5 rounded-full font-semibold text-base transition-all duration-300 border-2 border-transparent bg-black text-white hover:-translate-y-0.5 hover:shadow-[0_10px_25px_rgba(0,0,0,.2)] relative overflow-hidden" id="startBtn">Mulai Sekarang</a>
                    <a href="#features" class="px-8 py-3.5 rounded-full font-semibold text-base transition-all duration-300 border-2 border-black text-black hover:bg-black hover:text-white relative overflow-hidden">Pelajari Lebih Lanjut</a>
                </div>
                <div class="flex justify-center gap-16 flex-wrap fade-in-delay-3">
                    <div class="text-center">
                        <div class="text-5xl font-extrabold text-black mb-2 stat-number" data-target="500">0</div>
                        <div class="text-sm text-gray-600 uppercase tracking-wider">Pengguna Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-5xl font-extrabold text-black mb-2 stat-number" data-target="10000">0</div>
                        <div class="text-sm text-gray-600 uppercase tracking-wider">Transaksi/Bulan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-5xl font-extrabold text-black mb-2 stat-number" data-target="99">0</div>
                        <div class="text-sm text-gray-600 uppercase tracking-wider">% Kepuasan</div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-center">
                <img src="{{ asset('storage/hero-dashboard.svg') }}" alt="Ilustrasi dashboard keuangan Do-Account" class="max-w-4xl w-full h-auto border border-gray-200 rounded-2xl shadow-[0_20px_40px_rgba(0,0,0,.06)]"/>
            </div>
        </div>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10">
            <div class="w-6 h-10 border-2 border-black rounded-[15px] relative mouse">
                <div class="absolute top-2 left-1/2 -translate-x-1/2 w-1 h-2 bg-black rounded-sm"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-5">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-6xl font-extrabold mb-4 text-black">Fitur Lengkap untuk Bisnis Anda</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Semua yang Anda butuhkan untuk mengelola keuangan bisnis dalam satu platform
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-3xl border border-gray-200 transition-all duration-300 relative overflow-hidden hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,0,0,.1)] hover:border-black feature-card">
                    <div class="relative w-15 h-15 mb-6">
                        <div class="absolute w-15 h-15 bg-gray-100 rounded-full transition-all duration-300 icon-circle"></div>
                        <svg class="relative z-10 w-8 h-8 m-[15px] stroke-black transition-all duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-black">Jurnal & Buku Besar</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Buat jurnal dengan mudah, kelola buku besar, dan pantau semua transaksi keuangan Anda secara real-time.
                    </p>
                </div>
                <div class="bg-white p-10 rounded-3xl border border-gray-200 transition-all duration-300 relative overflow-hidden hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,0,0,.1)] hover:border-black feature-card">
                    <div class="relative w-15 h-15 mb-6">
                        <div class="absolute w-15 h-15 bg-gray-100 rounded-full transition-all duration-300 icon-circle"></div>
                        <svg class="relative z-10 w-8 h-8 m-[15px] stroke-black transition-all duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3h18v18H3z"></path>
                            <path d="M3 9h18"></path>
                            <path d="M9 3v18"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-black">Laporan Keuangan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Generate laporan laba rugi, neraca, dan arus kas secara otomatis dengan format profesional.
                    </p>
                </div>
                <div class="bg-white p-10 rounded-3xl border border-gray-200 transition-all duration-300 relative overflow-hidden hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,0,0,.1)] hover:border-black feature-card">
                    <div class="relative w-15 h-15 mb-6">
                        <div class="absolute w-15 h-15 bg-gray-100 rounded-full transition-all duration-300 icon-circle"></div>
                        <svg class="relative z-10 w-8 h-8 m-[15px] stroke-black transition-all duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path>
                            <path d="M14 2v6h6"></path>
                            <path d="M16 13H8"></path>
                            <path d="M16 17H8"></path>
                            <path d="M10 9H8"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-black">Invoice & Dokumen</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Buat invoice, offering, dan surat jalan dengan template profesional dan export ke PDF/Excel.
                    </p>
                </div>
                <div class="bg-white p-10 rounded-3xl border border-gray-200 transition-all duration-300 relative overflow-hidden hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,0,0,.1)] hover:border-black feature-card">
                    <div class="relative w-15 h-15 mb-6">
                        <div class="absolute w-15 h-15 bg-gray-100 rounded-full transition-all duration-300 icon-circle"></div>
                        <svg class="relative z-10 w-8 h-8 m-[15px] stroke-black transition-all duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-black">Dashboard Analytics</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pantau performa bisnis dengan dashboard interaktif dan grafik visual yang mudah dipahami.
                    </p>
                </div>
                <div class="bg-white p-10 rounded-3xl border border-gray-200 transition-all duration-300 relative overflow-hidden hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,0,0,.1)] hover:border-black feature-card">
                    <div class="relative w-15 h-15 mb-6">
                        <div class="absolute w-15 h-15 bg-gray-100 rounded-full transition-all duration-300 icon-circle"></div>
                        <svg class="relative z-10 w-8 h-8 m-[15px] stroke-black transition-all duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-black">Keamanan Data</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Data keuangan Anda aman dengan enkripsi tingkat tinggi dan backup otomatis.
                    </p>
                </div>
                <div class="bg-white p-10 rounded-3xl border border-gray-200 transition-all duration-300 relative overflow-hidden hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,0,0,.1)] hover:border-black feature-card">
                    <div class="relative w-15 h-15 mb-6">
                        <div class="absolute w-15 h-15 bg-gray-100 rounded-full transition-all duration-300 icon-circle"></div>
                        <svg class="relative z-10 w-8 h-8 m-[15px] stroke-black transition-all duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 00-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 010 7.75"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-black">Multi-User</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola tim dengan akses multi-user dan kontrol permission untuk setiap pengguna.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-5">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-6xl font-extrabold mb-4 text-black">Pilih Paket yang Tepat untuk Bisnis Anda</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Mulai dari paket starter hingga enterprise, semua dengan fitur lengkap
                </p>
            </div>
            <!-- Pricing Model Info -->
            <div class="max-w-4xl mx-auto mb-12 bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <div class="flex items-start gap-4">
                    <!-- <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 stroke-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-black mb-2">Model Pricing Hybrid (Base + Overage)</h3>
                        <p class="text-gray-600 text-sm mb-3">
                            Setiap paket memiliki <strong>base fee bulanan</strong> yang sudah termasuk sejumlah transaksi. 
                            Jika penggunaan melebihi limit, akan dikenakan <strong>biaya overage</strong> per transaksi tambahan.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="bg-white p-3 rounded-lg border border-gray-200">
                                <p class="font-semibold text-black mb-1">Starter</p>
                                <p class="text-gray-600 text-xs">Base: Rp 500rb</p>
                                <p class="text-gray-600 text-xs">Include: 100 transaksi</p>
                                <p class="text-gray-600 text-xs">Overage: Rp 2.000/transaksi</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-gray-200">
                                <p class="font-semibold text-black mb-1">Professional</p>
                                <p class="text-gray-600 text-xs">Base: Rp 1,5jt</p>
                                <p class="text-gray-600 text-xs">Include: 1.000 transaksi</p>
                                <p class="text-gray-600 text-xs">Overage: Rp 1.500/transaksi</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-gray-200">
                                <p class="font-semibold text-black mb-1">Enterprise</p>
                                <p class="text-gray-600 text-xs">Base: Rp 5jt</p>
                                <p class="text-gray-600 text-xs">Include: Unlimited</p>
                                <p class="text-gray-600 text-xs">Overage: Tidak ada</p>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs mt-4">
                            <strong>Contoh:</strong> Paket Starter dengan 150 transaksi = Rp 500.000 + (50 × Rp 2.000) = <strong>Rp 600.000/bulan</strong>
                        </p>
                    </div> -->
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Starter Package -->
                <div class="bg-white border-2 border-gray-200 rounded-3xl p-10 relative transition-all duration-300 hover:-translate-y-2.5 hover:shadow-[0_30px_60px_rgba(0,0,0,.15)] pricing-card">
                    <div class="text-center mb-8 pb-8 border-b border-gray-200">
                        <h3 class="text-2xl font-bold mb-4 text-black">Starter</h3>
                        <div class="flex items-baseline justify-center gap-1 mb-2">
                            <span class="text-xl font-semibold">Rp</span>
                            <span class="text-5xl font-extrabold text-black">500</span>
                            <span class="text-base text-gray-600">rb/bulan</span>
                        </div>
                        <p class="text-gray-500 text-xs mb-2">Base fee + overage</p>
                        <p class="text-gray-600 text-sm">Cocok untuk bisnis kecil dan startup</p>
                    </div>
                    <ul class="list-none mb-8">
                        <li class="flex items-start gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <div>
                                <span class="font-medium">Hingga 100 transaksi/bulan</span>
                                <p class="text-xs text-gray-500 mt-1">Overage: Rp 2.000/transaksi tambahan</p>
                            </div>
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Jurnal & Buku Besar
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Laporan Laba Rugi & Neraca
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Hingga 2 user
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Export PDF/Excel
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Support Email
                        </li>
                    </ul>
                    <a href="#contact" class="block w-full text-center px-6 py-3 rounded-full font-semibold text-base transition-all duration-300 border-2 border-gray-200 text-black hover:bg-black hover:text-white hover:border-black">Pilih Paket</a>
                </div>

                <!-- Professional Package -->
                <div class="bg-white border-2 border-black rounded-3xl p-10 relative transition-all duration-300 scale-105 shadow-[0_20px_60px_rgba(0,0,0,.1)] hover:-translate-y-2.5 hover:shadow-[0_30px_60px_rgba(0,0,0,.15)] pricing-card">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-black text-white px-5 py-2 rounded-full text-sm font-semibold">Paling Populer</div>
                    <div class="text-center mb-8 pb-8 border-b border-gray-200">
                        <h3 class="text-2xl font-bold mb-4 text-black">Professional</h3>
                        <div class="flex items-baseline justify-center gap-1 mb-2">
                            <span class="text-xl font-semibold">Rp</span>
                            <span class="text-5xl font-extrabold text-black">1.5</span>
                            <span class="text-base text-gray-600">jt/bulan</span>
                        </div>
                        <p class="text-gray-500 text-xs mb-2">Base fee + overage</p>
                        <p class="text-gray-600 text-sm">Ideal untuk bisnis menengah yang sedang berkembang</p>
                    </div>
                    <ul class="list-none mb-8">
                        <li class="flex items-start gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <div>
                                <span class="font-medium">Hingga 1.000 transaksi/bulan</span>
                                <p class="text-xs text-gray-500 mt-1">Overage: Rp 1.500/transaksi tambahan</p>
                            </div>
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Semua fitur Starter
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Invoice & Dokumen Lengkap
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Laporan Arus Kas
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Hingga 10 user
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Dashboard Analytics
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Import CSV
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Priority Support
                        </li>
                    </ul>
                    <a href="#contact" class="block w-full text-center px-6 py-3 rounded-full font-semibold text-base transition-all duration-300 border-2 border-transparent bg-black text-white hover:-translate-y-0.5 hover:shadow-[0_10px_25px_rgba(0,0,0,.2)]">Pilih Paket</a>
                </div>

                <!-- Enterprise Package -->
                <div class="bg-white border-2 border-gray-200 rounded-3xl p-10 relative transition-all duration-300 hover:-translate-y-2.5 hover:shadow-[0_30px_60px_rgba(0,0,0,.15)] pricing-card">
                    <div class="text-center mb-8 pb-8 border-b border-gray-200">
                        <h3 class="text-2xl font-bold mb-4 text-black">Enterprise</h3>
                        <div class="flex items-baseline justify-center gap-1 mb-2">
                            <span class="text-xl font-semibold">Rp</span>
                            <span class="text-5xl font-extrabold text-black">5</span>
                            <span class="text-base text-gray-600">jt/bulan</span>
                        </div>
                        <p class="text-gray-500 text-xs mb-2">Flat rate atau custom pricing</p>
                        <p class="text-gray-600 text-sm">Solusi lengkap untuk perusahaan besar</p>
                    </div>
                    <ul class="list-none mb-8">
                        <li class="flex items-start gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <div>
                                <span class="font-medium">Unlimited transaksi</span>
                                <p class="text-xs text-gray-500 mt-1">Tanpa batasan transaksi bulanan</p>
                            </div>
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Semua fitur Professional
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Unlimited user
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Custom Integration
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Dedicated Account Manager
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            On-site Training
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            24/7 Priority Support
                        </li>
                        <li class="flex items-center gap-3 py-3 text-gray-600">
                            <svg class="w-5 h-5 stroke-black flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            SLA Guarantee
                        </li>
                    </ul>
                    <a href="#contact" class="block w-full text-center px-6 py-3 rounded-full font-semibold text-base transition-all duration-300 border-2 border-gray-200 text-black hover:bg-black hover:text-white hover:border-black">Hubungi Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-5">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-6xl font-extrabold mb-4 text-black">Siap Memulai?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Hubungi kami untuk konsultasi gratis dan demo aplikasi
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 max-w-5xl mx-auto">
                <div class="flex flex-col gap-8">
                    <div class="flex gap-6 items-start">
                        <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 stroke-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-2 text-black">Telepon</h4>
                            <p class="text-gray-600 leading-relaxed">+62 21 1234 5678</p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start">
                        <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 stroke-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-2 text-black">Email</h4>
                            <p class="text-gray-600 leading-relaxed">info@do-account.id</p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start">
                        <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 stroke-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-2 text-black">Alamat</h4>
                            <p class="text-gray-600 leading-relaxed">Jl. Sudirman No. 123<br>Jakarta Pusat 10220</p>
                        </div>
                    </div>
                </div>
                <form class="bg-white p-10 rounded-3xl border border-gray-200" id="contactForm">
                    <div class="mb-6">
                        <input type="text" id="name" placeholder="Nama Lengkap" required class="w-full px-4.5 py-3.5 border-2 border-gray-200 rounded-xl font-sans text-base transition-all duration-300 bg-white text-black focus:outline-none focus:border-black">
                    </div>
                    <div class="mb-6">
                        <input type="email" id="email" placeholder="Email" required class="w-full px-4.5 py-3.5 border-2 border-gray-200 rounded-xl font-sans text-base transition-all duration-300 bg-white text-black focus:outline-none focus:border-black">
                    </div>
                    <div class="mb-6">
                        <input type="tel" id="phone" placeholder="Nomor Telepon" required class="w-full px-4.5 py-3.5 border-2 border-gray-200 rounded-xl font-sans text-base transition-all duration-300 bg-white text-black focus:outline-none focus:border-black">
                    </div>
                    <div class="mb-6">
                        <select id="package" required class="w-full px-4.5 py-3.5 border-2 border-gray-200 rounded-xl font-sans text-base transition-all duration-300 bg-white text-black focus:outline-none focus:border-black">
                            <option value="">Pilih Paket</option>
                            <option value="starter">Starter</option>
                            <option value="professional">Professional</option>
                            <option value="enterprise">Enterprise</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <textarea id="message" rows="5" placeholder="Pesan (Opsional)" class="w-full px-4.5 py-3.5 border-2 border-gray-200 rounded-xl font-sans text-base transition-all duration-300 bg-white text-black focus:outline-none focus:border-black resize-y"></textarea>
                    </div>
                    <button type="submit" class="w-full text-center px-6 py-3.5 rounded-full font-semibold text-base transition-all duration-300 border-2 border-transparent bg-black text-white hover:-translate-y-0.5 hover:shadow-[0_10px_25px_rgba(0,0,0,.2)] relative overflow-hidden">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-5">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-16 mb-12">
                <div class="flex flex-col gap-4">
                    <div class="w-10 h-10 bg-white rounded-full relative logo-circle">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-5 h-5 bg-black rounded-full"></div>
                    </div>
                    <span class="text-white font-bold text-xl">Do-Account</span>
                    <p class="text-white/70 leading-relaxed">
                        Sistem akuntansi modern untuk mengelola keuangan bisnis Anda dengan mudah dan akurat.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Produk</h4>
                    <ul class="list-none">
                        <li class="mb-3"><a href="#features" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">Fitur</a></li>
                        <li class="mb-3"><a href="#pricing" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">Paket & Harga</a></li>
                        <li class="mb-3"><a href="#" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">Integrasi</a></li>
                        <li class="mb-3"><a href="#" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">API</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Perusahaan</h4>
                    <ul class="list-none">
                        <li class="mb-3"><a href="#" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">Tentang Kami</a></li>
                        <li class="mb-3"><a href="#" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">Blog</a></li>
                        <li class="mb-3"><a href="#" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">Karir</a></li>
                        <li class="mb-3"><a href="#contact" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Dukungan</h4>
                    <ul class="list-none">
                        <li class="mb-3"><a href="#" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">Dokumentasi</a></li>
                        <li class="mb-3"><a href="#" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">Panduan</a></li>
                        <li class="mb-3"><a href="#" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">FAQ</a></li>
                        <li class="mb-3"><a href="#" class="text-white/70 no-underline transition-colors duration-300 hover:text-white">Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center pt-8 border-t border-white/10 text-white/50">
                <p>&copy; 2025 Do-Account. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Start Modal -->
    <div id="startModal" class="modal fixed inset-0 z-50 items-center justify-center modal-backdrop">
        <div class="bg-white rounded-3xl border-none max-w-lg w-full mx-4 relative">
            <div class="border-b border-gray-200 p-6">
                <h5 class="text-xl font-bold text-black flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Mulai Menggunakan Do-Account
                </h5>
                <button type="button" class="absolute top-6 right-6 text-gray-400 hover:text-black" id="closeModal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-8">
                <!-- Demo Account Info -->
                <div class="bg-gray-50 p-6 rounded-xl mb-6">
                    <h5 class="font-semibold mb-4 text-black flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Coba Demo Gratis
                    </h5>
                    <p class="text-gray-600 mb-4">Gunakan akun demo berikut untuk mencoba fitur-fitur Do-Account:</p>
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <p class="my-2 font-mono"><strong class="text-black">Email:</strong> demo@free.com</p>
                        <p class="my-2 font-mono"><strong class="text-black">Password:</strong> demo123</p>
                    </div>
                </div>

                <!-- Login Button -->
                <div class="mb-6">
                    <a href="{{ route('login') }}" class="block w-full text-center px-6 py-2.5 rounded-full font-semibold transition-all duration-300 border-2 border-transparent bg-black text-white hover:bg-gray-800">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login ke Akun Demo
                    </a>
                </div>

                <!-- Register Info -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h5 class="font-semibold mb-3 text-black flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Ingin Membuat Akun Berbayar?
                    </h5>
                    <p class="text-gray-600 text-sm mb-2">Untuk memesan atau membuat akun <strong>Starter</strong>, <strong>Professional</strong>, atau <strong>Enterprise</strong>, silakan daftar terlebih dahulu.</p>
                    <p class="text-gray-600 text-sm mb-4">Setelah registrasi, hubungi kami melalui form kontak atau email untuk aktivasi akun berbayar.</p>
                    <div class="mt-3">
                        <a href="{{ route('register') }}" class="block w-full text-center px-6 py-2.5 rounded-full font-semibold transition-all duration-300 border-2 border-black text-black hover:bg-black hover:text-white">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Daftar Akun Baru
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200 p-6">
                <button type="button" class="w-full text-center px-6 py-2.5 rounded-full font-semibold transition-all duration-300 border-2 border-black text-black hover:bg-black hover:text-white" id="closeModalBtn">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // Navigation Toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        const navbar = document.getElementById('navbar');

        if (navToggle && navMenu) {
            navToggle.addEventListener('click', () => {
                navToggle.classList.toggle('active');
                navMenu.classList.toggle('active');
            });

            document.querySelectorAll('.nav-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    navToggle.classList.remove('active');
                    navMenu.classList.remove('active');
                });
            });
        }

        // Navbar scroll effect
        if (navbar) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('shadow-md');
                } else {
                    navbar.classList.remove('shadow-md');
                }
            });
        }

        // Modal functionality
        const startBtn = document.getElementById('startBtn');
        const startModal = document.getElementById('startModal');
        const closeModal = document.getElementById('closeModal');
        const closeModalBtn = document.getElementById('closeModalBtn');

        if (startBtn && startModal) {
            startBtn.addEventListener('click', (e) => {
                e.preventDefault();
                startModal.classList.add('show');
                document.body.style.overflow = 'hidden';
            });
        }

        const closeModalFunc = () => {
            startModal.classList.remove('show');
            document.body.style.overflow = '';
        };

        if (closeModal) closeModal.addEventListener('click', closeModalFunc);
        if (closeModalBtn) closeModalBtn.addEventListener('click', closeModalFunc);
        if (startModal) {
            startModal.addEventListener('click', (e) => {
                if (e.target === startModal) closeModalFunc();
            });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                const target = document.querySelector(href);
                if (href.length > 1 && target) {
                    e.preventDefault();
                    const offsetTop = target.offsetTop - 80;
                    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            });
        });

        // Animate numbers on scroll
        const animateNumbers = () => {
            const stats = document.querySelectorAll('.stat-number');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = parseInt(entry.target.getAttribute('data-target'));
                        const duration = 2000;
                        const increment = target / (duration / 16);
                        let current = 0;
                        const updateNumber = () => {
                            current += increment;
                            if (current < target) {
                                entry.target.textContent = Math.floor(current);
                                requestAnimationFrame(updateNumber);
                            } else {
                                entry.target.textContent = target;
                            }
                        };
                        updateNumber();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            stats.forEach(stat => observer.observe(stat));
        };
        animateNumbers();

        // Feature cards animation on scroll
        const animateFeatures = () => {
            const featureCards = document.querySelectorAll('.feature-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '0';
                            entry.target.style.transform = 'translateY(30px)';
                            entry.target.style.transition = 'all 0.6s ease';
                            setTimeout(() => {
                                entry.target.style.opacity = '1';
                                entry.target.style.transform = 'translateY(0)';
                            }, 50);
                        }, index * 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            featureCards.forEach(card => observer.observe(card));
        };
        animateFeatures();

        // Pricing cards animation
        const animatePricing = () => {
            const pricingCards = document.querySelectorAll('.pricing-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '0';
                            entry.target.style.transform = 'scale(0.9)';
                            entry.target.style.transition = 'all 0.6s ease';
                            setTimeout(() => {
                                entry.target.style.opacity = '1';
                                entry.target.style.transform = 'scale(1)';
                            }, 50);
                        }, index * 150);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });
            pricingCards.forEach(card => observer.observe(card));
        };
        animatePricing();

        // Form submission
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const submitButton = contactForm.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Mengirim...';
                submitButton.disabled = true;
                setTimeout(() => {
                    submitButton.textContent = '✓ Terkirim!';
                    submitButton.style.background = '#22c55e';
                    contactForm.reset();
                    setTimeout(() => {
                        submitButton.textContent = originalText;
                        submitButton.style.background = '';
                        submitButton.disabled = false;
                    }, 3000);
                    alert('Terima kasih! Pesan Anda telah terkirim. Tim kami akan menghubungi Anda segera.');
                }, 1500);
            });
        }

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const shapes = document.querySelectorAll('.shape-1, .shape-2, .shape-3');
            shapes.forEach((shape, index) => {
                const speed = 0.5 + (index * 0.1);
                shape.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Add active class to current nav item
        const updateActiveNav = () => {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-menu a');
            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (window.pageYOffset >= sectionTop - 100) {
                        current = section.getAttribute('id');
                    }
                });
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('active');
                    }
                });
            });
        };
        updateActiveNav();

        // Add ripple effect to buttons
        document.querySelectorAll('.btn, button, a[class*="btn"]').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                this.appendChild(ripple);
                setTimeout(() => { ripple.remove(); }, 600);
            });
        });

        // Feature card hover effect
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                const iconCircle = this.querySelector('.icon-circle');
                const iconSvg = this.querySelector('.feature-icon svg');
                if (iconCircle) iconCircle.style.background = '#000';
                if (iconCircle) iconCircle.style.transform = 'scale(1.1)';
                if (iconSvg) iconSvg.style.stroke = '#fff';
            });
            card.addEventListener('mouseleave', function() {
                const iconCircle = this.querySelector('.icon-circle');
                const iconSvg = this.querySelector('.feature-icon svg');
                if (iconCircle) iconCircle.style.background = '#f5f5f5';
                if (iconCircle) iconCircle.style.transform = 'scale(1)';
                if (iconSvg) iconSvg.style.stroke = '#000';
            });
        });
    </script>
</body>
</html>
