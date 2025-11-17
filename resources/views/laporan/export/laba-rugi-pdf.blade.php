<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
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
        .text-center {
            text-align: center;
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
            <h3 style="margin: 0 0 5px 0; font-size: 18px; font-weight: bold;">LAPORAN LABA RUGI</h3>
            <p style="margin: 3px 0; font-size: 11px;">Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} s/d {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</p>
        </div>
    </div>

    <table>
        <tbody>
            <!-- PENDAPATAN -->
            <tr style="background-color: #e9ecef;">
                <td colspan="2"><strong>PENDAPATAN</strong></td>
            </tr>
            @foreach($pendapatan as $item)
            <tr>
                <td style="padding-left: 30px;">{{ $item->kode_akun }} - {{ $item->nama_akun }}</td>
                <td class="text-end">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #f8f9fa;">
                <td><strong>TOTAL PENDAPATAN</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
            </tr>

            <tr><td colspan="2" style="border: none; height: 10px;"></td></tr>

            <!-- BEBAN -->
            <tr style="background-color: #e9ecef;">
                <td colspan="2"><strong>BEBAN</strong></td>
            </tr>
            @foreach($beban as $item)
            <tr>
                <td style="padding-left: 30px;">{{ $item->kode_akun }} - {{ $item->nama_akun }}</td>
                <td class="text-end">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #f8f9fa;">
                <td><strong>TOTAL BEBAN</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($totalBeban, 0, ',', '.') }}</strong></td>
            </tr>

            <tr><td colspan="2" style="border: none; height: 10px;"></td></tr>

            <!-- LABA/RUGI -->
            <tr style="background-color: #cfe2ff;">
                <td><strong>{{ $labaRugi >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</strong></td>
                <td class="text-end">
                    <strong>Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }}</p>
    </div>
</body>
</html>

