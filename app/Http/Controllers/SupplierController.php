<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Block free account dari create/edit/delete, tapi tetap bisa view
        if ($this->isFreeAccount()) {
            // Tetap bisa view list, tapi akan di-block di create/edit/delete
        }

        $user = $this->currentUser();
        
        // Query suppliers
        $query = Supplier::query();
        
        // Owner bisa melihat semua supplier, non-owner hanya supplier miliknya
        if (!$user->is_owner) {
            $query = $query->where('user_id', $user->id);
        }
        
        // Filter search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_supplier', 'like', '%' . $search . '%')
                  ->orWhere('kode_supplier', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('telepon', 'like', '%' . $search . '%');
            });
        }
        
        // Filter aktif/non-aktif
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status == '1');
        }
        
        $suppliers = $query->orderBy('nama_supplier')->paginate(20);
        
        // Hitung saldo hutang untuk setiap supplier
        foreach ($suppliers as $supplier) {
            $supplier->saldo_hutang = $supplier->saldo_hutang;
        }
        
        return view('supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Block free account dari create
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Block free account dari create
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $validated = $request->validate([
            'kode_supplier' => 'nullable|string|max:50',
            'nama_supplier' => 'required|string|max:255',
            'nama_kontak' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $user = $this->currentUser();
        $validated['user_id'] = $user->id;
        
        // Generate kode supplier otomatis jika tidak diisi
        if (empty($validated['kode_supplier'])) {
            $lastSupplier = Supplier::where('user_id', $user->id)
                ->whereNotNull('kode_supplier')
                ->orderBy('id', 'desc')
                ->first();
            
            if ($lastSupplier && preg_match('/SUPP(\d+)/', $lastSupplier->kode_supplier, $matches)) {
                $newNumber = intval($matches[1]) + 1;
            } else {
                $newNumber = 1;
            }
            
            $validated['kode_supplier'] = 'SUPP' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        Supplier::create($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $user = $this->currentUser();
        
        // Owner bisa melihat semua supplier, non-owner hanya supplier miliknya
        if (!$user->is_owner && $supplier->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }
        
        // Hitung saldo hutang
        $supplier->saldo_hutang = $supplier->saldo_hutang;
        
        // Ambil transaksi hutang
        $transaksiHutang = $supplier->getTransaksiHutang();
        
        return view('supplier.show', compact('supplier', 'transaksiHutang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        // Block free account dari edit
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        
        // Owner bisa edit semua supplier, non-owner hanya supplier miliknya
        if (!$user->is_owner && $supplier->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        // Block free account dari update
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        
        // Owner bisa update semua supplier, non-owner hanya supplier miliknya
        if (!$user->is_owner && $supplier->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'kode_supplier' => 'nullable|string|max:50',
            'nama_supplier' => 'required|string|max:255',
            'nama_kontak' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $supplier->update($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        // Block free account dari delete
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        
        // Owner bisa delete semua supplier, non-owner hanya supplier miliknya
        if (!$user->is_owner && $supplier->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        // Cek apakah supplier sudah digunakan dalam transaksi
        if ($supplier->jurnalDetails()->count() > 0) {
            return redirect()->route('supplier.index')
                ->with('error', 'Supplier tidak dapat dihapus karena sudah digunakan dalam transaksi');
        }

        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus');
    }
}
