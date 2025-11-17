@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h2><i class="bi bi-pencil"></i> Edit Barang: {{ $barang->nama_barang }}</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('barang.update', $barang) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kode_barang" class="form-label">Kode Barang *</label>
                            <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" 
                                   id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" required>
                            @error('kode_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang *</label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" 
                                   id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control @error('kategori') is-invalid @enderror" 
                                   id="kategori" name="kategori" value="{{ old('kategori', $barang->kategori) }}">
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan *</label>
                            <select class="form-select @error('satuan') is-invalid @enderror" id="satuan" name="satuan" required>
                                <option value="">Pilih Satuan</option>
                                <option value="PCS" {{ old('satuan', $barang->satuan) == 'PCS' ? 'selected' : '' }}>PCS</option>
                                <option value="UNIT" {{ old('satuan', $barang->satuan) == 'UNIT' ? 'selected' : '' }}>UNIT</option>
                                <option value="SET" {{ old('satuan', $barang->satuan) == 'SET' ? 'selected' : '' }}>SET</option>
                                <option value="BOX" {{ old('satuan', $barang->satuan) == 'BOX' ? 'selected' : '' }}>BOX</option>
                                <option value="KG" {{ old('satuan', $barang->satuan) == 'KG' ? 'selected' : '' }}>KG</option>
                                <option value="LITER" {{ old('satuan', $barang->satuan) == 'LITER' ? 'selected' : '' }}>LITER</option>
                            </select>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="harga_beli" class="form-label">Harga Beli (per satuan) *</label>
                            <input type="number" class="form-control @error('harga_beli') is-invalid @enderror" 
                                   id="harga_beli" name="harga_beli" value="{{ old('harga_beli', $barang->harga_beli) }}" 
                                   min="0" step="0.01" required>
                            @error('harga_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="harga_jual" class="form-label">Harga Jual (per satuan) *</label>
                            <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" 
                                   id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}" 
                                   min="0" step="0.01" required>
                            @error('harga_jual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="stok_minimal" class="form-label">Stok Minimal *</label>
                            <input type="number" class="form-control @error('stok_minimal') is-invalid @enderror" 
                                   id="stok_minimal" name="stok_minimal" value="{{ old('stok_minimal', $barang->stok_minimal) }}" 
                                   min="0" required>
                            @error('stok_minimal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stok Saat Ini</label>
                    <input type="text" class="form-control" value="{{ $barang->stok }} {{ $barang->satuan }}" disabled>
                    <small class="text-muted">Stok tidak bisa diubah langsung. Gunakan Stok Masuk/Keluar.</small>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $barang->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                           {{ old('is_active', $barang->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Status Aktif
                    </label>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Update Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
