@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-arrow-up-circle me-2"></i>Tambah Stok Keluar (Penjualan)</h2>
        <a href="{{ route('stok-keluar.index') }}" class="btn btn-secondary">
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

    <form action="{{ route('stok-keluar.store') }}" method="POST">
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
                                <small class="text-muted">Format: SK-YYYYMM-XXXX</small>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_keluar" class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_keluar') is-invalid @enderror" 
                                    id="tanggal_keluar" name="tanggal_keluar" value="{{ old('tanggal_keluar', date('Y-m-d')) }}" required>
                                @error('tanggal_keluar')
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
                                        data-stok="{{ $barang->stok }}"
                                        data-harga-beli="{{ $barang->harga_beli }}"
                                        data-harga-jual="{{ $barang->harga_jual }}"
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
                            <label for="customer" class="form-label">Customer <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer') is-invalid @enderror" 
                                id="customer" name="customer" value="{{ old('customer') }}" 
                                placeholder="Nama customer/pembeli" required>
                            @error('customer')
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
                                <small class="text-danger d-none" id="stok-warning">
                                    <i class="bi bi-exclamation-triangle"></i> Stok tidak cukup!
                                </small>
                            </div>
                            <div class="col-md-4">
                                <label for="harga" class="form-label">Harga Jual <span class="text-danger">*</span></label>
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
                            <label for="metode_terima" class="form-label">Metode Penerimaan <span class="text-danger">*</span></label>
                            <select class="form-select @error('metode_terima') is-invalid @enderror" id="metode_terima" name="metode_terima" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="tunai" {{ old('metode_terima') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="kredit" {{ old('metode_terima') == 'kredit' ? 'selected' : '' }}>Kredit/Piutang</option>
                            </select>
                            @error('metode_terima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Tunai: Kas (D) vs Penjualan (K) | Kredit: Piutang (D) vs Penjualan (K)
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
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-lightbulb"></i>
                            <strong>Catatan:</strong>
                            <ul class="mb-0 mt-2">
                                <li>2 Jurnal otomatis akan dibuat:
                                    <ol>
                                        <li>Jurnal Penjualan</li>
                                        <li>Jurnal HPP</li>
                                    </ol>
                                </li>
                                <li>Stok barang akan berkurang otomatis</li>
                                <li>Pastikan stok mencukupi</li>
                                <li>Periode harus dalam status "Open"</li>
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
                                    <td>Stok Tersedia:</td>
                                    <td><span class="badge bg-success" id="info-stok">0</span></td>
                                </tr>
                                <tr>
                                    <td>Harga Beli:</td>
                                    <td id="info-harga-beli">Rp 0</td>
                                </tr>
                                <tr>
                                    <td>Harga Jual:</td>
                                    <td id="info-harga-jual">Rp 0</td>
                                </tr>
                                <tr>
                                    <td>Satuan:</td>
                                    <td id="info-satuan">-</td>
                                </tr>
                            </table>

                            <div id="profit-info" class="d-none">
                                <div class="alert alert-success">
                                    <strong>Estimasi Laba:</strong>
                                    <h5 class="mb-0 mt-2" id="profit-value">Rp 0</h5>
                                    <small id="profit-margin">Margin: 0%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2" id="btnSubmit">
                            <i class="bi bi-save"></i> Simpan Stok Keluar
                        </button>
                        <a href="{{ route('stok-keluar.index') }}" class="btn btn-secondary w-100">
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
    const stokWarning = document.getElementById('stok-warning');
    const barangInfo = document.getElementById('barang-info');
    const btnSubmit = document.getElementById('btnSubmit');
    const profitInfo = document.getElementById('profit-info');

    let stokTersedia = 0;
    let hargaBeli = 0;

    // Update info barang saat dipilih
    barangSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value) {
            const hargaJual = selectedOption.dataset.hargaJual;
            hargaBeli = parseFloat(selectedOption.dataset.hargaBeli);
            const satuan = selectedOption.dataset.satuan;
            const kode = selectedOption.dataset.kode;
            stokTersedia = parseFloat(selectedOption.dataset.stok);

            hargaInput.value = hargaJual;
            satuanInfo.textContent = 'Satuan: ' + satuan;
            
            document.getElementById('info-kode').textContent = kode;
            document.getElementById('info-stok').textContent = stokTersedia + ' ' + satuan;
            document.getElementById('info-harga-beli').textContent = 'Rp ' + hargaBeli.toLocaleString('id-ID');
            document.getElementById('info-harga-jual').textContent = 'Rp ' + parseInt(hargaJual).toLocaleString('id-ID');
            document.getElementById('info-satuan').textContent = satuan;
            barangInfo.classList.remove('d-none');

            cekStok();
            hitungSubtotal();
        } else {
            hargaInput.value = '';
            satuanInfo.textContent = 'Satuan: -';
            barangInfo.classList.add('d-none');
            stokWarning.classList.add('d-none');
            subtotalInput.value = '';
            profitInfo.classList.add('d-none');
        }
    });

    // Cek stok
    function cekStok() {
        const qty = parseFloat(qtyInput.value) || 0;
        if (qty > stokTersedia) {
            stokWarning.classList.remove('d-none');
            btnSubmit.disabled = true;
            btnSubmit.classList.add('btn-secondary');
            btnSubmit.classList.remove('btn-primary');
        } else {
            stokWarning.classList.add('d-none');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('btn-secondary');
            btnSubmit.classList.add('btn-primary');
        }
    }

    // Hitung subtotal dan laba
    function hitungSubtotal() {
        const qty = parseFloat(qtyInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        const subtotal = qty * harga;
        subtotalInput.value = 'Rp ' + subtotal.toLocaleString('id-ID');

        // Hitung laba
        if (qty > 0 && harga > 0 && hargaBeli > 0) {
            const totalHpp = qty * hargaBeli;
            const laba = subtotal - totalHpp;
            const margin = ((laba / subtotal) * 100).toFixed(2);
            
            document.getElementById('profit-value').textContent = 'Rp ' + laba.toLocaleString('id-ID');
            document.getElementById('profit-margin').textContent = 'Margin: ' + margin + '%';
            profitInfo.classList.remove('d-none');
        } else {
            profitInfo.classList.add('d-none');
        }

        cekStok();
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
