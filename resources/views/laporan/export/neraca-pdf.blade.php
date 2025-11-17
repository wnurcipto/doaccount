<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Neraca</title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 6px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .col-left, .col-right {
            width: 48%;
            vertical-align: top;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    @php
        $company = \App\Models\CompanyInfo::getInfo(auth()->id());
    @endphp

    <div class="header">
        <div style="display: flex; align-items: flex-start; gap: 20px; margin-bottom: 15px;">
            <!-- Logo di Kiri -->
            <div style="flex-shrink: 0;">
                @if($company->logo)
                    <img src="{{ Storage::url($company->logo) }}" alt="Logo" 
                         style="width: 80px; height: 80px; object-fit: contain;">
                @else
                    <div style="width: 80px; height: 80px; background: #f0f0f0; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 1.5rem; color: #999;">LOGO</span>
                    </div>
                @endif
            </div>
            
            <!-- Informasi di Kanan -->
            <div style="flex: 1; min-width: 0;">
                <h3 style="margin: 0 0 8px 0; font-weight: bold; color: #333; font-size: 18px;">{{ $company->nama_perusahaan ?? 'PT. Rama Advertize' }}</h3>
                @if($company->alamat)
                    <p style="margin: 3px 0; font-size: 11px; color: #666; line-height: 1.4;">{{ $company->alamat }}</p>
                @endif
                <p style="margin: 3px 0; font-size: 11px; color: #666; line-height: 1.4;">
                    @if($company->kota && $company->provinsi)
                        {{ $company->kota }}, {{ $company->provinsi }}
                        @if($company->kode_pos) {{ $company->kode_pos }} @endif
                    @endif
                </p>
                <p style="margin: 3px 0; font-size: 11px; color: #666; line-height: 1.4;">
                    @if($company->telepon) Telp: {{ $company->telepon }} @endif
                    @if($company->telepon && $company->email) | @endif
                    @if($company->email) Email: {{ $company->email }} @endif
                    @if(($company->telepon || $company->email) && $company->website) | @endif
                    @if($company->website) Website: {{ $company->website }} @endif
                </p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 15px;">
            <h3 style="margin: 0 0 5px 0; font-size: 18px; font-weight: bold;">NERACA</h3>
            <p style="margin: 3px 0; font-size: 11px;">Per {{ date('d F Y', strtotime($tanggal)) }}</p>
        </div>
    </div>

    <table>
        <tbody>
            <tr>
                <td class="col-left">
                    <!-- ASET -->
                    <table style="width: 100%;">
                        <tr style="background-color: #e9ecef;">
                            <td colspan="2"><strong>ASET</strong></td>
                        </tr>
                        @foreach($aset as $item)
                        <tr>
                            <td style="padding-left: 20px;">{{ $item->kode_akun }} - {{ $item->nama_akun }}</td>
                            <td class="text-end">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr style="background-color: #f8f9fa;">
                            <td><strong>TOTAL ASET</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($totalAset, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </td>
                <td class="col-right">
                    <!-- LIABILITAS -->
                    <table style="width: 100%;">
                        <tr style="background-color: #e9ecef;">
                            <td colspan="2"><strong>LIABILITAS</strong></td>
                        </tr>
                        @foreach($liabilitas as $item)
                        <tr>
                            <td style="padding-left: 20px;">{{ $item->kode_akun }} - {{ $item->nama_akun }}</td>
                            <td class="text-end">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr style="background-color: #f8f9fa;">
                            <td><strong>TOTAL LIABILITAS</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($totalLiabilitas, 0, ',', '.') }}</strong></td>
                        </tr>

                        <tr><td colspan="2" style="border: none; height: 10px;"></td></tr>

                        <!-- EKUITAS -->
                        <tr style="background-color: #e9ecef;">
                            <td colspan="2"><strong>EKUITAS</strong></td>
                        </tr>
                        @foreach($ekuitas as $item)
                        <tr>
                            <td style="padding-left: 20px;">{{ $item->kode_akun }} - {{ $item->nama_akun }}</td>
                            <td class="text-end">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        @if($labaRugiTahunBerjalan != 0)
                        <tr>
                            <td style="padding-left: 20px;">Laba/Rugi Tahun Berjalan</td>
                            <td class="text-end">Rp {{ number_format($labaRugiTahunBerjalan, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr style="background-color: #f8f9fa;">
                            <td><strong>TOTAL EKUITAS</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($totalEkuitas + $labaRugiTahunBerjalan, 0, ',', '.') }}</strong></td>
                        </tr>

                        <tr><td colspan="2" style="border: none; height: 10px;"></td></tr>

                        <tr style="background-color: #cfe2ff;">
                            <td><strong>TOTAL LIABILITAS + EKUITAS</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($totalLiabilitasEkuitas, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }}</p>
    </div>
</body>
</html>

