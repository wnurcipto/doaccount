@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-arrow-up-circle me-2"></i>Stok Keluar</h2>
        <a href="{{ route('stok-keluar.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Stok Keluar
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <!-- Filter -->
            <form method="GET" action="{{ route('stok-keluar.index') }}" class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label">Periode</label>
                    <select name="periode_id" class="form-select">
                        <option value="">Semua Periode</option>
                        @foreach($periodes as $p)
                            <option value="{{ $p->id }}" {{ request('periode_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Barang</label>
                    <select name="barang_id" class="form-select">
                        <option value="">Semua Barang</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}" {{ request('barang_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->kode_barang }} - {{ $b->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Customer</label>
                    <input type="text" name="customer" class="form-control" value="{{ request('customer') }}" placeholder="Cari customer...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('stok-keluar.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>No. Bukti</th>
                            <th>Tanggal</th>
                            <th>Periode</th>
                            <th>Barang</th>
                            <th>Customer</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="min-width:140px;white-space:nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokKeluars as $index => $sk)
                        <tr>
                            <td>{{ $stokKeluars->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('stok-keluar.show', $sk->id) }}" class="text-decoration-none">
                                    <strong>{{ $sk->no_bukti }}</strong>
                                </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($sk->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $sk->periode->status == 'Open' ? 'success' : 'secondary' }}">
                                    {{ $sk->periode->nama_periode }}
                                </span>
                            </td>
                            <td>
                                <div><strong>{{ $sk->barang->kode_barang }}</strong></div>
                                <small class="text-muted">{{ $sk->barang->nama_barang }}</small>
                            </td>
                            <td>{{ $sk->customer }}</td>
                            <td class="text-end">{{ $sk->qty }} {{ $sk->barang->satuan }}</td>
                            <td class="text-end">Rp {{ number_format($sk->harga, 0, ',', '.') }}</td>
                            <td class="text-end"><strong>Rp {{ number_format($sk->subtotal, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($sk->jurnal_header_id)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Dijurnal
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="bi bi-clock"></i> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap" style="min-width:135px;">
                                    <a href="{{ route('stok-keluar.show', $sk->id) }}" class="btn btn-outline-info btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(!$sk->jurnal_header_id)
                                    <a href="{{ route('stok-keluar.edit', $sk->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('stok-keluar.destroy', $sk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button class="btn btn-outline-secondary btn-sm" disabled title="Tidak bisa edit/hapus (sudah dijurnal)">
                                        <i class="bi bi-lock"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">Belum ada data stok keluar</p>
                                <a href="{{ route('stok-keluar.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Stok Keluar
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($stokKeluars->count() > 0)
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="8" class="text-end">Total Penjualan:</th>
                            <th class="text-end">Rp {{ number_format($stokKeluars->sum('subtotal'), 0, ',', '.') }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $stokKeluars->firstItem() ?? 0 }} sampai {{ $stokKeluars->lastItem() ?? 0 }} dari {{ $stokKeluars->total() }} data
                </div>
                <div>
                    {{ $stokKeluars->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
