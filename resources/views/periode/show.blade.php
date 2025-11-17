@extends('layouts.app')

@section('title', 'Detail Periode')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Periode</h2>
        <div>
            <a href="{{ route('periode.edit', $periode) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('periode.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Periode</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Nama Periode</th>
                            <td>: <strong>{{ $periode->nama_periode }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tahun</th>
                            <td>: {{ $periode->tahun }}</td>
                        </tr>
                        <tr>
                            <th>Bulan</th>
                            <td>: {{ $periode->bulan }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <td>: {{ $periode->tanggal_mulai->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td>: {{ $periode->tanggal_selesai->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($periode->status == 'Open')
                                    <span class="badge bg-success">Open</span>
                                @else
                                    <span class="badge bg-secondary">Closed</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>: {{ $periode->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Update</th>
                            <td>: {{ $periode->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>

                    <hr>

                    <div class="d-flex gap-2">
                        @if($periode->status == 'Open')
                            <form action="{{ route('periode.close', $periode) }}" method="POST" onsubmit="return confirm('Yakin ingin menutup periode ini?')">
                                @csrf
                                <button type="submit" class="btn btn-secondary">
                                    <i class="bi bi-lock"></i> Tutup Periode
                                </button>
                            </form>
                        @else
                            <form action="{{ route('periode.reopen', $periode) }}" method="POST" onsubmit="return confirm('Yakin ingin membuka kembali periode ini?')">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-unlock"></i> Buka Periode
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('periode.destroy', $periode) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus periode ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Hapus Periode
                            </button>
                        </form>
                    </div>
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
                                <h2 class="text-primary">{{ $periode->jurnalHeaders()->count() }}</h2>
                                <p class="mb-0">Total Jurnal</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <h2 class="text-success">{{ $periode->jurnalHeaders()->where('status', 'Posted')->count() }}</h2>
                                <p class="mb-0">Jurnal Posted</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <h2 class="text-warning">{{ $periode->jurnalHeaders()->where('status', 'Draft')->count() }}</h2>
                                <p class="mb-0">Jurnal Draft</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <h2 class="text-info">
                                    Rp {{ number_format($periode->jurnalHeaders()->sum('total_debit'), 0, ',', '.') }}
                                </h2>
                                <p class="mb-0">Total Transaksi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($periode->jurnalHeaders()->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Jurnal</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>No. Bukti</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($periode->jurnalHeaders()->latest()->take(10)->get() as $jurnal)
                                <tr>
                                    <td><a href="{{ route('jurnal.show', $jurnal) }}">{{ $jurnal->no_bukti }}</a></td>
                                    <td>{{ $jurnal->tanggal_transaksi->format('d/m/Y') }}</td>
                                    <td class="text-end">Rp {{ number_format($jurnal->total_debit, 0, ',', '.') }}</td>
                                    <td>
                                        @if($jurnal->status == 'Draft')
                                            <span class="badge bg-warning text-dark">Draft</span>
                                        @elseif($jurnal->status == 'Posted')
                                            <span class="badge bg-success">Posted</span>
                                        @else
                                            <span class="badge bg-danger">Void</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('jurnal.index') }}" class="btn btn-sm btn-primary mt-2">
                        Lihat Semua Jurnal
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
