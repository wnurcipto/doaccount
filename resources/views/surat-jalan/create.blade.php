@extends('layouts.app')

@section('title', 'Buat Surat Jalan Baru')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Form Input Surat Jalan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('surat-jalan.store') }}" method="POST" id="suratJalanForm">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="no_surat_jalan" class="form-label">No. Surat Jalan *</label>
                        <input type="text" class="form-control @error('no_surat_jalan') is-invalid @enderror" 
                               id="no_surat_jalan" name="no_surat_jalan" value="{{ old('no_surat_jalan', $noSuratJalan) }}" required>
                        @error('no_surat_jalan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal *</label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                               id="tanggal" name="tanggal" 
                               value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Informasi Pengirim</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="dari_nama" class="form-label">Nama *</label>
                        <input type="text" class="form-control @error('dari_nama') is-invalid @enderror" 
                               id="dari_nama" name="dari_nama" value="{{ old('dari_nama') }}" required>
                        @error('dari_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="dari_telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control @error('dari_telepon') is-invalid @enderror" 
                               id="dari_telepon" name="dari_telepon" value="{{ old('dari_telepon') }}">
                        @error('dari_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="dari_alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('dari_alamat') is-invalid @enderror" 
                                  id="dari_alamat" name="dari_alamat" rows="2">{{ old('dari_alamat') }}</textarea>
                        @error('dari_alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="dari_kota" class="form-label">Kota</label>
                        <input type="text" class="form-control @error('dari_kota') is-invalid @enderror" 
                               id="dari_kota" name="dari_kota" value="{{ old('dari_kota') }}">
                        @error('dari_kota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Informasi Penerima</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kepada_nama" class="form-label">Nama *</label>
                        <input type="text" class="form-control @error('kepada_nama') is-invalid @enderror" 
                               id="kepada_nama" name="kepada_nama" value="{{ old('kepada_nama') }}" required>
                        @error('kepada_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="kepada_telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control @error('kepada_telepon') is-invalid @enderror" 
                               id="kepada_telepon" name="kepada_telepon" value="{{ old('kepada_telepon') }}">
                        @error('kepada_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="kepada_alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('kepada_alamat') is-invalid @enderror" 
                                  id="kepada_alamat" name="kepada_alamat" rows="2">{{ old('kepada_alamat') }}</textarea>
                        @error('kepada_alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="kepada_kota" class="form-label">Kota</label>
                        <input type="text" class="form-control @error('kepada_kota') is-invalid @enderror" 
                               id="kepada_kota" name="kepada_kota" value="{{ old('kepada_kota') }}">
                        @error('kepada_kota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Informasi Pengiriman</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="no_kendaraan" class="form-label">No. Kendaraan</label>
                        <input type="text" class="form-control @error('no_kendaraan') is-invalid @enderror" 
                               id="no_kendaraan" name="no_kendaraan" value="{{ old('no_kendaraan') }}">
                        @error('no_kendaraan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nama_supir" class="form-label">Nama Supir</label>
                        <input type="text" class="form-control @error('nama_supir') is-invalid @enderror" 
                               id="nama_supir" name="nama_supir" value="{{ old('nama_supir') }}">
                        @error('nama_supir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Detail Item</h5>
                
                <div id="itemContainer">
                    <!-- Baris Item akan ditambahkan di sini -->
                </div>

                <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addItemRow()">
                    <i class="bi bi-plus"></i> Tambah Item
                </button>

                <hr>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('surat-jalan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Surat Jalan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 0;

function addItemRow(item = null) {
    const container = document.getElementById('itemContainer');
    const row = document.createElement('div');
    row.className = 'row mb-2 item-row';
    row.id = `item-${itemIndex}`;
    
    row.innerHTML = `
        <div class="col-md-5">
            <input type="text" class="form-control" name="items[${itemIndex}][nama_item]" 
                   placeholder="Nama Item" value="${item ? item.nama_item : ''}" required>
        </div>
        <div class="col-md-3">
            <textarea class="form-control" name="items[${itemIndex}][deskripsi]" 
                      placeholder="Deskripsi" rows="1">${item ? (item.deskripsi || '') : ''}</textarea>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="items[${itemIndex}][qty]" 
                   placeholder="Qty" value="${item ? item.qty : '1'}" min="1" step="1" required>
        </div>
        <div class="col-md-1">
            <input type="text" class="form-control" name="items[${itemIndex}][satuan]" 
                   placeholder="Satuan" value="${item ? (item.satuan || '') : ''}">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(${itemIndex})">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(row);
    itemIndex++;
}

function removeItemRow(index) {
    const row = document.getElementById(`item-${index}`);
    if (row) {
        row.remove();
    }
}

window.onload = function() {
    addItemRow();
};
</script>
@endpush

