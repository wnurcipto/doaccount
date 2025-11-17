@extends('layouts.app')

@section('title', 'Bantuan & Panduan')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">
        <i class="bi bi-question-circle"></i> Bantuan & Panduan
    </h2>

    <div class="row">
        <!-- Sidebar Menu Panduan -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list"></i> Daftar Panduan</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($panduan as $key => $item)
                    <a href="{{ route('bantuan.show', $key) }}" 
                       class="list-group-item list-group-item-action {{ request()->route('slug') == $key ? 'active' : '' }}">
                        <i class="{{ $item['icon'] }}"></i> {{ $item['title'] }}
                        <br><small class="text-muted">{{ $item['description'] }}</small>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pilih panduan dari menu di samping untuk melihat detail</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-book" style="font-size: 4rem; color: #6c757d;"></i>
                        <h4 class="mt-3 text-muted">Selamat Datang di Pusat Bantuan</h4>
                        <p class="text-muted">
                            Pilih salah satu panduan dari menu di samping untuk memulai.
                            <br>Semua panduan tersedia untuk membantu Anda menggunakan sistem dengan lebih baik.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-journal-text text-primary"></i> Panduan Jurnal
                                    </h6>
                                    <p class="card-text small">Pelajari cara membuat dan mengelola jurnal dengan benar.</p>
                                    <a href="{{ route('bantuan.show', 'jurnal') }}" class="btn btn-sm btn-primary">Baca Panduan</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-file-earmark-spreadsheet text-success"></i> Import CSV
                                    </h6>
                                    <p class="card-text small">Cara mengimport data transaksi dari file CSV ke jurnal.</p>
                                    <a href="{{ route('bantuan.show', 'import-csv') }}" class="btn btn-sm btn-success">Baca Panduan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

