<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\JurnalDetail;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Laporan Laba Rugi
     */
    public function labaRugi(Request $request)
    {
        $periodeQuery = Periode::query();
        $periodeQuery = $this->scopeUser($periodeQuery);
        $periodeQuery = $this->scopeFreeAccount($periodeQuery); // Free account hanya tahun 2024
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        if (!$request->has('tanggal_mulai') || !$request->has('tanggal_selesai')) {
            return view('laporan.laba-rugi', compact('periodes'));
        }

        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;

        // Ambil data Pendapatan
        $pendapatan = $this->getDataByTipeAkun('Pendapatan', $tanggalMulai, $tanggalSelesai);
        $totalPendapatan = $pendapatan->sum('saldo');

        // Ambil data Beban
        $beban = $this->getDataByTipeAkun('Beban', $tanggalMulai, $tanggalSelesai);
        $totalBeban = $beban->sum('saldo');

        // Hitung Laba/Rugi
        $labaRugi = $totalPendapatan - $totalBeban;

        return view('laporan.laba-rugi', compact('periodes', 'pendapatan', 'beban', 'totalPendapatan', 'totalBeban', 'labaRugi', 'tanggalMulai', 'tanggalSelesai'));
    }

    /**
     * Laporan Neraca
     */
    public function neraca(Request $request)
    {
        $user = $this->currentUser();
        
        // Free account bisa view untuk demo, tapi tidak bisa export
        // Check feature access hanya untuk non-free account
        if (!$this->isFreeAccount() && !$user->hasFeature('laporan_neraca')) {
            return redirect()->route('dashboard')
                ->with('error', 'Laporan Neraca hanya tersedia untuk plan Professional/Enterprise');
        }

        $periodeQuery = Periode::query();
        $periodeQuery = $this->scopeUser($periodeQuery);
        $periodeQuery = $this->scopeFreeAccount($periodeQuery); // Free account hanya tahun 2024
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        if (!$request->has('tanggal')) {
            // Untuk free account, set default tanggal ke akhir tahun 2024
            $defaultTanggal = $this->isFreeAccount() ? '2024-12-31' : date('Y-m-d');
            return view('laporan.neraca', compact('periodes', 'defaultTanggal'));
        }

        $tanggal = $request->tanggal;
        
        // Free account hanya bisa lihat tahun 2024
        if ($this->isFreeAccount()) {
            if (date('Y', strtotime($tanggal)) != 2024) {
                return redirect()->route('laporan.neraca')
                    ->with('error', 'Akun Free hanya dapat melihat data tahun 2024');
            }
            // Pastikan tanggal tidak melebihi 31 Desember 2024
            if (strtotime($tanggal) > strtotime('2024-12-31')) {
                $tanggal = '2024-12-31';
            }
        }

        // Ambil data Aset
        $aset = $this->getDataByTipeAkunUpTo('Aset', $tanggal);
        $totalAset = $aset->sum('saldo');

        // Ambil data Liabilitas
        $liabilitas = $this->getDataByTipeAkunUpTo('Liabilitas', $tanggal);
        $totalLiabilitas = $liabilitas->sum('saldo');

        // Ambil data Ekuitas
        $ekuitas = $this->getDataByTipeAkunUpTo('Ekuitas', $tanggal);
        $totalEkuitas = $ekuitas->sum('saldo');

        // Hitung Laba Rugi Tahun Berjalan
        // Untuk free account, pastikan awal tahun adalah 2024-01-01
        if ($this->isFreeAccount()) {
            $awalTahun = '2024-01-01';
        } else {
            $awalTahun = date('Y-01-01', strtotime($tanggal));
        }
        $pendapatan = $this->getDataByTipeAkun('Pendapatan', $awalTahun, $tanggal);
        $totalPendapatan = $pendapatan->sum('saldo');
        
        $beban = $this->getDataByTipeAkun('Beban', $awalTahun, $tanggal);
        $totalBeban = $beban->sum('saldo');
        
        $labaRugiTahunBerjalan = $totalPendapatan - $totalBeban;

        // Total Liabilitas + Ekuitas
        $totalLiabilitasEkuitas = $totalLiabilitas + $totalEkuitas + $labaRugiTahunBerjalan;

        return view('laporan.neraca', compact('periodes', 'aset', 'liabilitas', 'ekuitas', 'totalAset', 'totalLiabilitas', 'totalEkuitas', 'labaRugiTahunBerjalan', 'totalLiabilitasEkuitas', 'tanggal'));
    }

    /**
     * Helper: Ambil data saldo per COA berdasarkan tipe akun (untuk periode)
     */
    private function getDataByTipeAkun($tipeAkun, $tanggalMulai, $tanggalSelesai)
    {
        $user = $this->currentUser();
        $coas = Coa::where('tipe_akun', $tipeAkun)
            ->where('is_active', true)
            ->orderBy('kode_akun')
            ->get();

        return $coas->map(function($coa) use ($tanggalMulai, $tanggalSelesai, $user) {
            $data = JurnalDetail::where('coa_id', $coa->id)
                ->whereHas('jurnalHeader', function($query) use ($tanggalMulai, $tanggalSelesai, $user) {
                    $query->where('status', 'Posted')
                          ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalSelesai]);
                    if (!$user->is_owner) {
                        $query->where('user_id', $user->id);
                    }
                    // Free account hanya tahun 2024
                    if ($this->isFreeAccount()) {
                        $query->whereYear('tanggal_transaksi', 2024);
                    }
                })
                ->select(
                    DB::raw('SUM(CASE WHEN posisi = "Debit" THEN jumlah ELSE 0 END) as total_debit'),
                    DB::raw('SUM(CASE WHEN posisi = "Kredit" THEN jumlah ELSE 0 END) as total_kredit')
                )
                ->first();

            $totalDebit = $data->total_debit ?? 0;
            $totalKredit = $data->total_kredit ?? 0;

            // Hitung saldo berdasarkan posisi normal
            if ($coa->posisi_normal == 'Debit') {
                $saldo = $totalDebit - $totalKredit;
            } else {
                $saldo = $totalKredit - $totalDebit;
            }

            $coa->saldo = $saldo;
            return $coa;
        })->filter(function($coa) {
            return $coa->saldo != 0; // Hanya tampilkan yang memiliki saldo
        });
    }

    /**
     * Helper: Ambil data saldo per COA berdasarkan tipe akun (sampai dengan tanggal tertentu)
     */
    private function getDataByTipeAkunUpTo($tipeAkun, $tanggal)
    {
        $user = $this->currentUser();
        $coas = Coa::where('tipe_akun', $tipeAkun)
            ->where('is_active', true)
            ->orderBy('kode_akun')
            ->get();

        return $coas->map(function($coa) use ($tanggal, $user) {
            $data = JurnalDetail::where('coa_id', $coa->id)
                ->whereHas('jurnalHeader', function($query) use ($tanggal, $user) {
                    $query->where('status', 'Posted')
                          ->where('tanggal_transaksi', '<=', $tanggal);
                    if (!$user->is_owner) {
                        $query->where('user_id', $user->id);
                    }
                    // Free account hanya tahun 2024
                    if ($this->isFreeAccount()) {
                        $query->whereYear('tanggal_transaksi', 2024);
                    }
                })
                ->select(
                    DB::raw('SUM(CASE WHEN posisi = "Debit" THEN jumlah ELSE 0 END) as total_debit'),
                    DB::raw('SUM(CASE WHEN posisi = "Kredit" THEN jumlah ELSE 0 END) as total_kredit')
                )
                ->first();

            $totalDebit = $data->total_debit ?? 0;
            $totalKredit = $data->total_kredit ?? 0;

            // Hitung saldo berdasarkan posisi normal
            if ($coa->posisi_normal == 'Debit') {
                $saldo = $totalDebit - $totalKredit;
            } else {
                $saldo = $totalKredit - $totalDebit;
            }

            $coa->saldo = $saldo;
            return $coa;
        })->filter(function($coa) {
            return $coa->saldo != 0; // Hanya tampilkan yang memiliki saldo
        });
    }

    /**
     * Export Laporan Laba Rugi ke PDF
     */
    public function exportLabaRugiPdf(Request $request)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Check feature access
        $user = $this->currentUser();
        if (!$user->hasFeature('export_pdf')) {
            return redirect()->route('laporan.laba-rugi')
                ->with('error', 'Export PDF hanya tersedia untuk plan Professional/Enterprise');
        }

        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;

        // Ambil data Pendapatan
        $pendapatan = $this->getDataByTipeAkun('Pendapatan', $tanggalMulai, $tanggalSelesai);
        $totalPendapatan = $pendapatan->sum('saldo');

        // Ambil data Beban
        $beban = $this->getDataByTipeAkun('Beban', $tanggalMulai, $tanggalSelesai);
        $totalBeban = $beban->sum('saldo');

        // Hitung Laba/Rugi
        $labaRugi = $totalPendapatan - $totalBeban;

        $pdf = Pdf::loadView('laporan.export.laba-rugi-pdf', compact('pendapatan', 'beban', 'totalPendapatan', 'totalBeban', 'labaRugi', 'tanggalMulai', 'tanggalSelesai'));
        return $pdf->download('Laporan_Laba_Rugi_' . date('Y-m-d', strtotime($tanggalMulai)) . '_' . date('Y-m-d', strtotime($tanggalSelesai)) . '.pdf');
    }

    /**
     * Export Laporan Neraca ke PDF
     */
    public function exportNeracaPdf(Request $request)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        // Check feature access
        $user = $this->currentUser();
        if (!$user->hasFeature('export_pdf')) {
            return redirect()->route('laporan.neraca')
                ->with('error', 'Export PDF hanya tersedia untuk plan Professional/Enterprise');
        }

        $tanggal = $request->tanggal;

        // Ambil data Aset
        $aset = $this->getDataByTipeAkunUpTo('Aset', $tanggal);
        $totalAset = $aset->sum('saldo');

        // Ambil data Liabilitas
        $liabilitas = $this->getDataByTipeAkunUpTo('Liabilitas', $tanggal);
        $totalLiabilitas = $liabilitas->sum('saldo');

        // Ambil data Ekuitas
        $ekuitas = $this->getDataByTipeAkunUpTo('Ekuitas', $tanggal);
        $totalEkuitas = $ekuitas->sum('saldo');

        // Hitung Laba Rugi Tahun Berjalan
        $awalTahun = date('Y-01-01', strtotime($tanggal));
        $pendapatan = $this->getDataByTipeAkun('Pendapatan', $awalTahun, $tanggal);
        $totalPendapatan = $pendapatan->sum('saldo');
        
        $beban = $this->getDataByTipeAkun('Beban', $awalTahun, $tanggal);
        $totalBeban = $beban->sum('saldo');
        
        $labaRugiTahunBerjalan = $totalPendapatan - $totalBeban;

        // Total Liabilitas + Ekuitas
        $totalLiabilitasEkuitas = $totalLiabilitas + $totalEkuitas + $labaRugiTahunBerjalan;

        $pdf = Pdf::loadView('laporan.export.neraca-pdf', compact('aset', 'liabilitas', 'ekuitas', 'totalAset', 'totalLiabilitas', 'totalEkuitas', 'labaRugiTahunBerjalan', 'totalLiabilitasEkuitas', 'tanggal'));
        return $pdf->download('Laporan_Neraca_' . date('Y-m-d', strtotime($tanggal)) . '.pdf');
    }

    /**
     * Laporan Arus Kas
     */
    public function arusKas(Request $request)
    {
        // Check feature access
        $user = $this->currentUser();
        if (!$user->hasFeature('laporan_arus_kas')) {
            return redirect()->route('dashboard')
                ->with('error', 'Laporan Arus Kas hanya tersedia untuk plan Professional/Enterprise');
        }
        
        $periodeQuery = Periode::query();
        $periodeQuery = $this->scopeUser($periodeQuery);
        $periodeQuery = $this->scopeFreeAccount($periodeQuery); // Free account hanya tahun 2024
        $periodes = $periodeQuery->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        if (!$request->has('tanggal_mulai') || !$request->has('tanggal_selesai')) {
            return view('laporan.arus-kas', compact('periodes'));
        }

        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;

        // Aktivitas Operasi: Pendapatan dan Beban
        $pendapatan = $this->getDataByTipeAkun('Pendapatan', $tanggalMulai, $tanggalSelesai);
        $totalPendapatan = $pendapatan->sum('saldo');
        
        $beban = $this->getDataByTipeAkun('Beban', $tanggalMulai, $tanggalSelesai);
        $totalBeban = $beban->sum('saldo');
        
        // Kas dari operasi (pendapatan - beban)
        $kasDariOperasi = $totalPendapatan - $totalBeban;

        // Aktivitas Investasi: Perubahan aset tetap, investasi, dll
        // Untuk sederhana, kita ambil perubahan aset tetap dan investasi
        $asetTetap = $this->getDataByTipeAkun('Aset', $tanggalMulai, $tanggalSelesai)
            ->filter(function($item) {
                // Filter untuk aset tetap (biasanya kode akun 1-1xxx untuk aset tetap)
                return strpos($item->kode_akun, '1-1') === 0 && 
                       (strpos(strtolower($item->nama_akun), 'tanah') !== false ||
                        strpos(strtolower($item->nama_akun), 'bangunan') !== false ||
                        strpos(strtolower($item->nama_akun), 'peralatan') !== false ||
                        strpos(strtolower($item->nama_akun), 'kendaraan') !== false ||
                        strpos(strtolower($item->nama_akun), 'mesin') !== false);
            });
        $totalInvestasi = -$asetTetap->sum('saldo'); // Negatif karena pembelian aset mengurangi kas

        // Aktivitas Pendanaan: Perubahan ekuitas dan liabilitas jangka panjang
        $ekuitas = $this->getDataByTipeAkun('Ekuitas', $tanggalMulai, $tanggalSelesai);
        $totalEkuitas = $ekuitas->sum('saldo');
        
        $liabilitas = $this->getDataByTipeAkun('Liabilitas', $tanggalMulai, $tanggalSelesai);
        $totalLiabilitas = $liabilitas->sum('saldo');
        
        // Kas dari pendanaan (penambahan ekuitas/utang)
        $kasDariPendanaan = $totalEkuitas + $totalLiabilitas;

        // Saldo kas awal periode (hanya milik user)
        $kasAwal = $this->getSaldoKas($tanggalMulai, $user);
        
        // Saldo kas akhir periode (hanya milik user)
        $kasAkhir = $this->getSaldoKas($tanggalSelesai, $user);
        
        // Perubahan kas bersih
        $perubahanKas = $kasDariOperasi + $totalInvestasi + $kasDariPendanaan;

        return view('laporan.arus-kas', compact(
            'periodes', 
            'tanggalMulai', 
            'tanggalSelesai',
            'pendapatan',
            'beban',
            'totalPendapatan',
            'totalBeban',
            'kasDariOperasi',
            'asetTetap',
            'totalInvestasi',
            'ekuitas',
            'liabilitas',
            'totalEkuitas',
            'totalLiabilitas',
            'kasDariPendanaan',
            'kasAwal',
            'kasAkhir',
            'perubahanKas'
        ));
    }

    /**
     * Helper: Ambil saldo kas pada tanggal tertentu
     */
    private function getSaldoKas($tanggal, $user = null)
    {
        if (!$user) {
            $user = $this->currentUser();
        }
        
        // Cari akun kas (biasanya kode akun 1-1001 atau mengandung "kas")
        $kasCoa = Coa::where('is_active', true)
            ->where(function($query) {
                $query->where('kode_akun', 'like', '1-1001%')
                      ->orWhere('nama_akun', 'like', '%kas%')
                      ->orWhere('nama_akun', 'like', '%cash%');
            })
            ->first();

        if (!$kasCoa) {
            return 0;
        }

        $data = JurnalDetail::where('coa_id', $kasCoa->id)
            ->whereHas('jurnalHeader', function($query) use ($tanggal, $user) {
                $query->where('status', 'Posted')
                      ->where('tanggal_transaksi', '<=', $tanggal);
                if (!$user->is_owner) {
                    $query->where('user_id', $user->id);
                }
            })
            ->select(
                DB::raw('SUM(CASE WHEN posisi = "Debit" THEN jumlah ELSE 0 END) as total_debit'),
                DB::raw('SUM(CASE WHEN posisi = "Kredit" THEN jumlah ELSE 0 END) as total_kredit')
            )
            ->first();

        $totalDebit = $data->total_debit ?? 0;
        $totalKredit = $data->total_kredit ?? 0;

        return $totalDebit - $totalKredit; // Kas selalu debit
    }
}

