@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-arrow-down-circle me-2"></i>Edit Stok Masuk</h2>
        <a href="{{ route('stok-masuk.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($stokMasuk->jurnal_header_id)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Perhatian:</strong> Data ini sudah dijurnal dan tidak dapat diubah. 
            Silakan hapus jurnal terlebih dahulu jika ingin mengubah data.
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('stok-masuk.update', $stokMasuk->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Informasi Transaksi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="no_bukti" class="form-label">No. Bukti <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_bukti') is-invalid @enderror" 
                                    id="no_bukti" name="no_bukti" value="{{ old('no_bukti', $stokMasuk->no_bukti) }}" 
                                    {{ $stokMasuk->jurnal_header_id ? 'readonly' : 'required' }}>
                                @error('no_bukti')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                    id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', $stokMasuk->tanggal_masuk) }}" 
                                    {{ $stokMasuk->jurnal_header_id ? 'readonly' : 'required' }}>
                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="periode_id" class="form-label">Periode <span class="text-danger">*</span></label>
                            <select class="form-select @error('periode_id') is-invalid @enderror" id="periode_id" name="periode_id" 
                                {{ $stokMasuk->jurnal_header_id ? 'disabled' : 'required' }}>
                                @foreach($periodes as $periode)
                                    <option value="{{ $periode->id }}" {{ old('periode_id', $stokMasuk->periode_id) == $periode->id ? 'selected' : '' }}>
                                        {{ $periode->nama_periode }} ({{ $periode->status }})
                                    </option>
                                @endforeach
                            </select>
                            @error('periode_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="barang_id" class="form-label">Barang <span class="text-danger">*</span></label>
                            <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" 
                                {{ $stokMasuk->jurnal_header_id ? 'disabled' : 'required' }}>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" 
                                        data-kode="{{ $barang->kode_barang }}"
                                        data-harga="{{ $barang->harga_beli }}"
                                        data-satuan="{{ $barang->satuan }}"
                                        data-stok="{{ $barang->stok }}"
                                        {{ old('barang_id', $stokMasuk->barang_id) == $barang->id ? 'selected' : '' }}>
                                        {{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                                id="supplier" name="supplier" value="{{ old('supplier', $stokMasuk->supplier) }}" 
                                placeholder="Nama supplier/pemasok" {{ $stokMasuk->jurnal_header_id ? 'readonly' : 'required' }}>
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="qty" class="form-label">Qty <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('qty') is-invalid @enderror" 
                                    id="qty" name="qty" value="{{ old('qty', $stokMasuk->qty) }}" min="1" step="1" 
                                    {{ $stokMasuk->jurnal_header_id ? 'readonly' : 'required' }}>
                                @error('qty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="satuan-info">Satuan: {{ $stokMasuk->barang->satuan }}</small>
                            </div>
                            <div class="col-md-4">
                                <label for="harga" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                    id="harga" name="harga" value="{{ old('harga', $stokMasuk->harga) }}" min="0" step="1" 
                                    {{ $stokMasuk->jurnal_header_id ? 'readonly' : 'required' }}>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="subtotal" class="form-label">Subtotal</label>
                                <input type="text" class="form-control bg-light" id="subtotal" 
                                    value="Rp {{ number_format($stokMasuk->subtotal, 0, ',', '.') }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="metode_bayar" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('metode_bayar') is-invalid @enderror" id="metode_bayar" name="metode_bayar" 
                                {{ $stokMasuk->jurnal_header_id ? 'disabled' : 'required' }}>
                                <option value="tunai" {{ old('metode_bayar', $stokMasuk->metode_bayar) == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="kredit" {{ old('metode_bayar', $stokMasuk->metode_bayar) == 'kredit' ? 'selected' : '' }}>Kredit/Utang</option>
                            </select>
                            @error('metode_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                {{ $stokMasuk->jurnal_header_id ? 'readonly' : '' }}>{{ old('keterangan', $stokMasuk->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Detail Barang</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td>Kode:</td>
                                <td><strong>{{ $stokMasuk->barang->kode_barang }}</strong></td>
                            </tr>
                            <tr>
                                <td>Nama:</td>
                                <td>{{ $stokMasuk->barang->nama_barang }}</td>
                            </tr>
                            <tr>
                                <td>Stok Saat Ini:</td>
                                <td><span class="badge bg-success">{{ $stokMasuk->barang->stok }} {{ $stokMasuk->barang->satuan }}</span></td>
                            </tr>
                            <tr>
                                <td>Harga Beli:</td>
                                <td>Rp {{ number_format($stokMasuk->barang->harga_beli, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($stokMasuk->jurnal_header_id)
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-journal-check me-2"></i>Status Jurnal</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle"></i>
                            Sudah dijurnal
                            <br>
                            <small>
                                <a href="{{ route('jurnal.show', $stokMasuk->jurnal_header_id) }}" class="text-decoration-none" target="_blank">
                                    Lihat Jurnal <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        @if(!$stokMasuk->jurnal_header_id)
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-save"></i> Update Stok Masuk
                        </button>
                        @else
                        <button type="button" class="btn btn-secondary w-100 mb-2" disabled>
                            <i class="bi bi-lock"></i> Tidak Dapat Diubah
                        </button>
                        @endif
                        <a href="{{ route('stok-masuk.index') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@if(!$stokMasuk->jurnal_header_id)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('qty');
    const hargaInput = document.getElementById('harga');
    const subtotalInput = document.getElementById('subtotal');

    function hitungSubtotal() {
        const qty = parseFloat(qtyInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        const subtotal = qty * harga;
        subtotalInput.value = 'Rp ' + subtotal.toLocaleString('id-ID');
    }

    qtyInput.addEventListener('input', hitungSubtotal);
    hargaInput.addEventListener('input', hitungSubtotal);
});
</script>
@endif
@endsection
