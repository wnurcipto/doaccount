<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
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
        
        // Query customers
        $query = Customer::query();
        
        // Owner bisa melihat semua customer, non-owner hanya customer miliknya
        if (!$user->is_owner) {
            $query = $query->where('user_id', $user->id);
        }
        
        // Filter search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_customer', 'like', '%' . $search . '%')
                  ->orWhere('kode_customer', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('telepon', 'like', '%' . $search . '%');
            });
        }
        
        // Filter aktif/non-aktif
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status == '1');
        }
        
        $customers = $query->orderBy('nama_customer')->paginate(20);
        
        // Hitung saldo piutang untuk setiap customer
        foreach ($customers as $customer) {
            $customer->saldo_piutang = $customer->saldo_piutang;
        }
        
        return view('customer.index', compact('customers'));
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

        return view('customer.create');
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
            'kode_customer' => 'nullable|string|max:50',
            'nama_customer' => 'required|string|max:255',
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
        
        // Generate kode customer otomatis jika tidak diisi
        if (empty($validated['kode_customer'])) {
            $lastCustomer = Customer::where('user_id', $user->id)
                ->whereNotNull('kode_customer')
                ->orderBy('id', 'desc')
                ->first();
            
            if ($lastCustomer && preg_match('/CUST(\d+)/', $lastCustomer->kode_customer, $matches)) {
                $newNumber = intval($matches[1]) + 1;
            } else {
                $newNumber = 1;
            }
            
            $validated['kode_customer'] = 'CUST' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        Customer::create($validated);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $user = $this->currentUser();
        
        // Owner bisa melihat semua customer, non-owner hanya customer miliknya
        if (!$user->is_owner && $customer->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }
        
        // Hitung saldo piutang
        $customer->saldo_piutang = $customer->saldo_piutang;
        
        // Ambil transaksi piutang
        $transaksiPiutang = $customer->getTransaksiPiutang();
        
        return view('customer.show', compact('customer', 'transaksiPiutang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        // Block free account dari edit
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        
        // Owner bisa edit semua customer, non-owner hanya customer miliknya
        if (!$user->is_owner && $customer->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // Block free account dari update
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        
        // Owner bisa update semua customer, non-owner hanya customer miliknya
        if (!$user->is_owner && $customer->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'kode_customer' => 'nullable|string|max:50',
            'nama_customer' => 'required|string|max:255',
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

        $customer->update($validated);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // Block free account dari delete
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        
        // Owner bisa delete semua customer, non-owner hanya customer miliknya
        if (!$user->is_owner && $customer->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        // Cek apakah customer sudah digunakan dalam transaksi
        if ($customer->jurnalDetails()->count() > 0) {
            return redirect()->route('customer.index')
                ->with('error', 'Customer tidak dapat dihapus karena sudah digunakan dalam transaksi');
        }

        $customer->delete();

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil dihapus');
    }
}
