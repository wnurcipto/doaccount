@extends('layouts.app')

@section('title', 'Detail Customer')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person"></i> Detail Customer</h2>
        <div>
            @if(!(auth()->user()->plan === 'free' && !auth()->user()->is_owner))
            <a href="{{ route('customer.edit', $customer) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @endif
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Kode Customer</th>
                            <td>: <strong>{{ $customer->kode_customer ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nama Customer</th>
                            <td>: <strong>{{ $customer->nama_customer }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nama Kontak</th>
                            <td>: {{ $customer->nama_kontak ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>: {{ $customer->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>: {{ $customer->telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: {{ $customer->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kota</th>
                            <td>: {{ $customer->kota ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Provinsi</th>
                            <td>: {{ $customer->provinsi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kode Pos</th>
                            <td>: {{ $customer->kode_pos ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Website</th>
                            <td>: {{ $customer->website ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($customer->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                        @if($customer->keterangan)
                        <tr>
                            <th>Keterangan</th>
                            <td>: {{ $customer->keterangan }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Saldo Piutang</h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="{{ $customer->saldo_piutang > 0 ? 'text-danger' : 'text-success' }}">
                        Rp {{ number_format($customer->saldo_piutang, 0, ',', '.') }}
                    </h2>
                    @if($customer->saldo_piutang > 0)
                        <p class="text-muted mb-0">Piutang yang belum dilunasi</p>
                    @else
                        <p class="text-muted mb-0">Tidak ada piutang</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Piutang -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">History Transaksi Piutang</h5>
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
                        @forelse($transaksiPiutang as $transaksi)
                            @php
                                if ($transaksi->posisi == 'Debit') {
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
                                    <p class="mt-2">Belum ada transaksi piutang</p>
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

