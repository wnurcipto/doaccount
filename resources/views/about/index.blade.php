@extends('layouts.app')

@section('title', 'Informasi Perusahaan')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">
        <i class="bi bi-building"></i> Informasi Perusahaan
    </h2>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Perusahaan</h5>
            <small>Informasi ini akan digunakan pada kop surat saat mencetak laporan</small>
        </div>
        <div class="card-body">
            @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner)
                {{-- Free account hanya bisa lihat, tidak bisa edit --}}
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Akun Free hanya dapat melihat informasi perusahaan. Untuk mengedit, silakan upgrade ke plan Starter/Professional/Enterprise.
                </div>
            @endif
            <form action="{{ route('about.update') }}" method="POST" enctype="multipart/form-data" @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) onsubmit="event.preventDefault(); alert('Akun Free tidak dapat mengedit informasi perusahaan. Silakan upgrade plan Anda.'); return false;" @endif>
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Logo -->
                    <div class="col-md-4 mb-4">
                        <label class="form-label">Logo Perusahaan</label>
                        <div class="text-center mb-3">
                            @if($company->logo)
                                <img src="{{ Storage::url($company->logo) }}" alt="Logo" 
                                     class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            @else
                                <div class="border rounded p-5 text-muted">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                    <p class="mt-2 mb-0">Belum ada logo</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*" @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) disabled @endif>
                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                    </div>

                    <!-- Informasi Perusahaan -->
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="nama_perusahaan" class="form-label">Nama Perusahaan *</label>
                                <input type="text" class="form-control" id="nama_perusahaan" 
                                       name="nama_perusahaan" value="{{ old('nama_perusahaan', $company->nama_perusahaan) }}" 
                                       @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) readonly @endif required>
                            </div>

                            <div class="col-md-12">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="2" @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) readonly @endif>{{ old('alamat', $company->alamat) }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="kota" class="form-label">Kota</label>
                                <input type="text" class="form-control" id="kota" 
                                       name="kota" value="{{ old('kota', $company->kota) }}"
                                       @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) readonly @endif>
                            </div>

                            <div class="col-md-6">
                                <label for="provinsi" class="form-label">Provinsi</label>
                                <input type="text" class="form-control" id="provinsi" 
                                       name="provinsi" value="{{ old('provinsi', $company->provinsi) }}"
                                       @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) readonly @endif>
                            </div>

                            <div class="col-md-4">
                                <label for="kode_pos" class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" id="kode_pos" 
                                       name="kode_pos" value="{{ old('kode_pos', $company->kode_pos) }}"
                                       @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) readonly @endif>
                            </div>

                            <div class="col-md-4">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telepon" 
                                       name="telepon" value="{{ old('telepon', $company->telepon) }}"
                                       @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) readonly @endif>
                            </div>

                            <div class="col-md-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" 
                                       name="email" value="{{ old('email', $company->email) }}"
                                       @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) readonly @endif>
                            </div>

                            <div class="col-md-12">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" class="form-control" id="website" 
                                       name="website" value="{{ old('website', $company->website) }}"
                                       @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) readonly @endif>
                            </div>

                            <div class="col-md-12">
                                <label for="footer_text" class="form-label">Teks Footer (untuk cetakan)</label>
                                <input type="text" class="form-control" id="footer_text" 
                                       name="footer_text" value="{{ old('footer_text', $company->footer_text) }}"
                                       placeholder="Contoh: Terima kasih atas kepercayaan Anda"
                                       @if(auth()->user()->plan === 'free' && !auth()->user()->is_owner) readonly @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    @if(auth()->user()->plan !== 'free' || auth()->user()->is_owner)
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                    @endif
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> {{ auth()->user()->plan === 'free' && !auth()->user()->is_owner ? 'Kembali' : 'Batal' }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Kop Surat -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Preview Kop Surat</h5>
        </div>
        <div class="card-body">
            <div class="print-header-preview border p-4">
                <div class="d-flex align-items-start" style="gap: 20px;">
                    <!-- Logo di Kiri -->
                    <div style="flex-shrink: 0;">
                        @if($company->logo)
                            <img src="{{ Storage::url($company->logo) }}" alt="Logo" 
                                 style="width: 80px; height: 80px; object-fit: contain;">
                        @else
                            <div class="bg-light p-3 rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-building" style="font-size: 2rem; color: #ccc;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Informasi di Kanan -->
                    <div style="flex: 1; min-width: 0;">
                        <h4 class="mb-2" style="font-size: 18px; font-weight: bold;">{{ $company->nama_perusahaan }}</h4>
                        @if($company->alamat)
                            <p class="mb-1 small" style="font-size: 11px; line-height: 1.4;">{{ $company->alamat }}</p>
                        @endif
                        <p class="mb-1 small" style="font-size: 11px; line-height: 1.4;">
                            @if($company->kota && $company->provinsi)
                                {{ $company->kota }}, {{ $company->provinsi }}
                                @if($company->kode_pos) {{ $company->kode_pos }} @endif
                            @endif
                        </p>
                        <p class="mb-0 small" style="font-size: 11px; line-height: 1.4;">
                            @if($company->telepon) Telp: {{ $company->telepon }} @endif
                            @if($company->email) | Email: {{ $company->email }} @endif
                            @if($company->website) | Website: {{ $company->website }} @endif
                        </p>
                    </div>
                </div>
                <hr class="my-3" style="border-top: 2px solid #333;">
            </div>
        </div>
    </div>
</div>
@endsection

