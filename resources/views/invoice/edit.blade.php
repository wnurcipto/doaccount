@extends('layouts.app')

@section('title', 'Edit Invoice - ' . $invoice->no_invoice)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Invoice</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('invoice.update', $invoice) }}" method="POST" id="invoiceForm">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="no_invoice" class="form-label">No. Invoice *</label>
                        <input type="text" class="form-control @error('no_invoice') is-invalid @enderror" 
                               id="no_invoice" name="no_invoice" value="{{ old('no_invoice', $invoice->no_invoice) }}" required>
                        @error('no_invoice')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal *</label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                               id="tanggal" name="tanggal" 
                               value="{{ old('tanggal', $invoice->tanggal->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status">
                            <option value="Draft" {{ old('status', $invoice->status ?? 'Draft') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Sent" {{ old('status', $invoice->status ?? 'Draft') == 'Sent' ? 'selected' : '' }}>Sent</option>
                            <option value="Paid" {{ old('status', $invoice->status ?? 'Draft') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Overdue" {{ old('status', $invoice->status ?? 'Draft') == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                        @error('status')
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
                               id="kepada_nama" name="kepada_nama" value="{{ old('kepada_nama', $invoice->kepada_nama) }}" required>
                        @error('kepada_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="kepada_telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control @error('kepada_telepon') is-invalid @enderror" 
                               id="kepada_telepon" name="kepada_telepon" value="{{ old('kepada_telepon', $invoice->kepada_telepon) }}">
                        @error('kepada_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="kepada_alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('kepada_alamat') is-invalid @enderror" 
                                  id="kepada_alamat" name="kepada_alamat" rows="2">{{ old('kepada_alamat', $invoice->kepada_alamat) }}</textarea>
                        @error('kepada_alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="kepada_kota" class="form-label">Kota</label>
                        <input type="text" class="form-control @error('kepada_kota') is-invalid @enderror" 
                               id="kepada_kota" name="kepada_kota" value="{{ old('kepada_kota', $invoice->kepada_kota) }}">
                        @error('kepada_kota')
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

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $invoice->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                  id="catatan" name="catatan" rows="2">{{ old('catatan', $invoice->catatan) }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Term & Condition & Payment Terms</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="term_condition" class="form-label">
                            Term & Condition
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="loadDefaultTermCondition()">
                                <i class="bi bi-arrow-clockwise"></i> Load Template
                            </button>
                        </label>
                        <textarea class="form-control @error('term_condition') is-invalid @enderror" 
                                  id="term_condition" name="term_condition" rows="8">{{ old('term_condition', $invoice->term_condition) }}</textarea>
                        @error('term_condition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Syarat dan ketentuan yang berlaku untuk invoice ini</small>
                    </div>
                    <div class="col-md-6">
                        <label for="payment_terms" class="form-label">
                            Payment Terms (Aturan Pembayaran)
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="loadDefaultPaymentTerms()">
                                <i class="bi bi-arrow-clockwise"></i> Load Template
                            </button>
                        </label>
                        <textarea class="form-control @error('payment_terms') is-invalid @enderror" 
                                  id="payment_terms" name="payment_terms" rows="8">{{ old('payment_terms', $invoice->payment_terms) }}</textarea>
                        @error('payment_terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Ketentuan pembayaran dan metode pembayaran yang diterima</small>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Signature</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="signature_name" class="form-label">Nama Penandatangan</label>
                        <input type="text" class="form-control @error('signature_name') is-invalid @enderror" 
                               id="signature_name" name="signature_name" 
                               value="{{ old('signature_name', $invoice->signature_name) }}" 
                               placeholder="Nama yang akan muncul di bawah signature">
                        @error('signature_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Nama yang akan ditampilkan di bawah area signature</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Subtotal</strong></td>
                                <td class="text-end"><span id="displaySubtotal">Rp 0</span></td>
                                <input type="hidden" id="subtotal" name="subtotal" value="{{ $invoice->subtotal }}">
                            </tr>
                            <tr>
                                <td><strong>Diskon</strong></td>
                                <td>
                                    <input type="number" class="form-control text-end" id="diskon" name="diskon" 
                                           value="{{ old('diskon', $invoice->diskon) }}" min="0" step="0.01" onchange="calculateTotal()">
                                </td>
                            </tr>
                            <tr>
                                <td><strong>PPN</strong></td>
                                <td>
                                    <input type="number" class="form-control text-end" id="ppn" name="ppn" 
                                           value="{{ old('ppn', $invoice->ppn) }}" min="0" step="0.01" onchange="calculateTotal()">
                                </td>
                            </tr>
                            <tr>
                                <td><strong>DP (Uang Muka)</strong></td>
                                <td>
                                    <input type="number" class="form-control text-end" id="dp" name="dp" 
                                           value="{{ old('dp', $invoice->dp ?? 0) }}" min="0" step="0.01" onchange="calculateTotal()">
                                </td>
                            </tr>
                            <tr class="table-success">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-end"><strong><span id="displayTotal">Rp 0</span></strong></td>
                                <input type="hidden" id="total" name="total" value="{{ $invoice->total }}">
                            </tr>
                            <tr class="table-info">
                                <td><strong>Sisa Tagihan</strong></td>
                                <td class="text-end"><strong><span id="displaySisa">Rp 0</span></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('invoice.show', $invoice) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 0;
const items = @json($invoice->items);

function addItemRow(item = null) {
    const container = document.getElementById('itemContainer');
    const row = document.createElement('div');
    row.className = 'row mb-2 item-row';
    row.id = `item-${itemIndex}`;
    
    row.innerHTML = `
        <div class="col-md-4">
            <input type="text" class="form-control" name="items[${itemIndex}][nama_item]" 
                   placeholder="Nama Item" value="${item ? item.nama_item : ''}" required>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="items[${itemIndex}][qty]" 
                   placeholder="Qty" value="${item ? item.qty : '1'}" min="1" step="1" required onchange="calculateItemTotal(${itemIndex})">
        </div>
        <div class="col-md-1">
            <input type="text" class="form-control" name="items[${itemIndex}][satuan]" 
                   placeholder="Satuan" value="${item ? (item.satuan || '') : ''}">
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="items[${itemIndex}][harga]" 
                   placeholder="Harga" value="${item ? item.harga : ''}" min="0" step="0.01" required onchange="calculateItemTotal(${itemIndex})">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control bg-light" name="items[${itemIndex}][total]" 
                   placeholder="Total" value="${item ? item.total : ''}" readonly>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(${itemIndex})">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(row);
    itemIndex++;
    if (item) {
        calculateItemTotal(itemIndex - 1);
    }
}

function removeItemRow(index) {
    const row = document.getElementById(`item-${index}`);
    if (row) {
        row.remove();
        calculateTotal();
    }
}

function calculateItemTotal(index) {
    const row = document.getElementById(`item-${index}`);
    if (!row) return;
    
    const qty = parseFloat(row.querySelector('input[name*="[qty]"]').value) || 0;
    const harga = parseFloat(row.querySelector('input[name*="[harga]"]').value) || 0;
    const total = qty * harga;
    
    row.querySelector('input[name*="[total]"]').value = total.toFixed(2);
    calculateTotal();
}

function calculateTotal() {
    let subtotal = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const total = parseFloat(row.querySelector('input[name*="[total]"]').value) || 0;
        subtotal += total;
    });
    
    const diskon = parseFloat(document.getElementById('diskon').value) || 0;
    const ppn = parseFloat(document.getElementById('ppn').value) || 0;
    const dp = parseFloat(document.getElementById('dp').value) || 0;
    const total = subtotal - diskon + ppn;
    const sisa = total - dp;
    
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('displaySubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('total').value = total.toFixed(2);
    document.getElementById('displayTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('displaySisa').textContent = 'Rp ' + Math.max(0, sisa).toLocaleString('id-ID');
}

// Load existing items
window.onload = function() {
    if (items.length > 0) {
        items.forEach(item => {
            addItemRow(item);
        });
    } else {
        addItemRow();
    }
    calculateTotal();
    // Initialize displaySisa
    const dp = parseFloat(document.getElementById('dp').value) || 0;
    const total = parseFloat(document.getElementById('total').value) || 0;
    const sisa = total - dp;
    document.getElementById('displaySisa').textContent = 'Rp ' + Math.max(0, sisa).toLocaleString('id-ID');
};

// Template default Term & Condition
function loadDefaultTermCondition() {
    const defaultTerm = `1. Barang/jasa yang telah diterima tidak dapat dikembalikan kecuali ada kesepakatan tertulis sebelumnya.
2. Semua klaim atas cacat atau kerusakan harus dilaporkan dalam waktu 7 (tujuh) hari setelah barang diterima.
3. Harga yang tercantum dalam invoice ini adalah harga final dan tidak dapat dinegosiasikan setelah invoice diterbitkan.
4. Semua biaya pengiriman dan penanganan menjadi tanggung jawab pembeli kecuali dinyatakan lain.
5. Perusahaan berhak menolak atau membatalkan pesanan jika pembayaran tidak diterima sesuai dengan ketentuan yang berlaku.
6. Semua perselisihan akan diselesaikan melalui musyawarah, jika tidak tercapai akan diselesaikan melalui pengadilan yang berwenang.`;
    
    document.getElementById('term_condition').value = defaultTerm;
}

// Template default Payment Terms
function loadDefaultPaymentTerms() {
    const defaultPayment = `PEMBAYARAN:
1. Pembayaran harus dilakukan dalam jangka waktu 30 (tiga puluh) hari setelah tanggal invoice diterbitkan.
2. Pembayaran dapat dilakukan melalui:
   - Transfer Bank ke rekening: [NOMOR REKENING]
   - Bank: [NAMA BANK]
   - Atas Nama: [NAMA PEMILIK REKENING]
3. Bukti transfer harus dikirimkan via email atau WhatsApp untuk konfirmasi pembayaran.
4. Keterlambatan pembayaran akan dikenakan denda sebesar 2% per bulan dari total invoice.
5. Invoice dianggap lunas setelah pembayaran diterima dan dikonfirmasi oleh pihak kami.`;
    
    document.getElementById('payment_terms').value = defaultPayment;
}
</script>
@endpush

