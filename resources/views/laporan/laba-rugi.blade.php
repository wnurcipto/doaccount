@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Laporan Laba Rugi</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.laba-rugi') }}" method="GET" class="row g-3">
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
                    @if(isset($pendapatan))
                    <a href="{{ route('laporan.laba-rugi.export-pdf', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai]) }}" class="btn btn-danger">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </a>
                    @endif
                    <button type="button" class="btn btn-success" onclick="window.print()">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($pendapatan))
    <!-- Watermark -->
    @include('partials.print-watermark')

    <!-- Print Header (Hidden on screen, shown on print) -->
    <div class="d-none print-only">
        @include('partials.print-header-screen')
        <div class="text-center mb-4">
            <h3 style="margin: 20px 0 10px 0; font-weight: bold;">LAPORAN LABA RUGI</h3>
            <p class="mb-0">Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} s/d {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4 no-print">
                <h3>LAPORAN LABA RUGI</h3>
                <p class="mb-0">Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} s/d {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</p>
            </div>

            <table class="table">
                <tbody>
                    <!-- PENDAPATAN -->
                    <tr class="table-secondary">
                        <td colspan="2"><strong>PENDAPATAN</strong></td>
                    </tr>
                    @foreach($pendapatan as $item)
                    <tr>
                        <td style="padding-left: 30px">{{ $item->kode_akun }} - {{ $item->nama_akun }}</td>
                        <td class="text-end">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="table-light">
                        <td><strong>TOTAL PENDAPATAN</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <!-- BEBAN -->
                    <tr class="table-secondary">
                        <td colspan="2"><strong>BEBAN</strong></td>
                    </tr>
                    @foreach($beban as $item)
                    <tr>
                        <td style="padding-left: 30px">{{ $item->kode_akun }} - {{ $item->nama_akun }}</td>
                        <td class="text-end">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="table-light">
                        <td><strong>TOTAL BEBAN</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($totalBeban, 0, ',', '.') }}</strong></td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <!-- LABA/RUGI -->
                    <tr class="table-primary">
                        <td><strong>{{ $labaRugi >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</strong></td>
                        <td class="text-end">
                            <strong>Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}</strong>
                        </td>
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
    
    .sidebar, .btn, form, .no-print {
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
        position: relative;
        z-index: 1;
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
