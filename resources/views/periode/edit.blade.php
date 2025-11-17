@extends('layouts.app')

@section('title', 'Edit Periode')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Periode Akuntansi</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('periode.update', $periode) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tahun" class="form-label">Tahun *</label>
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror" 
                                       id="tahun" name="tahun" value="{{ old('tahun', $periode->tahun) }}" 
                                       min="2000" max="2100" required>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="bulan" class="form-label">Bulan *</label>
                                <select class="form-select @error('bulan') is-invalid @enderror" 
                                        id="bulan" name="bulan" required>
                                    <option value="">Pilih Bulan</option>
                                    <option value="1" {{ old('bulan', $periode->bulan) == 1 ? 'selected' : '' }}>Januari</option>
                                    <option value="2" {{ old('bulan', $periode->bulan) == 2 ? 'selected' : '' }}>Februari</option>
                                    <option value="3" {{ old('bulan', $periode->bulan) == 3 ? 'selected' : '' }}>Maret</option>
                                    <option value="4" {{ old('bulan', $periode->bulan) == 4 ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ old('bulan', $periode->bulan) == 5 ? 'selected' : '' }}>Mei</option>
                                    <option value="6" {{ old('bulan', $periode->bulan) == 6 ? 'selected' : '' }}>Juni</option>
                                    <option value="7" {{ old('bulan', $periode->bulan) == 7 ? 'selected' : '' }}>Juli</option>
                                    <option value="8" {{ old('bulan', $periode->bulan) == 8 ? 'selected' : '' }}>Agustus</option>
                                    <option value="9" {{ old('bulan', $periode->bulan) == 9 ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ old('bulan', $periode->bulan) == 10 ? 'selected' : '' }}>Oktober</option>
                                    <option value="11" {{ old('bulan', $periode->bulan) == 11 ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ old('bulan', $periode->bulan) == 12 ? 'selected' : '' }}>Desember</option>
                                </select>
                                @error('bulan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai *</label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       id="tanggal_mulai" name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai', $periode->tanggal_mulai->format('Y-m-d')) }}" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai *</label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai', $periode->tanggal_selesai->format('Y-m-d')) }}" required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="Open" {{ old('status', $periode->status) == 'Open' ? 'selected' : '' }}>Open (Dapat Digunakan)</option>
                                <option value="Closed" {{ old('status', $periode->status) == 'Closed' ? 'selected' : '' }}>Closed (Tidak Dapat Digunakan)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($periode->jurnalHeaders()->count() > 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Perhatian:</strong> Periode ini sudah memiliki {{ $periode->jurnalHeaders()->count() }} transaksi jurnal.
                        </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('periode.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
