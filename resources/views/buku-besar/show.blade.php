@extends('layouts.app')

@section('title', 'Buku Besar - ' . $coa->nama_akun)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Buku Besar</h2>
        <div>
            <a href="{{ route('buku-besar.export-pdf', ['coa_id' => $coa->id, 'tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai]) }}" class="btn btn-danger">
                <i class="bi bi-file-pdf"></i> Export PDF
            </a>
            <button type="button" class="btn btn-success" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
            @php
                $filters = $filters ?? session('buku_besar_filter', []);
                $backUrl = route('buku-besar.index');
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
            <h4 style="margin: 20px 0 10px 0; font-weight: bold;">BUKU BESAR</h4>
            <p class="mb-0">{{ $coa->kode_akun }} - {{ $coa->nama_akun }}</p>
            <p class="mb-0">Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} s/d {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Header Informasi -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th width="35%">Kode Akun</th>
                            <td>: <strong>{{ $coa->kode_akun }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nama Akun</th>
                            <td>: <strong>{{ $coa->nama_akun }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tipe Akun</th>
                            <td>: {{ $coa->tipe_akun }}</td>
                        </tr>
                        <tr>
                            <th>Posisi Normal</th>
                            <td>: {{ $coa->posisi_normal }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th width="35%">Periode</th>
                            <td>: {{ date('d/m/Y', strtotime($tanggalMulai)) }} s/d {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</td>
                        </tr>
                        <tr>
                            <th>Saldo Awal</th>
                            <td>: <strong>Rp {{ number_format($saldoAwal, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <th>Total Transaksi</th>
                            <td>: {{ $transaksiWithSaldo->count() }} transaksi</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <!-- Tabel Transaksi -->
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th width="10%">Tanggal</th>
                            <th width="15%">No. Bukti</th>
                            <th width="30%">Keterangan</th>
                            <th width="12%" class="text-end">Debit</th>
                            <th width="12%" class="text-end">Kredit</th>
                            <th width="15%" class="text-end">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Saldo Awal -->
                        <tr class="table-secondary">
                            <td colspan="3"><strong>Saldo Awal</strong></td>
                            <td class="text-end">-</td>
                            <td class="text-end">-</td>
                            <td class="text-end"><strong>{{ number_format($saldoAwal, 0, ',', '.') }}</strong></td>
                        </tr>

                        @forelse($transaksiWithSaldo as $detail)
                        <tr>
                            <td>{{ $detail->jurnalHeader->tanggal_transaksi->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('jurnal.show', $detail->jurnalHeader) }}" target="_blank">
                                    {{ $detail->jurnalHeader->no_bukti }}
                                </a>
                            </td>
                            <td>
                                {{ $detail->jurnalHeader->deskripsi }}
                                @if($detail->keterangan)
                                    <br><small class="text-muted">{{ $detail->keterangan }}</small>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($detail->posisi == 'Debit')
                                    {{ number_format($detail->jumlah, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end">
                                @if($detail->posisi == 'Kredit')
                                    {{ number_format($detail->jumlah, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end">
                                <strong>{{ number_format($detail->saldo, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada transaksi pada periode ini
                            </td>
                        </tr>
                        @endforelse

                        @if($transaksiWithSaldo->count() > 0)
                        <!-- Saldo Akhir -->
                        <tr class="table-success">
                            <td colspan="3"><strong>Saldo Akhir</strong></td>
                            <td class="text-end">
                                <strong>
                                    {{ number_format($transaksiWithSaldo->where('posisi', 'Debit')->sum('jumlah'), 0, ',', '.') }}
                                </strong>
                            </td>
                            <td class="text-end">
                                <strong>
                                    {{ number_format($transaksiWithSaldo->where('posisi', 'Kredit')->sum('jumlah'), 0, ',', '.') }}
                                </strong>
                            </td>
                            <td class="text-end">
                                <strong>{{ number_format($transaksiWithSaldo->last()->saldo, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if($transaksiWithSaldo->count() == 0)
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i> 
                Tidak ada transaksi yang diposting untuk akun ini pada periode yang dipilih.
            </div>
            @endif
        </div>
    </div>

    <!-- Informasi Tambahan -->
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="text-muted">Ringkasan Mutasi</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td>Saldo Awal</td>
                            <td class="text-end"><strong>Rp {{ number_format($saldoAwal, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td>Total Debit</td>
                            <td class="text-end">Rp {{ number_format($transaksiWithSaldo->where('posisi', 'Debit')->sum('jumlah'), 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Total Kredit</td>
                            <td class="text-end">Rp {{ number_format($transaksiWithSaldo->where('posisi', 'Kredit')->sum('jumlah'), 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Saldo Akhir</strong></td>
                            <td class="text-end">
                                <strong>
                                    Rp {{ number_format($transaksiWithSaldo->count() > 0 ? $transaksiWithSaldo->last()->saldo : $saldoAwal, 0, ',', '.') }}
                                </strong>
                            </td>
                        </tr>
                    </table>
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
    
    
    .btn, .sidebar, .d-flex.justify-content-between {
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
        position: relative;
        z-index: 1;
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
        font-size: 10px;
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
