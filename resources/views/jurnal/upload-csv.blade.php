@extends('layouts.app')

@section('title', 'Upload CSV ke Jurnal')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Upload CSV ke Jurnal</h2>
        <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            @if(session('import_stats'))
                <hr>
                <div class="mt-2">
                    <strong>Ringkasan Import:</strong><br>
                    <ul class="mb-0">
                        <li>Berhasil: <strong>{{ session('import_stats')['success'] }}</strong> jurnal</li>
                        <li>Error: <strong>{{ session('import_stats')['error'] }}</strong> jurnal</li>
                        <li>Dilewati: <strong>{{ session('import_stats')['skipped'] }}</strong> jurnal</li>
                    </ul>
                    @if(count(session('import_stats')['errors']) > 0)
                        <details class="mt-2">
                            <summary class="text-danger">Detail Error ({{ count(session('import_stats')['errors']) }})</summary>
                            <ul class="mt-2 small">
                                @foreach(session('import_stats')['errors'] as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </details>
                    @endif
                </div>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-upload me-2"></i>Upload File CSV</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('jurnal.import-csv') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Pilih File CSV <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('csv_file') is-invalid @enderror" 
                                   id="csv_file" name="csv_file" accept=".csv,.txt" required>
                            @error('csv_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Format file: CSV atau TXT (maksimal 10MB)
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Format CSV yang didukung:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Kolom 1: Timestamp</li>
                                <li>Kolom 2: Tanggal (format: M/D/Y atau M-D-Y)</li>
                                <li>Kolom 3: Pemasukan/Pengeluaran</li>
                                <li>Kolom 4: Jenis</li>
                                <li>Kolom 5: Deskripsi</li>
                                <li>Kolom 6: Debit</li>
                                <li>Kolom 7: Kredit</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="bi bi-upload me-2"></i>Upload dan Import CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Mapping Transaksi ke COA:</h6>
                    <ul class="small">
                        <li><strong>Pemasukan:</strong>
                            <ul>
                                <li>Penjualan Barang → Pendapatan Penjualan</li>
                                <li>Penjualan Jasa → Pendapatan Jasa</li>
                                <li>Lain-lain → Pendapatan Lain-lain</li>
                            </ul>
                        </li>
                        <li><strong>Pengeluaran:</strong>
                            <ul>
                                <li>Belanja → HPP</li>
                                <li>Kantor → Beban Administrasi</li>
                                <li>Trasportasi/Perbaikan/Hadiah → Beban Lain-lain</li>
                            </ul>
                        </li>
                    </ul>

                    <hr>

                    <h6 class="fw-bold">Catatan Penting:</h6>
                    <ul class="small">
                        <li>Semua jurnal yang diimport akan berstatus <strong>Draft</strong></li>
                        <li>Periode akan dibuat otomatis jika belum ada</li>
                        <li>Transaksi duplikat akan dilewati</li>
                        <li>Pastikan file CSV sesuai format yang ditentukan</li>
                    </ul>

                    <hr>

                    <div class="alert alert-warning small mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Peringatan:</strong> Pastikan untuk backup database sebelum import data besar.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Contoh Format CSV</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Timestamp</th>
                            <th>Tanggal</th>
                            <th>Pemasukan/Pengeluaran</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2/12/2025 3:08</td>
                            <td>8/3/2024</td>
                            <td>Pengeluaran</td>
                            <td>Trasportasi -</td>
                            <td>Akomodasi Febri</td>
                            <td>0</td>
                            <td>150000</td>
                        </tr>
                        <tr>
                            <td>2/12/2025 3:11</td>
                            <td>8/8/2024</td>
                            <td>Pemasukan</td>
                            <td>Penjualan Barang +</td>
                            <td>Tagihan Bengkel Juni-Juli</td>
                            <td>12145000</td>
                            <td>0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const fileInput = document.getElementById('csv_file');
    
    if (!fileInput.files.length) {
        e.preventDefault();
        alert('Silakan pilih file CSV terlebih dahulu!');
        return;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengupload...';
    
    // Jika form berhasil submit, tombol akan tetap disabled
    // Jika ada error, halaman akan reload dan tombol akan kembali normal
});
</script>
@endsection

