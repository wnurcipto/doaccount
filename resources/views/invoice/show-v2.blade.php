@extends('layouts.app')

@section('title', 'Detail Invoice V2 - ' . $invoice->no_invoice)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Invoice V2</h2>
        <div>
            <a href="{{ route('invoice.export-pdf-v2', $invoice) }}" class="btn btn-danger">
                <i class="bi bi-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('invoice.export-excel-v2', $invoice) }}" class="btn btn-success">
                <i class="bi bi-file-excel"></i> Export Excel
            </a>
            <button type="button" class="btn btn-info" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
            <a href="{{ route('invoice.show', $invoice) }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Template Baru
            </a>
            <a href="{{ route('invoice.edit', $invoice) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('invoice.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Watermark untuk print -->
    @php
        $company = \App\Models\CompanyInfo::getInfo(auth()->id());
    @endphp
    @if($company->logo)
    <div class="print-watermark-logo" style="display: none;">
        <img src="{{ Storage::url($company->logo) }}" alt="Watermark">
    </div>
    @else
    <div class="print-watermark-text" style="display: none;">
        {{ $company->nama_perusahaan ?? 'DOCUMENT' }}
    </div>
    @endif

    <!-- Print Header (Hidden on screen, shown on print) -->
    <div class="d-none print-only">
        @include('partials.print-header-screen')
        <div class="text-center mb-4">
            <h4 style="margin: 20px 0 10px 0; font-weight: bold;">INVOICE</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Header Invoice -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <p style="margin: 0; font-size: 9px;"><strong>No. Invoice:</strong> {{ $invoice->no_invoice }}</p>
                    <p style="margin: 2px 0 0 0; font-size: 9px;"><strong>Tanggal:</strong> {{ date('d F Y', strtotime($invoice->tanggal)) }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p style="margin: 0; font-size: 9px;"><strong>Kepada:</strong></p>
                    <p style="margin: 2px 0; font-size: 9px;"><strong>{{ $invoice->kepada_nama }}</strong></p>
                    @if($invoice->kepada_alamat)
                        <p style="margin: 2px 0; font-size: 9px;">{{ $invoice->kepada_alamat }}</p>
                    @endif
                    @if($invoice->kepada_kota)
                        <p style="margin: 2px 0; font-size: 9px;">{{ $invoice->kepada_kota }}</p>
                    @endif
                    @if($invoice->kepada_telepon)
                        <p style="margin: 2px 0; font-size: 9px;">Telp: {{ $invoice->kepada_telepon }}</p>
                    @endif
                </div>
            </div>

            @if($invoice->keterangan)
            <div class="mb-3">
                <strong>Keterangan:</strong>
                <p class="border rounded p-2 bg-light">{{ $invoice->keterangan }}</p>
            </div>
            @endif

            <hr>

            <!-- Detail Items & Summary -->
            <div class="row">
                <div class="col-md-8">
                    <p style="margin: 0 0 5px 0; font-size: 11px; font-weight: bold;">Detail Item</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%" style="font-size: 11px;">No</th>
                                    <th width="25%" style="font-size: 11px;">Nama Item</th>
                                    <th width="20%" style="font-size: 11px;">Deskripsi</th>
                                    <th width="8%" style="font-size: 11px; text-align: center;">Qty</th>
                                    <th width="10%" style="font-size: 11px; text-align: center;">Satuan</th>
                                    <th width="15%" style="font-size: 11px; text-align: right; white-space: nowrap;">Harga</th>
                                    <th width="17%" style="font-size: 11px; text-align: right; white-space: nowrap;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $index => $item)
                                <tr>
                                    <td style="font-size: 11px;">{{ $index + 1 }}</td>
                                    <td style="font-size: 11px;"><strong>{{ $item->nama_item }}</strong></td>
                                    <td style="font-size: 11px;">{{ $item->deskripsi ?? '-' }}</td>
                                    <td style="font-size: 11px; text-align: center;">{{ $item->qty }}</td>
                                    <td style="font-size: 11px; text-align: center;">{{ $item->satuan ?? '-' }}</td>
                                    <td style="font-size: 11px; text-align: right; white-space: nowrap;">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td style="font-size: 11px; text-align: right; white-space: nowrap;">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                
                                <!-- Total -->
                                <tr class="table-light">
                                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>Subtotal</strong></td>
                                    <td class="text-end" style="font-size: 11px; white-space: nowrap;"><strong>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</strong></td>
                                </tr>
                                @if($invoice->diskon > 0)
                                <tr class="table-light">
                                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>Diskon</strong></td>
                                    <td class="text-end" style="font-size: 11px; white-space: nowrap;"><strong>Rp {{ number_format($invoice->diskon, 0, ',', '.') }}</strong></td>
                                </tr>
                                @endif
                                @if($invoice->ppn > 0)
                                <tr class="table-light">
                                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>PPN</strong></td>
                                    <td class="text-end" style="font-size: 11px; white-space: nowrap;"><strong>Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</strong></td>
                                </tr>
                                @endif
                                <tr class="table-success">
                                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>TOTAL</strong></td>
                                    <td class="text-end" style="font-size: 11px; white-space: nowrap;"><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
                                </tr>
                                @if(($invoice->dp ?? 0) > 0)
                                @php
                                    $sisaTagihan = $invoice->total - ($invoice->dp ?? 0);
                                @endphp
                                <tr class="table-light">
                                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>DP (Uang Muka)</strong></td>
                                    <td class="text-end" style="font-size: 11px; white-space: nowrap;"><strong>Rp {{ number_format($invoice->dp, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr class="table-warning">
                                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>SISA TAGIHAN</strong></td>
                                    <td class="text-end" style="font-size: 11px; white-space: nowrap;"><strong>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</strong></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Space for future content -->
                </div>
            </div>

            @if($invoice->catatan)
            <div class="mt-3">
                <strong>Catatan:</strong>
                <p class="border rounded p-2 bg-light">{{ $invoice->catatan }}</p>
            </div>
            @endif

            <!-- Term & Condition & Payment Terms (Horizontal) -->
            @if($invoice->term_condition || $invoice->payment_terms)
            <div class="row mt-4">
                <div class="col-md-6">
                    @if($invoice->term_condition)
                    <div>
                        <p style="margin: 0 0 5px 0; font-size: 11px; font-weight: bold;">Terms & Conditions:</p>
                        <div style="font-size: 10px; white-space: pre-line; line-height: 1.5;">
                            {{ $invoice->term_condition }}
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-6">
                    @if($invoice->payment_terms)
                    <div>
                        <p style="margin: 0 0 5px 0; font-size: 11px; font-weight: bold;">Payment Info:</p>
                        <div style="font-size: 10px; white-space: pre-line; line-height: 1.5;">
                            {{ $invoice->payment_terms }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Signature Area -->
            <div class="row mt-4">
                <div class="col-md-6"></div>
                <div class="col-md-6 text-end">
                    @php
                        $company = \App\Models\CompanyInfo::getInfo(auth()->id());
                    @endphp
                    <div style="position: relative; display: inline-block; min-height: 120px; min-width: 200px;">
                        @if($company->logo)
                            <img src="{{ Storage::url($company->logo) }}" alt="Logo" 
                                 style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 150px; height: 150px; opacity: 0.15; z-index: 0;">
                        @endif
                        <div style="position: relative; z-index: 1; padding-top: 60px;">
                            <div style="border-bottom: 1px solid #333; width: 200px; margin: 0 auto 5px auto; height: 50px;"></div>
                            <p style="margin: 0; font-size: 11px; color: #666;">
                                @if($invoice->signature_name)
                                    {{ $invoice->signature_name }}
                                @else
                                    Authorised Sign
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
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
    
    /* Watermark dengan logo perusahaan */
    .print-watermark-logo {
        display: block !important;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        z-index: 0;
        opacity: 0.08;
        pointer-events: none;
    }
    
    .print-watermark-logo img {
        width: 600px;
        height: 600px;
        object-fit: contain;
    }
    
    .print-watermark-text {
        display: block !important;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        z-index: 0;
        opacity: 0.1;
        pointer-events: none;
        font-size: 120px;
        font-weight: bold;
        color: #999;
        white-space: nowrap;
    }
    
    /* Perkecil text nomor invoice dan penerima */
    .col-md-6 p {
        font-size: 9px !important;
    }
    
    /* Atur lebar kolom agar nominal tidak wrap */
    table.table-bordered th,
    table.table-bordered td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    table.table-bordered th:nth-child(6),
    table.table-bordered td:nth-child(6),
    table.table-bordered th:nth-child(7),
    table.table-bordered td:nth-child(7) {
        white-space: nowrap !important;
        min-width: 100px;
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
    
    /* Payment Terms & Term & Condition styling for print (Horizontal Layout) */
    .row.mt-4 p[style*="font-weight: bold"] {
        font-size: 10px !important;
        margin-bottom: 5px !important;
    }
    
    .row.mt-4 div[style*="font-size: 10px"] {
        font-size: 9px !important;
        line-height: 1.5 !important;
    }
    
    /* Ensure horizontal layout in print */
    .row.mt-4 .col-md-6 {
        display: inline-block;
        width: 48% !important;
        vertical-align: top;
        padding: 0 10px;
    }
    
    /* Summary table styling */
    .col-md-4 .table {
        font-size: 10px !important;
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

