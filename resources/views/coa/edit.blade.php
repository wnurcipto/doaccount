@extends('layouts.app')

@section('title', 'Edit COA')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Chart of Account</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('coa.update', $coa) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="kode_akun" class="form-label">Kode Akun *</label>
                            <input type="text" class="form-control @error('kode_akun') is-invalid @enderror" 
                                   id="kode_akun" name="kode_akun" value="{{ old('kode_akun', $coa->kode_akun) }}" required>
                            @error('kode_akun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama_akun" class="form-label">Nama Akun *</label>
                            <input type="text" class="form-control @error('nama_akun') is-invalid @enderror" 
                                   id="nama_akun" name="nama_akun" value="{{ old('nama_akun', $coa->nama_akun) }}" required>
                            @error('nama_akun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tipe_akun" class="form-label">Tipe Akun *</label>
                            <select class="form-select @error('tipe_akun') is-invalid @enderror" 
                                    id="tipe_akun" name="tipe_akun" required>
                                <option value="">Pilih Tipe</option>
                                <option value="Aset" {{ old('tipe_akun', $coa->tipe_akun) == 'Aset' ? 'selected' : '' }}>Aset</option>
                                <option value="Liabilitas" {{ old('tipe_akun', $coa->tipe_akun) == 'Liabilitas' ? 'selected' : '' }}>Liabilitas</option>
                                <option value="Ekuitas" {{ old('tipe_akun', $coa->tipe_akun) == 'Ekuitas' ? 'selected' : '' }}>Ekuitas</option>
                                <option value="Pendapatan" {{ old('tipe_akun', $coa->tipe_akun) == 'Pendapatan' ? 'selected' : '' }}>Pendapatan</option>
                                <option value="Beban" {{ old('tipe_akun', $coa->tipe_akun) == 'Beban' ? 'selected' : '' }}>Beban</option>
                            </select>
                            @error('tipe_akun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="posisi_normal" class="form-label">Posisi Normal *</label>
                            <select class="form-select @error('posisi_normal') is-invalid @enderror" 
                                    id="posisi_normal" name="posisi_normal" required>
                                <option value="">Pilih Posisi</option>
                                <option value="Debit" {{ old('posisi_normal', $coa->posisi_normal) == 'Debit' ? 'selected' : '' }}>Debit</option>
                                <option value="Kredit" {{ old('posisi_normal', $coa->posisi_normal) == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                            </select>
                            @error('posisi_normal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="level" class="form-label">Level *</label>
                            <input type="number" class="form-control @error('level') is-invalid @enderror" 
                                   id="level" name="level" value="{{ old('level', $coa->level) }}" min="1" max="5" required>
                            @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent Account (Opsional)</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror" 
                                    id="parent_id" name="parent_id">
                                <option value="">Tidak Ada Parent</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->kode_akun }}" {{ old('parent_id', $coa->parent_id) == $parent->kode_akun ? 'selected' : '' }}>
                                        {{ $parent->kode_akun }} - {{ $parent->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', $coa->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $coa->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($coa->jurnalDetails()->count() > 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Perhatian:</strong> Akun ini sudah digunakan dalam {{ $coa->jurnalDetails()->count() }} transaksi.
                            Perubahan dapat mempengaruhi data historis.
                        </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('coa.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
