@extends('layouts.app')

@section('title', 'Offering')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-file-earmark-text"></i> Offering</h2>
        <a href="{{ route('offering.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Buat Offering Baru
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Offering</th>
                            <th>Tanggal</th>
                            <th>Kepada</th>
                            <th class="text-end">Total</th>
                            <th class="text-center" style="min-width:140px;white-space:nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offerings as $offering)
                        <tr>
                            <td><strong>{{ $offering->no_offering }}</strong></td>
                            <td>{{ date('d/m/Y', strtotime($offering->tanggal)) }}</td>
                            <td>{{ $offering->kepada_nama }}</td>
                            <td class="text-end">Rp {{ number_format($offering->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap" style="min-width:135px;">
                                    <a href="{{ route('offering.show', $offering) }}" class="btn btn-outline-info btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('offering.edit', $offering) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('offering.destroy', $offering) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus offering ini?')">
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
                            <td colspan="5" class="text-center text-muted">
                                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                <p class="mt-2">Belum ada offering</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $offerings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

