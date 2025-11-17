@extends('layouts.app')

@section('title', 'Daftar Periode')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Periode Akuntansi</h2>
        <a href="{{ route('periode.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Periode
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Periode</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="min-width:180px;white-space:nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periodes as $periode)
                        <tr>
                            <td><strong>{{ $periode->nama_periode }}</strong></td>
                            <td>{{ $periode->tahun }}</td>
                            <td>{{ $periode->bulan }}</td>
                            <td>{{ $periode->tanggal_mulai->format('d/m/Y') }}</td>
                            <td>{{ $periode->tanggal_selesai->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @if($periode->status == 'Open')
                                    <span class="badge bg-success">Open</span>
                                @else
                                    <span class="badge bg-secondary">Closed</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap" style="min-width:175px;">
                                    <a href="{{ route('periode.show', $periode) }}" class="btn btn-outline-info btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('periode.edit', $periode) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    @if($periode->status == 'Open')
                                        <form action="{{ route('periode.close', $periode) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menutup periode ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm" title="Tutup Periode">
                                                <i class="bi bi-lock"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('periode.reopen', $periode) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membuka kembali periode ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm" title="Buka Periode">
                                                <i class="bi bi-unlock"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('periode.destroy', $periode) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus periode ini?')">
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
                            <td colspan="7" class="text-center text-muted">
                                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                <p class="mt-2">Belum ada data periode. <a href="{{ route('periode.create') }}">Tambah periode baru</a></p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="alert alert-info mt-3">
        <i class="bi bi-info-circle"></i> 
        <strong>Catatan:</strong> Periode dengan status <span class="badge bg-success">Open</span> dapat digunakan untuk transaksi. 
        Periode dengan status <span class="badge bg-secondary">Closed</span> tidak dapat menerima transaksi baru.
    </div>
</div>
@endsection
