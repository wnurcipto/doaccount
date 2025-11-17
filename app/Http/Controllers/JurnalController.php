<?php

namespace App\Http\Controllers;

use App\Models\JurnalHeader;
use App\Models\JurnalDetail;
use App\Models\Coa;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Jika ada parameter 'clear_filter', hapus session filter
        if ($request->has('clear_filter')) {
            session()->forget('jurnal_filter');
        }
        
        // Ambil filter dari request atau session
        $filters = [
            'periode_id' => $request->input('periode_id') ?? session('jurnal_filter.periode_id'),
            'status' => $request->input('status') ?? session('jurnal_filter.status'),
            'tanggal_mulai' => $request->input('tanggal_mulai') ?? session('jurnal_filter.tanggal_mulai'),
            'tanggal_selesai' => $request->input('tanggal_selesai') ?? session('jurnal_filter.tanggal_selesai'),
            'search' => $request->input('search') ?? session('jurnal_filter.search'),
        ];
        
        // Simpan filter ke session jika ada filter baru dari request
        if ($request->hasAny(['periode_id', 'status', 'tanggal_mulai', 'tanggal_selesai', 'search', 'clear_filter'])) {
            if ($request->has('clear_filter')) {
                session()->forget('jurnal_filter');
                $filters = [
                    'periode_id' => null,
                    'status' => null,
                    'tanggal_mulai' => null,
                    'tanggal_selesai' => null,
                    'search' => null,
                ];
            } else {
                session(['jurnal_filter' => array_filter($filters, function($value) {
                    return $value !== null && $value !== '';
                })]);
            }
        }
        
        $query = JurnalHeader::with(['periode', 'user', 'details']);
        
        // Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
        $user = $this->currentUser();
        if (!$user->is_owner) {
            $query = $query->where('user_id', $user->id);
        }
        
        // Free account hanya bisa lihat tahun 2024
        if ($this->isFreeAccount()) {
            $query->whereHas('periode', function($q) {
                $q->where('tahun', 2024);
            });
        }
        
        // Filter berdasarkan periode
        if (!empty($filters['periode_id'])) {
            $query->where('periode_id', $filters['periode_id']);
        }
        
        // Filter berdasarkan status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        // Filter berdasarkan tanggal mulai
        if (!empty($filters['tanggal_mulai'])) {
            $query->where('tanggal_transaksi', '>=', $filters['tanggal_mulai']);
        }
        
        // Filter berdasarkan tanggal selesai
        if (!empty($filters['tanggal_selesai'])) {
            $query->where('tanggal_transaksi', '<=', $filters['tanggal_selesai']);
        }
        
        // Filter berdasarkan no. bukti atau deskripsi (search)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('no_bukti', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }
        
        // Build query string untuk pagination
        $queryString = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });
        
        $jurnals = $query->orderBy('tanggal_transaksi', 'desc')
            ->paginate(20)
            ->withQueryString();
        
        // Jika ada filter di session, tambahkan ke query string
        if (!empty($queryString)) {
            $jurnals->appends($queryString);
        }
        
        // Data untuk filter (periode milik user, free account hanya tahun 2024)
        // Owner bisa melihat semua periode, non-owner hanya periode miliknya
        $periodeQuery = Periode::query();
        
        if (!$user->is_owner) {
            $periodeQuery = $periodeQuery->where('user_id', $user->id);
        }
        
        $periodeQuery = $this->scopeFreeAccount($periodeQuery);
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        // Untuk bulk action, owner perlu daftar semua user dan periode
        $users = null;
        $allPeriodes = null;
        if ($user->is_owner) {
            $users = \App\Models\User::orderBy('name')->get();
            // Ambil semua periode dengan relasi user (untuk menampilkan nama user di dropdown)
            // Tidak perlu filter duplikat karena setiap periode punya ID unik dan user berbeda
            $allPeriodes = \App\Models\Periode::with('user')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->orderBy('user_id', 'asc')
                ->get();
        }
        
        return view('jurnal.index', compact('jurnals', 'periodes', 'filters', 'users', 'allPeriodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Free account tidak bisa create, tapi tetap bisa akses halaman (untuk demo)
        if ($this->isFreeAccount()) {
            // Tetap bisa view form, tapi akan di-block di store
        }

        $user = $this->currentUser();
        
        $coas = Coa::active()->orderBy('kode_akun')->get();
        
        // Ambil customers dan suppliers untuk dropdown
        $customersQuery = \App\Models\Customer::active();
        $suppliersQuery = \App\Models\Supplier::active();
        
        // Owner bisa melihat semua, non-owner hanya miliknya
        if (!$user->is_owner) {
            $customersQuery = $customersQuery->where('user_id', $user->id);
            $suppliersQuery = $suppliersQuery->where('user_id', $user->id);
        }
        
        $customers = $customersQuery->orderBy('nama_customer')->get();
        $suppliers = $suppliersQuery->orderBy('nama_supplier')->get();
        
        // Periode milik user yang open (free account hanya tahun 2024)
        // Owner bisa melihat semua periode, non-owner hanya periode miliknya
        $periodeQuery = Periode::open();
        
        if (!$user->is_owner) {
            $periodeQuery = $periodeQuery->where('user_id', $user->id);
        }
        
        $periodeQuery = $this->scopeFreeAccount($periodeQuery);
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        // Generate nomor bukti otomatis (hanya dari jurnal user)
        // Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
        $jurnalQuery = JurnalHeader::whereYear('tanggal_transaksi', date('Y'))
            ->whereMonth('tanggal_transaksi', date('m'));
        
        if (!$user->is_owner) {
            $jurnalQuery = $jurnalQuery->where('user_id', $user->id);
        }
        $lastJurnal = $jurnalQuery->latest()->first();
        
        if ($lastJurnal) {
            $lastNumber = intval(substr($lastJurnal->no_bukti, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        $noBukti = 'JRN/' . date('Y') . '/' . date('m') . '/' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        
        return view('jurnal.create', compact('coas', 'periodes', 'noBukti', 'customers', 'suppliers'));
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
            'no_bukti' => 'required|unique:jurnal_headers|max:50',
            'tanggal_transaksi' => 'required|date',
            'periode_id' => 'required|exists:periodes,id',
            'deskripsi' => 'required|string',
            'details' => 'required|array|min:2',
            'details.*.coa_id' => 'required|exists:coas,id',
            'details.*.customer_id' => 'nullable|exists:customers,id',
            'details.*.supplier_id' => 'nullable|exists:suppliers,id',
            'details.*.posisi' => 'required|in:Debit,Kredit',
            'details.*.jumlah' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string'
        ]);

        // Validasi: Total Debit harus sama dengan Total Kredit
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($request->details as $detail) {
            if ($detail['posisi'] == 'Debit') {
                $totalDebit += $detail['jumlah'];
            } else {
                $totalKredit += $detail['jumlah'];
            }
        }

        if ($totalDebit != $totalKredit) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['balance' => 'Total Debit (Rp ' . number_format($totalDebit, 2) . ') harus sama dengan Total Kredit (Rp ' . number_format($totalKredit, 2) . ')']);
        }

        DB::beginTransaction();
        try {
            // Simpan jurnal header
            $jurnal = JurnalHeader::create([
                'no_bukti' => $validated['no_bukti'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'periode_id' => $validated['periode_id'],
                'deskripsi' => $validated['deskripsi'],
                'total_debit' => $totalDebit,
                'total_kredit' => $totalKredit,
                'status' => 'Draft',
                'user_id' => auth()->id()
            ]);

            // Simpan jurnal details
            foreach ($request->details as $detail) {
                JurnalDetail::create([
                    'jurnal_header_id' => $jurnal->id,
                    'coa_id' => $detail['coa_id'],
                    'customer_id' => $detail['customer_id'] ?? null,
                    'supplier_id' => $detail['supplier_id'] ?? null,
                    'posisi' => $detail['posisi'],
                    'jumlah' => $detail['jumlah'],
                    'keterangan' => $detail['keterangan'] ?? null
                ]);
            }

            DB::commit();

            // Ambil filter dari session untuk redirect
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.show', $jurnal->id);
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }

            return redirect($redirectUrl)
                ->with('success', 'Jurnal berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JurnalHeader $jurnal)
    {
        // Pastikan user hanya bisa lihat jurnal miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $jurnal->user_id !== $user->id) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat jurnal ini');
        }

        $jurnal->load(['periode', 'user', 'details.coa']);
        
        // Ambil filter dari session untuk tombol kembali
        $filters = session('jurnal_filter', []);
        
        return view('jurnal.show', compact('jurnal', 'filters'));
    }

    /**
     * Duplicate the specified jurnal.
     */
    public function duplicate(JurnalHeader $jurnal)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa duplicate jurnal miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $jurnal->user_id !== $user->id) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Anda tidak memiliki akses untuk menduplikasi jurnal ini');
        }

        // Check feature access untuk duplicate
        if (!$user->hasFeature('duplicate_jurnal')) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Fitur duplikasi hanya tersedia untuk plan Professional/Enterprise');
        }

        $jurnal->load('details');
        
        // Generate new nomor bukti (hanya dari jurnal user)
        // Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
        $jurnalQuery = JurnalHeader::whereYear('tanggal_transaksi', date('Y'))
            ->whereMonth('tanggal_transaksi', date('m'))
            ->where('no_bukti', 'like', 'JRN/%');
        
        if (!$user->is_owner) {
            $jurnalQuery = $jurnalQuery->where('user_id', $user->id);
        }
        $lastJurnal = $jurnalQuery->latest()->first();
        
        if ($lastJurnal) {
            $lastNumber = intval(substr($lastJurnal->no_bukti, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        $newNoBukti = 'JRN/' . date('Y') . '/' . date('m') . '/' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        
        // Pastikan nomor bukti unik (hanya check di jurnal user)
        // Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
        $checkQuery = JurnalHeader::where('no_bukti', $newNoBukti);
        if (!$user->is_owner) {
            $checkQuery = $checkQuery->where('user_id', $user->id);
        }
        while ($checkQuery->exists()) {
            $newNumber++;
            $newNoBukti = 'JRN/' . date('Y') . '/' . date('m') . '/' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            $checkQuery = JurnalHeader::where('no_bukti', $newNoBukti);
            if (!$user->is_owner) {
                $checkQuery = $checkQuery->where('user_id', $user->id);
            }
        }
        
        DB::beginTransaction();
        try {
            // Get current active periode milik user
            $periodeQuery = Periode::where('status', 'Open')
                ->where('tahun', date('Y'))
                ->where('bulan', date('m'));
            
            // Owner bisa melihat semua periode, non-owner hanya periode miliknya
            if (!$user->is_owner) {
                $periodeQuery = $periodeQuery->where('user_id', $user->id);
            }
            $periode = $periodeQuery->first();
            
            if (!$periode) {
                // If no active periode, use the same periode as original
                $periode = $jurnal->periode;
            }
            
            // Create new jurnal header
            $newJurnal = JurnalHeader::create([
                'no_bukti' => $newNoBukti,
                'tanggal_transaksi' => date('Y-m-d'),
                'periode_id' => $periode->id,
                'deskripsi' => $jurnal->deskripsi . ' (Copy)',
                'total_debit' => $jurnal->total_debit,
                'total_kredit' => $jurnal->total_kredit,
                'status' => 'Draft',
                'user_id' => auth()->id()
            ]);
            
            // Duplicate all details
            foreach ($jurnal->details as $detail) {
                JurnalDetail::create([
                    'jurnal_header_id' => $newJurnal->id,
                    'coa_id' => $detail->coa_id,
                    'posisi' => $detail->posisi,
                    'jumlah' => $detail->jumlah,
                    'keterangan' => $detail->keterangan
                ]);
            }
            
            DB::commit();
            
            // Ambil filter dari session untuk redirect
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.edit', $newJurnal->id);
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }
            
            return redirect($redirectUrl)
                ->with('success', 'Jurnal berhasil diduplikasi. Silakan edit sesuai kebutuhan.');
        } catch (\Exception $e) {
            DB::rollback();
            
            // Ambil filter dari session untuk redirect
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.index');
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }
            
            return redirect($redirectUrl)
                ->with('error', 'Gagal menduplikasi jurnal: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JurnalHeader $jurnal)
    {
        // Free account bisa view form edit (untuk demo), tapi akan di-block di update
        // Pastikan user hanya bisa edit jurnal miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $jurnal->user_id !== $user->id) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit jurnal ini');
        }

        // Hanya jurnal dengan status Draft yang bisa diedit
        // Owner bisa edit jurnal Posted (untuk koreksi), tapi tidak bisa edit Void
        if ($jurnal->status == 'Void') {
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.index');
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }
            return redirect($redirectUrl)
                ->with('error', 'Jurnal dengan status Void tidak dapat diedit');
        }
        
        // Non-owner hanya bisa edit Draft
        if (!$user->is_owner && $jurnal->status != 'Draft') {
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.index');
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }
            return redirect($redirectUrl)
                ->with('error', 'Hanya jurnal dengan status Draft yang dapat diedit');
        }

        $coas = Coa::active()->orderBy('kode_akun')->get();
        
        // Ambil customers dan suppliers untuk dropdown
        $customersQuery = \App\Models\Customer::active();
        $suppliersQuery = \App\Models\Supplier::active();
        
        // Owner bisa melihat semua, non-owner hanya miliknya
        if (!$user->is_owner) {
            $customersQuery = $customersQuery->where('user_id', $user->id);
            $suppliersQuery = $suppliersQuery->where('user_id', $user->id);
        }
        
        $customers = $customersQuery->orderBy('nama_customer')->get();
        $suppliers = $suppliersQuery->orderBy('nama_supplier')->get();
        
        // Untuk edit, ambil semua periode milik user (termasuk closed) agar bisa edit jurnal lama
        // Owner bisa melihat semua periode, non-owner hanya periode miliknya
        $periodeQuery = Periode::query();
        
        if (!$user->is_owner) {
            $periodeQuery = $periodeQuery->where('user_id', $user->id);
        }
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        // Owner bisa mengubah kepemilikan jurnal, ambil daftar semua user
        $users = null;
        if ($user->is_owner) {
            $users = \App\Models\User::orderBy('name')->get();
        }
        
        $jurnal->load('details.coa', 'details.customer', 'details.supplier');

        return view('jurnal.edit', compact('jurnal', 'coas', 'periodes', 'users', 'customers', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JurnalHeader $jurnal)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa update jurnal miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $jurnal->user_id !== $user->id) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate jurnal ini');
        }

        // Hanya jurnal dengan status Draft yang bisa diupdate
        // Owner bisa update jurnal Posted (untuk koreksi), tapi tidak bisa update Void
        if ($jurnal->status == 'Void') {
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.index');
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }
            return redirect($redirectUrl)
                ->with('error', 'Jurnal dengan status Void tidak dapat diupdate');
        }
        
        // Non-owner hanya bisa update Draft
        if (!$user->is_owner && $jurnal->status != 'Draft') {
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.index');
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }
            return redirect($redirectUrl)
                ->with('error', 'Hanya jurnal dengan status Draft yang dapat diupdate');
        }

        $validationRules = [
            'no_bukti' => 'required|max:50|unique:jurnal_headers,no_bukti,' . $jurnal->id,
            'tanggal_transaksi' => 'required|date',
            'periode_id' => 'required|exists:periodes,id',
            'deskripsi' => 'required|string',
            'details' => 'required|array|min:2',
            'details.*.coa_id' => 'required|exists:coas,id',
            'details.*.customer_id' => 'nullable|exists:customers,id',
            'details.*.supplier_id' => 'nullable|exists:suppliers,id',
            'details.*.posisi' => 'required|in:Debit,Kredit',
            'details.*.jumlah' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string'
        ];
        
        // Owner bisa mengubah user_id (kepemilikan jurnal)
        if ($user->is_owner) {
            $validationRules['user_id'] = 'nullable|exists:users,id';
        }
        
        $validated = $request->validate($validationRules);

        // Validasi: Total Debit harus sama dengan Total Kredit
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($request->details as $detail) {
            if ($detail['posisi'] == 'Debit') {
                $totalDebit += $detail['jumlah'];
            } else {
                $totalKredit += $detail['jumlah'];
            }
        }

        if ($totalDebit != $totalKredit) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['balance' => 'Total Debit (Rp ' . number_format($totalDebit, 2) . ') harus sama dengan Total Kredit (Rp ' . number_format($totalKredit, 2) . ')']);
        }

        DB::beginTransaction();
        try {
            // Update jurnal header
            $updateData = [
                'no_bukti' => $validated['no_bukti'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'periode_id' => $validated['periode_id'],
                'deskripsi' => $validated['deskripsi'],
                'total_debit' => $totalDebit,
                'total_kredit' => $totalKredit,
            ];
            
            // Owner bisa mengubah user_id (kepemilikan jurnal)
            // Jika user_id dipilih, update. Jika kosong, tetap gunakan user_id yang lama
            if ($user->is_owner && isset($validated['user_id']) && !empty($validated['user_id'])) {
                $updateData['user_id'] = $validated['user_id'];
            }
            
            $jurnal->update($updateData);

            // Hapus detail lama
            $jurnal->details()->delete();

            // Simpan detail baru
            foreach ($request->details as $detail) {
                JurnalDetail::create([
                    'jurnal_header_id' => $jurnal->id,
                    'coa_id' => $detail['coa_id'],
                    'customer_id' => $detail['customer_id'] ?? null,
                    'supplier_id' => $detail['supplier_id'] ?? null,
                    'posisi' => $detail['posisi'],
                    'jumlah' => $detail['jumlah'],
                    'keterangan' => $detail['keterangan'] ?? null
                ]);
            }

            DB::commit();

            // Ambil filter dari session untuk redirect
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.show', $jurnal->id);
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }

            return redirect($redirectUrl)
                ->with('success', 'Jurnal berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JurnalHeader $jurnal)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa hapus jurnal miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $jurnal->user_id !== $user->id) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus jurnal ini');
        }

        // Hanya jurnal dengan status Draft yang bisa dihapus
        if ($jurnal->status != 'Draft') {
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.index');
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }
            return redirect($redirectUrl)
                ->with('error', 'Hanya jurnal dengan status Draft yang dapat dihapus');
        }

        DB::beginTransaction();
        try {
            $jurnal->details()->delete();
            $jurnal->delete();

            DB::commit();

            // Ambil filter dari session untuk redirect
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.index');
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }

            return redirect($redirectUrl)
                ->with('success', 'Jurnal berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Post jurnal (ubah status dari Draft ke Posted)
     */
    public function post(JurnalHeader $jurnal)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Pastikan user hanya bisa post jurnal miliknya sendiri (kecuali owner)
        $user = $this->currentUser();
        if (!$user->is_owner && $jurnal->user_id !== $user->id) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Anda tidak memiliki akses untuk memposting jurnal ini');
        }

        if ($jurnal->status != 'Draft') {
            return redirect()->back()
                ->with('error', 'Hanya jurnal dengan status Draft yang dapat diposting');
        }

        if (!$jurnal->isBalanced()) {
            return redirect()->back()
                ->with('error', 'Jurnal tidak balance, tidak dapat diposting');
        }

        $jurnal->update(['status' => 'Posted']);

        // Ambil filter dari session untuk redirect
        $filters = session('jurnal_filter', []);
        $redirectUrl = route('jurnal.show', $jurnal->id);
        if (!empty($filters)) {
            $redirectUrl .= '?' . http_build_query($filters);
        }

        return redirect($redirectUrl)
            ->with('success', 'Jurnal berhasil diposting');
    }

    /**
     * Show form upload CSV
     */
    public function showUploadForm()
    {
        return view('jurnal.upload-csv');
    }

    /**
     * Import jurnal dari CSV file
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // max 10MB
        ]);

        $file = $request->file('csv_file');
        
        // Mapping jenis transaksi ke COA
        $coaMapping = [
            'Penjualan Barang' => '4-1002',
            'Pejualan Jasa' => '4-1001',
            'Penjualan Jasa' => '4-1001',
            'Belanja' => '5-1001',
            'Trasportasi' => '5-1009',
            'Perbaikan' => '5-1009',
            'Kantor' => '5-1008',
            'Hadiah' => '5-1009',
        ];
        
        $kasCoa = '1-1001';
        $userId = auth()->id();
        
        $successCount = 0;
        $errorCount = 0;
        $skippedCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
                $header = fgetcsv($handle); // Skip header
                $rowNum = 1;
                
                while (($data = fgetcsv($handle)) !== FALSE) {
                    $rowNum++;
                    
                    try {
                        if (count($data) < 7) {
                            $skippedCount++;
                            $errors[] = "Baris {$rowNum}: Data tidak lengkap";
                            continue;
                        }

                        $row = [
                            'timestamp' => $data[0],
                            'tanggal' => $data[1],
                            'tipe' => $data[2],
                            'jenis' => $data[3],
                            'deskripsi' => $data[4],
                            'debit' => floatval(str_replace(',', '', $data[5])),
                            'kredit' => floatval(str_replace(',', '', $data[6])),
                        ];

                        // Parse tanggal
                        $tanggal = $this->parseDate($row['tanggal']);
                        if (!$tanggal) {
                            $skippedCount++;
                            $errors[] = "Baris {$rowNum}: Tanggal tidak valid: {$row['tanggal']}";
                            continue;
                        }

                        // Cari atau buat periode
                        $periode = $this->getOrCreatePeriode($tanggal);

                        // Tentukan akun berdasarkan jenis transaksi
                        $coaCode = $this->getCoaCode($row['jenis'], $row['tipe'], $coaMapping);
                        $coa = Coa::where('kode_akun', $coaCode)->first();
                        
                        if (!$coa) {
                            $errorCount++;
                            $errors[] = "Baris {$rowNum}: COA tidak ditemukan: {$coaCode}";
                            continue;
                        }

                        // Tentukan jumlah
                        $jumlah = $row['debit'] > 0 ? $row['debit'] : $row['kredit'];
                        
                        if ($jumlah <= 0) {
                            $skippedCount++;
                            continue;
                        }

                        // Buat jurnal entry
                        $jurnal = $this->createJurnalFromCsv($row, $tanggal, $periode, $coa, $jumlah, $userId, $kasCoa);
                        if ($jurnal) {
                            $successCount++;
                        } else {
                            $skippedCount++;
                        }
                    } catch (\Exception $e) {
                        $errorCount++;
                        $errors[] = "Baris {$rowNum}: " . $e->getMessage();
                    }
                }
                fclose($handle);
            }

            DB::commit();

            $message = "Import selesai! Berhasil: {$successCount}, Error: {$errorCount}, Dilewati: {$skippedCount}";
            
            if (count($errors) > 0 && count($errors) <= 10) {
                $message .= "\n\nError detail:\n" . implode("\n", array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $message .= "\n... dan " . (count($errors) - 10) . " error lainnya";
                }
            }

            // Ambil filter dari session untuk redirect
            $filters = session('jurnal_filter', []);
            $redirectUrl = route('jurnal.index');
            if (!empty($filters)) {
                $redirectUrl .= '?' . http_build_query($filters);
            }

            return redirect($redirectUrl)
                ->with('success', $message)
                ->with('import_stats', [
                    'success' => $successCount,
                    'error' => $errorCount,
                    'skipped' => $skippedCount,
                    'errors' => array_slice($errors, 0, 20)
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat import: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper methods untuk import CSV
     */
    private function parseDate($dateString)
    {
        $formats = ['m/d/Y', 'm-d-Y', 'n/j/Y', 'n-j-Y'];
        
        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $dateString);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }
        
        return null;
    }

    private function getOrCreatePeriode($tanggal)
    {
        $date = Carbon::parse($tanggal);
        $tahun = $date->year;
        $bulan = $date->month;
        $user = $this->currentUser();

        $periodeQuery = Periode::where('tahun', $tahun)
            ->where('bulan', $bulan);
        
        // Owner bisa melihat semua periode, non-owner hanya periode miliknya
        if (!$user->is_owner) {
            $periodeQuery = $periodeQuery->where('user_id', $user->id);
        }
        $periode = $periodeQuery->first();

        if (!$periode) {
            $start = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            
            $periode = Periode::create([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'status' => 'Open',
                'tanggal_mulai' => $start->toDateString(),
                'tanggal_selesai' => $end->toDateString(),
                'user_id' => $user->id,
            ]);
        }

        return $periode;
    }

    private function getCoaCode($jenis, $tipe, $coaMapping)
    {
        if (stripos($tipe, 'Pemasukan') !== false) {
            foreach ($coaMapping as $key => $code) {
                if (stripos($jenis, $key) !== false) {
                    return $code;
                }
            }
            return '4-1003'; // Pendapatan Lain-lain
        }
        
        if (stripos($tipe, 'Pengeluaran') !== false) {
            foreach ($coaMapping as $key => $code) {
                if (stripos($jenis, $key) !== false) {
                    return $code;
                }
            }
            return '5-1009'; // Beban Lain-lain
        }

        return '5-1009';
    }

    private function createJurnalFromCsv($row, $tanggal, $periode, $coa, $jumlah, $userId, $kasCoaCode)
    {
        $date = Carbon::parse($tanggal);
        // Cari nomor bukti terakhir untuk bulan ini dengan pattern yang benar
        $lastJurnal = JurnalHeader::whereYear('tanggal_transaksi', $date->year)
            ->whereMonth('tanggal_transaksi', $date->month)
            ->where('no_bukti', 'like', 'JRN/' . $date->format('Y') . '/' . $date->format('m') . '/%')
            ->orderByRaw('CAST(SUBSTRING(no_bukti, -4) AS UNSIGNED) DESC')
            ->first();
        
        if ($lastJurnal && preg_match('/\/(\d{4})$/', $lastJurnal->no_bukti, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        $noBukti = 'JRN/' . $date->format('Y') . '/' . $date->format('m') . '/' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Cek apakah sudah ada jurnal dengan deskripsi yang sama di tanggal yang sama
        $existing = JurnalHeader::where('tanggal_transaksi', $tanggal)
            ->where('deskripsi', $row['deskripsi'])
            ->where('total_debit', $jumlah)
            ->first();
        
        if ($existing) {
            return null; // Skip duplicate
        }

        $kasCoa = Coa::where('kode_akun', $kasCoaCode)->first();
        if (!$kasCoa) {
            throw new \Exception("COA Kas tidak ditemukan");
        }

        $isPemasukan = stripos($row['tipe'], 'Pemasukan') !== false;
        
        $jurnal = JurnalHeader::create([
            'no_bukti' => $noBukti,
            'tanggal_transaksi' => $tanggal,
            'periode_id' => $periode->id,
            'deskripsi' => $row['deskripsi'],
            'total_debit' => $jumlah,
            'total_kredit' => $jumlah,
            'status' => 'Draft',
            'user_id' => $userId
        ]);

        if ($isPemasukan) {
            // Pemasukan: Debit Kas, Kredit Pendapatan
            JurnalDetail::create([
                'jurnal_header_id' => $jurnal->id,
                'coa_id' => $kasCoa->id,
                'posisi' => 'Debit',
                'jumlah' => $jumlah,
                'keterangan' => $row['deskripsi']
            ]);
            
            JurnalDetail::create([
                'jurnal_header_id' => $jurnal->id,
                'coa_id' => $coa->id,
                'posisi' => 'Kredit',
                'jumlah' => $jumlah,
                'keterangan' => $row['deskripsi']
            ]);
        } else {
            // Pengeluaran: Debit Beban, Kredit Kas
            JurnalDetail::create([
                'jurnal_header_id' => $jurnal->id,
                'coa_id' => $coa->id,
                'posisi' => 'Debit',
                'jumlah' => $jumlah,
                'keterangan' => $row['deskripsi']
            ]);
            
            JurnalDetail::create([
                'jurnal_header_id' => $jurnal->id,
                'coa_id' => $kasCoa->id,
                'posisi' => 'Kredit',
                'jumlah' => $jumlah,
                'keterangan' => $row['deskripsi']
            ]);
        }

        return $jurnal;
    }

    /**
     * Bulk update jurnal (hanya untuk owner)
     */
    public function bulkUpdate(Request $request)
    {
        $user = $this->currentUser();
        
        // Hanya owner yang bisa melakukan bulk update
        if (!$user->is_owner) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Anda tidak memiliki akses untuk melakukan bulk update');
        }

        // Validasi dinamis berdasarkan bulk_action
        $rules = [
            'jurnal_ids' => 'required|string',
            'bulk_action' => 'required|in:change_owner,change_status,change_periode',
        ];

        // Tambahkan validasi sesuai dengan action yang dipilih
        if ($request->bulk_action === 'change_owner') {
            $rules['user_id'] = 'required|exists:users,id';
            $rules['status'] = 'nullable';
            $rules['periode_id'] = 'nullable';
        } elseif ($request->bulk_action === 'change_status') {
            $rules['status'] = 'required|in:Draft,Posted,Void';
            $rules['user_id'] = 'nullable';
            $rules['periode_id'] = 'nullable';
        } elseif ($request->bulk_action === 'change_periode') {
            $rules['periode_id'] = 'required|exists:periodes,id';
            $rules['user_id'] = 'nullable';
            $rules['status'] = 'nullable';
        }

        $request->validate($rules);

        $jurnalIds = explode(',', $request->jurnal_ids);
        $jurnalIds = array_filter(array_map('intval', $jurnalIds));

        if (empty($jurnalIds)) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Tidak ada jurnal yang dipilih');
        }

        DB::beginTransaction();
        try {
            $jurnals = JurnalHeader::whereIn('id', $jurnalIds)->get();
            $updatedCount = 0;

            foreach ($jurnals as $jurnal) {
                $updateData = [];

                switch ($request->bulk_action) {
                    case 'change_owner':
                        $updateData['user_id'] = $request->user_id;
                        break;
                    
                    case 'change_status':
                        // Validasi: Void tidak bisa diubah ke status lain
                        if ($jurnal->status == 'Void' && $request->status != 'Void') {
                            // Skip jurnal yang sudah Void - gunakan break untuk keluar dari switch, lalu continue loop
                            break;
                        }
                        $updateData['status'] = $request->status;
                        break;
                    
                    case 'change_periode':
                        $updateData['periode_id'] = $request->periode_id;
                        break;
                }
                
                // Skip jurnal yang sudah Void saat mengubah status
                if ($request->bulk_action == 'change_status' && $jurnal->status == 'Void' && $request->status != 'Void') {
                    continue; // Continue ke jurnal berikutnya
                }

                if (!empty($updateData)) {
                    $jurnal->update($updateData);
                    $updatedCount++;
                }
            }

            DB::commit();

            $actionName = [
                'change_owner' => 'mengubah pemilik',
                'change_status' => 'mengubah status',
                'change_periode' => 'mengubah periode'
            ][$request->bulk_action] ?? 'memperbarui';

            return redirect()->route('jurnal.index')
                ->with('success', "Berhasil {$actionName} {$updatedCount} jurnal");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('jurnal.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
