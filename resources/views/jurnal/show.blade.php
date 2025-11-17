@extends('layouts.app')

@section('title', 'Detail Jurnal')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Jurnal</h2>
        <div>
            @if($jurnal->status == 'Draft')
                <form action="{{ route('jurnal.post', $jurnal) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin memposting jurnal ini?')">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Post Jurnal
                    </button>
                </form>
            @endif
            @if($jurnal->status == 'Draft' || (auth()->user()->is_owner && $jurnal->status != 'Void'))
                <a href="{{ route('jurnal.edit', $jurnal) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            @endif
            <button type="button" class="btn btn-info" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
            @php
                $filters = $filters ?? session('jurnal_filter', []);
                $backUrl = route('jurnal.index');
                if (!empty($filters)) {
                    $backUrl .= '?' . http_build_query($filters);
                }
            @endphp
            <a href="{{ $backUrl }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Watermark -->
    @include('partials.print-watermark')

    <!-- Print Header (Hidden on screen, shown on print) -->
    <div class="d-none print-only">
        @include('partials.print-header-screen')
        <div class="text-center mb-4">
            <h4 style="margin: 20px 0 10px 0; font-weight: bold;">JURNAL UMUM</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Header Jurnal -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">No. Bukti</th>
                            <td>: <strong>{{ $jurnal->no_bukti }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tanggal Transaksi</th>
                            <td>: {{ $jurnal->tanggal_transaksi->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Periode</th>
                            <td>: {{ $jurnal->periode->nama_periode }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($jurnal->status == 'Draft')
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @elseif($jurnal->status == 'Posted')
                                    <span class="badge bg-success">Posted</span>
                                @else
                                    <span class="badge bg-danger">Void</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">Dibuat Oleh</th>
                            <td>: {{ $jurnal->user->name ?? 'System' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>: {{ $jurnal->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Total Debit</th>
                            <td>: <strong>Rp {{ number_format($jurnal->total_debit, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <th>Total Kredit</th>
                            <td>: <strong>Rp {{ number_format($jurnal->total_kredit, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mb-3">
                <strong>Deskripsi:</strong>
                <p class="border rounded p-2 bg-light">{{ $jurnal->deskripsi }}</p>
            </div>

            <hr>

            <!-- Detail Jurnal -->
            <h5 class="mb-3">Detail Transaksi</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th width="15%">Kode Akun</th>
                            <th width="30%">Nama Akun</th>
                            <th width="20%">Keterangan</th>
                            <th width="12%" class="text-end">Debit</th>
                            <th width="12%" class="text-end">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jurnal->details as $detail)
                        <tr>
                            <td>{{ $detail->coa->kode_akun }}</td>
                            <td>{{ $detail->coa->nama_akun }}</td>
                            <td>{{ $detail->keterangan ?? '-' }}</td>
                            <td class="text-end">
                                @if($detail->posisi == 'Debit')
                                    <strong>{{ number_format($detail->jumlah, 0, ',', '.') }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end">
                                @if($detail->posisi == 'Kredit')
                                    <strong>{{ number_format($detail->jumlah, 0, ',', '.') }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        
                        <!-- Total -->
                        <tr class="table-success">
                            <td colspan="3" class="text-end"><strong>TOTAL</strong></td>
                            <td class="text-end"><strong>{{ number_format($jurnal->total_debit, 0, ',', '.') }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($jurnal->total_kredit, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Status Balance -->
            @if($jurnal->isBalanced())
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> 
                    <strong>Jurnal Balance!</strong> Total Debit sama dengan Total Kredit.
                </div>
            @else
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> 
                    <strong>Jurnal Tidak Balance!</strong> Total Debit tidak sama dengan Total Kredit.
                    Selisih: Rp {{ number_format(abs($jurnal->total_debit - $jurnal->total_kredit), 0, ',', '.') }}
                </div>
            @endif

            @if($jurnal->status == 'Draft')
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                Jurnal ini masih dalam status <strong>Draft</strong>. Anda dapat mengedit atau mempostingnya.
            </div>
            @endif
        </div>
    </div>

    <!-- Print Footer -->
    <div class="d-none print-only">
        @include('partials.print-footer')
    </div>
</div>

@push('styles')
<style>
@media print {
    @page {
        margin: 1cm;
        size: A4;
    }
    
    
    .btn, .sidebar, .alert, .d-flex.justify-content-between {
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
    
    h2, h4, h5 {
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
