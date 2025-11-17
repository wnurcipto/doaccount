@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-box-seam me-2"></i>Detail Barang</h2>
        <div>
            <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
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
        <!-- Informasi Barang -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Barang</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Kode Barang</th>
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
                        <tr>
                            <th>Harga Beli</th>
                            <td>: <span class="text-primary">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</span></td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>: <span class="text-success">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</span></td>
                        </tr>
                        <tr>
                            <th>Margin</th>
                            <td>: 
                                @php
                                    $margin = $barang->harga_beli > 0 ? (($barang->harga_jual - $barang->harga_beli) / $barang->harga_beli * 100) : 0;
                                @endphp
                                <span class="badge bg-success">{{ number_format($margin, 2) }}%</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($barang->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Status Stok -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-boxes me-2"></i>Status Stok</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted">Stok Saat Ini</h6>
                                    <h2 class="mb-0">
                                        @if($barang->stok > $barang->stok_minimal)
                                            <span class="text-success">{{ $barang->stok }}</span>
                                        @elseif($barang->stok > 0)
                                            <span class="text-warning">{{ $barang->stok }}</span>
                                        @else
                                            <span class="text-danger">{{ $barang->stok }}</span>
                                        @endif
                                    </h2>
                                    <small class="text-muted">{{ $barang->satuan }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted">Stok Minimal</h6>
                                    <h2 class="mb-0 text-info">{{ $barang->stok_minimal }}</h2>
                                    <small class="text-muted">{{ $barang->satuan }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert {{ $barang->stok > $barang->stok_minimal ? 'alert-success' : ($barang->stok > 0 ? 'alert-warning' : 'alert-danger') }} mb-0">
                        <i class="bi {{ $barang->stok > $barang->stok_minimal ? 'bi-check-circle' : ($barang->stok > 0 ? 'bi-exclamation-triangle' : 'bi-x-circle') }}"></i>
                        <strong>{{ $barang->stok_status }}</strong>
                        @if($barang->stok <= $barang->stok_minimal && $barang->stok > 0)
                            - Segera lakukan pengadaan!
                        @elseif($barang->stok == 0)
                            - Stok habis, perlu segera diadakan!
                        @endif
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted">Nilai Stok (Harga Beli)</small>
                            <h6 class="mb-0">Rp {{ number_format($barang->stok * $barang->harga_beli, 0, ',', '.') }}</h6>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Nilai Stok (Harga Jual)</small>
                            <h6 class="mb-0">Rp {{ number_format($barang->stok * $barang->harga_jual, 0, ',', '.') }}</h6>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Potensi Laba</small>
                            <h6 class="mb-0 text-success">Rp {{ number_format($barang->stok * ($barang->harga_jual - $barang->harga_beli), 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Transaksi -->
    <div class="row">
        <!-- Stok Masuk Terakhir -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-arrow-down-circle me-2"></i>Stok Masuk Terakhir</h5>
                    <a href="{{ route('stok-masuk.create') }}?barang_id={{ $barang->id }}" class="btn btn-sm btn-light">
                        <i class="bi bi-plus"></i> Tambah
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($stokMasuks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. Bukti</th>
                                        <th>Supplier</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stokMasuks as $sm)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($sm->tanggal)->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('stok-masuk.show', $sm->id) }}" class="text-decoration-none">
                                                {{ $sm->no_bukti }}
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($sm->supplier, 20) }}</td>
                                        <td class="text-end">{{ $sm->qty }}</td>
                                        <td class="text-end">{{ number_format($sm->harga, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mb-0">Belum ada transaksi stok masuk</p>
                        </div>
                    @endif
                </div>
                @if($stokMasuks->count() > 0)
                <div class="card-footer text-center">
                    <small>
                        <a href="{{ route('kartu-stok.show', $barang->id) }}" class="text-decoration-none">
                            Lihat Semua Transaksi <i class="bi bi-arrow-right"></i>
                        </a>
                    </small>
                </div>
                @endif
            </div>
        </div>

        <!-- Stok Keluar Terakhir -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-arrow-up-circle me-2"></i>Stok Keluar Terakhir</h5>
                    <a href="{{ route('stok-keluar.create') }}?barang_id={{ $barang->id }}" class="btn btn-sm btn-light">
                        <i class="bi bi-plus"></i> Tambah
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($stokKeluars->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. Bukti</th>
                                        <th>Customer</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stokKeluars as $sk)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($sk->tanggal)->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('stok-keluar.show', $sk->id) }}" class="text-decoration-none">
                                                {{ $sk->no_bukti }}
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($sk->customer, 20) }}</td>
                                        <td class="text-end">{{ $sk->qty }}</td>
                                        <td class="text-end">{{ number_format($sk->harga, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mb-0">Belum ada transaksi stok keluar</p>
                        </div>
                    @endif
                </div>
                @if($stokKeluars->count() > 0)
                <div class="card-footer text-center">
                    <small>
                        <a href="{{ route('kartu-stok.show', $barang->id) }}" class="text-decoration-none">
                            Lihat Semua Transaksi <i class="bi bi-arrow-right"></i>
                        </a>
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
