@extends('layouts.app')

@section('title', 'Surat Jalan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-truck"></i> Surat Jalan</h2>
        <a href="{{ route('surat-jalan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Buat Surat Jalan Baru
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Surat Jalan</th>
                            <th>Tanggal</th>
                            <th>Dari</th>
                            <th>Kepada</th>
                            <th class="text-center" style="min-width:140px;white-space:nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratJalans as $suratJalan)
                        <tr>
                            <td><strong>{{ $suratJalan->no_surat_jalan }}</strong></td>
                            <td>{{ date('d/m/Y', strtotime($suratJalan->tanggal)) }}</td>
                            <td>{{ $suratJalan->dari_nama }}</td>
                            <td>{{ $suratJalan->kepada_nama }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap" style="min-width:135px;">
                                    <a href="{{ route('surat-jalan.show', $suratJalan) }}" class="btn btn-outline-info btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('surat-jalan.edit', $suratJalan) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('surat-jalan.destroy', $suratJalan) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus surat jalan ini?')">
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
                                <p class="mt-2">Belum ada surat jalan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $suratJalans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

