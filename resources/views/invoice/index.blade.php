@extends('layouts.app')

@section('title', 'Invoice')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-receipt"></i> Invoice</h2>
        <a href="{{ route('invoice.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Buat Invoice Baru
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Kepada</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                            <th class="text-center" style="min-width:140px;white-space:nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td><strong>{{ $invoice->no_invoice }}</strong></td>
                            <td>{{ date('d/m/Y', strtotime($invoice->tanggal)) }}</td>
                            <td>{{ $invoice->kepada_nama }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'Draft' => 'secondary',
                                        'Sent' => 'info',
                                        'Paid' => 'success',
                                        'Overdue' => 'danger'
                                    ];
                                    $color = $statusColors[$invoice->status ?? 'Draft'] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ $invoice->status ?? 'Draft' }}</span>
                            </td>
                            <td class="text-end">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap" style="min-width:180px;">
                                    <a href="{{ route('invoice.show', $invoice) }}" class="btn btn-outline-info btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('invoice.edit', $invoice) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('invoice.duplicate', $invoice) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" title="Duplicate">
                                            <i class="bi bi-files"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('invoice.destroy', $invoice) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus invoice ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                <p class="mt-2">Belum ada invoice</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

