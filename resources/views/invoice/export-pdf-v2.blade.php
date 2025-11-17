<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice V2 - {{ $invoice->no_invoice }}</title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            position: relative;
        }
        /* Watermark dengan logo perusahaan */
        @if($company->logo)
        body::before {
            content: '';
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            z-index: 0;
            opacity: 0.08;
            pointer-events: none;
            width: 600px;
            height: 600px;
            background-image: url('{{ Storage::url($company->logo) }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        @else
        body::before {
            content: '{{ $company->nama_perusahaan ?? "DOCUMENT" }}';
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
        @endif
        
        .content {
            position: relative;
            z-index: 1;
        }
        
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header .logo-section {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }
        .header .logo-section img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .header .company-name {
            margin: 0 0 8px 0;
            font-weight: bold;
            color: #333;
            font-size: 18px;
        }
        .header .company-info {
            font-size: 11px;
            color: #666;
            margin: 3px 0;
            line-height: 1.4;
        }
        .invoice-title {
            text-align: center;
            margin-top: 15px;
        }
        .invoice-title h2 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            color: #6f42c1;
        }
        .invoice-info {
            text-align: right;
            font-size: 9px;
        }
        .invoice-to {
            margin: 15px 0;
            font-size: 9px;
        }
        .invoice-to strong {
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
            position: relative;
            z-index: 1;
        }
        th, td {
            padding: 6px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #212529;
            color: white;
            font-weight: bold;
            font-size: 10px;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .nowrap {
            white-space: nowrap;
        }
        .footer {
            margin-top: 30px;
            font-size: 9px;
        }
        .footer-section {
            margin-bottom: 15px;
        }
        .footer-section p {
            margin: 2px 0;
            font-size: 9px;
        }
        .signature-area {
            position: relative;
            text-align: right;
            min-height: 120px;
        }
        .signature-area .watermark-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150px;
            height: 150px;
            opacity: 0.15;
            z-index: 0;
        }
        .signature-area .signature-line {
            position: relative;
            z-index: 1;
            padding-top: 60px;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin: 0 auto 5px auto;
            height: 50px;
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Header Invoice -->
        <div class="header">
            <div class="logo-section">
                @if($company->logo)
                    <img src="{{ Storage::url($company->logo) }}" alt="Logo">
                @else
                    <div style="width: 80px; height: 80px; background: #f0f0f0; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 1.5rem; color: #999;">LOGO</span>
                    </div>
                @endif
                <div style="flex: 1; min-width: 0;">
                    <h3 class="company-name">{{ $company->nama_perusahaan ?? 'PT. Rama Advertize' }}</h3>
                    @if($company->alamat)
                        <p class="company-info">{{ $company->alamat }}</p>
                    @endif
                    <p class="company-info">
                        @if($company->kota && $company->provinsi)
                            {{ $company->kota }}, {{ $company->provinsi }}
                            @if($company->kode_pos) {{ $company->kode_pos }} @endif
                        @endif
                    </p>
                    <p class="company-info">
                        @if($company->telepon) Telp: {{ $company->telepon }} @endif
                        @if($company->telepon && $company->email) | @endif
                        @if($company->email) Email: {{ $company->email }} @endif
                        @if(($company->telepon || $company->email) && $company->website) | @endif
                        @if($company->website) Website: {{ $company->website }} @endif
                    </p>
                </div>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
            </div>
        </div>

        <!-- Invoice Info & To -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <div style="flex: 1;">
                <p style="margin: 0; font-size: 9px;"><strong>No. Invoice:</strong> {{ $invoice->no_invoice }}</p>
                <p style="margin: 2px 0 0 0; font-size: 9px;"><strong>Tanggal:</strong> {{ date('d F Y', strtotime($invoice->tanggal)) }}</p>
            </div>
            <div style="flex: 1; text-align: right;">
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
        <div style="margin-bottom: 15px;">
            <strong style="font-size: 10px;">Keterangan:</strong>
            <div style="border: 1px solid #ddd; border-radius: 4px; padding: 8px; background-color: #f8f9fa; font-size: 9px;">
                {{ $invoice->keterangan }}
            </div>
        </div>
        @endif

        <hr style="margin: 15px 0;">

        <!-- Detail Items -->
        <p style="margin: 0 0 5px 0; font-size: 11px; font-weight: bold;">Detail Item</p>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Item</th>
                    <th width="20%">Deskripsi</th>
                    <th width="8%" class="text-center">Qty</th>
                    <th width="10%" class="text-center">Satuan</th>
                    <th width="15%" class="text-end nowrap">Harga</th>
                    <th width="17%" class="text-end nowrap">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item->nama_item }}</strong></td>
                    <td>{{ $item->deskripsi ?? '-' }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-center">{{ $item->satuan ?? '-' }}</td>
                    <td class="text-end nowrap">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-end nowrap">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                
                <!-- Total -->
                <tr style="background-color: #f8f9fa;">
                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>Subtotal</strong></td>
                    <td class="text-end nowrap" style="font-size: 11px;"><strong>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</strong></td>
                </tr>
                @if($invoice->diskon > 0)
                <tr style="background-color: #f8f9fa;">
                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>Diskon</strong></td>
                    <td class="text-end nowrap" style="font-size: 11px;"><strong>Rp {{ number_format($invoice->diskon, 0, ',', '.') }}</strong></td>
                </tr>
                @endif
                @if($invoice->ppn > 0)
                <tr style="background-color: #f8f9fa;">
                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>PPN</strong></td>
                    <td class="text-end nowrap" style="font-size: 11px;"><strong>Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</strong></td>
                </tr>
                @endif
                <tr style="background-color: #d1e7dd;">
                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>TOTAL</strong></td>
                    <td class="text-end nowrap" style="font-size: 11px;"><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
                </tr>
                @if(($invoice->dp ?? 0) > 0)
                @php
                    $sisaTagihan = $invoice->total - ($invoice->dp ?? 0);
                @endphp
                <tr style="background-color: #f8f9fa;">
                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>DP (Uang Muka)</strong></td>
                    <td class="text-end nowrap" style="font-size: 11px;"><strong>Rp {{ number_format($invoice->dp, 0, ',', '.') }}</strong></td>
                </tr>
                <tr style="background-color: #fff3cd;">
                    <td colspan="6" class="text-end" style="font-size: 11px;"><strong>SISA TAGIHAN</strong></td>
                    <td class="text-end nowrap" style="font-size: 11px;"><strong>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</strong></td>
                </tr>
                @endif
            </tbody>
        </table>

        @if($invoice->catatan)
        <div style="margin-top: 20px;">
            <strong style="font-size: 10px;">Catatan:</strong>
            <div style="border: 1px solid #ddd; border-radius: 4px; padding: 8px; background-color: #f8f9fa; font-size: 9px;">
                {{ $invoice->catatan }}
            </div>
        </div>
        @endif

        <!-- Term & Condition & Payment Terms (Horizontal) -->
        @if($invoice->term_condition || $invoice->payment_terms)
        <div style="display: flex; gap: 20px; margin-top: 30px;">
            <div style="flex: 1;">
                @if($invoice->term_condition)
                <div>
                    <p style="margin: 0 0 5px 0; font-size: 11px; font-weight: bold;">Terms & Conditions:</p>
                    <div style="font-size: 10px; white-space: pre-line; line-height: 1.5;">
                        {{ $invoice->term_condition }}
                    </div>
                </div>
                @endif
            </div>
            <div style="flex: 1;">
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
        <div style="margin-top: 30px; text-align: right;">
            <div class="signature-area" style="display: inline-block;">
                @if($company->logo)
                    <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="watermark-bg">
                @endif
                <div class="signature-line"></div>
                <p style="margin: 5px 0 0 0; font-size: 11px;">
                    @if($invoice->signature_name)
                        {{ $invoice->signature_name }}
                    @else
                        Authorised Sign
                    @endif
                </p>
            </div>
        </div>
    </div>
</body>
</html>

