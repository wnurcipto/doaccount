<?php

namespace App\Http\Controllers;

use App\Models\Offering;
use App\Models\OfferingItem;
use Illuminate\Http\Request;

class OfferingController extends Controller
{
    public function index()
    {
        $query = Offering::query();
        $query = $this->scopeUser($query);
        $offerings = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('offering.index', compact('offerings'));
    }

    public function create()
    {
        $user = $this->currentUser();
        $offeringQuery = Offering::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'));
        $offeringQuery = $this->scopeUser($offeringQuery);
        $noOffering = 'OFF/' . date('Y/m') . '/' . str_pad(($offeringQuery->count() + 1), 4, '0', STR_PAD_LEFT);
        
        return view('offering.create', compact('noOffering'));
    }

    public function store(Request $request)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        $validated = $request->validate([
            'no_offering' => 'required|unique:offerings,no_offering,NULL,id,user_id,' . $user->id,
            'tanggal' => 'required|date',
            'kepada_nama' => 'required|string|max:255',
            'kepada_alamat' => 'nullable|string',
            'kepada_kota' => 'nullable|string|max:255',
            'kepada_telepon' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = $user->id;
        $offering = Offering::create($validated);

        foreach ($request->items as $item) {
            OfferingItem::create([
                'offering_id' => $offering->id,
                'nama_item' => $item['nama_item'],
                'deskripsi' => $item['deskripsi'] ?? null,
                'qty' => $item['qty'],
                'satuan' => $item['satuan'] ?? null,
                'harga' => $item['harga'],
                'total' => $item['total'],
            ]);
        }

        return redirect()->route('offering.show', $offering)->with('success', 'Offering berhasil dibuat.');
    }

    public function show(Offering $offering)
    {
        $user = $this->currentUser();
        if (!$user->is_owner && $offering->user_id !== $user->id) {
            return redirect()->route('offering.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat offering ini');
        }

        $offering->load('items');
        return view('offering.show', compact('offering'));
    }

    public function edit(Offering $offering)
    {
        $user = $this->currentUser();
        if (!$user->is_owner && $offering->user_id !== $user->id) {
            return redirect()->route('offering.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit offering ini');
        }

        $offering->load('items');
        return view('offering.edit', compact('offering'));
    }

    public function update(Request $request, Offering $offering)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        if (!$user->is_owner && $offering->user_id !== $user->id) {
            return redirect()->route('offering.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate offering ini');
        }

        $validated = $request->validate([
            'no_offering' => 'required|unique:offerings,no_offering,' . $offering->id . ',id,user_id,' . $user->id,
            'tanggal' => 'required|date',
            'kepada_nama' => 'required|string|max:255',
            'kepada_alamat' => 'nullable|string',
            'kepada_kota' => 'nullable|string|max:255',
            'kepada_telepon' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        $offering->update($validated);

        $offering->items()->delete();

        foreach ($request->items as $item) {
            OfferingItem::create([
                'offering_id' => $offering->id,
                'nama_item' => $item['nama_item'],
                'deskripsi' => $item['deskripsi'] ?? null,
                'qty' => $item['qty'],
                'satuan' => $item['satuan'] ?? null,
                'harga' => $item['harga'],
                'total' => $item['total'],
            ]);
        }

        return redirect()->route('offering.show', $offering)->with('success', 'Offering berhasil diupdate.');
    }

    public function destroy(Offering $offering)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        if (!$user->is_owner && $offering->user_id !== $user->id) {
            return redirect()->route('offering.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus offering ini');
        }

        $offering->items()->delete();
        $offering->delete();

        return redirect()->route('offering.index')->with('success', 'Offering berhasil dihapus.');
    }
}
