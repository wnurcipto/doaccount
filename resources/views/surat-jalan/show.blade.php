@extends('layouts.app')

@section('title', 'Detail Surat Jalan - ' . $suratJalan->no_surat_jalan)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Surat Jalan</h2>
        <div>
            <button type="button" class="btn btn-info" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
            <a href="{{ route('surat-jalan.edit', $suratJalan) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('surat-jalan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Watermark -->
    @include('partials.print-watermark')

    <!-- Print Header (Hidden on screen, shown on print) -->
    <div class="d-none print-only">
        @include('partials.print-header-screen')
        <div class="text-center mb-4">
            <h4 style="margin: 20px 0 10px 0; font-weight: bold;">SURAT JALAN</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Header Surat Jalan -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>No. Surat Jalan: <strong>{{ $suratJalan->no_surat_jalan }}</strong></h5>
                    <p class="mb-1"><strong>Tanggal:</strong> {{ date('d F Y', strtotime($suratJalan->tanggal)) }}</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Dari:</h5>
                    <p class="mb-1"><strong>{{ $suratJalan->dari_nama }}</strong></p>
                    @if($suratJalan->dari_alamat)
                        <p class="mb-1">{{ $suratJalan->dari_alamat }}</p>
                    @endif
                    @if($suratJalan->dari_kota)
                        <p class="mb-1">{{ $suratJalan->dari_kota }}</p>
                    @endif
                    @if($suratJalan->dari_telepon)
                        <p class="mb-1">Telp: {{ $suratJalan->dari_telepon }}</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <h5>Kepada:</h5>
                    <p class="mb-1"><strong>{{ $suratJalan->kepada_nama }}</strong></p>
                    @if($suratJalan->kepada_alamat)
                        <p class="mb-1">{{ $suratJalan->kepada_alamat }}</p>
                    @endif
                    @if($suratJalan->kepada_kota)
                        <p class="mb-1">{{ $suratJalan->kepada_kota }}</p>
                    @endif
                    @if($suratJalan->kepada_telepon)
                        <p class="mb-1">Telp: {{ $suratJalan->kepada_telepon }}</p>
                    @endif
                </div>
            </div>

            @if($suratJalan->no_kendaraan || $suratJalan->nama_supir)
            <div class="row mb-3">
                <div class="col-md-6">
                    @if($suratJalan->no_kendaraan)
                        <p class="mb-1"><strong>No. Kendaraan:</strong> {{ $suratJalan->no_kendaraan }}</p>
                    @endif
                    @if($suratJalan->nama_supir)
                        <p class="mb-1"><strong>Nama Supir:</strong> {{ $suratJalan->nama_supir }}</p>
                    @endif
                </div>
            </div>
            @endif

            <hr>

            <!-- Detail Items -->
            <h5 class="mb-3">Detail Item</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Nama Item</th>
                            <th width="35%">Deskripsi</th>
                            <th width="10%" class="text-center">Qty</th>
                            <th width="10%">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suratJalan->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_item }}</td>
                            <td>{{ $item->deskripsi ?? '-' }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td>{{ $item->satuan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($suratJalan->keterangan)
            <div class="mt-3">
                <strong>Keterangan:</strong>
                <p class="border rounded p-2 bg-light">{{ $suratJalan->keterangan }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Print Footer -->
    <div class="d-none print-only">
        @include('partials.print-footer')
    </div>
</div>

@push('styles')
<style>
@media print {
    @page {
        margin: 1cm;
        size: A4;
    }
    
    .btn, .sidebar, .alert, .d-flex.justify-content-between {
        display: none !important;
    }
    
    .print-only {
        display: block !important;
    }
    
    .d-none.print-only {
        display: block !important;
    }
    
    .main-content {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
        position: relative;
        z-index: 1;
    }
    
    .card-body {
        padding: 10px 0 !important;
        position: relative;
        z-index: 1;
    }
    
    table {
        font-size: 11px;
        position: relative;
        z-index: 1;
    }
    
    h2, h4, h5 {
        page-break-after: avoid;
        position: relative;
        z-index: 1;
    }
    
    .table {
        page-break-inside: avoid;
    }
}

@media screen {
    .print-only {
        display: none !important;
    }
}
</style>
@endpush
@endsection

