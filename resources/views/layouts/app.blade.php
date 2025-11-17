@php
    use App\Models\CompanyInfo;
    use Illuminate\Support\Facades\Storage;
    $company = CompanyInfo::getInfo(auth()->id());
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Akuntansi')</title>
    @if($company->logo && Storage::disk('public')->exists($company->logo))
        <link rel="icon" type="image/png" href="{{ Storage::url($company->logo) }}">
        <link rel="shortcut icon" type="image/png" href="{{ Storage::url($company->logo) }}">
    @else
        <link rel="icon" type="image/png" href="{{ route('favicon') }}">
        <link rel="shortcut icon" type="image/png" href="{{ route('favicon') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-width: 16.666667%; }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width); /* col-md-2 equivalent */
            background-color: #212529;
            overflow: hidden;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 15px;
            text-align: center;
            border-bottom: 3px solid #495057;
        }
        .sidebar-header .logo {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }
        .sidebar-header .logo img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }
        .sidebar-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        .sidebar-header .subtitle {
            font-size: 11px;
            color: rgba(255,255,255,0.8);
            margin-top: 4px;
        }
        .sidebar-nav-wrapper::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-nav-wrapper::-webkit-scrollbar-track {
            background: #212529;
        }
        .sidebar-nav-wrapper::-webkit-scrollbar-thumb {
            background: #495057;
            border-radius: 3px;
        }
        .sidebar-nav-wrapper::-webkit-scrollbar-thumb:hover {
            background: #6c757d;
        }
        .sidebar-nav-wrapper {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .nav-link:hover {
            background-color: #343a40;
            color: white;
            border-left-color: #667eea;
        }
        .nav-link.active {
            background-color: #343a40;
            color: white;
            border-left-color: #667eea;
            font-weight: 600;
        }
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 20px;
            padding-bottom: 80px; /* Extra space for footer */
            background-color: #f8f9fa;
            width: calc(100% - var(--sidebar-width));
            position: relative;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: var(--sidebar-width);
            right: 0;
            background-color: #212529;
            color: white;
            padding: 12px 20px;
            text-align: center;
            font-size: 12px;
            z-index: 999;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }
        .footer p {
            margin: 0;
            font-size: 14px;
        }
        .footer a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .user-info {
            padding: 15px 20px;
            border-top: 1px solid #495057;
            background-color: #1a1d20;
            margin-top: auto;
            flex-shrink: 0;
        }
        .profile-button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s;
            width: 100%;
            color: white;
        }
        .profile-button:hover {
            background-color: #343a40;
        }
        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            flex-shrink: 0;
        }
        .profile-info {
            text-align: left;
            flex: 1;
            min-width: 0;
        }
        .profile-info .name {
            font-weight: 600;
            font-size: 14px;
            color: white;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .profile-info .email {
            font-size: 12px;
            color: #adb5bd;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .profile-button i {
            color: #adb5bd;
            flex-shrink: 0;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding-bottom: 80px;
            }
            .footer {
                left: 0;
                width: 100%;
            }
        }
        
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
                <!-- Company Header -->
                @php
                    $company = CompanyInfo::getInfo(auth()->id());
                @endphp
                <div class="sidebar-header">
                    @if($company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="logo">
                    @else
                        <div class="logo">RA</div>
                    @endif
                    <h5>{{ $company->nama_perusahaan }}</h5>
                    <div class="subtitle">Sistem Akuntansi</div>
                </div>
                
                <!-- Navigation Menu -->
                <div class="sidebar-nav-wrapper">
                <nav class="nav flex-column">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('coa.index') }}" class="nav-link {{ request()->routeIs('coa.*') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i> Chart of Accounts
                    </a>
                    <a href="{{ route('periode.index') }}" class="nav-link {{ request()->routeIs('periode.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i> Periode
                    </a>
                    <a href="{{ route('jurnal.index', ['clear_filter' => 1]) }}" class="nav-link {{ request()->routeIs('jurnal.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i> Jurnal
                    </a>
                    <a href="{{ route('buku-besar.index', ['clear_filter' => 1]) }}" class="nav-link {{ request()->routeIs('buku-besar.*') ? 'active' : '' }}">
                        <i class="bi bi-book"></i> Buku Besar
                    </a>
                    
                    <div class="px-3 py-2 text-white-50 small">CUSTOMER & SUPPLIER</div>
                    <a href="{{ route('customer.index') }}" class="nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Customer
                    </a>
                    <a href="{{ route('supplier.index') }}" class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i> Supplier
                    </a>
                    
                    @if(auth()->user()->hasFeature('inventory'))
                        <div class="px-3 py-2 text-white-50 small">INVENTORI</div>
                        <a href="{{ route('barang.index') }}" class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam"></i> Master Barang
                        </a>
                        <a href="{{ route('stok-masuk.index') }}" class="nav-link {{ request()->routeIs('stok-masuk.*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-down-circle"></i> Stok Masuk
                        </a>
                        <a href="{{ route('stok-keluar.index') }}" class="nav-link {{ request()->routeIs('stok-keluar.*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-up-circle"></i> Stok Keluar
                        </a>
                        <a href="{{ route('kartu-stok.index') }}" class="nav-link {{ request()->routeIs('kartu-stok.*') ? 'active' : '' }}">
                            <i class="bi bi-card-list"></i> Kartu Stok
                        </a>
                    @else
                        <div class="px-3 py-2 text-white-50 small">INVENTORI <span class="badge bg-warning text-dark ms-2" style="font-size: 0.7rem;">Pro</span></div>
                        <a href="#" class="nav-link text-muted" onclick="alert('Fitur ini hanya tersedia untuk plan Professional/Enterprise'); return false;">
                            <i class="bi bi-box-seam"></i> Master Barang
                        </a>
                    @endif
                    
                    <div class="px-3 py-2 text-white-50 small">LAPORAN</div>
                    <a href="{{ route('laporan.laba-rugi') }}" class="nav-link {{ request()->routeIs('laporan.laba-rugi') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i> Laba Rugi
                    </a>
                    {{-- Free account bisa lihat neraca untuk demo, tapi dengan badge Demo --}}
                    @if(auth()->user()->hasFeature('laporan_neraca'))
                        <a href="{{ route('laporan.neraca') }}" class="nav-link {{ request()->routeIs('laporan.neraca') ? 'active' : '' }}">
                            <i class="bi bi-pie-chart"></i> Neraca
                        </a>
                    @elseif(auth()->user()->plan === 'free' && !auth()->user()->is_owner)
                        {{-- Free account bisa akses untuk demo --}}
                        <a href="{{ route('laporan.neraca') }}" class="nav-link {{ request()->routeIs('laporan.neraca') ? 'active' : '' }}">
                            <i class="bi bi-pie-chart"></i> Neraca <span class="badge bg-info text-white ms-2" style="font-size: 0.7rem;">Demo</span>
                        </a>
                    @else
                        <a href="#" class="nav-link text-muted" onclick="alert('Fitur ini hanya tersedia untuk plan Professional/Enterprise'); return false;">
                            <i class="bi bi-pie-chart"></i> Neraca <span class="badge bg-warning text-dark ms-2" style="font-size: 0.7rem;">Pro</span>
                        </a>
                    @endif
                    @if(auth()->user()->hasFeature('laporan_arus_kas'))
                        <a href="{{ route('laporan.arus-kas') }}" class="nav-link {{ request()->routeIs('laporan.arus-kas') ? 'active' : '' }}">
                            <i class="bi bi-cash-coin"></i> Arus Kas
                        </a>
                    @else
                        <a href="#" class="nav-link text-muted" onclick="alert('Fitur ini hanya tersedia untuk plan Professional/Enterprise'); return false;">
                            <i class="bi bi-cash-coin"></i> Arus Kas <span class="badge bg-warning text-dark ms-2" style="font-size: 0.7rem;">Pro</span>
                        </a>
                    @endif
                    
                    @if(auth()->user()->hasFeature('invoice'))
                        <div class="px-3 py-2 text-white-50 small">DOKUMEN</div>
                        <a href="{{ route('invoice.index') }}" class="nav-link {{ request()->routeIs('invoice.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text"></i> Invoice
                        </a>
                        <a href="{{ route('offering.index') }}" class="nav-link {{ request()->routeIs('offering.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-check"></i> Offering
                        </a>
                        <a href="{{ route('surat-jalan.index') }}" class="nav-link {{ request()->routeIs('surat-jalan.*') ? 'active' : '' }}">
                            <i class="bi bi-truck"></i> Surat Jalan
                        </a>
                    @else
                        <div class="px-3 py-2 text-white-50 small">DOKUMEN <span class="badge bg-warning text-dark ms-2" style="font-size: 0.7rem;">Pro</span></div>
                        <a href="#" class="nav-link text-muted" onclick="alert('Fitur ini hanya tersedia untuk plan Professional/Enterprise'); return false;">
                            <i class="bi bi-file-earmark-text"></i> Invoice
                        </a>
                    @endif
                </nav>
                </div>

                <!-- Profile Button -->
                <div class="user-info">
                    <button type="button" class="profile-button" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <div class="profile-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="profile-info">
                            <p class="name">{{ Auth::user()->name }}</p>
                            <p class="email">{{ Auth::user()->email }}</p>
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
            </div>

            <!-- Profile Modal -->
            <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="profileModalLabel">
                                <i class="bi bi-person-circle"></i> Profil Pengguna
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <div class="profile-avatar mx-auto" style="width: 60px; height: 60px; font-size: 24px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <h5 class="mt-3 mb-1">{{ Auth::user()->name }}</h5>
                                <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                                <span class="badge {{ Auth::user()->is_owner ? 'bg-success' : (Auth::user()->plan == 'enterprise' ? 'bg-primary' : (Auth::user()->plan == 'professional' ? 'bg-info' : (Auth::user()->plan == 'starter' ? 'bg-secondary' : 'bg-warning text-dark'))) }} mt-2">
                                    {{ Auth::user()->getPlanDisplayName() }}
                                </span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-grid gap-2">
                                <div class="row g-2">
                                    <div class="col-6">
                                        {{-- Bantuan hanya untuk Enterprise --}}
                                        @if(auth()->user()->plan === 'enterprise' || auth()->user()->is_owner)
                                            <a href="{{ route('bantuan.index') }}" class="btn btn-outline-primary w-100" onclick="document.getElementById('profileModal').querySelector('[data-bs-dismiss]').click();">
                                                <i class="bi bi-question-circle"></i> Bantuan
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-outline-primary w-100 disabled" disabled title="Menu Bantuan hanya tersedia untuk plan Enterprise">
                                                <i class="bi bi-question-circle"></i> Bantuan <i class="bi bi-lock-fill ms-1" style="font-size: 0.7rem;"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        {{-- Informasi Perusahaan selalu bisa diakses oleh semua user (termasuk free account) --}}
                                        <a href="{{ route('about.index') }}" class="btn btn-outline-info w-100" onclick="document.getElementById('profileModal').querySelector('[data-bs-dismiss]').click();">
                                            <i class="bi bi-building"></i> Informasi Perusahaan
                                        </a>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row g-2">
                                    <div class="col-4">
                                        @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner)
                                            <button type="button" class="btn btn-outline-success w-100 btn-sm disabled" disabled title="Fitur ini hanya tersedia untuk plan Starter/Professional/Enterprise">
                                                <i class="bi bi-receipt"></i> Invoice <i class="bi bi-lock-fill ms-1" style="font-size: 0.7rem;"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('invoice.index') }}" class="btn btn-outline-success w-100 btn-sm" onclick="document.getElementById('profileModal').querySelector('[data-bs-dismiss]').click();">
                                                <i class="bi bi-receipt"></i> Invoice
                                            </a>
                                        @endif
                                    </div>
                                    <div class="col-4">
                                        @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner)
                                            <button type="button" class="btn btn-outline-warning w-100 btn-sm disabled" disabled title="Fitur ini hanya tersedia untuk plan Starter/Professional/Enterprise">
                                                <i class="bi bi-file-earmark-text"></i> Offering <i class="bi bi-lock-fill ms-1" style="font-size: 0.7rem;"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('offering.index') }}" class="btn btn-outline-warning w-100 btn-sm" onclick="document.getElementById('profileModal').querySelector('[data-bs-dismiss]').click();">
                                                <i class="bi bi-file-earmark-text"></i> Offering
                                            </a>
                                        @endif
                                    </div>
                                    <div class="col-4">
                                        @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner)
                                            <button type="button" class="btn btn-outline-secondary w-100 btn-sm disabled" disabled title="Fitur ini hanya tersedia untuk plan Starter/Professional/Enterprise">
                                                <i class="bi bi-truck"></i> Surat Jalan <i class="bi bi-lock-fill ms-1" style="font-size: 0.7rem;"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('surat-jalan.index') }}" class="btn btn-outline-secondary w-100 btn-sm" onclick="document.getElementById('profileModal').querySelector('[data-bs-dismiss]').click();">
                                                <i class="bi bi-truck"></i> Surat Jalan
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary" onclick="document.getElementById('profileModal').querySelector('[data-bs-dismiss]').click();">
                                    <i class="bi bi-person-gear"></i> Edit Profil
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <form method="POST" action="{{ route('logout') }}" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $company->nama_perusahaan }}. All rights reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
