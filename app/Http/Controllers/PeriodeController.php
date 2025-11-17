<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Periode::query();
        $query = $this->scopeUser($query);
        $query = $this->scopeFreeAccount($query); // Free account hanya tahun 2024
        $periodes = $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        return view('periode.index', compact('periodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('periode.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $validated = $request->validate([
            'tahun' => 'required|integer|min:2000|max:2100',
            'bulan' => 'required|integer|min:1|max:12',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:Open,Closed'
        ]);

        // Set user_id untuk periode baru
        $user = $this->currentUser();
        $validated['user_id'] = $user->id;

        // Cek apakah periode dengan tahun dan bulan yang sama sudah ada (untuk user yang sama)
        $query = Periode::where('tahun', $validated['tahun'])
            ->where('bulan', $validated['bulan']);
        $query = $this->scopeUser($query);
        $exists = $query->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['periode' => 'Periode untuk tahun dan bulan tersebut sudah ada']);
        }

        Periode::create($validated);

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Periode $periode)
    {
        return view('periode.show', compact('periode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Periode $periode)
    {
        return view('periode.edit', compact('periode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Periode $periode)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $validated = $request->validate([
            'tahun' => 'required|integer|min:2000|max:2100',
            'bulan' => 'required|integer|min:1|max:12',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:Open,Closed'
        ]);

        // Pastikan user hanya bisa edit periode miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $periode->user_id !== $user->id) {
            return redirect()->route('periode.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit periode ini');
        }

        // Cek apakah periode dengan tahun dan bulan yang sama sudah ada (kecuali periode ini sendiri)
        $query = Periode::where('tahun', $validated['tahun'])
            ->where('bulan', $validated['bulan'])
            ->where('id', '!=', $periode->id);
        $query = $this->scopeUser($query);
        $exists = $query->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['periode' => 'Periode untuk tahun dan bulan tersebut sudah ada']);
        }

        $periode->update($validated);

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Periode $periode)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa hapus periode miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $periode->user_id !== $user->id) {
            return redirect()->route('periode.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus periode ini');
        }

        // Cek apakah periode ini sudah digunakan dalam transaksi
        if ($periode->jurnalHeaders()->count() > 0) {
            return redirect()->route('periode.index')
                ->with('error', 'Periode tidak dapat dihapus karena sudah digunakan dalam transaksi');
        }

        $periode->delete();

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil dihapus');
    }

    /**
     * Close periode
     */
    public function close(Periode $periode)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa close periode miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $periode->user_id !== $user->id) {
            return redirect()->route('periode.index')
                ->with('error', 'Anda tidak memiliki akses untuk menutup periode ini');
        }

        if ($periode->status == 'Closed') {
            return redirect()->back()
                ->with('error', 'Periode sudah dalam status Closed');
        }

        $periode->update(['status' => 'Closed']);

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil ditutup');
    }

    /**
     * Reopen periode
     */
    public function reopen(Periode $periode)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa reopen periode miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $periode->user_id !== $user->id) {
            return redirect()->route('periode.index')
                ->with('error', 'Anda tidak memiliki akses untuk membuka kembali periode ini');
        }

        if ($periode->status == 'Open') {
            return redirect()->back()
                ->with('error', 'Periode sudah dalam status Open');
        }

        $periode->update(['status' => 'Open']);

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil dibuka kembali');
    }
}
