<?php

namespace App\Http\Controllers;

use App\Models\SuratJalan;
use App\Models\SuratJalanItem;
use Illuminate\Http\Request;

class SuratJalanController extends Controller
{
    public function index()
    {
        $query = SuratJalan::query();
        $query = $this->scopeUser($query);
        $suratJalans = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('surat-jalan.index', compact('suratJalans'));
    }

    public function create()
    {
        $user = $this->currentUser();
        $suratJalanQuery = SuratJalan::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'));
        $suratJalanQuery = $this->scopeUser($suratJalanQuery);
        $noSuratJalan = 'SJ/' . date('Y/m') . '/' . str_pad(($suratJalanQuery->count() + 1), 4, '0', STR_PAD_LEFT);
        
        return view('surat-jalan.create', compact('noSuratJalan'));
    }

    public function store(Request $request)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        $validated = $request->validate([
            'no_surat_jalan' => 'required|unique:surat_jalans,no_surat_jalan,NULL,id,user_id,' . $user->id,
            'tanggal' => 'required|date',
            'dari_nama' => 'required|string|max:255',
            'dari_alamat' => 'nullable|string',
            'dari_kota' => 'nullable|string|max:255',
            'dari_telepon' => 'nullable|string|max:255',
            'kepada_nama' => 'required|string|max:255',
            'kepada_alamat' => 'nullable|string',
            'kepada_kota' => 'nullable|string|max:255',
            'kepada_telepon' => 'nullable|string|max:255',
            'no_kendaraan' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'nullable|string|max:50',
        ]);

        $validated['user_id'] = $user->id;
        $suratJalan = SuratJalan::create($validated);

        foreach ($request->items as $item) {
            SuratJalanItem::create([
                'surat_jalan_id' => $suratJalan->id,
                'nama_item' => $item['nama_item'],
                'deskripsi' => $item['deskripsi'] ?? null,
                'qty' => $item['qty'],
                'satuan' => $item['satuan'] ?? null,
            ]);
        }

        return redirect()->route('surat-jalan.show', $suratJalan)->with('success', 'Surat Jalan berhasil dibuat.');
    }

    public function show(SuratJalan $suratJalan)
    {
        $user = $this->currentUser();
        if (!$user->is_owner && $suratJalan->user_id !== $user->id) {
            return redirect()->route('surat-jalan.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat surat jalan ini');
        }

        $suratJalan->load('items');
        return view('surat-jalan.show', compact('suratJalan'));
    }

    public function edit(SuratJalan $suratJalan)
    {
        $user = $this->currentUser();
        if (!$user->is_owner && $suratJalan->user_id !== $user->id) {
            return redirect()->route('surat-jalan.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit surat jalan ini');
        }

        $suratJalan->load('items');
        return view('surat-jalan.edit', compact('suratJalan'));
    }

    public function update(Request $request, SuratJalan $suratJalan)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        if (!$user->is_owner && $suratJalan->user_id !== $user->id) {
            return redirect()->route('surat-jalan.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate surat jalan ini');
        }

        $validated = $request->validate([
            'no_surat_jalan' => 'required|unique:surat_jalans,no_surat_jalan,' . $suratJalan->id . ',id,user_id,' . $user->id,
            'tanggal' => 'required|date',
            'dari_nama' => 'required|string|max:255',
            'dari_alamat' => 'nullable|string',
            'dari_kota' => 'nullable|string|max:255',
            'dari_telepon' => 'nullable|string|max:255',
            'kepada_nama' => 'required|string|max:255',
            'kepada_alamat' => 'nullable|string',
            'kepada_kota' => 'nullable|string|max:255',
            'kepada_telepon' => 'nullable|string|max:255',
            'no_kendaraan' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'nullable|string|max:50',
        ]);

        $suratJalan->update($validated);

        $suratJalan->items()->delete();

        foreach ($request->items as $item) {
            SuratJalanItem::create([
                'surat_jalan_id' => $suratJalan->id,
                'nama_item' => $item['nama_item'],
                'deskripsi' => $item['deskripsi'] ?? null,
                'qty' => $item['qty'],
                'satuan' => $item['satuan'] ?? null,
            ]);
        }

        return redirect()->route('surat-jalan.show', $suratJalan)->with('success', 'Surat Jalan berhasil diupdate.');
    }

    public function destroy(SuratJalan $suratJalan)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        if (!$user->is_owner && $suratJalan->user_id !== $user->id) {
            return redirect()->route('surat-jalan.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus surat jalan ini');
        }

        $suratJalan->items()->delete();
        $suratJalan->delete();

        return redirect()->route('surat-jalan.index')->with('success', 'Surat Jalan berhasil dihapus.');
    }
}
