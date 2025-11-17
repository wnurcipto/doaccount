<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->no_invoice }}</title>
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
            background-color: #0d6efd;
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
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .summary-table td {
            padding: 5px;
            font-size: 10px;
            border: 1px solid #ddd;
        }
        .summary-table td:last-child {
            white-space: nowrap;
            text-align: right;
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
            <div class="invoice-info" style="text-align: right; margin-top: 10px;">
                <p style="margin: 0;"><strong>Invoice#</strong> {{ $invoice->no_invoice }}</p>
                <p style="margin: 3px 0 0 0;"><strong>Date</strong> {{ date('d-m-Y', strtotime($invoice->tanggal)) }}</p>
            </div>
        </div>

        <!-- Invoice To -->
        <div class="invoice-to">
            <p style="margin: 0;"><strong>Invoice to: {{ $invoice->kepada_nama }}</strong></p>
            @if($invoice->kepada_alamat)
                <p style="margin: 2px 0;">{{ $invoice->kepada_alamat }}</p>
            @endif
            @if($invoice->kepada_kota)
                <p style="margin: 2px 0;">{{ $invoice->kepada_kota }}</p>
            @endif
            @if($invoice->kepada_telepon)
                <p style="margin: 2px 0;">Telp: {{ $invoice->kepada_telepon }}</p>
            @endif
        </div>

        <hr style="margin: 15px 0;">

        <!-- Detail Items & Summary -->
        <div style="display: flex; gap: 20px;">
            <div style="flex: 2;">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Description</th>
                            <th width="20%" class="text-end nowrap">Price</th>
                            <th width="10%" class="text-center">Qty</th>
                            <th width="30%" class="text-end nowrap">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->nama_item }}</strong>
                                @if($item->deskripsi)
                                    <br><small style="color: #666;">{{ $item->deskripsi }}</small>
                                @endif
                            </td>
                            <td class="text-end nowrap">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-end nowrap">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        @for($i = count($invoice->items); $i < 10; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>&nbsp;</td>
                            <td class="text-end">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-end">Rp -</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            
            <div style="flex: 1;">
                <table class="summary-table">
                    <tr>
                        <td style="text-align: right;"><strong>SUBTOTAL</strong></td>
                        <td class="text-end nowrap"><strong>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</strong></td>
                    </tr>
                    @if($invoice->diskon > 0)
                    @php
                        $diskonPercent = ($invoice->diskon / $invoice->subtotal) * 100;
                    @endphp
                    <tr>
                        <td style="text-align: right;"><strong>DISCONT</strong></td>
                        <td class="text-end nowrap"><strong>{{ number_format($diskonPercent, 1) }}% Rp {{ number_format($invoice->diskon, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endif
                    <tr style="background-color: #d1e7dd;">
                        <td style="text-align: right;"><strong>PAYMENT</strong></td>
                        <td class="text-end nowrap"><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
                    </tr>
                    @if(($invoice->dp ?? 0) > 0)
                    @php
                        $sisaTagihan = $invoice->total - ($invoice->dp ?? 0);
                    @endphp
                    <tr>
                        <td style="text-align: right;"><strong>DP (Uang Muka)</strong></td>
                        <td class="text-end nowrap"><strong>Rp {{ number_format($invoice->dp, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr style="background-color: #fff3cd;">
                        <td style="text-align: right;"><strong>SISA TAGIHAN</strong></td>
                        <td class="text-end nowrap"><strong>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <p style="margin: 0 0 15px 0; font-weight: bold;"><strong>Thank you for your business</strong></p>
                    
                    @if($invoice->payment_terms)
                    <div class="footer-section">
                        <p style="margin: 0 0 5px 0; font-weight: bold;">Payment Info:</p>
                        <div style="white-space: pre-line; line-height: 1.5;">
                            {{ $invoice->payment_terms }}
                        </div>
                    </div>
                    @endif
                    
                    @if($invoice->term_condition)
                    <div class="footer-section">
                        <p style="margin: 0 0 5px 0; font-weight: bold;">Terms & Conditions:</p>
                        <div style="white-space: pre-line; line-height: 1.5;">
                            {{ $invoice->term_condition }}
                        </div>
                    </div>
                    @endif
                </div>
                
                <div style="flex: 1; text-align: right;">
                    <div class="signature-area">
                        @if($company->logo)
                            <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="watermark-bg">
                        @endif
                        <div class="signature-line"></div>
                        <p style="margin: 5px 0 0 0;">
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
</body>
</html>

