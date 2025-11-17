@extends('layouts.app')

@section('title', 'Detail Invoice - ' . $invoice->no_invoice)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Invoice</h2>
        <div>
            <a href="{{ route('invoice.export-pdf', $invoice) }}" class="btn btn-danger">
                <i class="bi bi-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('invoice.export-excel', $invoice) }}" class="btn btn-success">
                <i class="bi bi-file-excel"></i> Export Excel
            </a>
            <button type="button" class="btn btn-info" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
            <a href="{{ route('invoice.show-v2', $invoice) }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark"></i> Template V2
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

    <div class="card">
        <div class="card-body">
            @php
                $company = \App\Models\CompanyInfo::getInfo(auth()->id());
            @endphp

            <!-- Header Invoice -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="d-flex align-items-start" style="gap: 15px;">
                        @if($company->logo)
                            <img src="{{ Storage::url($company->logo) }}" alt="Logo" 
                                 style="width: 70px; height: 70px; object-fit: contain;">
                        @endif
                        <div>
                            <h3 style="margin: 0; font-weight: bold; color: #0d6efd; font-size: 20px;">{{ $company->nama_perusahaan ?? 'PT. Rama Advertize' }}</h3>
                            @if($company->alamat)
                                <p style="margin: 3px 0; font-size: 10px; color: #333;">{{ $company->alamat }}</p>
                            @endif
                            @if($company->kota && $company->provinsi)
                                <p style="margin: 3px 0; font-size: 10px; color: #333;">{{ $company->kota }}, {{ $company->provinsi }} {{ $company->kode_pos ?? '' }}</p>
                            @endif
                            @if($company->telepon || $company->email || $company->website)
                                <p style="margin: 3px 0; font-size: 9px; color: #666;">
                                    @if($company->telepon)Telp: {{ $company->telepon }}@endif
                                    @if($company->telepon && $company->email) | @endif
                                    @if($company->email)Email: {{ $company->email }}@endif
                                    @if(($company->telepon || $company->email) && $company->website) | @endif
                                    @if($company->website)Website: {{ $company->website }}@endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <!-- INVOICE Title - Center below company info -->
                    <div class="text-center mt-2">
                        <h2 style="margin: 0; font-weight: bold; color: #6f42c1; font-size: 28px;">INVOICE</h2>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <p style="margin: 0; font-size: 9px;"><strong>No. Invoice:</strong> {{ $invoice->no_invoice }}</p>
                    <p style="margin: 2px 0 0 0; font-size: 9px;"><strong>Tanggal:</strong> {{ date('d F Y', strtotime($invoice->tanggal)) }}</p>
                    @php
                        $statusColors = [
                            'Draft' => 'secondary',
                            'Sent' => 'info',
                            'Paid' => 'success',
                            'Overdue' => 'danger'
                        ];
                        $color = $statusColors[$invoice->status ?? 'Draft'] ?? 'secondary';
                    @endphp
                    <p style="margin: 2px 0 0 0;">
                        <span class="badge bg-{{ $color }}">{{ $invoice->status ?? 'Draft' }}</span>
                    </p>
                </div>
            </div>

            <!-- Invoice To -->
            <div class="mb-3">
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

            <hr>

            <!-- Detail Items & Summary -->
            <div class="row">
                <div class="col-md-8">
                    <p style="margin: 0 0 5px 0; font-size: 11px; font-weight: bold;">Detail Item</p>
                    <table class="table table-bordered" style="margin-bottom: 0;">
                        <thead style="background-color: #0d6efd; color: white;">
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
                        </tbody>
                    </table>
                </div>
                
                <div class="col-md-4">
                    <!-- Summary Pembayaran -->
                    <div class="text-end" style="margin-top: 40px;">
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                            <tr>
                                <td style="text-align: right; padding: 5px; font-size: 11px; border: 1px solid #ddd;"><strong>SUBTOTAL</strong></td>
                                <td style="text-align: right; padding: 5px; font-size: 11px; border: 1px solid #ddd; white-space: nowrap;"><strong>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                            @if($invoice->diskon > 0)
                            @php
                                $diskonPercent = ($invoice->diskon / $invoice->subtotal) * 100;
                            @endphp
                            <tr>
                                <td style="text-align: right; padding: 5px; font-size: 11px; border: 1px solid #ddd;"><strong>DISCONT</strong></td>
                                <td style="text-align: right; padding: 5px; font-size: 11px; border: 1px solid #ddd; white-space: nowrap;">
                                    <strong>{{ number_format($diskonPercent, 1) }}% Rp {{ number_format($invoice->diskon, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            @endif
                            <tr style="background-color: #d1e7dd;">
                                <td style="text-align: right; padding: 5px; font-size: 11px; border: 1px solid #ddd;"><strong>PAYMENT</strong></td>
                                <td style="text-align: right; padding: 5px; font-size: 11px; border: 1px solid #ddd; white-space: nowrap;"><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
                            </tr>
                            @if(($invoice->dp ?? 0) > 0)
                            @php
                                $sisaTagihan = $invoice->total - ($invoice->dp ?? 0);
                            @endphp
                            <tr>
                                <td style="text-align: right; padding: 5px; font-size: 11px; border: 1px solid #ddd;"><strong>DP (Uang Muka)</strong></td>
                                <td style="text-align: right; padding: 5px; font-size: 11px; border: 1px solid #ddd; white-space: nowrap;"><strong>Rp {{ number_format($invoice->dp, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr style="background-color: #fff3cd;">
                                <td style="text-align: right; padding: 5px; font-size: 11px; border: 1px solid #ddd;"><strong>SISA TAGIHAN</strong></td>
                                <td style="text-align: right; padding: 5px; font-size: 11px; border: 1px solid #ddd; white-space: nowrap;"><strong>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</strong></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="row mt-4" style="margin-top: 30px;">
                <div class="col-md-6">
                    <p style="margin: 0 0 15px 0; font-size: 12px; color: #333;"><strong>Thank you for your business</strong></p>
                    
                    @if($invoice->payment_terms)
                    <div style="margin-bottom: 15px;">
                        <p style="margin: 0 0 5px 0; font-size: 11px; font-weight: bold;">Payment Info:</p>
                        <div style="font-size: 10px; white-space: pre-line; line-height: 1.5;">
                            {{ $invoice->payment_terms }}
                        </div>
                    </div>
                    @endif
                    
                    @if($invoice->term_condition)
                    <div>
                        <p style="margin: 0 0 5px 0; font-size: 11px; font-weight: bold;">Terms & Conditions:</p>
                        <div style="font-size: 10px; white-space: pre-line; line-height: 1.5;">
                            {{ $invoice->term_condition }}
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="col-md-6 text-end">
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
        background: transparent !important;
    }
    
    .card-body {
        padding: 10px 0 !important;
        position: relative;
        z-index: 1;
        background: transparent !important;
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
    .col-md-4.text-end p {
        font-size: 9px !important;
    }
    
    .mb-3 p {
        font-size: 9px !important;
    }
    
    /* Atur lebar kolom agar nominal tidak wrap */
    table.table-bordered th,
    table.table-bordered td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    table.table-bordered th:nth-child(3),
    table.table-bordered td:nth-child(3),
    table.table-bordered th:nth-child(5),
    table.table-bordered td:nth-child(5) {
        white-space: nowrap !important;
        min-width: 100px;
    }
    
    /* Summary table - pastikan nominal tidak wrap */
    table[style*="border-collapse"] td:last-child {
        white-space: nowrap !important;
        min-width: 120px;
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
    
    /* Footer styling for print */
    .row.mt-4 p {
        font-size: 10px !important;
    }
    
    .row.mt-4 div[style*="font-size: 10px"] {
        font-size: 9px !important;
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

