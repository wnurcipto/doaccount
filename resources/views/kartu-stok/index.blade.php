@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-card-list me-2"></i>Kartu Stok</h2>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter Laporan</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('kartu-stok.show', ['barang' => 0]) }}" id="formFilter">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="barang_id" class="form-label">Barang <span class="text-danger">*</span></label>
                        <select class="form-select" id="barang_id" name="barang_id" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                        <input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                        <small class="text-muted">Kosongkan untuk semua</small>
                    </div>

                    <div class="col-md-3">
                        <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                        <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                        <small class="text-muted">Kosongkan untuk semua</small>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Tampilkan Kartu Stok
                        </button>
                        <a href="{{ route('kartu-stok.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(request('barang_id'))
        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle"></i>
            Silakan klik <strong>"Tampilkan Kartu Stok"</strong> untuk melihat laporan.
        </div>
    @else
        <div class="card mt-4">
            <div class="card-body text-center py-5">
                <i class="bi bi-card-list" style="font-size: 4rem; color: #ccc;"></i>
                <h4 class="text-muted mt-3">Pilih Barang untuk Menampilkan Kartu Stok</h4>
                <p class="text-muted">Kartu stok akan menampilkan semua transaksi keluar masuk barang dengan running balance</p>
            </div>
        </div>
    @endif

    <!-- Informasi Kartu Stok -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Tentang Kartu Stok</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="bi bi-check-circle text-success"></i> Apa itu Kartu Stok?</h6>
                    <p class="text-muted">
                        Kartu stok adalah laporan yang menampilkan riwayat pergerakan barang (masuk dan keluar) 
                        beserta saldo akhir stok pada setiap transaksi.
                    </p>

                    <h6 class="mt-3"><i class="bi bi-list-check text-primary"></i> Informasi yang Ditampilkan:</h6>
                    <ul class="text-muted">
                        <li>Tanggal transaksi</li>
                        <li>No. Bukti dan keterangan</li>
                        <li>Stok Masuk (pembelian)</li>
                        <li>Stok Keluar (penjualan)</li>
                        <li>Saldo stok (running balance)</li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <h6><i class="bi bi-bar-chart text-warning"></i> Kegunaan Kartu Stok:</h6>
                    <ul class="text-muted">
                        <li>Memonitor pergerakan stok barang</li>
                        <li>Mengecek histori transaksi per barang</li>
                        <li>Mengetahui kapan stok masuk/keluar</li>
                        <li>Verifikasi saldo stok</li>
                        <li>Audit trail untuk inventori</li>
                    </ul>

                    <div class="alert alert-warning mt-3 mb-0">
                        <small>
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Tips:</strong> Gunakan filter tanggal untuk melihat transaksi pada periode tertentu saja.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-arrow-down-circle text-info" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">Stok Masuk</h6>
                    <p class="text-muted small">Input pembelian barang</p>
                    <a href="{{ route('stok-masuk.create') }}" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-arrow-up-circle text-warning" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">Stok Keluar</h6>
                    <p class="text-muted small">Input penjualan barang</p>
                    <a href="{{ route('stok-keluar.create') }}" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-box-seam text-success" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">Master Barang</h6>
                    <p class="text-muted small">Kelola data barang</p>
                    <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-list"></i> Lihat Semua
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formFilter = document.getElementById('formFilter');
    const barangSelect = document.getElementById('barang_id');

    formFilter.addEventListener('submit', function(e) {
        e.preventDefault();
        const barangId = barangSelect.value;
        if (!barangId) {
            alert('Silakan pilih barang terlebih dahulu');
            return;
        }
        
        const tanggalDari = document.getElementById('tanggal_dari').value;
        const tanggalSampai = document.getElementById('tanggal_sampai').value;
        
        let url = '{{ route("kartu-stok.show", ["barang" => "BARANG_ID"]) }}'.replace('BARANG_ID', barangId);
        
        const params = new URLSearchParams();
        if (tanggalDari) params.append('tanggal_dari', tanggalDari);
        if (tanggalSampai) params.append('tanggal_sampai', tanggalSampai);
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        window.location.href = url;
    });
});
</script>
@endsection
