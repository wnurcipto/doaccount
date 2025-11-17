<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\JurnalDetail;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuBesarController extends Controller
{
    public function index(Request $request)
    {
        // Jika ada parameter 'clear_filter', hapus session filter
        if ($request->has('clear_filter')) {
            session()->forget('buku_besar_filter');
        }
        
        // Ambil filter dari request atau session
        $filters = [
            'coa_id' => $request->input('coa_id') ?? session('buku_besar_filter.coa_id'),
            'tanggal_mulai' => $request->input('tanggal_mulai') ?? session('buku_besar_filter.tanggal_mulai') ?? date('Y-m-01'),
            'tanggal_selesai' => $request->input('tanggal_selesai') ?? session('buku_besar_filter.tanggal_selesai') ?? date('Y-m-d'),
        ];
        
        // Simpan filter ke session jika ada filter baru dari request
        if ($request->hasAny(['coa_id', 'tanggal_mulai', 'tanggal_selesai', 'clear_filter'])) {
            if ($request->has('clear_filter')) {
                session()->forget('buku_besar_filter');
                $filters = [
                    'coa_id' => null,
                    'tanggal_mulai' => date('Y-m-01'),
                    'tanggal_selesai' => date('Y-m-d'),
                ];
            } else {
                // Simpan filter ke session (hanya yang tidak kosong)
                $filterToSave = [];
                if (!empty($filters['coa_id'])) {
                    $filterToSave['coa_id'] = $filters['coa_id'];
                }
                if (!empty($filters['tanggal_mulai'])) {
                    $filterToSave['tanggal_mulai'] = $filters['tanggal_mulai'];
                }
                if (!empty($filters['tanggal_selesai'])) {
                    $filterToSave['tanggal_selesai'] = $filters['tanggal_selesai'];
                }
                session(['buku_besar_filter' => $filterToSave]);
            }
        }
        
        $coas = Coa::active()->orderBy('kode_akun')->get(); // COA global
        
        $periodeQuery = Periode::query();
        $periodeQuery = $this->scopeUser($periodeQuery);
        $periodeQuery = $this->scopeFreeAccount($periodeQuery); // Free account hanya tahun 2024
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        return view('buku-besar.index', compact('coas', 'periodes', 'filters'));
    }

    public function show(Request $request)
    {
        // Ambil filter dari request atau session
        $coaId = $request->input('coa_id') ?? session('buku_besar_filter.coa_id');
        $tanggalMulai = $request->input('tanggal_mulai') ?? session('buku_besar_filter.tanggal_mulai') ?? date('Y-m-01');
        $tanggalSelesai = $request->input('tanggal_selesai') ?? session('buku_besar_filter.tanggal_selesai') ?? date('Y-m-d');
        
        // Validasi
        $request->merge([
            'coa_id' => $coaId,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai
        ]);
        
        $request->validate([
            'coa_id' => 'required|exists:coas,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ]);

        // Simpan filter ke session
        session(['buku_besar_filter' => [
            'coa_id' => $coaId,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai
        ]]);

        $coa = Coa::findOrFail($coaId);

        // Hitung saldo awal (transaksi sebelum tanggal mulai, hanya milik user)
        // Free account hanya bisa lihat tahun 2024
        $user = $this->currentUser();
        if ($this->isFreeAccount()) {
            // Pastikan tanggal filter hanya tahun 2024
            if (date('Y', strtotime($tanggalMulai)) != 2024 || date('Y', strtotime($tanggalSelesai)) != 2024) {
                return redirect()->route('buku-besar.index')
                    ->with('error', 'Akun Free hanya dapat melihat data tahun 2024');
            }
        }
        
        $saldoAwalData = JurnalDetail::where('coa_id', $coa->id)
            ->whereHas('jurnalHeader', function($query) use ($tanggalMulai, $user) {
                $query->where('status', 'Posted')
                      ->where('tanggal_transaksi', '<', $tanggalMulai);
                if (!$user->is_owner) {
                    $query->where('user_id', $user->id);
                }
            })
            ->select(
                DB::raw('SUM(CASE WHEN posisi = "Debit" THEN jumlah ELSE 0 END) as total_debit'),
                DB::raw('SUM(CASE WHEN posisi = "Kredit" THEN jumlah ELSE 0 END) as total_kredit')
            )
            ->first();

        $totalDebitAwal = $saldoAwalData->total_debit ?? 0;
        $totalKreditAwal = $saldoAwalData->total_kredit ?? 0;

        // Saldo awal tergantung posisi normal akun
        if ($coa->posisi_normal == 'Debit') {
            $saldoAwal = $totalDebitAwal - $totalKreditAwal;
        } else {
            $saldoAwal = $totalKreditAwal - $totalDebitAwal;
        }

        // Ambil transaksi dalam periode (hanya milik user)
        $transaksi = JurnalDetail::where('coa_id', $coa->id)
            ->whereHas('jurnalHeader', function($query) use ($tanggalMulai, $tanggalSelesai, $user) {
                $query->where('status', 'Posted')
                      ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalSelesai]);
                if (!$user->is_owner) {
                    $query->where('user_id', $user->id);
                }
            })
            ->with(['jurnalHeader'])
            ->join('jurnal_headers', 'jurnal_details.jurnal_header_id', '=', 'jurnal_headers.id')
            ->orderBy('jurnal_headers.tanggal_transaksi')
            ->orderBy('jurnal_headers.no_bukti')
            ->select('jurnal_details.*')
            ->get();

        // Hitung running balance
        $saldo = $saldoAwal;
        $transaksiWithSaldo = $transaksi->map(function($item) use (&$saldo, $coa) {
            if ($coa->posisi_normal == 'Debit') {
                if ($item->posisi == 'Debit') {
                    $saldo += $item->jumlah;
                } else {
                    $saldo -= $item->jumlah;
                }
            } else {
                if ($item->posisi == 'Kredit') {
                    $saldo += $item->jumlah;
                } else {
                    $saldo -= $item->jumlah;
                }
            }
            $item->saldo = $saldo;
            return $item;
        });

        // Ambil filter dari session untuk tombol kembali
        $filters = session('buku_besar_filter', []);
        
        return view('buku-besar.show', compact('coa', 'transaksiWithSaldo', 'saldoAwal', 'tanggalMulai', 'tanggalSelesai', 'filters'));
    }

    public function exportPdf(Request $request)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $coaId = $request->input('coa_id');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        
        $request->validate([
            'coa_id' => 'required|exists:coas,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ]);

        $coa = Coa::findOrFail($coaId);
        $user = $this->currentUser();

        // Hitung saldo awal (hanya milik user)
        $saldoAwalData = JurnalDetail::where('coa_id', $coa->id)
            ->whereHas('jurnalHeader', function($query) use ($tanggalMulai, $user) {
                $query->where('status', 'Posted')
                      ->where('tanggal_transaksi', '<', $tanggalMulai);
                if (!$user->is_owner) {
                    $query->where('user_id', $user->id);
                }
            })
            ->select(
                DB::raw('SUM(CASE WHEN posisi = "Debit" THEN jumlah ELSE 0 END) as total_debit'),
                DB::raw('SUM(CASE WHEN posisi = "Kredit" THEN jumlah ELSE 0 END) as total_kredit')
            )
            ->first();

        $totalDebitAwal = $saldoAwalData->total_debit ?? 0;
        $totalKreditAwal = $saldoAwalData->total_kredit ?? 0;

        if ($coa->posisi_normal == 'Debit') {
            $saldoAwal = $totalDebitAwal - $totalKreditAwal;
        } else {
            $saldoAwal = $totalKreditAwal - $totalDebitAwal;
        }

        // Ambil transaksi (hanya milik user)
        $transaksi = JurnalDetail::where('coa_id', $coa->id)
            ->whereHas('jurnalHeader', function($query) use ($tanggalMulai, $tanggalSelesai, $user) {
                $query->where('status', 'Posted')
                      ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalSelesai]);
                if (!$user->is_owner) {
                    $query->where('user_id', $user->id);
                }
            })
            ->with(['jurnalHeader'])
            ->join('jurnal_headers', 'jurnal_details.jurnal_header_id', '=', 'jurnal_headers.id')
            ->orderBy('jurnal_headers.tanggal_transaksi')
            ->orderBy('jurnal_headers.no_bukti')
            ->select('jurnal_details.*')
            ->get();

        // Hitung running balance
        $saldo = $saldoAwal;
        $transaksiWithSaldo = $transaksi->map(function($item) use (&$saldo, $coa) {
            if ($coa->posisi_normal == 'Debit') {
                if ($item->posisi == 'Debit') {
                    $saldo += $item->jumlah;
                } else {
                    $saldo -= $item->jumlah;
                }
            } else {
                if ($item->posisi == 'Kredit') {
                    $saldo += $item->jumlah;
                } else {
                    $saldo -= $item->jumlah;
                }
            }
            $item->saldo = $saldo;
            return $item;
        });

        $pdf = Pdf::loadView('buku-besar.export-pdf', compact('coa', 'transaksiWithSaldo', 'saldoAwal', 'tanggalMulai', 'tanggalSelesai'));
        return $pdf->download('Buku_Besar_' . $coa->kode_akun . '_' . date('Y-m-d', strtotime($tanggalMulai)) . '_' . date('Y-m-d', strtotime($tanggalSelesai)) . '.pdf');
    }
}

