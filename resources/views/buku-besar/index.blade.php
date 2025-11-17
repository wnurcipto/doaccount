@extends('layouts.app')

@section('title', 'Buku Besar')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Buku Besar</h2>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Filter Buku Besar</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('buku-besar.show') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="coa_id" class="form-label">Pilih Akun *</label>
                        <select class="form-select" id="coa_id" name="coa_id" required>
                            <option value="">-- Pilih Akun --</option>
                            @foreach($coas as $coa)
                                <option value="{{ $coa->id }}" {{ ($filters['coa_id'] ?? '') == $coa->id ? 'selected' : '' }}>
                                    {{ $coa->kode_akun }} - {{ $coa->nama_akun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai *</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" 
                               value="{{ $filters['tanggal_mulai'] ?? date('Y-m-01') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai *</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" 
                               value="{{ $filters['tanggal_selesai'] ?? date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Tampilkan
                            </button>
                            @if(!empty($filters['coa_id'] ?? null))
                            <a href="{{ route('buku-besar.index', ['clear_filter' => 1]) }}" class="btn btn-secondary" title="Reset">
                                <i class="bi bi-x-circle"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Informasi:</strong> Pilih akun dan periode untuk melihat buku besar. 
                        Buku besar menampilkan semua transaksi yang sudah diposting (status Posted).
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted">Contoh Penggunaan:</h6>
                                    <ol class="mb-0">
                                        <li>Pilih akun yang ingin dilihat</li>
                                        <li>Tentukan periode tanggal</li>
                                        <li>Klik tombol Tampilkan</li>
                                        <li>Sistem akan menampilkan:
                                            <ul>
                                                <li>Saldo awal</li>
                                                <li>Detail transaksi</li>
                                                <li>Saldo berjalan</li>
                                            </ul>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted">Daftar Akun Populer:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Aset:</strong>
                                            <ul class="small">
                                                <li>1-1001 - Kas</li>
                                                <li>1-1002 - Bank</li>
                                                <li>1-1003 - Piutang Usaha</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Pendapatan & Beban:</strong>
                                            <ul class="small">
                                                <li>4-1001 - Pendapatan Jasa</li>
                                                <li>5-1001 - Beban Gaji</li>
                                                <li>5-1002 - Beban Sewa</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tambahkan search functionality untuk select akun
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('coa_id');
    
    // Simple search on select
    select.addEventListener('keyup', function(e) {
        const searchText = this.value.toLowerCase();
        const options = this.querySelectorAll('option');
        
        options.forEach(option => {
            const text = option.textContent.toLowerCase();
            if (text.includes(searchText) || option.value === '') {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection
