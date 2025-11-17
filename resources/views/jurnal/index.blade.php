@extends('layouts.app')

@section('title', 'Daftar Jurnal')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Jurnal</h2>
        <div class="btn-group">
            <a href="{{ route('jurnal.upload-csv') }}" class="btn btn-success">
                <i class="bi bi-upload"></i> Upload CSV
            </a>
            <a href="{{ route('jurnal.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Buat Jurnal Baru
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <button class="btn btn-link text-dark text-decoration-none p-0 w-100 text-start" type="button" 
                        data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="{{ !empty(array_filter($filters ?? [])) ? 'true' : 'false' }}">
                    <i class="bi bi-funnel"></i> Filter Jurnal
                    @if(!empty(array_filter($filters ?? [])))
                        <span class="badge bg-primary ms-2">{{ count(array_filter($filters ?? [])) }}</span>
                    @endif
                    <i class="bi bi-chevron-down float-end"></i>
                </button>
            </h5>
        </div>
        <div class="collapse {{ !empty(array_filter($filters ?? [])) ? 'show' : '' }}" id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('jurnal.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Cari (No. Bukti / Deskripsi)</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ $filters['search'] ?? '' }}" placeholder="Cari...">
                        </div>
                        <div class="col-md-2">
                            <label for="periode_id" class="form-label">Periode</label>
                            <select class="form-select" id="periode_id" name="periode_id">
                                <option value="">Semua Periode</option>
                                @foreach($periodes as $periode)
                                    <option value="{{ $periode->id }}" {{ ($filters['periode_id'] ?? '') == $periode->id ? 'selected' : '' }}>
                                        {{ $periode->nama_periode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="Draft" {{ ($filters['status'] ?? '') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Posted" {{ ($filters['status'] ?? '') == 'Posted' ? 'selected' : '' }}>Posted</option>
                                <option value="Void" {{ ($filters['status'] ?? '') == 'Void' ? 'selected' : '' }}>Void</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" 
                                   value="{{ $filters['tanggal_mulai'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" 
                                   value="{{ $filters['tanggal_selesai'] ?? '' }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                @if(!empty(array_filter($filters ?? [])))
                                <a href="{{ route('jurnal.index', ['clear_filter' => 1]) }}" class="btn btn-secondary" title="Reset">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(auth()->user()->is_owner)
    <!-- Bulk Action Form untuk Owner -->
    <div class="card mb-3" id="bulkActionCard" style="display: none;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-check2-square"></i> Bulk Action
                <span id="selectedCount" class="badge bg-light text-dark ms-2">0</span> jurnal dipilih
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('jurnal.bulk-update') }}" method="POST" id="bulkActionForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="jurnal_ids" id="bulkJurnalIds">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="bulk_action" class="form-label">Aksi</label>
                        <select class="form-select" id="bulk_action" name="bulk_action" required>
                            <option value="">Pilih Aksi</option>
                            <option value="change_owner">Ubah Pemilik</option>
                            <option value="change_status">Ubah Status</option>
                            <option value="change_periode">Ubah Periode</option>
                        </select>
                    </div>
                    <div class="col-md-4" id="bulkOwnerField" style="display: none;">
                        <label for="bulk_user_id" class="form-label">Pemilik Baru</label>
                        <select class="form-select" id="bulk_user_id" name="user_id">
                            <option value="">Pilih Pemilik</option>
                            @if(isset($users))
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4" id="bulkStatusField" style="display: none;">
                        <label for="bulk_status" class="form-label">Status Baru</label>
                        <select class="form-select" id="bulk_status" name="status">
                            <option value="">Pilih Status</option>
                            <option value="Draft">Draft</option>
                            <option value="Posted">Posted</option>
                            <option value="Void">Void</option>
                        </select>
                    </div>
                    <div class="col-md-4" id="bulkPeriodeField" style="display: none;">
                        <label for="bulk_periode_id" class="form-label">Periode Baru</label>
                        <select class="form-select" id="bulk_periode_id" name="periode_id">
                            <option value="">Pilih Periode</option>
                            @if(isset($allPeriodes))
                                @foreach($allPeriodes as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->nama_periode }}@if($p->user) ({{ $p->user->name }})@endif
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Terapkan
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="clearBulkSelection()">
                            <i class="bi bi-x-circle"></i> Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            @if(auth()->user()->is_owner)
                            <th width="40">
                                <input type="checkbox" id="selectAll" title="Pilih Semua">
                            </th>
                            @endif
                            <th>No. Bukti</th>
                            <th>Tanggal</th>
                            <th>Periode</th>
                            <th>Deskripsi</th>
                            @if(auth()->user()->is_owner)
                            <th>Pemilik</th>
                            @endif
                            <th class="text-end">Total Debit</th>
                            <th class="text-end">Total Kredit</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="min-width:220px;white-space:nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jurnals as $jurnal)
                        <tr>
                            @if(auth()->user()->is_owner)
                            <td>
                                <input type="checkbox" class="jurnal-checkbox" value="{{ $jurnal->id }}" 
                                       data-jurnal-id="{{ $jurnal->id }}">
                            </td>
                            @endif
                            <td><strong>{{ $jurnal->no_bukti }}</strong></td>
                            <td>{{ $jurnal->tanggal_transaksi->format('d/m/Y') }}</td>
                            <td>{{ $jurnal->periode->nama_periode }}</td>
                            <td>{{ Str::limit($jurnal->deskripsi, 50) }}</td>
                            @if(auth()->user()->is_owner)
                            <td>
                                <span class="badge bg-info text-white">
                                    <i class="bi bi-person"></i> {{ $jurnal->user->name ?? 'System' }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $jurnal->user->email ?? '-' }}</small>
                            </td>
                            @endif
                            <td class="text-end">Rp {{ number_format($jurnal->total_debit, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($jurnal->total_kredit, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($jurnal->status == 'Draft')
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @elseif($jurnal->status == 'Posted')
                                    <span class="badge bg-success">Posted</span>
                                @else
                                    <span class="badge bg-danger">Void</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap" style="min-width:220px;">
                                    <a href="{{ route('jurnal.show', $jurnal) }}" class="btn btn-outline-info btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('jurnal.duplicate', $jurnal) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" title="Duplicate">
                                            <i class="bi bi-files"></i>
                                        </button>
                                    </form>
                                    @if($jurnal->status == 'Draft' || (auth()->user()->is_owner && $jurnal->status != 'Void'))
                                        <a href="{{ route('jurnal.edit', $jurnal) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                    @if($jurnal->status == 'Draft')
                                        <form action="{{ route('jurnal.post', $jurnal) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm" title="Post" 
                                                    onclick="return confirm('Yakin ingin memposting jurnal ini?')">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('jurnal.destroy', $jurnal) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus" 
                                                    onclick="return confirm('Yakin ingin menghapus jurnal ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->is_owner ? '10' : '8' }}" class="text-center text-muted">
                                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                <p class="mt-2">Belum ada data jurnal. <a href="{{ route('jurnal.create') }}">Buat jurnal baru</a></p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($jurnals->hasPages())
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ $jurnals->firstItem() ?? 0 }} sampai {{ $jurnals->lastItem() ?? 0 }} dari {{ $jurnals->total() }} data
                </div>
                <div>
                    {{ $jurnals->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->is_owner)
@push('scripts')
<script>
// Bulk Selection JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.jurnal-checkbox');
    const bulkActionCard = document.getElementById('bulkActionCard');
    const selectedCount = document.getElementById('selectedCount');
    const bulkJurnalIds = document.getElementById('bulkJurnalIds');
    const bulkAction = document.getElementById('bulk_action');
    const bulkOwnerField = document.getElementById('bulkOwnerField');
    const bulkStatusField = document.getElementById('bulkStatusField');
    const bulkPeriodeField = document.getElementById('bulkPeriodeField');
    const bulkUserId = document.getElementById('bulk_user_id');
    const bulkStatus = document.getElementById('bulk_status');
    const bulkPeriodeId = document.getElementById('bulk_periode_id');
    const bulkActionForm = document.getElementById('bulkActionForm');

    // Select All checkbox
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkAction();
        });
    }

    // Individual checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAll();
            updateBulkAction();
        });
    });

    // Update Select All checkbox
    function updateSelectAll() {
        if (selectAll) {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            const someChecked = Array.from(checkboxes).some(cb => cb.checked);
            selectAll.checked = allChecked;
            selectAll.indeterminate = someChecked && !allChecked;
        }
    }

    // Update Bulk Action UI
    function updateBulkAction() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
        const count = selected.length;

        if (count > 0) {
            bulkActionCard.style.display = 'block';
            selectedCount.textContent = count;
            bulkJurnalIds.value = selected.join(',');
        } else {
            bulkActionCard.style.display = 'none';
            selectedCount.textContent = '0';
            bulkJurnalIds.value = '';
            // Reset semua field termasuk bulkAction
            bulkAction.value = '';
            resetBulkFields();
        }
    }

    // Reset bulk fields (tidak reset bulkAction)
    function resetBulkFields() {
        bulkOwnerField.style.display = 'none';
        bulkStatusField.style.display = 'none';
        bulkPeriodeField.style.display = 'none';
        bulkUserId.value = '';
        bulkStatus.value = '';
        bulkPeriodeId.value = '';
        bulkUserId.required = false;
        bulkStatus.required = false;
        bulkPeriodeId.required = false;
    }

    // Show/hide fields based on action
    bulkAction.addEventListener('change', function(e) {
        e.stopPropagation(); // Prevent event bubbling
        
        const selectedValue = this.value;
        console.log('Bulk action changed to:', selectedValue);
        
        // Sembunyikan semua field dulu
        bulkOwnerField.style.display = 'none';
        bulkStatusField.style.display = 'none';
        bulkPeriodeField.style.display = 'none';
        bulkUserId.required = false;
        bulkStatus.required = false;
        bulkPeriodeId.required = false;
        
        // Reset nilai field
        bulkUserId.value = '';
        bulkStatus.value = '';
        bulkPeriodeId.value = '';
        
        // Tampilkan field sesuai action yang dipilih
        if (selectedValue === 'change_owner') {
            bulkOwnerField.style.display = 'block';
            bulkUserId.required = true;
            console.log('Showing owner field');
        } else if (selectedValue === 'change_status') {
            bulkStatusField.style.display = 'block';
            bulkStatus.required = true;
            console.log('Showing status field');
        } else if (selectedValue === 'change_periode') {
            bulkPeriodeField.style.display = 'block';
            bulkPeriodeId.required = true;
            console.log('Showing periode field');
        }
    });

    // Form validation
    bulkActionForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default submit
        
        const action = bulkAction.value;
        let isValid = true;
        let errorMessage = '';

        if (!action) {
            isValid = false;
            errorMessage = 'Silakan pilih aksi terlebih dahulu';
        } else if (action === 'change_owner' && !bulkUserId.value) {
            isValid = false;
            errorMessage = 'Silakan pilih pemilik baru';
        } else if (action === 'change_status' && !bulkStatus.value) {
            isValid = false;
            errorMessage = 'Silakan pilih status baru';
        } else if (action === 'change_periode' && !bulkPeriodeId.value) {
            isValid = false;
            errorMessage = 'Silakan pilih periode baru';
        }

        if (!isValid) {
            alert(errorMessage);
            return false;
        }

        // Konfirmasi sebelum submit
        const selectedCount = document.getElementById('selectedCount').textContent;
        const actionNames = {
            'change_owner': 'mengubah pemilik',
            'change_status': 'mengubah status',
            'change_periode': 'mengubah periode'
        };
        const actionName = actionNames[action] || 'memperbarui';
        
        if (confirm(`Yakin ingin ${actionName} ${selectedCount} jurnal yang dipilih?`)) {
            // Submit form
            this.submit();
        }
    });
});

// Clear bulk selection
function clearBulkSelection() {
    document.querySelectorAll('.jurnal-checkbox').forEach(cb => cb.checked = false);
    if (document.getElementById('selectAll')) {
        document.getElementById('selectAll').checked = false;
        document.getElementById('selectAll').indeterminate = false;
    }
    document.getElementById('bulkActionCard').style.display = 'none';
    document.getElementById('bulk_action').value = '';
    document.getElementById('bulkOwnerField').style.display = 'none';
    document.getElementById('bulkStatusField').style.display = 'none';
    document.getElementById('bulkPeriodeField').style.display = 'none';
}
</script>
@endpush
@endif
@endsection
