<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Besar - {{ $coa->nama_akun }}</title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 10px;
        }
        .info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9px;
        }
        th, td {
            padding: 5px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: white;
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .footer {
            margin-top: 20px;
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
            <h3 style="margin: 0 0 5px 0; font-size: 16px; font-weight: bold;">BUKU BESAR</h3>
            <p style="margin: 3px 0; font-size: 11px;">{{ $coa->kode_akun }} - {{ $coa->nama_akun }}</p>
            <p style="margin: 3px 0; font-size: 11px;">Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} s/d {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</p>
        </div>
    </div>

    <div class="info">
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 50%;">
                    <strong>Kode Akun:</strong> {{ $coa->kode_akun }}<br>
                    <strong>Nama Akun:</strong> {{ $coa->nama_akun }}<br>
                    <strong>Tipe Akun:</strong> {{ $coa->tipe_akun }}<br>
                    <strong>Posisi Normal:</strong> {{ $coa->posisi_normal }}
                </td>
                <td style="border: none; width: 50%;">
                    <strong>Saldo Awal:</strong> Rp {{ number_format($saldoAwal, 0, ',', '.') }}<br>
                    <strong>Total Transaksi:</strong> {{ $transaksiWithSaldo->count() }} transaksi
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="12%">Tanggal</th>
                <th width="15%">No. Bukti</th>
                <th width="35%">Keterangan</th>
                <th width="12%" class="text-end">Debit</th>
                <th width="12%" class="text-end">Kredit</th>
                <th width="14%" class="text-end">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <!-- Saldo Awal -->
            <tr style="background-color: #e9ecef;">
                <td colspan="3"><strong>Saldo Awal</strong></td>
                <td class="text-end">-</td>
                <td class="text-end">-</td>
                <td class="text-end"><strong>Rp {{ number_format($saldoAwal, 0, ',', '.') }}</strong></td>
            </tr>

            <!-- Transaksi -->
            @foreach($transaksiWithSaldo as $item)
            <tr>
                <td>{{ date('d/m/Y', strtotime($item->jurnalHeader->tanggal_transaksi)) }}</td>
                <td>{{ $item->jurnalHeader->no_bukti }}</td>
                <td>{{ $item->jurnalHeader->deskripsi }}</td>
                <td class="text-end">{{ $item->posisi == 'Debit' ? 'Rp ' . number_format($item->jumlah, 0, ',', '.') : '-' }}</td>
                <td class="text-end">{{ $item->posisi == 'Kredit' ? 'Rp ' . number_format($item->jumlah, 0, ',', '.') : '-' }}</td>
                <td class="text-end">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            <!-- Saldo Akhir -->
            @if($transaksiWithSaldo->count() > 0)
            @php
                $saldoAkhir = $transaksiWithSaldo->last()->saldo;
            @endphp
            <tr style="background-color: #f8f9fa;">
                <td colspan="3"><strong>Saldo Akhir</strong></td>
                <td class="text-end">-</td>
                <td class="text-end">-</td>
                <td class="text-end"><strong>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }}</p>
    </div>
</body>
</html>

