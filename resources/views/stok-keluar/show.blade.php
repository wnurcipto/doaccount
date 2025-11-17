@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-arrow-up-circle me-2"></i>Detail Stok Keluar</h2>
        <div>
            @if(!$stokKeluar->jurnal_header_id)
            <a href="{{ route('stok-keluar.edit', $stokKeluar->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @endif
            <a href="{{ route('stok-keluar.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informasi Transaksi -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">No. Bukti</th>
                                    <td>: <strong>{{ $stokKeluar->no_bukti }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>: {{ \Carbon\Carbon::parse($stokKeluar->tanggal_keluar)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Periode</th>
                                    <td>: 
                                        <span class="badge bg-{{ $stokKeluar->periode->status == 'Open' ? 'success' : 'secondary' }}">
                                            {{ $stokKeluar->periode->nama_periode }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Customer</th>
                                    <td>: {{ $stokKeluar->customer }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Metode Terima</th>
                                    <td>: 
                                        <span class="badge bg-{{ $stokKeluar->metode_terima == 'tunai' ? 'success' : 'info' }}">
                                            {{ strtoupper($stokKeluar->metode_terima) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>: 
                                        @if($stokKeluar->jurnal_header_id)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Dijurnal
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock"></i> Draft
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat Oleh</th>
                                    <td>: {{ $stokKeluar->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat Pada</th>
                                    <td>: {{ $stokKeluar->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($stokKeluar->keterangan)
                    <hr>
                    <div>
                        <strong>Keterangan:</strong>
                        <p class="mb-0 mt-2">{{ $stokKeluar->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Detail Barang -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Detail Barang</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Harga Jual</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a href="{{ route('barang.show', $stokKeluar->barang->id) }}" class="text-decoration-none">
                                            <strong>{{ $stokKeluar->barang->kode_barang }}</strong>
                                        </a>
                                    </td>
                                    <td>{{ $stokKeluar->barang->nama_barang }}</td>
                                    <td><span class="badge bg-info">{{ $stokKeluar->barang->kategori }}</span></td>
                                    <td class="text-end">{{ $stokKeluar->qty }} {{ $stokKeluar->barang->satuan }}</td>
                                    <td class="text-end">Rp {{ number_format($stokKeluar->harga, 0, ',', '.') }}</td>
                                    <td class="text-end"><strong>Rp {{ number_format($stokKeluar->subtotal, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="5" class="text-end">TOTAL PENJUALAN:</th>
                                    <th class="text-end">Rp {{ number_format($stokKeluar->subtotal, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Perhitungan Laba -->
                    @php
                        $hpp = $stokKeluar->qty * $stokKeluar->barang->harga_beli;
                        $laba = $stokKeluar->subtotal - $hpp;
                        $margin = $stokKeluar->subtotal > 0 ? ($laba / $stokKeluar->subtotal * 100) : 0;
                    @endphp
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <small class="text-muted">Penjualan</small>
                                    <h5 class="text-primary mb-0">Rp {{ number_format($stokKeluar->subtotal, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <small class="text-muted">HPP</small>
                                    <h5 class="text-danger mb-0">Rp {{ number_format($hpp, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card {{ $laba >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                                <div class="card-body text-center">
                                    <small>Laba ({{ number_format($margin, 2) }}%)</small>
                                    <h5 class="mb-0">Rp {{ number_format($laba, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        <strong>Stok Barang Saat Ini:</strong> {{ $stokKeluar->barang->stok }} {{ $stokKeluar->barang->satuan }}
                    </div>
                </div>
            </div>

            <!-- Jurnal yang Terbuat -->
            @if($stokKeluar->jurnal_header_id)
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-journal-check me-2"></i>Jurnal #1: Penjualan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>No. Jurnal:</strong> 
                        <a href="{{ route('jurnal.show', $stokKeluar->jurnalHeader->id) }}" class="text-decoration-none" target="_blank">
                            {{ $stokKeluar->jurnalHeader->no_bukti }} <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode COA</th>
                                    <th>Nama COA</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalDebit = 0; $totalKredit = 0; @endphp
                                @foreach($stokKeluar->jurnalHeader->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->coa->kode_akun }}</td>
                                    <td>{{ $detail->coa->nama_akun }}</td>
                                    <td class="text-end">
                                        @if($detail->posisi === 'Debit')
                                            Rp {{ number_format($detail->jumlah, 0, ',', '.') }}
                                            @php $totalDebit += $detail->jumlah; @endphp
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($detail->posisi === 'Kredit')
                                            Rp {{ number_format($detail->jumlah, 0, ',', '.') }}
                                            @php $totalKredit += $detail->jumlah; @endphp
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">TOTAL:</th>
                                    <th class="text-end">Rp {{ number_format($totalDebit, 0, ',', '.') }}</th>
                                    <th class="text-end">Rp {{ number_format($totalKredit, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($jurnalHPP)
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-journal-check me-2"></i>Jurnal #2: HPP (Harga Pokok Penjualan)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>No. Jurnal HPP:</strong>
                        <a href="{{ route('jurnal.show', $jurnalHPP->id) }}" class="text-decoration-none" target="_blank">
                            {{ $jurnalHPP->no_bukti }} <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode COA</th>
                                    <th>Nama COA</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $hppDebit = 0; $hppKredit = 0; @endphp
                                @foreach($jurnalHPP->details as $i => $d)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $d->coa->kode_akun }}</td>
                                    <td>{{ $d->coa->nama_akun }}</td>
                                    <td class="text-end">
                                        @if($d->posisi === 'Debit')
                                            Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                            @php $hppDebit += $d->jumlah; @endphp
                                        @else - @endif
                                    </td>
                                    <td class="text-end">
                                        @if($d->posisi === 'Kredit')
                                            Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                            @php $hppKredit += $d->jumlah; @endphp
                                        @else - @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">TOTAL:</th>
                                    <th class="text-end">Rp {{ number_format($hppDebit, 0, ',', '.') }}</th>
                                    <th class="text-end">Rp {{ number_format($hppKredit, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>Ringkasan Penjualan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td>Jumlah Item:</td>
                            <td class="text-end"><strong>{{ $stokKeluar->qty }} {{ $stokKeluar->barang->satuan }}</strong></td>
                        </tr>
                        <tr>
                            <td>Harga per Unit:</td>
                            <td class="text-end">Rp {{ number_format($stokKeluar->harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Total Penjualan:</strong></td>
                            <td class="text-end"><strong class="text-success">Rp {{ number_format($stokKeluar->subtotal, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td>HPP:</td>
                            <td class="text-end text-danger">Rp {{ number_format($hpp, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Laba Kotor:</strong></td>
                            <td class="text-end"><strong class="text-primary">Rp {{ number_format($laba, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td>Margin:</td>
                            <td class="text-end"><span class="badge bg-success">{{ number_format($margin, 2) }}%</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-boxes me-2"></i>Informasi Stok</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h6 class="text-muted">Stok Barang Saat Ini</h6>
                        <h2 class="text-success mb-0">{{ $stokKeluar->barang->stok }}</h2>
                        <small class="text-muted">{{ $stokKeluar->barang->satuan }}</small>
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('kartu-stok.show', $stokKeluar->barang->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-card-list"></i> Lihat Kartu Stok
                        </a>
                    </div>
                </div>
            </div>

            @if(!$stokKeluar->jurnal_header_id)
            <div class="card border-warning">
                <div class="card-body">
                    <div class="alert alert-warning mb-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Status Draft</strong><br>
                        <small>Transaksi ini belum dijurnal. Silakan edit jika ada yang perlu diperbaiki.</small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('stok-keluar.edit', $stokKeluar->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Transaksi
                        </a>
                        <form action="{{ route('stok-keluar.destroy', $stokKeluar->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash"></i> Hapus Transaksi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <div class="card border-success">
                <div class="card-body">
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle"></i>
                        <strong>Sudah Dijurnal</strong><br>
                        <small>Transaksi ini sudah dijurnal (2 jurnal: Penjualan + HPP) dan tidak dapat diubah/dihapus.</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
