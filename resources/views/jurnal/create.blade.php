@extends('layouts.app')

@section('title', 'Buat Jurnal Baru')

@section('content')
<div class="container-fluid">
    <!-- Form Input Jurnal -->
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Form Input Jurnal</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('jurnal.store') }}" method="POST" id="jurnalForm">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="no_bukti" class="form-label">No. Bukti *</label>
                        <input type="text" class="form-control @error('no_bukti') is-invalid @enderror" 
                               id="no_bukti" name="no_bukti" value="{{ old('no_bukti', $noBukti) }}" required>
                        @error('no_bukti')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi *</label>
                        <input type="date" class="form-control @error('tanggal_transaksi') is-invalid @enderror" 
                               id="tanggal_transaksi" name="tanggal_transaksi" 
                               value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
                        @error('tanggal_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="periode_id" class="form-label">Periode *</label>
                        <select class="form-select @error('periode_id') is-invalid @enderror" 
                                id="periode_id" name="periode_id" required>
                            <option value="">Pilih Periode</option>
                            @foreach($periodes as $periode)
                                <option value="{{ $periode->id }}" {{ old('periode_id') == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama_periode }} - {{ $periode->status }}
                                </option>
                            @endforeach
                        </select>
                        @error('periode_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi *</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="2" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <h5 class="mb-3">Detail Jurnal</h5>
                
                <div id="detailContainer">
                    <!-- Baris Detail akan ditambahkan di sini -->
                </div>

                <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addDetailRow()">
                    <i class="bi bi-plus"></i> Tambah Baris
                </button>

                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <strong>Total Debit:</strong> <span id="totalDebit">Rp 0</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <strong>Total Kredit:</strong> <span id="totalKredit">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Jurnal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let detailIndex = 0;
const coas = @json($coas);
const customers = @json($customers ?? []);
const suppliers = @json($suppliers ?? []);

function addDetailRow() {
    const container = document.getElementById('detailContainer');
    const row = document.createElement('div');
    row.className = 'row mb-2 detail-row';
    row.id = `detail-${detailIndex}`;
    
    row.innerHTML = `
        <div class="col-md-3">
            <select class="form-select" name="details[${detailIndex}][coa_id]" required onchange="calculateTotals()">
                <option value="">Pilih Akun</option>
                ${coas.map(coa => `<option value="${coa.id}">${coa.kode_akun} - ${coa.nama_akun}</option>`).join('')}
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" name="details[${detailIndex}][customer_id]">
                <option value="">Customer</option>
                ${customers.map(customer => `<option value="${customer.id}">${customer.nama_customer}</option>`).join('')}
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" name="details[${detailIndex}][supplier_id]">
                <option value="">Supplier</option>
                ${suppliers.map(supplier => `<option value="${supplier.id}">${supplier.nama_supplier}</option>`).join('')}
            </select>
        </div>
        <div class="col-md-1">
            <select class="form-select" name="details[${detailIndex}][posisi]" required onchange="calculateTotals()">
                <option value="">Posisi</option>
                <option value="Debit">Debit</option>
                <option value="Kredit">Kredit</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="details[${detailIndex}][jumlah]" 
                   placeholder="Jumlah" step="0.01" min="0" required onkeyup="calculateTotals()">
        </div>
        <div class="col-md-1">
            <input type="text" class="form-control" name="details[${detailIndex}][keterangan]" 
                   placeholder="Keterangan">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeDetailRow(${detailIndex})">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(row);
    detailIndex++;
}

function removeDetailRow(index) {
    const row = document.getElementById(`detail-${index}`);
    if (row) {
        row.remove();
        calculateTotals();
    }
}

function calculateTotals() {
    let totalDebit = 0;
    let totalKredit = 0;
    
    document.querySelectorAll('.detail-row').forEach(row => {
        const posisi = row.querySelector('select[name*="[posisi]"]').value;
        const jumlah = parseFloat(row.querySelector('input[name*="[jumlah]"]').value) || 0;
        
        if (posisi === 'Debit') {
            totalDebit += jumlah;
        } else if (posisi === 'Kredit') {
            totalKredit += jumlah;
        }
    });
    
    document.getElementById('totalDebit').textContent = 'Rp ' + totalDebit.toLocaleString('id-ID');
    document.getElementById('totalKredit').textContent = 'Rp ' + totalKredit.toLocaleString('id-ID');
    
    // Highlight jika tidak balance
    const debitAlert = document.querySelector('.alert-info:nth-of-type(1)');
    const kreditAlert = document.querySelector('.alert-info:nth-of-type(2)');
    
    if (totalDebit === totalKredit && totalDebit > 0) {
        debitAlert.className = 'alert alert-success';
        kreditAlert.className = 'alert alert-success';
    } else {
        debitAlert.className = 'alert alert-danger';
        kreditAlert.className = 'alert alert-danger';
    }
}

// Tambahkan 2 baris awal
window.onload = function() {
    addDetailRow();
    addDetailRow();
};
</script>
@endpush
