@extends('layouts.app')

@section('title', 'Edit Offering - ' . $offering->no_offering)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Offering</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('offering.update', $offering) }}" method="POST" id="offeringForm">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="no_offering" class="form-label">No. Offering *</label>
                        <input type="text" class="form-control @error('no_offering') is-invalid @enderror" 
                               id="no_offering" name="no_offering" value="{{ old('no_offering', $offering->no_offering) }}" required>
                        @error('no_offering')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal *</label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                               id="tanggal" name="tanggal" 
                               value="{{ old('tanggal', $offering->tanggal->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_berlaku" class="form-label">Tanggal Berlaku</label>
                        <input type="date" class="form-control @error('tanggal_berlaku') is-invalid @enderror" 
                               id="tanggal_berlaku" name="tanggal_berlaku" 
                               value="{{ old('tanggal_berlaku', $offering->tanggal_berlaku ? $offering->tanggal_berlaku->format('Y-m-d') : '') }}">
                        @error('tanggal_berlaku')
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
                               id="kepada_nama" name="kepada_nama" value="{{ old('kepada_nama', $offering->kepada_nama) }}" required>
                        @error('kepada_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="kepada_telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control @error('kepada_telepon') is-invalid @enderror" 
                               id="kepada_telepon" name="kepada_telepon" value="{{ old('kepada_telepon', $offering->kepada_telepon) }}">
                        @error('kepada_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="kepada_alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('kepada_alamat') is-invalid @enderror" 
                                  id="kepada_alamat" name="kepada_alamat" rows="2">{{ old('kepada_alamat', $offering->kepada_alamat) }}</textarea>
                        @error('kepada_alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="kepada_kota" class="form-label">Kota</label>
                        <input type="text" class="form-control @error('kepada_kota') is-invalid @enderror" 
                               id="kepada_kota" name="kepada_kota" value="{{ old('kepada_kota', $offering->kepada_kota) }}">
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
                                  id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $offering->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                  id="catatan" name="catatan" rows="2">{{ old('catatan', $offering->catatan) }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Subtotal</strong></td>
                                <td class="text-end"><span id="displaySubtotal">Rp 0</span></td>
                                <input type="hidden" id="subtotal" name="subtotal" value="{{ $offering->subtotal }}">
                            </tr>
                            <tr>
                                <td><strong>Diskon</strong></td>
                                <td>
                                    <input type="number" class="form-control text-end" id="diskon" name="diskon" 
                                           value="{{ old('diskon', $offering->diskon) }}" min="0" step="0.01" onchange="calculateTotal()">
                                </td>
                            </tr>
                            <tr>
                                <td><strong>PPN</strong></td>
                                <td>
                                    <input type="number" class="form-control text-end" id="ppn" name="ppn" 
                                           value="{{ old('ppn', $offering->ppn) }}" min="0" step="0.01" onchange="calculateTotal()">
                                </td>
                            </tr>
                            <tr class="table-success">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-end"><strong><span id="displayTotal">Rp 0</span></strong></td>
                                <input type="hidden" id="total" name="total" value="{{ $offering->total }}">
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('offering.show', $offering) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Offering</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 0;
const items = @json($offering->items);

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
    const total = subtotal - diskon + ppn;
    
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('displaySubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('total').value = total.toFixed(2);
    document.getElementById('displayTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

window.onload = function() {
    if (items.length > 0) {
        items.forEach(item => {
            addItemRow(item);
        });
    } else {
        addItemRow();
    }
    calculateTotal();
};
</script>
@endpush

