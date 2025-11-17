@extends('layouts.app')

@section('title', 'Tambah Periode')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tambah Periode Akuntansi</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('periode.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tahun" class="form-label">Tahun *</label>
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror" 
                                       id="tahun" name="tahun" value="{{ old('tahun', date('Y')) }}" 
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
                                    <option value="1" {{ old('bulan') == 1 ? 'selected' : '' }}>Januari</option>
                                    <option value="2" {{ old('bulan') == 2 ? 'selected' : '' }}>Februari</option>
                                    <option value="3" {{ old('bulan') == 3 ? 'selected' : '' }}>Maret</option>
                                    <option value="4" {{ old('bulan') == 4 ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ old('bulan') == 5 ? 'selected' : '' }}>Mei</option>
                                    <option value="6" {{ old('bulan') == 6 ? 'selected' : '' }}>Juni</option>
                                    <option value="7" {{ old('bulan') == 7 ? 'selected' : '' }}>Juli</option>
                                    <option value="8" {{ old('bulan') == 8 ? 'selected' : '' }}>Agustus</option>
                                    <option value="9" {{ old('bulan') == 9 ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ old('bulan') == 10 ? 'selected' : '' }}>Oktober</option>
                                    <option value="11" {{ old('bulan') == 11 ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ old('bulan') == 12 ? 'selected' : '' }}>Desember</option>
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
                                       value="{{ old('tanggal_mulai') }}" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai *</label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai') }}" required>
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
                                <option value="Open" {{ old('status') == 'Open' ? 'selected' : '' }}>Open (Dapat Digunakan)</option>
                                <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed (Tidak Dapat Digunakan)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Tips:</strong> Biasanya periode dibuat untuk satu bulan penuh. 
                            Contoh: Januari 2025 dari 01/01/2025 sampai 31/01/2025
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('periode.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-set tanggal mulai dan selesai berdasarkan tahun dan bulan
document.getElementById('tahun').addEventListener('change', updateDates);
document.getElementById('bulan').addEventListener('change', updateDates);

function updateDates() {
    const tahun = document.getElementById('tahun').value;
    const bulan = document.getElementById('bulan').value;
    
    if (tahun && bulan) {
        // Tanggal mulai = hari pertama bulan
        const tanggalMulai = new Date(tahun, bulan - 1, 1);
        
        // Tanggal selesai = hari terakhir bulan
        const tanggalSelesai = new Date(tahun, bulan, 0);
        
        // Format ke YYYY-MM-DD
        document.getElementById('tanggal_mulai').value = tanggalMulai.toISOString().split('T')[0];
        document.getElementById('tanggal_selesai').value = tanggalSelesai.toISOString().split('T')[0];
    }
}
</script>
@endpush
@endsection
