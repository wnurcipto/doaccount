<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $query = Barang::query();
        $query = $this->scopeUser($query);
        $barangs = $query->orderBy('kode_barang')->paginate(20);
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:20|unique:barangs,kode_barang,NULL,id,user_id,' . $user->id,
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'nullable|string|max:50',
            'satuan' => 'required|string|max:20',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok_minimal' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['user_id'] = $this->currentUser()->id;
        
        Barang::create($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function show(Barang $barang)
    {
        // Pastikan user hanya bisa lihat barang miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $barang->user_id !== $user->id) {
            return redirect()->route('barang.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat barang ini');
        }

        $stokMasuks = $barang->stokMasuks()->latest()->take(10)->get();
        $stokKeluars = $barang->stokKeluars()->latest()->take(10)->get();
        
        return view('barang.show', compact('barang', 'stokMasuks', 'stokKeluars'));
    }

    public function edit(Barang $barang)
    {
        // Pastikan user hanya bisa edit barang miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $barang->user_id !== $user->id) {
            return redirect()->route('barang.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit barang ini');
        }

        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa update barang miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $barang->user_id !== $user->id) {
            return redirect()->route('barang.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate barang ini');
        }

        $validated = $request->validate([
            'kode_barang' => 'required|string|max:20|unique:barangs,kode_barang,' . $barang->id . ',id,user_id,' . $user->id,
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'nullable|string|max:50',
            'satuan' => 'required|string|max:20',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok_minimal' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate');
    }

    public function destroy(Barang $barang)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa hapus barang miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $barang->user_id !== $user->id) {
            return redirect()->route('barang.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus barang ini');
        }

        // Cek apakah barang sudah digunakan di transaksi
        if ($barang->stokMasuks()->count() > 0 || $barang->stokKeluars()->count() > 0) {
            return redirect()->route('barang.index')->with('error', 'Barang tidak dapat dihapus karena sudah ada transaksi');
        }

        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
