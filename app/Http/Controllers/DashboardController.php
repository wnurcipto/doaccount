<?php

namespace App\Http\Controllers;

use App\Models\JurnalHeader;
use App\Models\Coa;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = $this->currentUser();
        
        // Dapatkan periode tahun berjalan (tahun saat ini)
        // Free account hanya bisa lihat tahun 2024
        // Owner bisa melihat semua periode, non-owner hanya periode miliknya
        $tahunBerjalan = $this->isFreeAccount() ? 2024 : Carbon::now()->year;
        $periodeQuery = Periode::where('tahun', $tahunBerjalan);
        
        // Owner bisa melihat semua periode, non-owner hanya periode miliknya
        if (!$user->is_owner) {
            $periodeQuery = $periodeQuery->where('user_id', $user->id);
        }
        
        $periodeTahunBerjalan = $periodeQuery->pluck('id')->toArray();
        
        // Query dasar untuk jurnal periode tahun berjalan
        // Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
        $jurnalQuery = JurnalHeader::whereIn('periode_id', $periodeTahunBerjalan);
        
        // Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
        if (!$user->is_owner) {
            $jurnalQuery = $jurnalQuery->where('user_id', $user->id);
        }
        
        $jurnalTahunBerjalan = $jurnalQuery;
        
        // Statistik Umum (hanya periode tahun berjalan)
        // Untuk free account, pastikan hanya menghitung transaksi tahun 2024
        $totalTransaksiQuery = (clone $jurnalTahunBerjalan)->where('status', 'Posted');
        if ($this->isFreeAccount()) {
            $totalTransaksiQuery = $totalTransaksiQuery->whereYear('tanggal_transaksi', 2024);
        }
        
        $stats = [
            'total_jurnal' => (clone $jurnalTahunBerjalan)->count(),
            'jurnal_posted' => (clone $jurnalTahunBerjalan)->where('status', 'Posted')->count(),
            'jurnal_draft' => (clone $jurnalTahunBerjalan)->where('status', 'Draft')->count(),
            'total_coa' => Coa::where('is_active', true)->count(), // COA global
            'periode_aktif' => (clone $periodeQuery)->where('status', 'Open')->count(),
            'total_periode' => (clone $periodeQuery)->count(),
            'total_transaksi' => $totalTransaksiQuery->sum('total_debit'),
        ];

        // Data untuk grafik jurnal per bulan (6 bulan terakhir) - hanya periode tahun berjalan milik user
        // Untuk free account, ambil 6 bulan terakhir dari tahun 2024 (Jul-Des 2024)
        // Untuk user lain, ambil 6 bulan terakhir dari tanggal saat ini
        if ($this->isFreeAccount()) {
            // Free account: 6 bulan terakhir tahun 2024 (Jul-Des)
            $tanggalMulai = Carbon::create(2024, 7, 1)->startOfMonth();
            $tanggalAkhir = Carbon::create(2024, 12, 31)->endOfMonth();
            
            $jurnalPerBulanQuery = JurnalHeader::whereIn('periode_id', $periodeTahunBerjalan)
                ->where('status', 'Posted')
                ->where('tanggal_transaksi', '>=', $tanggalMulai)
                ->where('tanggal_transaksi', '<=', $tanggalAkhir);
            
            // Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
            if (!$user->is_owner) {
                $jurnalPerBulanQuery = $jurnalPerBulanQuery->where('user_id', $user->id);
            }
            $jurnalPerBulan = $jurnalPerBulanQuery
                ->select(
                    DB::raw('DATE_FORMAT(tanggal_transaksi, "%Y-%m") as bulan'),
                    DB::raw('COUNT(*) as jumlah'),
                    DB::raw('SUM(total_debit) as total')
                )
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            $labelsJurnal = [];
            $dataJurnal = [];
            $totalJurnal = [];
            
            // 6 bulan terakhir tahun 2024: Jul, Agu, Sep, Okt, Nov, Des
            $bulanLabels = ['Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($bulan = 7; $bulan <= 12; $bulan++) {
                $bulanStr = '2024-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
                $label = $bulanLabels[$bulan - 7];
                $labelsJurnal[] = $label . ' 2024';
                
                $data = $jurnalPerBulan->firstWhere('bulan', $bulanStr);
                $dataJurnal[] = $data ? $data->jumlah : 0;
                $totalJurnal[] = $data ? (float)$data->total : 0;
            }
        } else {
            // User biasa: 6 bulan terakhir dari tanggal saat ini
            $jurnalPerBulanQuery = JurnalHeader::whereIn('periode_id', $periodeTahunBerjalan)
                ->where('status', 'Posted')
                ->where('tanggal_transaksi', '>=', Carbon::now()->subMonths(5)->startOfMonth());
            
            // Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
            if (!$user->is_owner) {
                $jurnalPerBulanQuery = $jurnalPerBulanQuery->where('user_id', $user->id);
            }
            $jurnalPerBulan = $jurnalPerBulanQuery
                ->select(
                    DB::raw('DATE_FORMAT(tanggal_transaksi, "%Y-%m") as bulan'),
                    DB::raw('COUNT(*) as jumlah'),
                    DB::raw('SUM(total_debit) as total')
                )
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            $labelsJurnal = [];
            $dataJurnal = [];
            $totalJurnal = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $bulan = Carbon::now()->subMonths($i)->format('Y-m');
                $label = Carbon::now()->subMonths($i)->format('M Y');
                $labelsJurnal[] = $label;
                
                $data = $jurnalPerBulan->firstWhere('bulan', $bulan);
                $dataJurnal[] = $data ? $data->jumlah : 0;
                $totalJurnal[] = $data ? (float)$data->total : 0;
            }
        }

        // Data untuk grafik status jurnal - hanya periode tahun berjalan
        $statusJurnal = [
            'Posted' => (clone $jurnalTahunBerjalan)->where('status', 'Posted')->count(),
            'Draft' => (clone $jurnalTahunBerjalan)->where('status', 'Draft')->count(),
            'Void' => (clone $jurnalTahunBerjalan)->where('status', 'Void')->count(),
        ];

        // Jurnal terbaru - hanya periode tahun berjalan
        // Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
        $jurnalTerbaruQuery = JurnalHeader::with(['periode', 'user'])
            ->whereIn('periode_id', $periodeTahunBerjalan);
        
        if (!$user->is_owner) {
            $jurnalTerbaruQuery = $jurnalTerbaruQuery->where('user_id', $user->id);
        }
        $jurnalTerbaru = $jurnalTerbaruQuery
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(5)
            ->get();

        // Data untuk grafik pendapatan per bulan (Jan-Dec) tahun berjalan
        // Free account tidak ada tahun sebelumnya (hanya 2024)
        $tahunSebelumnya = $this->isFreeAccount() ? 2023 : ($tahunBerjalan - 1);
        $periodeTahunSebelumnyaQuery = Periode::where('tahun', $tahunSebelumnya);
        
        // Owner bisa melihat semua periode, non-owner hanya periode miliknya
        if (!$user->is_owner) {
            $periodeTahunSebelumnyaQuery = $periodeTahunSebelumnyaQuery->where('user_id', $user->id);
        }
        
        $periodeTahunSebelumnya = $periodeTahunSebelumnyaQuery->pluck('id')->toArray();
        
        // Pendapatan per bulan tahun berjalan (Jan-Dec) milik user
        // Menggunakan filter tanggal eksplisit untuk memastikan semua bulan tahun 2024 ter-cover
        // Pastikan format tanggal menggunakan string 'Y-m-d' untuk kompatibilitas database
        $tanggalMulai = $tahunBerjalan . '-01-01';
        $tanggalAkhir = $tahunBerjalan . '-12-31';
        
        $pendapatanPerBulanQuery = DB::table('jurnal_details')
            ->join('coas', 'jurnal_details.coa_id', '=', 'coas.id')
            ->join('jurnal_headers', 'jurnal_details.jurnal_header_id', '=', 'jurnal_headers.id')
            ->where('coas.tipe_akun', 'Pendapatan')
            ->where('jurnal_details.posisi', 'Kredit')
            ->where('jurnal_headers.status', 'Posted')
            ->where('jurnal_headers.tanggal_transaksi', '>=', $tanggalMulai)
            ->where('jurnal_headers.tanggal_transaksi', '<=', $tanggalAkhir);
        
        if (!$user->is_owner) {
            $pendapatanPerBulanQuery = $pendapatanPerBulanQuery->where('jurnal_headers.user_id', $user->id);
        }
        
        $pendapatanPerBulan = $pendapatanPerBulanQuery
            ->select(
                DB::raw('MONTH(jurnal_headers.tanggal_transaksi) as bulan'),
                DB::raw('SUM(jurnal_details.jumlah) as total_pendapatan')
            )
            ->groupBy(DB::raw('MONTH(jurnal_headers.tanggal_transaksi)'))
            ->orderBy(DB::raw('MONTH(jurnal_headers.tanggal_transaksi)'))
            ->get();

        // Baseline: Hitung rata-rata pendapatan per bulan dari tahun sebelumnya
        // Untuk free account, gunakan nilai tetap 40.000.000
        // Untuk akun lain, hitung dari data aktual tahun sebelumnya
        if ($this->isFreeAccount()) {
            // Free account: gunakan nilai tetap
            $baseline = 40000000;
        } else {
            // Akun non-free: hitung rata-rata pendapatan per bulan tahun sebelumnya
            $tanggalMulaiTahunSebelumnya = $tahunSebelumnya . '-01-01';
            $tanggalAkhirTahunSebelumnya = $tahunSebelumnya . '-12-31';
            
            // Query untuk menghitung total pendapatan tahun sebelumnya
            $baselineQuery = DB::table('jurnal_details')
                ->join('coas', 'jurnal_details.coa_id', '=', 'coas.id')
                ->join('jurnal_headers', 'jurnal_details.jurnal_header_id', '=', 'jurnal_headers.id')
                ->where('coas.tipe_akun', 'Pendapatan')
                ->where('jurnal_details.posisi', 'Kredit')
                ->where('jurnal_headers.status', 'Posted')
                ->where('jurnal_headers.tanggal_transaksi', '>=', $tanggalMulaiTahunSebelumnya)
                ->where('jurnal_headers.tanggal_transaksi', '<=', $tanggalAkhirTahunSebelumnya);
            
            // Filter berdasarkan user_id jika bukan owner
            if (!$user->is_owner) {
                $baselineQuery = $baselineQuery->where('jurnal_headers.user_id', $user->id);
            }
            
            // Hitung total pendapatan tahun sebelumnya
            $totalPendapatanTahunSebelumnya = $baselineQuery->sum('jurnal_details.jumlah');
            
            // Baseline = rata-rata pendapatan per bulan tahun sebelumnya (total / 12)
            // Jika tidak ada data tahun sebelumnya, gunakan default 40.000.000
            if ($totalPendapatanTahunSebelumnya && $totalPendapatanTahunSebelumnya > 0) {
                $baseline = $totalPendapatanTahunSebelumnya / 12;
            } else {
                // Tidak ada data tahun sebelumnya, gunakan default
                $baseline = 40000000;
            }
        }

        // Siapkan data pendapatan untuk semua bulan (Jan-Dec)
        $labelsPendapatan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataPendapatan = [];
        $baselineData = [];
        
        // Buat array keyed by bulan untuk memudahkan pencarian
        // Pastikan bulan di-cast sebagai integer untuk matching yang benar
        $pendapatanByBulan = [];
        foreach ($pendapatanPerBulan as $item) {
            // Cast bulan sebagai integer untuk memastikan matching yang benar
            $bulanKey = (int)$item->bulan;
            $pendapatanByBulan[$bulanKey] = (float)$item->total_pendapatan;
        }
        
        // Pastikan semua bulan (1-12) ada di array, termasuk November (11) dan Desember (12)
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            // Jika bulan tidak ada di hasil query, set ke 0
            $dataPendapatan[] = isset($pendapatanByBulan[$bulan]) ? $pendapatanByBulan[$bulan] : 0;
            $baselineData[] = $baseline;
        }
        
        // Debug: Pastikan November dan Desember ada di data
        // \Log::info('Pendapatan per bulan:', [
        //     'raw_data' => $pendapatanPerBulan->toArray(),
        //     'by_bulan' => $pendapatanByBulan,
        //     'nov_index' => $dataPendapatan[10] ?? 'NOT SET',
        //     'des_index' => $dataPendapatan[11] ?? 'NOT SET',
        //     'all_data' => $dataPendapatan
        // ]);

        // Total Pendapatan tahun berjalan
        $totalPendapatanQuery = DB::table('jurnal_details')
            ->join('coas', 'jurnal_details.coa_id', '=', 'coas.id')
            ->join('jurnal_headers', 'jurnal_details.jurnal_header_id', '=', 'jurnal_headers.id')
            ->join('periodes', 'jurnal_headers.periode_id', '=', 'periodes.id')
            ->where('coas.tipe_akun', 'Pendapatan')
            ->where('jurnal_details.posisi', 'Kredit')
            ->where('jurnal_headers.status', 'Posted')
            ->where('periodes.tahun', $tahunBerjalan);
        
        if (!$user->is_owner) {
            $totalPendapatanQuery = $totalPendapatanQuery->where('jurnal_headers.user_id', $user->id);
        }
        
        $totalPendapatan = $totalPendapatanQuery->sum('jurnal_details.jumlah');

        // Total Pengeluaran (Beban) tahun berjalan
        $totalPengeluaranQuery = DB::table('jurnal_details')
            ->join('coas', 'jurnal_details.coa_id', '=', 'coas.id')
            ->join('jurnal_headers', 'jurnal_details.jurnal_header_id', '=', 'jurnal_headers.id')
            ->join('periodes', 'jurnal_headers.periode_id', '=', 'periodes.id')
            ->where('coas.tipe_akun', 'Beban')
            ->where('jurnal_details.posisi', 'Debit')
            ->where('jurnal_headers.status', 'Posted')
            ->where('periodes.tahun', $tahunBerjalan);
        
        if (!$user->is_owner) {
            $totalPengeluaranQuery = $totalPengeluaranQuery->where('jurnal_headers.user_id', $user->id);
        }
        
        $totalPengeluaran = $totalPengeluaranQuery->sum('jurnal_details.jumlah');

        // Laba/Rugi tahun berjalan
        $labaRugi = $totalPendapatan - $totalPengeluaran;

        // Top 5 Transaksi per Tipe Akun - hanya periode tahun berjalan milik user
        $top5TransaksiQuery = DB::table('jurnal_details')
            ->join('coas', 'jurnal_details.coa_id', '=', 'coas.id')
            ->join('jurnal_headers', 'jurnal_details.jurnal_header_id', '=', 'jurnal_headers.id')
            ->where('jurnal_headers.status', 'Posted')
            ->whereIn('jurnal_headers.periode_id', $periodeTahunBerjalan);
        
        if (!$user->is_owner) {
            $top5TransaksiQuery = $top5TransaksiQuery->where('jurnal_headers.user_id', $user->id);
        }
        
        if ($this->isFreeAccount()) {
            $top5TransaksiQuery = $top5TransaksiQuery->whereYear('jurnal_headers.tanggal_transaksi', 2024);
        }
        
        $top5Transaksi = $top5TransaksiQuery
            ->select(
                'coas.tipe_akun',
                DB::raw('SUM(CASE WHEN jurnal_details.posisi = "Debit" THEN jurnal_details.jumlah ELSE 0 END) as total_debit'),
                DB::raw('SUM(CASE WHEN jurnal_details.posisi = "Kredit" THEN jurnal_details.jumlah ELSE 0 END) as total_kredit'),
                DB::raw('SUM(jurnal_details.jumlah) as total_transaksi')
            )
            ->groupBy('coas.tipe_akun')
            ->orderByDesc(DB::raw('SUM(jurnal_details.jumlah)'))
            ->limit(5)
            ->get();

        // Siapkan data untuk chart Top 5 Transaksi
        $labelsTop5Transaksi = $top5Transaksi->pluck('tipe_akun')->toArray();
        $dataTop5Transaksi = $top5Transaksi->pluck('total_transaksi')->map(function($value) {
            return (float)$value;
        })->toArray();

        return view('dashboard', compact(
            'stats',
            'labelsJurnal',
            'dataJurnal',
            'totalJurnal',
            'statusJurnal',
            'jurnalTerbaru',
            'labelsPendapatan',
            'dataPendapatan',
            'baselineData',
            'tahunBerjalan',
            'tahunSebelumnya',
            'totalPendapatan',
            'totalPengeluaran',
            'labaRugi',
            'top5Transaksi',
            'labelsTop5Transaksi',
            'dataTop5Transaksi'
        ));
    }
}
