@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-arrow-down-circle me-2"></i>Tambah Stok Masuk</h2>
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

    <form action="{{ route('stok-masuk.store') }}" method="POST">
        @csrf
        
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
                                    id="no_bukti" name="no_bukti" value="{{ old('no_bukti', $noBukti) }}" required>
                                @error('no_bukti')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: SM-YYYYMM-XXXX</small>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                    id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="periode_id" class="form-label">Periode <span class="text-danger">*</span></label>
                            <select class="form-select @error('periode_id') is-invalid @enderror" id="periode_id" name="periode_id" required>
                                <option value="">-- Pilih Periode --</option>
                                @foreach($periodes as $periode)
                                    <option value="{{ $periode->id }}" {{ old('periode_id', $periodeAktif?->id) == $periode->id ? 'selected' : '' }}
                                        {{ $periode->status != 'Open' ? 'disabled' : '' }}>
                                        {{ $periode->nama_periode }} 
                                        @if($periode->status != 'Open')
                                            ({{ $periode->status }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('periode_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="barang_id" class="form-label">Barang <span class="text-danger">*</span></label>
                            <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" 
                                        data-kode="{{ $barang->kode_barang }}"
                                        data-harga="{{ $barang->harga_beli }}"
                                        data-satuan="{{ $barang->satuan }}"
                                        {{ old('barang_id', request('barang_id')) == $barang->id ? 'selected' : '' }}>
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
                                id="supplier" name="supplier" value="{{ old('supplier') }}" 
                                placeholder="Nama supplier/pemasok" required>
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="qty" class="form-label">Qty <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('qty') is-invalid @enderror" 
                                    id="qty" name="qty" value="{{ old('qty', 1) }}" min="1" step="1" required>
                                @error('qty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="satuan-info">Satuan: -</small>
                            </div>
                            <div class="col-md-4">
                                <label for="harga" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                    id="harga" name="harga" value="{{ old('harga') }}" min="0" step="1" required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="subtotal" class="form-label">Subtotal</label>
                                <input type="text" class="form-control bg-light" id="subtotal" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="metode_bayar" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('metode_bayar') is-invalid @enderror" id="metode_bayar" name="metode_bayar" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="tunai" {{ old('metode_bayar') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="kredit" {{ old('metode_bayar') == 'kredit' ? 'selected' : '' }}>Kredit/Utang</option>
                            </select>
                            @error('metode_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Tunai: Persediaan (D) vs Kas (K) | Kredit: Persediaan (D) vs Utang Usaha (K)
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-lightbulb"></i>
                            <strong>Catatan:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Transaksi akan otomatis dijurnal</li>
                                <li>Stok barang akan bertambah otomatis</li>
                                <li>Periode harus dalam status "Open"</li>
                                <li>Data yang sudah dijurnal tidak bisa diedit</li>
                            </ul>
                        </div>

                        <div id="barang-info" class="d-none">
                            <h6 class="border-bottom pb-2">Detail Barang</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Kode:</td>
                                    <td><strong id="info-kode">-</strong></td>
                                </tr>
                                <tr>
                                    <td>Stok Saat Ini:</td>
                                    <td><span class="badge bg-success" id="info-stok">0</span></td>
                                </tr>
                                <tr>
                                    <td>Harga Beli:</td>
                                    <td id="info-harga">Rp 0</td>
                                </tr>
                                <tr>
                                    <td>Satuan:</td>
                                    <td id="info-satuan">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-save"></i> Simpan Stok Masuk
                        </button>
                        <a href="{{ route('stok-masuk.index') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('barang_id');
    const qtyInput = document.getElementById('qty');
    const hargaInput = document.getElementById('harga');
    const subtotalInput = document.getElementById('subtotal');
    const satuanInfo = document.getElementById('satuan-info');
    const barangInfo = document.getElementById('barang-info');

    // Update info barang saat dipilih
    barangSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value) {
            const harga = selectedOption.dataset.harga;
            const satuan = selectedOption.dataset.satuan;
            const kode = selectedOption.dataset.kode;
            const stokText = selectedOption.text.match(/Stok: (\d+)/);
            const stok = stokText ? stokText[1] : '0';

            hargaInput.value = harga;
            satuanInfo.textContent = 'Satuan: ' + satuan;
            
            document.getElementById('info-kode').textContent = kode;
            document.getElementById('info-stok').textContent = stok + ' ' + satuan;
            document.getElementById('info-harga').textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
            document.getElementById('info-satuan').textContent = satuan;
            barangInfo.classList.remove('d-none');

            hitungSubtotal();
        } else {
            hargaInput.value = '';
            satuanInfo.textContent = 'Satuan: -';
            barangInfo.classList.add('d-none');
            subtotalInput.value = '';
        }
    });

    // Hitung subtotal
    function hitungSubtotal() {
        const qty = parseFloat(qtyInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        const subtotal = qty * harga;
        subtotalInput.value = 'Rp ' + subtotal.toLocaleString('id-ID');
    }

    qtyInput.addEventListener('input', hitungSubtotal);
    hargaInput.addEventListener('input', hitungSubtotal);

    // Trigger saat halaman load jika ada barang terpilih
    if (barangSelect.value) {
        barangSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
