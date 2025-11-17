@extends('layouts.app')

@section('title', 'Detail Supplier')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-truck"></i> Detail Supplier</h2>
        <div>
            @if(!(auth()->user()->plan === 'free' && !auth()->user()->is_owner))
            <a href="{{ route('supplier.edit', $supplier) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @endif
            <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Supplier</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Kode Supplier</th>
                            <td>: <strong>{{ $supplier->kode_supplier ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nama Supplier</th>
                            <td>: <strong>{{ $supplier->nama_supplier }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nama Kontak</th>
                            <td>: {{ $supplier->nama_kontak ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>: {{ $supplier->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>: {{ $supplier->telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: {{ $supplier->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kota</th>
                            <td>: {{ $supplier->kota ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Provinsi</th>
                            <td>: {{ $supplier->provinsi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kode Pos</th>
                            <td>: {{ $supplier->kode_pos ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Website</th>
                            <td>: {{ $supplier->website ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($supplier->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                        @if($supplier->keterangan)
                        <tr>
                            <th>Keterangan</th>
                            <td>: {{ $supplier->keterangan }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Saldo Hutang</h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="{{ $supplier->saldo_hutang > 0 ? 'text-danger' : 'text-success' }}">
                        Rp {{ number_format($supplier->saldo_hutang, 0, ',', '.') }}
                    </h2>
                    @if($supplier->saldo_hutang > 0)
                        <p class="text-muted mb-0">Hutang yang belum dilunasi</p>
                    @else
                        <p class="text-muted mb-0">Tidak ada hutang</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Hutang -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">History Transaksi Hutang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Bukti</th>
                            <th>Deskripsi</th>
                            <th class="text-end">Debit</th>
                            <th class="text-end">Kredit</th>
                            <th class="text-end">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $saldo = 0;
                        @endphp
                        @forelse($transaksiHutang as $transaksi)
                            @php
                                if ($transaksi->posisi == 'Kredit') {
                                    $saldo += $transaksi->jumlah;
                                } else {
                                    $saldo -= $transaksi->jumlah;
                                }
                            @endphp
                            <tr>
                                <td>{{ $transaksi->jurnalHeader->tanggal_transaksi->format('d/m/Y') }}</td>
                                <td>{{ $transaksi->jurnalHeader->no_bukti }}</td>
                                <td>{{ $transaksi->jurnalHeader->deskripsi }}</td>
                                <td class="text-end">{{ $transaksi->posisi == 'Debit' ? 'Rp ' . number_format($transaksi->jumlah, 0, ',', '.') : '-' }}</td>
                                <td class="text-end">{{ $transaksi->posisi == 'Kredit' ? 'Rp ' . number_format($transaksi->jumlah, 0, ',', '.') : '-' }}</td>
                                <td class="text-end"><strong>Rp {{ number_format($saldo, 0, ',', '.') }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                    <p class="mt-2">Belum ada transaksi hutang</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

