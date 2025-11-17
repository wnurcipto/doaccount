@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-card-list me-2"></i>Kartu Stok - {{ $barang->nama_barang }}</h2>
        <div>
            <button onclick="window.print()" class="btn btn-success">
                <i class="bi bi-printer"></i> Print
            </button>
            <a href="{{ route('kartu-stok.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info Barang -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="30%">Kode Barang</th>
                            <td>: <strong>{{ $barang->kode_barang }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>: {{ $barang->nama_barang }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>: <span class="badge bg-info">{{ $barang->kategori }}</span></td>
                        </tr>
                        <tr>
                            <th>Satuan</th>
                            <td>: {{ $barang->satuan }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="30%">Harga Beli</th>
                            <td>: Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>: Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Stok Saat Ini</th>
                            <td>: 
                                <span class="badge {{ $barang->stok > $barang->stok_minimal ? 'bg-success' : ($barang->stok > 0 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $barang->stok }} {{ $barang->satuan }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Periode</th>
                            <td>: 
                                @if($tanggalDari && $tanggalSampai)
                                    {{ \Carbon\Carbon::parse($tanggalDari)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalSampai)->format('d/m/Y') }}
                                @else
                                    Semua Periode
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <small class="text-muted">Saldo Awal</small>
                    <h4 class="mb-0">{{ $saldoAwal }}</h4>
                    <small class="text-muted">{{ $barang->satuan }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <small>Total Stok Masuk</small>
                    <h4 class="mb-0">{{ $totalMasuk }}</h4>
                    <small>{{ $barang->satuan }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <small>Total Stok Keluar</small>
                    <h4 class="mb-0">{{ $totalKeluar }}</h4>
                    <small>{{ $barang->satuan }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <small>Saldo Akhir</small>
                    <h4 class="mb-0">{{ $saldoAwal + $totalMasuk - $totalKeluar }}</h4>
                    <small>{{ $barang->satuan }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Stok Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-table me-2"></i>Riwayat Transaksi</h5>
        </div>
        <div class="card-body p-0">
            @if($transaksis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Tanggal</th>
                                <th width="15%">No. Bukti</th>
                                <th>Keterangan</th>
                                <th width="10%" class="text-end">Masuk</th>
                                <th width="10%" class="text-end">Keluar</th>
                                <th width="10%" class="text-end">Saldo</th>
                                <th width="12%" class="text-end">Nilai (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Saldo Awal -->
                            <tr class="table-secondary">
                                <td colspan="4"><strong>Saldo Awal</strong></td>
                                <td class="text-end">-</td>
                                <td class="text-end">-</td>
                                <td class="text-end"><strong>{{ $saldoAwal }}</strong></td>
                                <td class="text-end">{{ number_format($saldoAwal * $barang->harga_beli, 0, ',', '.') }}</td>
                            </tr>

                            @php $saldo = $saldoAwal; @endphp
                            @foreach($transaksis as $index => $trx)
                                @php
                                    $saldo += $trx->masuk - $trx->keluar;
                                    $nilai = ($trx->masuk > 0 ? $trx->masuk * $barang->harga_beli : $trx->keluar * $barang->harga_jual);
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($trx->jenis == 'masuk')
                                            <a href="{{ route('stok-masuk.show', $trx->id) }}" class="text-decoration-none" target="_blank">
                                                {{ $trx->no_bukti }}
                                            </a>
                                        @else
                                            <a href="{{ route('stok-keluar.show', $trx->id) }}" class="text-decoration-none" target="_blank">
                                                {{ $trx->no_bukti }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($trx->jenis == 'masuk')
                                            <i class="bi bi-arrow-down-circle text-info"></i> 
                                            <span class="text-muted">Pembelian dari {{ $trx->supplier ?? $trx->customer }}</span>
                                        @else
                                            <i class="bi bi-arrow-up-circle text-warning"></i> 
                                            <span class="text-muted">Penjualan ke {{ $trx->customer ?? $trx->supplier }}</span>
                                        @endif
                                        @if($trx->keterangan)
                                            <br><small class="text-muted">{{ Str::limit($trx->keterangan, 50) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($trx->masuk > 0)
                                            <span class="badge bg-info">{{ $trx->masuk }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($trx->keluar > 0)
                                            <span class="badge bg-warning">{{ $trx->keluar }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $saldo > $barang->stok_minimal ? 'text-success' : ($saldo > 0 ? 'text-warning' : 'text-danger') }}">
                                            {{ $saldo }}
                                        </strong>
                                    </td>
                                    <td class="text-end text-muted">
                                        {{ number_format($nilai, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach

                            <!-- Saldo Akhir -->
                            <tr class="table-success">
                                <td colspan="4"><strong>Saldo Akhir</strong></td>
                                <td class="text-end"><strong>{{ $totalMasuk }}</strong></td>
                                <td class="text-end"><strong>{{ $totalKeluar }}</strong></td>
                                <td class="text-end"><strong>{{ $saldo }}</strong></td>
                                <td class="text-end"><strong>{{ number_format($saldo * $barang->harga_beli, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-5 text-center">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Belum ada transaksi untuk barang ini</p>
                    <div class="mt-3">
                        <a href="{{ route('stok-masuk.create') }}?barang_id={{ $barang->id }}" class="btn btn-info btn-sm me-2">
                            <i class="bi bi-arrow-down-circle"></i> Tambah Stok Masuk
                        </a>
                        <a href="{{ route('stok-keluar.create') }}?barang_id={{ $barang->id }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-arrow-up-circle"></i> Tambah Stok Keluar
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($transaksis->count() > 0)
    <!-- Keterangan -->
    <div class="card mt-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="bi bi-info-circle text-info"></i> Keterangan:</h6>
                    <ul class="small text-muted">
                        <li><i class="bi bi-arrow-down-circle text-info"></i> = Stok Masuk (Pembelian)</li>
                        <li><i class="bi bi-arrow-up-circle text-warning"></i> = Stok Keluar (Penjualan)</li>
                        <li><strong>Saldo</strong> = Stok tersedia setelah transaksi</li>
                        <li><strong>Nilai</strong> = Saldo × Harga Beli (untuk valuasi inventori)</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6><i class="bi bi-calculator text-success"></i> Rumus Perhitungan:</h6>
                    <ul class="small text-muted">
                        <li>Saldo = Saldo Sebelumnya + Masuk - Keluar</li>
                        <li>Total Masuk = Σ Qty Pembelian</li>
                        <li>Total Keluar = Σ Qty Penjualan</li>
                        <li>Saldo Akhir = Saldo Awal + Total Masuk - Total Keluar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-3 mb-4">
        <div class="col-md-12">
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('barang.show', $barang->id) }}" class="btn btn-outline-primary">
                    <i class="bi bi-box-seam"></i> Detail Barang
                </a>
                <a href="{{ route('stok-masuk.create') }}?barang_id={{ $barang->id }}" class="btn btn-outline-info">
                    <i class="bi bi-arrow-down-circle"></i> Tambah Stok Masuk
                </a>
                <a href="{{ route('stok-keluar.create') }}?barang_id={{ $barang->id }}" class="btn btn-outline-warning">
                    <i class="bi bi-arrow-up-circle"></i> Tambah Stok Keluar
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
@media print {
    .btn, .card-header, nav, .no-print {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    body {
        font-size: 12px;
    }
    table {
        page-break-inside: avoid;
    }
}
</style>
@endsection
