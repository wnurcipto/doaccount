<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coas = Coa::orderBy('kode_akun')->get();
        return view('coa.index', compact('coas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Coa::where('level', '<', 3)->orderBy('kode_akun')->get();
        return view('coa.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_akun' => 'required|unique:coas|max:20',
            'nama_akun' => 'required|max:100',
            'tipe_akun' => 'required|in:Aset,Liabilitas,Ekuitas,Pendapatan,Beban',
            'posisi_normal' => 'required|in:Debit,Kredit',
            'parent_id' => 'nullable|exists:coas,kode_akun',
            'level' => 'required|integer|min:1|max:5',
            'is_active' => 'boolean',
            'deskripsi' => 'nullable|string'
        ]);

        Coa::create($validated);

        return redirect()->route('coa.index')
            ->with('success', 'Chart of Account berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coa $coa)
    {
        return view('coa.show', compact('coa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coa $coa)
    {
        $parents = Coa::where('level', '<', 3)
            ->where('kode_akun', '!=', $coa->kode_akun)
            ->orderBy('kode_akun')
            ->get();
        return view('coa.edit', compact('coa', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coa $coa)
    {
        $validated = $request->validate([
            'kode_akun' => 'required|max:20|unique:coas,kode_akun,' . $coa->id,
            'nama_akun' => 'required|max:100',
            'tipe_akun' => 'required|in:Aset,Liabilitas,Ekuitas,Pendapatan,Beban',
            'posisi_normal' => 'required|in:Debit,Kredit',
            'parent_id' => 'nullable|exists:coas,kode_akun',
            'level' => 'required|integer|min:1|max:5',
            'is_active' => 'boolean',
            'deskripsi' => 'nullable|string'
        ]);

        $coa->update($validated);

        return redirect()->route('coa.index')
            ->with('success', 'Chart of Account berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coa $coa)
    {
        // Cek apakah COA ini sudah digunakan dalam transaksi
        if ($coa->jurnalDetails()->count() > 0) {
            return redirect()->route('coa.index')
                ->with('error', 'COA tidak dapat dihapus karena sudah digunakan dalam transaksi');
        }

        $coa->delete();

        return redirect()->route('coa.index')
            ->with('success', 'Chart of Account berhasil dihapus');
    }
}
