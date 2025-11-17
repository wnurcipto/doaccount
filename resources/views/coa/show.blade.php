@extends('layouts.app')

@section('title', 'Detail COA')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Chart of Account</h2>
        <div>
            <a href="{{ route('coa.edit', $coa) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('coa.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Akun</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Kode Akun</th>
                            <td>: <strong>{{ $coa->kode_akun }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nama Akun</th>
                            <td>: <strong>{{ $coa->nama_akun }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tipe Akun</th>
                            <td>: <span class="badge bg-info">{{ $coa->tipe_akun }}</span></td>
                        </tr>
                        <tr>
                            <th>Posisi Normal</th>
                            <td>: {{ $coa->posisi_normal }}</td>
                        </tr>
                        <tr>
                            <th>Level</th>
                            <td>: {{ $coa->level }}</td>
                        </tr>
                        <tr>
                            <th>Parent Account</th>
                            <td>: 
                                @if($coa->parent)
                                    <a href="{{ route('coa.show', $coa->parent) }}">
                                        {{ $coa->parent->kode_akun }} - {{ $coa->parent->nama_akun }}
                                    </a>
                                @else
                                    <em class="text-muted">Tidak ada parent</em>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($coa->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>: {{ $coa->deskripsi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>: {{ $coa->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Update</th>
                            <td>: {{ $coa->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <h2 class="text-primary">{{ $coa->jurnalDetails()->count() }}</h2>
                                <p class="mb-0">Total Transaksi</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <h2 class="text-success">{{ $coa->children()->count() }}</h2>
                                <p class="mb-0">Child Accounts</p>
                            </div>
                        </div>
                    </div>

                    @if($coa->jurnalDetails()->count() > 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        Akun ini sudah digunakan dalam transaksi dan tidak dapat dihapus.
                    </div>
                    @endif

                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('buku-besar.index') }}?coa_id={{ $coa->id }}" class="btn btn-primary">
                            <i class="bi bi-book"></i> Lihat Buku Besar
                        </a>
                    </div>
                </div>
            </div>

            @if($coa->children()->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Child Accounts</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($coa->children as $child)
                        <a href="{{ route('coa.show', $child) }}" class="list-group-item list-group-item-action">
                            <strong>{{ $child->kode_akun }}</strong> - {{ $child->nama_akun }}
                            <span class="badge bg-info float-end">Level {{ $child->level }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
