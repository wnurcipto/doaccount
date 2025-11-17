@extends('layouts.app')

@section('title', 'Laporan Neraca')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Laporan Neraca</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.neraca') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">Per Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                           value="{{ request('tanggal', $defaultTanggal ?? date('Y-m-d')) }}" 
                           @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner)
                               min="2024-01-01" max="2024-12-31"
                           @endif
                           required>
                    @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner)
                        <small class="text-muted">Akun Free hanya dapat melihat data tahun 2024</small>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Tampilkan Laporan
                    </button>
                    @if(isset($aset))
                    <a href="{{ route('laporan.neraca.export-pdf', ['tanggal' => $tanggal]) }}" class="btn btn-danger">
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

    @if(isset($aset))
    <!-- Watermark -->
    @include('partials.print-watermark')

    <!-- Print Header (Hidden on screen, shown on print) -->
    <div class="d-none print-only">
        @include('partials.print-header-screen')
        <div class="text-center mb-4">
            <h3 style="margin: 20px 0 10px 0; font-weight: bold;">NERACA</h3>
            <p class="mb-0">Per {{ date('d F Y', strtotime($tanggal)) }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4 no-print">
                <h3>NERACA</h3>
                <p class="mb-0">Per {{ date('d F Y', strtotime($tanggal)) }}</p>
            </div>

            <div class="row">
                <!-- KOLOM KIRI: ASET -->
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tbody>
                            <tr class="table-secondary">
                                <td colspan="2"><strong>ASET</strong></td>
                            </tr>
                            @foreach($aset as $item)
                            <tr>
                                <td style="padding-left: 20px">{{ $item->kode_akun }} - {{ $item->nama_akun }}</td>
                                <td class="text-end">{{ number_format($item->saldo, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-light">
                                <td><strong>TOTAL ASET</strong></td>
                                <td class="text-end"><strong>{{ number_format($totalAset, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- KOLOM KANAN: LIABILITAS & EKUITAS -->
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tbody>
                            <!-- LIABILITAS -->
                            <tr class="table-secondary">
                                <td colspan="2"><strong>LIABILITAS</strong></td>
                            </tr>
                            @foreach($liabilitas as $item)
                            <tr>
                                <td style="padding-left: 20px">{{ $item->kode_akun }} - {{ $item->nama_akun }}</td>
                                <td class="text-end">{{ number_format($item->saldo, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-light">
                                <td><strong>TOTAL LIABILITAS</strong></td>
                                <td class="text-end"><strong>{{ number_format($totalLiabilitas, 0, ',', '.') }}</strong></td>
                            </tr>

                            <tr><td colspan="2">&nbsp;</td></tr>

                            <!-- EKUITAS -->
                            <tr class="table-secondary">
                                <td colspan="2"><strong>EKUITAS</strong></td>
                            </tr>
                            @foreach($ekuitas as $item)
                            <tr>
                                <td style="padding-left: 20px">{{ $item->kode_akun }} - {{ $item->nama_akun }}</td>
                                <td class="text-end">{{ number_format($item->saldo, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td style="padding-left: 20px">Laba Rugi Tahun Berjalan</td>
                                <td class="text-end">{{ number_format($labaRugiTahunBerjalan, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>TOTAL EKUITAS</strong></td>
                                <td class="text-end"><strong>{{ number_format($totalEkuitas + $labaRugiTahunBerjalan, 0, ',', '.') }}</strong></td>
                            </tr>

                            <tr><td colspan="2">&nbsp;</td></tr>

                            <!-- TOTAL -->
                            <tr class="table-primary">
                                <td><strong>TOTAL LIABILITAS & EKUITAS</strong></td>
                                <td class="text-end"><strong>{{ number_format($totalLiabilitasEkuitas, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if(abs($totalAset - $totalLiabilitasEkuitas) > 0.01)
            <div class="alert alert-warning mt-3">
                <strong>Perhatian:</strong> Neraca tidak balance! 
                Selisih: Rp {{ number_format(abs($totalAset - $totalLiabilitasEkuitas), 0, ',', '.') }}
            </div>
            @else
            <div class="alert alert-success mt-3">
                <strong>Neraca Balance!</strong> Total Aset sama dengan Total Liabilitas & Ekuitas.
            </div>
            @endif
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
    
    .sidebar, .btn, form, .no-print, .alert {
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
