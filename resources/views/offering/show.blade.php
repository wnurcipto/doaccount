@extends('layouts.app')

@section('title', 'Detail Offering - ' . $offering->no_offering)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Offering</h2>
        <div>
            <button type="button" class="btn btn-info" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
            <a href="{{ route('offering.edit', $offering) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('offering.index') }}" class="btn btn-secondary">
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
            <h4 style="margin: 20px 0 10px 0; font-weight: bold;">OFFERING</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Header Offering -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>No. Offering: <strong>{{ $offering->no_offering }}</strong></h5>
                    <p class="mb-1"><strong>Tanggal:</strong> {{ date('d F Y', strtotime($offering->tanggal)) }}</p>
                    @if($offering->tanggal_berlaku)
                        <p class="mb-1"><strong>Tanggal Berlaku:</strong> {{ date('d F Y', strtotime($offering->tanggal_berlaku)) }}</p>
                    @endif
                </div>
                <div class="col-md-6 text-end">
                    <h5>Kepada:</h5>
                    <p class="mb-1"><strong>{{ $offering->kepada_nama }}</strong></p>
                    @if($offering->kepada_alamat)
                        <p class="mb-1">{{ $offering->kepada_alamat }}</p>
                    @endif
                    @if($offering->kepada_kota)
                        <p class="mb-1">{{ $offering->kepada_kota }}</p>
                    @endif
                    @if($offering->kepada_telepon)
                        <p class="mb-1">Telp: {{ $offering->kepada_telepon }}</p>
                    @endif
                </div>
            </div>

            @if($offering->keterangan)
            <div class="mb-3">
                <strong>Keterangan:</strong>
                <p class="border rounded p-2 bg-light">{{ $offering->keterangan }}</p>
            </div>
            @endif

            <hr>

            <!-- Detail Items -->
            <h5 class="mb-3">Detail Item</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="30%">Nama Item</th>
                            <th width="25%">Deskripsi</th>
                            <th width="8%" class="text-center">Qty</th>
                            <th width="8%">Satuan</th>
                            <th width="12%" class="text-end">Harga</th>
                            <th width="12%" class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offering->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_item }}</td>
                            <td>{{ $item->deskripsi ?? '-' }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td>{{ $item->satuan ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        
                        <!-- Total -->
                        <tr class="table-light">
                            <td colspan="6" class="text-end"><strong>Subtotal</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($offering->subtotal, 0, ',', '.') }}</strong></td>
                        </tr>
                        @if($offering->diskon > 0)
                        <tr class="table-light">
                            <td colspan="6" class="text-end"><strong>Diskon</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($offering->diskon, 0, ',', '.') }}</strong></td>
                        </tr>
                        @endif
                        @if($offering->ppn > 0)
                        <tr class="table-light">
                            <td colspan="6" class="text-end"><strong>PPN</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($offering->ppn, 0, ',', '.') }}</strong></td>
                        </tr>
                        @endif
                        <tr class="table-success">
                            <td colspan="6" class="text-end"><strong>TOTAL</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($offering->total, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($offering->catatan)
            <div class="mt-3">
                <strong>Catatan:</strong>
                <p class="border rounded p-2 bg-light">{{ $offering->catatan }}</p>
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

