<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\Barang;
use App\Models\Periode;
use App\Models\Coa;
use App\Models\JurnalHeader;
use App\Models\JurnalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StokMasukController extends Controller
{
    public function index()
    {
        $query = StokMasuk::with(['barang', 'periode', 'jurnalHeader']);
        $query = $this->scopeUser($query);
        
        // Filter
        if (request('periode_id')) {
            $query->where('periode_id', request('periode_id'));
        }
        if (request('barang_id')) {
            $query->where('barang_id', request('barang_id'));
        }
        if (request('supplier')) {
            $query->where('supplier', 'like', '%' . request('supplier') . '%');
        }
        
        $stokMasuks = $query->latest('tanggal_masuk')->paginate(20);
        
        // Data untuk filter (milik user)
        $periodeQuery = Periode::query();
        $periodeQuery = $this->scopeUser($periodeQuery);
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        $barangQuery = Barang::active();
        $barangQuery = $this->scopeUser($barangQuery);
        $barangs = $barangQuery->orderBy('kode_barang')->get();
        
        return view('stok-masuk.index', compact('stokMasuks', 'periodes', 'barangs'));
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
        
        // Generate nomor bukti otomatis (hanya dari stok masuk user)
        $stokMasukQuery = StokMasuk::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'));
        $stokMasukQuery = $this->scopeUser($stokMasukQuery);
        $lastNo = $stokMasukQuery->count();
        $noBukti = 'SM-' . date('Ym') . '-' . str_pad($lastNo + 1, 4, '0', STR_PAD_LEFT);
        
        return view('stok-masuk.create', compact('barangs', 'periodes', 'noBukti', 'periodeAktif'));
    }

    public function store(Request $request)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        $validated = $request->validate([
            'no_bukti' => 'required|string|max:30|unique:stok_masuks,no_bukti,NULL,id,user_id,' . $user->id,
            'tanggal_masuk' => 'required|date',
            'barang_id' => 'required|exists:barangs,id',
            'periode_id' => 'required|exists:periodes,id',
            'supplier' => 'nullable|string|max:100',
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'metode_bayar' => 'required|in:tunai,kredit'
        ]);

        $periode = Periode::findOrFail($validated['periode_id']);
        if ($periode->status !== 'Open') {
            return back()->with('error', 'Periode sudah ditutup')->withInput();
        }

        DB::beginTransaction();
        try {
            $subtotal = $validated['qty'] * $validated['harga'];
            
            $stokMasuk = StokMasuk::create([
                'no_bukti' => $validated['no_bukti'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'barang_id' => $validated['barang_id'],
                'periode_id' => $validated['periode_id'],
                'supplier' => $validated['supplier'],
                'qty' => $validated['qty'],
                'harga' => $validated['harga'],
                'subtotal' => $subtotal,
                'metode_bayar' => $validated['metode_bayar'],
                'keterangan' => $validated['keterangan'],
                'user_id' => auth()->id()
            ]);

            // Auto-create jurnal
            $barang = Barang::find($validated['barang_id']);
            
            // Tentukan COA berdasarkan metode bayar
            if ($validated['metode_bayar'] === 'tunai') {
                $coaKredit = Coa::where('kode_akun', '1-1001')->first(); // Kas
            } else {
                $coaKredit = Coa::where('kode_akun', '2-1001')->first(); // Utang Usaha
            }
            
            $coaPersediaan = Coa::where('kode_akun', '1-1004')->first(); // Persediaan Barang
            
            if (!$coaPersediaan || !$coaKredit) {
                DB::rollBack();
                return back()->with('error', 'COA tidak ditemukan. Pastikan COA sudah di-seed')->withInput();
            }

            // Buat jurnal header
            $jurnalHeader = JurnalHeader::create([
                'no_bukti' => 'JU-SM-' . $validated['no_bukti'],
                'tanggal_transaksi' => $validated['tanggal_masuk'],
                'periode_id' => $validated['periode_id'],
                'deskripsi' => "Pembelian barang: {$barang->nama_barang} - {$validated['supplier']}",
                'total_debit' => $subtotal,
                'total_kredit' => $subtotal,
                'status' => 'Posted',
                'user_id' => auth()->id()
            ]);

            // Detail Debit: Persediaan
            JurnalDetail::create([
                'jurnal_header_id' => $jurnalHeader->id,
                'coa_id' => $coaPersediaan->id,
                'posisi' => 'Debit',
                'jumlah' => $subtotal,
                'keterangan' => 'Pembelian persediaan'
            ]);

            // Detail Kredit: Kas/Utang
            JurnalDetail::create([
                'jurnal_header_id' => $jurnalHeader->id,
                'coa_id' => $coaKredit->id,
                'posisi' => 'Kredit',
                'jumlah' => $subtotal,
                'keterangan' => $validated['metode_bayar'] === 'tunai' ? 'Pembayaran tunai' : 'Pembelian kredit'
            ]);

            // Link jurnal ke stok masuk
            $stokMasuk->update(['jurnal_header_id' => $jurnalHeader->id]);

            DB::commit();
            return redirect()->route('stok-masuk.index')->with('success', 'Stok masuk berhasil ditambahkan dan dijurnal');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    public function show(StokMasuk $stokMasuk)
    {
        $user = $this->currentUser();
        if (!$user->is_owner && $stokMasuk->user_id !== $user->id) {
            return redirect()->route('stok-masuk.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat stok masuk ini');
        }

        $stokMasuk->load(['barang', 'periode', 'user', 'jurnalHeader.details.coa']);
        return view('stok-masuk.show', compact('stokMasuk'));
    }

    public function edit(StokMasuk $stokMasuk)
    {
        $user = $this->currentUser();
        if (!$user->is_owner && $stokMasuk->user_id !== $user->id) {
            return redirect()->route('stok-masuk.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit stok masuk ini');
        }

        // Tidak bisa edit jika sudah ada jurnal
        if ($stokMasuk->jurnal_header_id) {
            return redirect()->route('stok-masuk.show', $stokMasuk)
                ->with('error', 'Stok masuk yang sudah dijurnal tidak dapat diubah');
        }

        $barangQuery = Barang::active();
        $barangQuery = $this->scopeUser($barangQuery);
        $barangs = $barangQuery->orderBy('nama_barang')->get();
        
        $periodeQuery = Periode::open();
        $periodeQuery = $this->scopeUser($periodeQuery);
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        return view('stok-masuk.edit', compact('stokMasuk', 'barangs', 'periodes'));
    }

    public function update(Request $request, StokMasuk $stokMasuk)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $user = $this->currentUser();
        if (!$user->is_owner && $stokMasuk->user_id !== $user->id) {
            return redirect()->route('stok-masuk.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate stok masuk ini');
        }

        // Tidak bisa edit jika sudah ada jurnal
        if ($stokMasuk->jurnal_header_id) {
            return redirect()->route('stok-masuk.show', $stokMasuk)
                ->with('error', 'Stok masuk yang sudah dijurnal tidak dapat diubah');
        }

        $validated = $request->validate([
            'tanggal_masuk' => 'required|date',
            'supplier' => 'nullable|string|max:100',
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $subtotal = $validated['qty'] * $validated['harga'];
        $validated['subtotal'] = $subtotal;
        
        $stokMasuk->update($validated);

        return redirect()->route('stok-masuk.index')->with('success', 'Stok masuk berhasil diupdate');
    }

    public function destroy(StokMasuk $stokMasuk)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Tidak bisa hapus jika sudah ada jurnal
        if ($stokMasuk->jurnal_header_id) {
            return redirect()->route('stok-masuk.index')
                ->with('error', 'Stok masuk yang sudah dijurnal tidak dapat dihapus');
        }

        $stokMasuk->delete();
        return redirect()->route('stok-masuk.index')->with('success', 'Stok masuk berhasil dihapus');
    }
}
