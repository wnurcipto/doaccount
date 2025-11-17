<?php

namespace App\Http\Controllers;

use App\Models\StokKeluar;
use App\Models\Barang;
use App\Models\Periode;
use App\Models\Coa;
use App\Models\JurnalHeader;
use App\Models\JurnalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StokKeluarController extends Controller
{
    public function index()
    {
        $query = StokKeluar::with(['barang', 'periode', 'jurnalHeader']);
        $query = $this->scopeUser($query);
        
        // Filter
        if (request('periode_id')) {
            $query->where('periode_id', request('periode_id'));
        }
        if (request('barang_id')) {
            $query->where('barang_id', request('barang_id'));
        }
        if (request('customer')) {
            $query->where('customer', 'like', '%' . request('customer') . '%');
        }
        
        $stokKeluars = $query->latest('tanggal_keluar')->paginate(20);
        
        // Data untuk filter (milik user)
        $periodeQuery = Periode::query();
        $periodeQuery = $this->scopeUser($periodeQuery);
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        $barangQuery = Barang::active();
        $barangQuery = $this->scopeUser($barangQuery);
        $barangs = $barangQuery->orderBy('kode_barang')->get();
        
        return view('stok-keluar.index', compact('stokKeluars', 'periodes', 'barangs'));
    }

    public function create()
    {
        $barangQuery = Barang::active();
        $barangQuery = $this->scopeUser($barangQuery);
        $barangs = $barangQuery->orderBy('nama_barang')->get();
        
        $periodeQuery = Periode::query();
        $periodeQuery = $this->scopeUser($periodeQuery);
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        $periodeAktifQuery = Periode::where('status', 'Open');
        $periodeAktifQuery = $this->scopeUser($periodeAktifQuery);
        $periodeAktif = $periodeAktifQuery->first();
        
        // Generate nomor bukti otomatis (hanya dari stok keluar user)
        $stokKeluarQuery = StokKeluar::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'));
        $stokKeluarQuery = $this->scopeUser($stokKeluarQuery);
        $lastNo = $stokKeluarQuery->count();
        $noBukti = 'SK-' . date('Ym') . '-' . str_pad($lastNo + 1, 4, '0', STR_PAD_LEFT);
        
        return view('stok-keluar.create', compact('barangs', 'periodes', 'noBukti', 'periodeAktif'));
    }

    public function store(Request $request)
    {
        $user = $this->currentUser();
        $validated = $request->validate([
            'no_bukti' => 'required|string|max:30|unique:stok_keluars,no_bukti,NULL,id,user_id,' . $user->id,
            'tanggal_keluar' => 'required|date',
            'barang_id' => 'required|exists:barangs,id',
            'periode_id' => 'required|exists:periodes,id',
            'customer' => 'nullable|string|max:100',
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'metode_terima' => 'required|in:tunai,kredit'
        ]);

        $periode = Periode::findOrFail($validated['periode_id']);
        if ($periode->status !== 'Open') {
            return back()->with('error', 'Periode sudah ditutup')->withInput();
        }

        // Cek stok tersedia
        $barang = Barang::findOrFail($validated['barang_id']);
        if ($barang->stok < $validated['qty']) {
            return back()->with('error', "Stok tidak cukup! Stok tersedia: {$barang->stok}")->withInput();
        }

        DB::beginTransaction();
        try {
            $subtotal = $validated['qty'] * $validated['harga'];
            
            // Simpan stok keluar
            $stokKeluar = StokKeluar::create([
                'no_bukti' => $validated['no_bukti'],
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'barang_id' => $validated['barang_id'],
                'periode_id' => $validated['periode_id'],
                'customer' => $validated['customer'],
                'qty' => $validated['qty'],
                'harga' => $validated['harga'],
                'subtotal' => $subtotal,
                'keterangan' => $validated['keterangan'],
                'user_id' => Auth::id()
            ]);

            // JURNAL 1: PENJUALAN
            // Kas/Piutang (D) xxx
            //     Penjualan (K) xxx
            
            $deskripsiJual = "Penjualan {$barang->nama_barang} - {$validated['no_bukti']}";
            
            $coaPenjualan = Coa::where('kode_akun', '4-1002')->first();
            
            if ($validated['metode_terima'] === 'tunai') {
                $coaPenerimaan = Coa::where('kode_akun', '1-1001')->first(); // Kas
            } else {
                $coaPenerimaan = Coa::where('kode_akun', '1-1003')->first(); // Piutang
            }

            if (!$coaPenjualan || !$coaPenerimaan) {
                throw new \Exception('COA tidak ditemukan');
            }

            // Buat Jurnal Penjualan
            $jurnalJual = JurnalHeader::create([
                'no_bukti' => 'JU-' . $validated['no_bukti'] . '-JUAL',
                'tanggal_transaksi' => $validated['tanggal_keluar'],
                'periode_id' => $validated['periode_id'],
                'deskripsi' => $deskripsiJual,
                'total_debit' => $subtotal,
                'total_kredit' => $subtotal,
                'status' => 'Posted',
                'user_id' => Auth::id()
            ]);

            JurnalDetail::create([
                'jurnal_header_id' => $jurnalJual->id,
                'coa_id' => $coaPenerimaan->id,
                'posisi' => 'Debit',
                'jumlah' => $subtotal,
                'keterangan' => $deskripsiJual
            ]);

            JurnalDetail::create([
                'jurnal_header_id' => $jurnalJual->id,
                'coa_id' => $coaPenjualan->id,
                'posisi' => 'Kredit',
                'jumlah' => $subtotal,
                'keterangan' => $deskripsiJual
            ]);

            // JURNAL 2: HPP (Harga Pokok Penjualan)
            // HPP (D) xxx
            //     Persediaan (K) xxx
            
            $hpp = $validated['qty'] * $barang->harga_beli;
            $deskripsiHPP = "HPP Penjualan {$barang->nama_barang} - {$validated['no_bukti']}";
            
            $coaHPP = Coa::where('kode_akun', '5-1001')->first();
            $coaPersediaan = Coa::where('kode_akun', '1-1004')->first();

            if (!$coaHPP || !$coaPersediaan) {
                throw new \Exception('COA HPP/Persediaan tidak ditemukan');
            }

            // Buat Jurnal HPP
            $jurnalHPP = JurnalHeader::create([
                'no_bukti' => 'JU-' . $validated['no_bukti'] . '-HPP',
                'tanggal_transaksi' => $validated['tanggal_keluar'],
                'periode_id' => $validated['periode_id'],
                'deskripsi' => $deskripsiHPP,
                'total_debit' => $hpp,
                'total_kredit' => $hpp,
                'status' => 'Posted',
                'user_id' => Auth::id()
            ]);

            JurnalDetail::create([
                'jurnal_header_id' => $jurnalHPP->id,
                'coa_id' => $coaHPP->id,
                'posisi' => 'Debit',
                'jumlah' => $hpp,
                'keterangan' => $deskripsiHPP
            ]);

            JurnalDetail::create([
                'jurnal_header_id' => $jurnalHPP->id,
                'coa_id' => $coaPersediaan->id,
                'posisi' => 'Kredit',
                'jumlah' => $hpp,
                'keterangan' => $deskripsiHPP
            ]);

            // Link jurnal ke stok keluar (simpan ID jurnal penjualan)
            $stokKeluar->update(['jurnal_header_id' => $jurnalJual->id]);

            DB::commit();
            return redirect()->route('stok-keluar.index')->with('success', 'Stok keluar berhasil ditambahkan. 2 Jurnal otomatis telah dibuat (Penjualan + HPP)');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(StokKeluar $stokKeluar)
    {
        $user = $this->currentUser();
        if (!$user->is_owner && $stokKeluar->user_id !== $user->id) {
            return redirect()->route('stok-keluar.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat stok keluar ini');
        }

        $stokKeluar->load(['barang', 'periode', 'user', 'jurnalHeader.details.coa']);
        
        // Cari jurnal HPP juga (hanya dari jurnal user)
        $jurnalHPPQuery = JurnalHeader::where('no_bukti', 'JU-' . $stokKeluar->no_bukti . '-HPP');
        $jurnalHPPQuery = $this->scopeUser($jurnalHPPQuery);
        $jurnalHPP = $jurnalHPPQuery->first();
        
        return view('stok-keluar.show', compact('stokKeluar', 'jurnalHPP'));
    }

    public function edit(StokKeluar $stokKeluar)
    {
        $user = $this->currentUser();
        if (!$user->is_owner && $stokKeluar->user_id !== $user->id) {
            return redirect()->route('stok-keluar.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit stok keluar ini');
        }

        if ($stokKeluar->jurnal_header_id) {
            return redirect()->route('stok-keluar.show', $stokKeluar)
                ->with('error', 'Stok keluar yang sudah dijurnal tidak dapat diubah');
        }

        $barangQuery = Barang::active();
        $barangQuery = $this->scopeUser($barangQuery);
        $barangs = $barangQuery->orderBy('nama_barang')->get();
        
        $periodeQuery = Periode::open();
        $periodeQuery = $this->scopeUser($periodeQuery);
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        return view('stok-keluar.edit', compact('stokKeluar', 'barangs', 'periodes'));
    }

    public function update(Request $request, StokKeluar $stokKeluar)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        if (!$user->is_owner && $stokKeluar->user_id !== $user->id) {
            return redirect()->route('stok-keluar.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate stok keluar ini');
        }

        if ($stokKeluar->jurnal_header_id) {
            return redirect()->route('stok-keluar.show', $stokKeluar)
                ->with('error', 'Stok keluar yang sudah dijurnal tidak dapat diubah');
        }

        $validated = $request->validate([
            'tanggal_keluar' => 'required|date',
            'customer' => 'nullable|string|max:100',
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $subtotal = $validated['qty'] * $validated['harga'];
        $validated['subtotal'] = $subtotal;
        
        $stokKeluar->update($validated);

        return redirect()->route('stok-keluar.index')->with('success', 'Stok keluar berhasil diupdate');
    }

    public function destroy(StokKeluar $stokKeluar)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        if ($stokKeluar->jurnal_header_id) {
            return redirect()->route('stok-keluar.index')
                ->with('error', 'Stok keluar yang sudah dijurnal tidak dapat dihapus');
        }

        $stokKeluar->delete();
        return redirect()->route('stok-keluar.index')->with('success', 'Stok keluar berhasil dihapus');
    }
}
