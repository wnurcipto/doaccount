<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index()
    {
        $query = Invoice::query();
        $query = $this->scopeUser($query);
        $invoices = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('invoice.index', compact('invoices'));
    }

    public function create()
    {
        $user = $this->currentUser();
        $invoiceQuery = Invoice::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'));
        $invoiceQuery = $this->scopeUser($invoiceQuery);
        $noInvoice = 'INV/' . date('Y/m') . '/' . str_pad(($invoiceQuery->count() + 1), 4, '0', STR_PAD_LEFT);
        
        return view('invoice.create', compact('noInvoice'));
    }

    public function store(Request $request)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        $validated = $request->validate([
            'no_invoice' => 'required|unique:invoices,no_invoice,NULL,id,user_id,' . $user->id,
            'tanggal' => 'required|date',
            'kepada_nama' => 'required|string|max:255',
            'kepada_alamat' => 'nullable|string',
            'kepada_kota' => 'nullable|string|max:255',
            'kepada_telepon' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'term_condition' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'signature_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:Draft,Sent,Paid,Overdue',
            'subtotal' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'dp' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = $user->id;
        $invoice = Invoice::create($validated);

        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'nama_item' => $item['nama_item'],
                'deskripsi' => $item['deskripsi'] ?? null,
                'qty' => $item['qty'],
                'satuan' => $item['satuan'] ?? null,
                'harga' => $item['harga'],
                'total' => $item['total'],
            ]);
        }

        return redirect()->route('invoice.show', $invoice)->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $invoice)
    {
        // Pastikan user hanya bisa lihat invoice miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $invoice->user_id !== $user->id) {
            return redirect()->route('invoice.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat invoice ini');
        }

        $invoice->load('items');
        return view('invoice.show', compact('invoice'));
    }

    public function showV2(Invoice $invoice)
    {
        // Pastikan user hanya bisa lihat invoice miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $invoice->user_id !== $user->id) {
            return redirect()->route('invoice.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat invoice ini');
        }

        $invoice->load('items');
        return view('invoice.show-v2', compact('invoice'));
    }

    public function duplicate(Invoice $invoice)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa duplicate invoice miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $invoice->user_id !== $user->id) {
            return redirect()->route('invoice.index')
                ->with('error', 'Anda tidak memiliki akses untuk menduplikasi invoice ini');
        }

        $invoice->load('items');
        
        // Generate new invoice number (hanya dari invoice user)
        $invoiceQuery = Invoice::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'));
        $invoiceQuery = $this->scopeUser($invoiceQuery);
        $newNoInvoice = 'INV/' . date('Y/m') . '/' . str_pad(($invoiceQuery->count() + 1), 4, '0', STR_PAD_LEFT);
        
        // Create new invoice with same data
        $newInvoice = $invoice->replicate();
        $newInvoice->no_invoice = $newNoInvoice;
        $newInvoice->tanggal = date('Y-m-d');
        $newInvoice->status = 'Draft';
        $newInvoice->user_id = $user->id;
        $newInvoice->save();
        
        // Duplicate items
        foreach ($invoice->items as $item) {
            $newItem = $item->replicate();
            $newItem->invoice_id = $newInvoice->id;
            $newItem->save();
        }
        
        return redirect()->route('invoice.edit', $newInvoice)->with('success', 'Invoice berhasil diduplikasi. Silakan edit sesuai kebutuhan.');
    }

    public function exportPdf(Invoice $invoice)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa export invoice miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $invoice->user_id !== $user->id) {
            return redirect()->route('invoice.index')
                ->with('error', 'Anda tidak memiliki akses untuk export invoice ini');
        }

        $invoice->load('items');
        $company = \App\Models\CompanyInfo::getInfo($user->id);
        
        // Replace "/" and "\" with "-" in filename
        $safeInvoiceNo = str_replace(['/', '\\'], '-', $invoice->no_invoice);
        $filename = 'Invoice_' . $safeInvoiceNo . '_' . date('Y-m-d', strtotime($invoice->tanggal)) . '.pdf';
        
        $pdf = Pdf::loadView('invoice.export-pdf', compact('invoice', 'company'));
        return $pdf->download($filename);
    }

    public function exportExcel(Invoice $invoice)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa export invoice miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $invoice->user_id !== $user->id) {
            return redirect()->route('invoice.index')
                ->with('error', 'Anda tidak memiliki akses untuk export invoice ini');
        }

        $invoice->load('items');
        $company = \App\Models\CompanyInfo::getInfo($user->id);
        
        // Replace "/" and "\" with "-" in filename
        $safeInvoiceNo = str_replace(['/', '\\'], '-', $invoice->no_invoice);
        $filename = 'Invoice_' . $safeInvoiceNo . '_' . date('Y-m-d', strtotime($invoice->tanggal)) . '.xlsx';
        
        // Create Excel content
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $data = [
            ['INVOICE'],
            ['No. Invoice:', $invoice->no_invoice],
            ['Tanggal:', date('d-m-Y', strtotime($invoice->tanggal))],
            ['Kepada:', $invoice->kepada_nama],
            ['Alamat:', $invoice->kepada_alamat ?? ''],
            ['Kota:', $invoice->kepada_kota ?? ''],
            ['Telepon:', $invoice->kepada_telepon ?? ''],
            [],
            ['No', 'Nama Item', 'Deskripsi', 'Qty', 'Satuan', 'Harga', 'Total'],
        ];
        
        foreach ($invoice->items as $index => $item) {
            $data[] = [
                $index + 1,
                $item->nama_item,
                $item->deskripsi ?? '',
                $item->qty,
                $item->satuan ?? '',
                $item->harga,
                $item->total,
            ];
        }
        
        $data[] = [];
        $data[] = ['Subtotal:', '', '', '', '', '', $invoice->subtotal];
        if ($invoice->diskon > 0) {
            $data[] = ['Diskon:', '', '', '', '', '', $invoice->diskon];
        }
        if ($invoice->ppn > 0) {
            $data[] = ['PPN:', '', '', '', '', '', $invoice->ppn];
        }
        if (($invoice->dp ?? 0) > 0) {
            $data[] = ['DP (Uang Muka):', '', '', '', '', '', $invoice->dp];
        }
        $data[] = ['TOTAL:', '', '', '', '', '', $invoice->total];
        if (($invoice->dp ?? 0) > 0) {
            $sisaTagihan = $invoice->total - ($invoice->dp ?? 0);
            $data[] = ['Sisa Tagihan:', '', '', '', '', '', $sisaTagihan];
        }
        
        // Generate CSV (simple Excel alternative)
        $csv = fopen('php://temp', 'r+');
        foreach ($data as $row) {
            fputcsv($csv, $row, ';');
        }
        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);
        
        // Convert CSV to Excel-like format
        $excelContent = str_replace(';', "\t", $csvContent);
        
        return response($excelContent, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . str_replace('.xlsx', '.xls', $filename) . '"',
        ]);
    }

    public function exportPdfV2(Invoice $invoice)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa export invoice miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $invoice->user_id !== $user->id) {
            return redirect()->route('invoice.index')
                ->with('error', 'Anda tidak memiliki akses untuk export invoice ini');
        }

        $invoice->load('items');
        $company = \App\Models\CompanyInfo::getInfo($user->id);
        
        // Replace "/" and "\" with "-" in filename
        $safeInvoiceNo = str_replace(['/', '\\'], '-', $invoice->no_invoice);
        $filename = 'Invoice_V2_' . $safeInvoiceNo . '_' . date('Y-m-d', strtotime($invoice->tanggal)) . '.pdf';
        
        $pdf = Pdf::loadView('invoice.export-pdf-v2', compact('invoice', 'company'));
        return $pdf->download($filename);
    }

    public function exportExcelV2(Invoice $invoice)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa export invoice miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $invoice->user_id !== $user->id) {
            return redirect()->route('invoice.index')
                ->with('error', 'Anda tidak memiliki akses untuk export invoice ini');
        }

        $invoice->load('items');
        $company = \App\Models\CompanyInfo::getInfo($user->id);
        
        // Replace "/" and "\" with "-" in filename
        $safeInvoiceNo = str_replace(['/', '\\'], '-', $invoice->no_invoice);
        $filename = 'Invoice_V2_' . $safeInvoiceNo . '_' . date('Y-m-d', strtotime($invoice->tanggal)) . '.xlsx';
        
        // Create Excel content
        $data = [
            ['INVOICE'],
            ['No. Invoice:', $invoice->no_invoice],
            ['Tanggal:', date('d-m-Y', strtotime($invoice->tanggal))],
            ['Kepada:', $invoice->kepada_nama],
            ['Alamat:', $invoice->kepada_alamat ?? ''],
            ['Kota:', $invoice->kepada_kota ?? ''],
            ['Telepon:', $invoice->kepada_telepon ?? ''],
            [],
            ['No', 'Nama Item', 'Deskripsi', 'Qty', 'Satuan', 'Harga', 'Total'],
        ];
        
        foreach ($invoice->items as $index => $item) {
            $data[] = [
                $index + 1,
                $item->nama_item,
                $item->deskripsi ?? '',
                $item->qty,
                $item->satuan ?? '',
                $item->harga,
                $item->total,
            ];
        }
        
        $data[] = [];
        $data[] = ['Subtotal:', '', '', '', '', '', $invoice->subtotal];
        if ($invoice->diskon > 0) {
            $data[] = ['Diskon:', '', '', '', '', '', $invoice->diskon];
        }
        if ($invoice->ppn > 0) {
            $data[] = ['PPN:', '', '', '', '', '', $invoice->ppn];
        }
        if (($invoice->dp ?? 0) > 0) {
            $data[] = ['DP (Uang Muka):', '', '', '', '', '', $invoice->dp];
        }
        $data[] = ['TOTAL:', '', '', '', '', '', $invoice->total];
        if (($invoice->dp ?? 0) > 0) {
            $sisaTagihan = $invoice->total - ($invoice->dp ?? 0);
            $data[] = ['Sisa Tagihan:', '', '', '', '', '', $sisaTagihan];
        }
        
        // Generate CSV (simple Excel alternative)
        $csv = fopen('php://temp', 'r+');
        foreach ($data as $row) {
            fputcsv($csv, $row, ';');
        }
        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);
        
        // Convert CSV to Excel-like format
        $excelContent = str_replace(';', "\t", $csvContent);
        
        return response($excelContent, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . str_replace('.xlsx', '.xls', $filename) . '"',
        ]);
    }

    public function edit(Invoice $invoice)
    {
        // Pastikan user hanya bisa edit invoice miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $invoice->user_id !== $user->id) {
            return redirect()->route('invoice.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit invoice ini');
        }

        $invoice->load('items');
        return view('invoice.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa update invoice miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $invoice->user_id !== $user->id) {
            return redirect()->route('invoice.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate invoice ini');
        }

        $validated = $request->validate([
            'no_invoice' => 'required|unique:invoices,no_invoice,' . $invoice->id . ',id,user_id,' . $user->id,
            'tanggal' => 'required|date',
            'kepada_nama' => 'required|string|max:255',
            'kepada_alamat' => 'nullable|string',
            'kepada_kota' => 'nullable|string|max:255',
            'kepada_telepon' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'term_condition' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'signature_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:Draft,Sent,Paid,Overdue',
            'subtotal' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'dp' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        $invoice->update($validated);

        // Hapus items lama
        $invoice->items()->delete();

        // Buat items baru
        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'nama_item' => $item['nama_item'],
                'deskripsi' => $item['deskripsi'] ?? null,
                'qty' => $item['qty'],
                'satuan' => $item['satuan'] ?? null,
                'harga' => $item['harga'],
                'total' => $item['total'],
            ]);
        }

        return redirect()->route('invoice.show', $invoice)->with('success', 'Invoice berhasil diupdate.');
    }

    public function destroy(Invoice $invoice)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa hapus invoice miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $invoice->user_id !== $user->id) {
            return redirect()->route('invoice.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus invoice ini');
        }
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dihapus.');
    }
}
