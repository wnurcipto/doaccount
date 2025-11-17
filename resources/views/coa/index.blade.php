@extends('layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chart of Accounts (COA)</h2>
        <a href="{{ route('coa.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah COA
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Tipe</th>
                            <th>Posisi Normal</th>
                            <th>Level</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="min-width:140px;white-space:nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coas as $coa)
                        <tr>
                            <td><strong>{{ $coa->kode_akun }}</strong></td>
                            <td style="padding-left: {{ ($coa->level - 1) * 20 }}px">
                                {{ $coa->nama_akun }}
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $coa->tipe_akun }}</span>
                            </td>
                            <td>{{ $coa->posisi_normal }}</td>
                            <td>{{ $coa->level }}</td>
                            <td class="text-center">
                                @if($coa->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap" style="min-width:135px;">
                                    <a href="{{ route('coa.show', $coa) }}" class="btn btn-outline-info btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('coa.edit', $coa) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('coa.destroy', $coa) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus COA ini?')">
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
                                <p class="mt-2">Belum ada data COA. <a href="{{ route('coa.create') }}">Tambah COA baru</a></p>
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
