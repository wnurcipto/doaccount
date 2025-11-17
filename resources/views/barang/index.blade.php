@extends('layouts.app')

@section('title', 'Master Barang')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-box-seam"></i> Master Barang</h2>
        <a href="{{ route('barang.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Barang
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th class="text-end">Harga Beli</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="min-width:140px;white-space:nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $barang)
                        <tr>
                            <td><strong>{{ $barang->kode_barang }}</strong></td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->kategori ?? '-' }}</td>
                            <td>{{ $barang->satuan }}</td>
                            <td class="text-end">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($barang->stok <= 0)
                                    <span class="badge bg-danger">{{ $barang->stok }}</span>
                                @elseif($barang->stok <= $barang->stok_minimal)
                                    <span class="badge bg-warning text-dark">{{ $barang->stok }}</span>
                                @else
                                    <span class="badge bg-success">{{ $barang->stok }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($barang->stok <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($barang->stok <= $barang->stok_minimal)
                                    <span class="badge bg-warning text-dark">Rendah</span>
                                @else
                                    <span class="badge bg-success">Tersedia</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap" style="min-width:135px;">
                                    <a href="{{ route('barang.show', $barang) }}" class="btn btn-outline-info btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('barang.edit', $barang) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('barang.destroy', $barang) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                <p class="mt-2">Belum ada data barang. <a href="{{ route('barang.create') }}">Tambah barang baru</a></p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($barangs->hasPages())
            <div class="mt-3">
                {{ $barangs->links() }}
            </div>
            @endif
        </div>
    </div>

    <div class="alert alert-info mt-3">
        <strong><i class="bi bi-info-circle"></i> Keterangan Status Stok:</strong><br>
        <span class="badge bg-success">Tersedia</span> = Stok > Stok Minimal<br>
        <span class="badge bg-warning text-dark">Rendah</span> = Stok ≤ Stok Minimal (Perlu Restock)<br>
        <span class="badge bg-danger">Habis</span> = Stok = 0
    </div>
</div>
@endsection
