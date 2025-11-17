@extends('layouts.app')

@section('title', 'Laporan Arus Kas')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Laporan Arus Kas</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.arus-kas') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" 
                           value="{{ request('tanggal_mulai', date('Y-m-01')) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" 
                           value="{{ request('tanggal_selesai', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Tampilkan Laporan
                    </button>
                    <button type="button" class="btn btn-success" onclick="window.print()">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($kasDariOperasi))
    <!-- Watermark -->
    @include('partials.print-watermark')

    <!-- Print Header (Hidden on screen, shown on print) -->
    <div class="d-none print-only">
        @include('partials.print-header-screen')
        <div class="text-center mb-4">
            <h3 style="margin: 20px 0 10px 0; font-weight: bold;">LAPORAN ARUS KAS</h3>
            <p class="mb-0">Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} s/d {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4 no-print">
                <h3>LAPORAN ARUS KAS</h3>
                <p class="mb-0">Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} s/d {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</p>
            </div>

            <table class="table">
                <tbody>
                    <!-- AKTIVITAS OPERASI -->
                    <tr class="table-secondary">
                        <td colspan="2"><strong>AKTIVITAS OPERASI</strong></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 30px;">Pendapatan</td>
                        <td class="text-end">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 30px;">Beban</td>
                        <td class="text-end">(Rp {{ number_format($totalBeban, 0, ',', '.') }})</td>
                    </tr>
                    <tr class="table-light">
                        <td><strong>Kas dari Aktivitas Operasi</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($kasDariOperasi, 0, ',', '.') }}</strong></td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <!-- AKTIVITAS INVESTASI -->
                    <tr class="table-secondary">
                        <td colspan="2"><strong>AKTIVITAS INVESTASI</strong></td>
                    </tr>
                    @if($asetTetap->count() > 0)
                        @foreach($asetTetap as $item)
                        <tr>
                            <td style="padding-left: 30px;">Pembelian {{ $item->nama_akun }}</td>
                            <td class="text-end">(Rp {{ number_format($item->saldo, 0, ',', '.') }})</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td style="padding-left: 30px;">Tidak ada aktivitas investasi</td>
                            <td class="text-end">-</td>
                        </tr>
                    @endif
                    <tr class="table-light">
                        <td><strong>Kas dari Aktivitas Investasi</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($totalInvestasi, 0, ',', '.') }}</strong></td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <!-- AKTIVITAS PENDANAAN -->
                    <tr class="table-secondary">
                        <td colspan="2"><strong>AKTIVITAS PENDANAAN</strong></td>
                    </tr>
                    @if($ekuitas->count() > 0)
                        @foreach($ekuitas as $item)
                        <tr>
                            <td style="padding-left: 30px;">{{ $item->nama_akun }}</td>
                            <td class="text-end">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @endif
                    @if($liabilitas->count() > 0)
                        @foreach($liabilitas as $item)
                        <tr>
                            <td style="padding-left: 30px;">{{ $item->nama_akun }}</td>
                            <td class="text-end">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @endif
                    @if($ekuitas->count() == 0 && $liabilitas->count() == 0)
                        <tr>
                            <td style="padding-left: 30px;">Tidak ada aktivitas pendanaan</td>
                            <td class="text-end">-</td>
                        </tr>
                    @endif
                    <tr class="table-light">
                        <td><strong>Kas dari Aktivitas Pendanaan</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($kasDariPendanaan, 0, ',', '.') }}</strong></td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <!-- PERUBAHAN KAS BERSIH -->
                    <tr class="table-primary">
                        <td><strong>PERUBAHAN KAS BERSIH</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($perubahanKas, 0, ',', '.') }}</strong></td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <!-- SALDO KAS -->
                    <tr>
                        <td>Saldo Kas Awal Periode</td>
                        <td class="text-end">Rp {{ number_format($kasAwal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Perubahan Kas Bersih</td>
                        <td class="text-end">Rp {{ number_format($perubahanKas, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="table-success">
                        <td><strong>SALDO KAS AKHIR PERIODE</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($kasAkhir, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Print Footer -->
    <div class="d-none print-only">
        @include('partials.print-footer')
    </div>
    @endif
</div>

@push('styles')
<style>
@media print {
    @page {
        margin: 1cm;
        size: A4;
    }
    
    .btn, .sidebar, .alert, .d-flex.justify-content-between, .no-print {
        display: none !important;
    }
    
    .print-only {
        display: block !important;
    }
    
    .d-none.print-only {
        display: block !important;
    }
    
    .main-content {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
        position: relative;
        z-index: 1;
    }
    
    .card-body {
        padding: 10px 0 !important;
        position: relative;
        z-index: 1;
    }
    
    table {
        font-size: 11px;
        position: relative;
        z-index: 1;
    }
    
    h2, h3, h4, h5 {
        page-break-after: avoid;
        position: relative;
        z-index: 1;
    }
    
    .table {
        page-break-inside: avoid;
    }
}

@media screen {
    .print-only {
        display: none !important;
    }
}
</style>
@endpush
@endsection

