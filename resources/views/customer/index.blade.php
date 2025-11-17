@extends('layouts.app')

@section('title', 'Daftar Customer')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people"></i> Daftar Customer</h2>
        @if(!(auth()->user()->plan === 'free' && !auth()->user()->is_owner))
        <a href="{{ route('customer.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Customer
        </a>
        @endif
    </div>

    <!-- Filter Card -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('customer.index') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Cari Customer</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nama, Kode, Email, atau Telepon">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('customer.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
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
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Customer</th>
                            <th>Kontak</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th class="text-end">Saldo Piutang</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="min-width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td><strong>{{ $customer->kode_customer ?? '-' }}</strong></td>
                            <td>
                                <strong>{{ $customer->nama_customer }}</strong>
                                @if($customer->kota)
                                    <br><small class="text-muted">{{ $customer->kota }}, {{ $customer->provinsi }}</small>
                                @endif
                            </td>
                            <td>{{ $customer->nama_kontak ?? '-' }}</td>
                            <td>{{ $customer->email ?? '-' }}</td>
                            <td>{{ $customer->telepon ?? '-' }}</td>
                            <td class="text-end">
                                <strong class="{{ $customer->saldo_piutang > 0 ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($customer->saldo_piutang, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td class="text-center">
                                @if($customer->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    <a href="{{ route('customer.show', $customer) }}" class="btn btn-outline-info btn-sm" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(!(auth()->user()->plan === 'free' && !auth()->user()->is_owner))
                                    <a href="{{ route('customer.edit', $customer) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('customer.destroy', $customer) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus customer ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                <p class="mt-2">Belum ada data customer.</p>
                                @if(!(auth()->user()->plan === 'free' && !auth()->user()->is_owner))
                                <a href="{{ route('customer.create') }}" class="btn btn-primary mt-2">Tambah Customer Baru</a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

