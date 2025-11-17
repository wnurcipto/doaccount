@extends('layouts.app')

@section('title', 'Dashboard')

@php
    use App\Models\CompanyInfo;
    $company = CompanyInfo::getInfo(auth()->id());
@endphp

@push('styles')
<style>
    .stat-card {
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .stat-card .stat-icon {
        font-size: 48px;
        opacity: 0.3;
        position: absolute;
        right: 20px;
        top: 20px;
    }
    .stat-card .stat-value {
        font-size: 36px;
        font-weight: bold;
        margin: 10px 0;
    }
    .stat-card .stat-label {
        font-size: 14px;
        opacity: 0.9;
    }
    .stat-card-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .stat-card-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .stat-card-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .stat-card-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .stat-card-danger {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .stat-card-secondary {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #333;
    }
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .chart-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .status-bar-chart {
        padding: 10px 0;
    }
    .status-bar-item {
        margin-bottom: 20px;
    }
    .status-bar-item:last-child {
        margin-bottom: 0;
    }
    .status-bar-label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }
    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .status-value {
        margin-left: auto;
        font-weight: 600;
        color: #667eea;
    }
    .status-bar-wrapper {
        width: 100%;
        height: 24px;
        background-color: #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }
    .status-bar {
        height: 100%;
        border-radius: 12px;
        transition: width 0.6s ease;
        position: relative;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .status-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .pendapatan-pengeluaran-chart {
        padding: 10px 0;
    }
    .pendapatan-item {
        margin-bottom: 20px;
    }
    .pendapatan-item:last-child {
        margin-bottom: 0;
    }
    .pendapatan-label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }
    .pendapatan-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .pendapatan-value {
        margin-left: auto;
        font-weight: 600;
        font-size: 13px;
    }
    .pendapatan-bar-wrapper {
        width: 100%;
        height: 24px;
        background-color: #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }
    .pendapatan-bar {
        height: 100%;
        border-radius: 12px;
        transition: width 0.6s ease;
        position: relative;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .pendapatan-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
        <div class="text-muted">
            <i class="bi bi-calendar3"></i> {{ now()->format('d F Y') }}
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card stat-card-primary">
                <i class="bi bi-journal-text stat-icon"></i>
                <div class="stat-label">Total Jurnal</div>
                <div class="stat-value">{{ number_format($stats['total_jurnal']) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-success">
                <i class="bi bi-check-circle stat-icon"></i>
                <div class="stat-label">Jurnal Posted</div>
                <div class="stat-value">{{ number_format($stats['jurnal_posted']) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-warning">
                <i class="bi bi-file-earmark-text stat-icon"></i>
                <div class="stat-label">Jurnal Draft</div>
                <div class="stat-value">{{ number_format($stats['jurnal_draft']) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-info">
                <i class="bi bi-list-ul stat-icon"></i>
                <div class="stat-label">Chart of Accounts</div>
                <div class="stat-value">{{ number_format($stats['total_coa']) }}</div>
            </div>
        </div>
    </div>

    @if(!auth()->user()->is_owner && auth()->user()->plan == 'free')
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1"><i class="bi bi-info-circle"></i> Plan Anda: <strong>{{ auth()->user()->getPlanDisplayName() }}</strong></h5>
                    <p class="mb-0">Upgrade ke <strong>Professional</strong> untuk akses fitur lengkap: Neraca, Arus Kas, Inventory, Invoice, Export PDF/Excel, dan banyak lagi!</p>
                </div>
                <div>
                    <button class="btn btn-primary" onclick="alert('Fitur upgrade akan segera tersedia!');">
                        <i class="bi bi-arrow-up-circle"></i> Upgrade Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="bi bi-pie-chart-fill text-primary"></i>
                    Status Jurnal
                </div>
                <div class="status-bar-chart">
                    <div class="status-bar-item">
                        <div class="status-bar-label">
                            <span class="status-dot bg-success"></span>
                            <span>Posted</span>
                            <span class="status-value">{{ number_format($statusJurnal['Posted']) }}</span>
                        </div>
                        <div class="status-bar-wrapper">
                            <div class="status-bar bg-success" style="width: {{ $stats['total_jurnal'] > 0 ? ($statusJurnal['Posted'] / $stats['total_jurnal'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="status-bar-item">
                        <div class="status-bar-label">
                            <span class="status-dot bg-warning"></span>
                            <span>Draft</span>
                            <span class="status-value">{{ number_format($statusJurnal['Draft']) }}</span>
                        </div>
                        <div class="status-bar-wrapper">
                            <div class="status-bar bg-warning" style="width: {{ $stats['total_jurnal'] > 0 ? ($statusJurnal['Draft'] / $stats['total_jurnal'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="status-bar-item">
                        <div class="status-bar-label">
                            <span class="status-dot bg-danger"></span>
                            <span>Void</span>
                            <span class="status-value">{{ number_format($statusJurnal['Void']) }}</span>
                        </div>
                        <div class="status-bar-wrapper">
                            <div class="status-bar bg-danger" style="width: {{ $stats['total_jurnal'] > 0 ? ($statusJurnal['Void'] / $stats['total_jurnal'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="bi bi-graph-up-arrow text-success"></i>
                    Pendapatan & Pengeluaran Tahun {{ $tahunBerjalan }}
                </div>
                <div class="pendapatan-pengeluaran-chart">
                    <div class="pendapatan-item">
                        <div class="pendapatan-label">
                            <span class="pendapatan-dot bg-success"></span>
                            <span>Total Pendapatan</span>
                            <span class="pendapatan-value text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                        </div>
                        <div class="pendapatan-bar-wrapper">
                            @php
                                $maxValue = max($totalPendapatan, $totalPengeluaran);
                                $pendapatanPercent = $maxValue > 0 ? ($totalPendapatan / $maxValue * 100) : 0;
                            @endphp
                            <div class="pendapatan-bar bg-success" style="width: {{ $pendapatanPercent }}%"></div>
                        </div>
                    </div>
                    <div class="pendapatan-item">
                        <div class="pendapatan-label">
                            <span class="pendapatan-dot bg-danger"></span>
                            <span>Total Pengeluaran</span>
                            <span class="pendapatan-value text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                        </div>
                        <div class="pendapatan-bar-wrapper">
                            @php
                                $pengeluaranPercent = $maxValue > 0 ? ($totalPengeluaran / $maxValue * 100) : 0;
                            @endphp
                            <div class="pendapatan-bar bg-danger" style="width: {{ $pengeluaranPercent }}%"></div>
                        </div>
                    </div>
                    <div class="pendapatan-item">
                        <div class="pendapatan-label">
                            <span class="pendapatan-dot {{ $labaRugi >= 0 ? 'bg-success' : 'bg-danger' }}"></span>
                            <span>Laba/Rugi</span>
                            <span class="pendapatan-value {{ $labaRugi >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($labaRugi, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="pendapatan-bar-wrapper">
                            @php
                                $labaRugiAbs = abs($labaRugi);
                                $labaRugiMax = max($totalPendapatan, $totalPengeluaran, $labaRugiAbs);
                                $labaRugiPercent = $labaRugiMax > 0 ? ($labaRugiAbs / $labaRugiMax * 100) : 0;
                            @endphp
                            <div class="pendapatan-bar {{ $labaRugi >= 0 ? 'bg-success' : 'bg-danger' }}" style="width: {{ $labaRugiPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-card-success mb-3">
                <i class="bi bi-calendar3 stat-icon"></i>
                <div class="stat-label">Total Periode</div>
                <div class="stat-value">{{ number_format($stats['total_periode']) }}</div>
            </div>
            <div class="stat-card stat-card-primary">
                <i class="bi bi-cash-coin stat-icon"></i>
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">Rp {{ number_format($stats['total_transaksi'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="bi bi-bar-chart-fill text-primary"></i>
                    Jurnal per Bulan (6 Bulan Terakhir)
                </div>
                <canvas id="jurnalChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="bi bi-trophy-fill text-warning"></i>
                    Top 5 Transaksi per Tipe Akun
                </div>
                <canvas id="top5TransaksiChart"></canvas>
            </div>
        </div>
    </div>


    <div class="row mb-4">
        <div class="col-md-12">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="bi bi-graph-up-arrow text-success"></i>
                    Pendapatan per Bulan ({{ $tahunBerjalan }}) vs Baseline (Rata-rata {{ $tahunSebelumnya }})
                </div>
                <canvas id="pendapatanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Jurnal Terbaru -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Jurnal Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Bukti</th>
                                    <th>Tanggal</th>
                                    <th>Periode</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Total Debit</th>
                                    <th class="text-end">Total Kredit</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jurnalTerbaru as $jurnal)
                                <tr>
                                    <td><strong>{{ $jurnal->no_bukti }}</strong></td>
                                    <td>{{ $jurnal->tanggal_transaksi->format('d/m/Y') }}</td>
                                    <td>{{ $jurnal->periode->nama_periode }}</td>
                                    <td>{{ Str::limit($jurnal->deskripsi, 40) }}</td>
                                    <td class="text-end">Rp {{ number_format($jurnal->total_debit, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($jurnal->total_kredit, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($jurnal->status == 'Draft')
                                            <span class="badge bg-warning text-dark">Draft</span>
                                        @elseif($jurnal->status == 'Posted')
                                            <span class="badge bg-success">Posted</span>
                                        @else
                                            <span class="badge bg-danger">Void</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('jurnal.show', $jurnal) }}" class="btn btn-outline-info btn-sm" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                        <p class="mt-2">Belum ada jurnal</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart Jurnal per Bulan
    const ctxJurnal = document.getElementById('jurnalChart').getContext('2d');
    new Chart(ctxJurnal, {
        type: 'bar',
        data: {
            labels: @json($labelsJurnal),
            datasets: [{
                label: 'Jumlah Jurnal',
                data: @json($dataJurnal),
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(118, 75, 162, 0.8)',
                    'rgba(250, 112, 154, 0.8)',
                    'rgba(254, 225, 64, 0.8)',
                    'rgba(79, 172, 254, 0.8)',
                    'rgba(17, 153, 142, 0.8)'
                ],
                borderColor: [
                    'rgba(102, 126, 234, 1)',
                    'rgba(118, 75, 162, 1)',
                    'rgba(250, 112, 154, 1)',
                    'rgba(254, 225, 64, 1)',
                    'rgba(79, 172, 254, 1)',
                    'rgba(17, 153, 142, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });


    // Chart Top 5 Transaksi per Tipe Akun
    const ctxTop5Transaksi = document.getElementById('top5TransaksiChart').getContext('2d');
    new Chart(ctxTop5Transaksi, {
        type: 'bar',
        data: {
            labels: @json($labelsTop5Transaksi),
            datasets: [{
                label: 'Total Transaksi',
                data: @json($dataTop5Transaksi),
                backgroundColor: [
                    'rgba(255, 215, 0, 0.8)',   // Gold untuk #1
                    'rgba(192, 192, 192, 0.8)', // Silver untuk #2
                    'rgba(205, 127, 50, 0.8)',  // Bronze untuk #3
                    'rgba(102, 126, 234, 0.8)', // Purple untuk #4
                    'rgba(118, 75, 162, 0.8)'   // Purple untuk #5
                ],
                borderColor: [
                    'rgba(255, 215, 0, 1)',
                    'rgba(192, 192, 192, 1)',
                    'rgba(205, 127, 50, 1)',
                    'rgba(102, 126, 234, 1)',
                    'rgba(118, 75, 162, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Chart Pendapatan per Bulan dengan Baseline
    const ctxPendapatan = document.getElementById('pendapatanChart').getContext('2d');
    
    // Debug: Log data untuk memastikan November-Desember ada
    console.log('Labels Pendapatan:', @json($labelsPendapatan));
    console.log('Data Pendapatan:', @json($dataPendapatan));
    console.log('Baseline Data:', @json($baselineData));
    
    new Chart(ctxPendapatan, {
        type: 'line',
        data: {
            labels: @json($labelsPendapatan),
            datasets: [
                {
                    label: 'Pendapatan {{ $tahunBerjalan }}',
                    data: @json($dataPendapatan),
                    borderColor: 'rgba(17, 153, 142, 1)',
                    backgroundColor: 'rgba(17, 153, 142, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: 'rgba(17, 153, 142, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Baseline (Rata-rata {{ $tahunSebelumnya }})',
                    data: @json($baselineData),
                    borderColor: 'rgba(239, 68, 68, 1)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false,
                    pointRadius: 0,
                    pointHoverRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

</script>
@endpush
@endsection
