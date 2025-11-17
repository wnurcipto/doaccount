@extends('layouts.app')

@section('title', $panduan['title'] . ' - Bantuan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="{{ $panduan['icon'] }}"></i> {{ $panduan['title'] }}
        </h2>
        <a href="{{ route('bantuan.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Panduan
        </a>
    </div>

    <div class="row">
        <!-- Sidebar Menu Panduan -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list"></i> Daftar Panduan</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('bantuan.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-house"></i> Beranda Bantuan
                    </a>
                    <a href="{{ route('bantuan.show', 'umum') }}" 
                       class="list-group-item list-group-item-action {{ $slug == 'umum' ? 'active' : '' }}">
                        <i class="bi bi-book"></i> Panduan Umum
                    </a>
                    <a href="{{ route('bantuan.show', 'jurnal') }}" 
                       class="list-group-item list-group-item-action {{ $slug == 'jurnal' ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i> Panduan Jurnal
                    </a>
                    <a href="{{ route('bantuan.show', 'import-csv') }}" 
                       class="list-group-item list-group-item-action {{ $slug == 'import-csv' ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Import CSV ke Jurnal
                    </a>
                    <a href="{{ route('bantuan.show', 'penutupan-tahun') }}" 
                       class="list-group-item list-group-item-action {{ $slug == 'penutupan-tahun' ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i> Penutupan Akhir Tahun
                    </a>
                    <a href="{{ route('bantuan.show', 'inventori') }}" 
                       class="list-group-item list-group-item-action {{ $slug == 'inventori' ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Modul Inventori
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ $panduan['title'] }}</h5>
                    <small class="text-muted">{{ $panduan['description'] }}</small>
                </div>
                <div class="card-body">
                    <div class="markdown-content">
                        {!! $panduan['html'] ?? 'Panduan tidak tersedia.' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.markdown-content {
    line-height: 1.8;
    color: #333;
}

.markdown-content h1 {
    font-size: 2rem;
    font-weight: bold;
    margin-top: 2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #dee2e6;
}

.markdown-content h2 {
    font-size: 1.5rem;
    font-weight: bold;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #495057;
}

.markdown-content h3 {
    font-size: 1.25rem;
    font-weight: bold;
    margin-top: 1.25rem;
    margin-bottom: 0.5rem;
    color: #6c757d;
}

.markdown-content h4, .markdown-content h5, .markdown-content h6 {
    font-size: 1.1rem;
    font-weight: bold;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

.markdown-content p {
    margin-bottom: 1rem;
}

.markdown-content ul, .markdown-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.markdown-content li {
    margin-bottom: 0.5rem;
}

.markdown-content code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
    color: #e83e8c;
}

.markdown-content pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
    overflow-x: auto;
    margin-bottom: 1rem;
}

.markdown-content pre code {
    background-color: transparent;
    padding: 0;
    color: #333;
    display: block;
}

.markdown-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin-left: 0;
    color: #6c757d;
    font-style: italic;
}

.markdown-content table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

.markdown-content table th,
.markdown-content table td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    text-align: left;
}

.markdown-content table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.markdown-content table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.markdown-content hr {
    border: none;
    border-top: 2px solid #dee2e6;
    margin: 2rem 0;
}

.markdown-content strong {
    font-weight: bold;
    color: #495057;
}

.markdown-content em {
    font-style: italic;
}

.markdown-content a {
    color: #007bff;
    text-decoration: none;
}

.markdown-content a:hover {
    text-decoration: underline;
}
</style>
@endpush
@endsection

