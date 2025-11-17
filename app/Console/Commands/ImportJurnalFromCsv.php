<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JurnalHeader;
use App\Models\JurnalDetail;
use App\Models\Coa;
use App\Models\Periode;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportJurnalFromCsv extends Command
{
    protected $signature = 'jurnal:import-csv {file} {--user-id=1} {--dry-run}';
    protected $description = 'Import jurnal dari file CSV';

    // Mapping jenis transaksi ke COA
    private $coaMapping = [
        // Pendapatan
        'Penjualan Barang' => '4-1002', // Pendapatan Penjualan
        'Pejualan Jasa' => '4-1001', // Pendapatan Jasa
        'Penjualan Jasa' => '4-1001', // Pendapatan Jasa
        
        // Beban
        'Belanja' => '5-1001', // HPP (untuk bahan baku)
        'Trasportasi' => '5-1009', // Beban Lain-lain
        'Perbaikan' => '5-1009', // Beban Lain-lain
        'Kantor' => '5-1008', // Beban Administrasi
        'Hadiah' => '5-1009', // Beban Lain-lain
    ];

    // COA Kas default
    private $kasCoa = '1-1001'; // Kas

    public function handle()
    {
        $filePath = $this->argument('file');
        $userId = $this->option('user-id');
        $dryRun = $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        $this->info("Membaca file CSV: {$filePath}");
        
        // Baca file CSV
        $rows = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $header = fgetcsv($handle); // Skip header
            $rowNum = 1;
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                $rowNum++;
                
                if (count($data) < 7) {
                    $this->warn("Baris {$rowNum} tidak lengkap, dilewati");
                    continue;
                }

                $rows[] = [
                    'timestamp' => $data[0],
                    'tanggal' => $data[1],
                    'tipe' => $data[2], // Pemasukan/Pengeluaran
                    'jenis' => $data[3],
                    'deskripsi' => $data[4],
                    'debit' => floatval(str_replace(',', '', $data[5])),
                    'kredit' => floatval(str_replace(',', '', $data[6])),
                ];
            }
            fclose($handle);
        }

        $this->info("Ditemukan " . count($rows) . " transaksi");

        if ($dryRun) {
            $this->warn("DRY RUN MODE - Tidak ada data yang akan disimpan");
        }

        // Validasi COA mapping
        $this->validateCoaMapping();

        $successCount = 0;
        $errorCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                try {
                    // Parse tanggal
                    $tanggal = $this->parseDate($row['tanggal']);
                    if (!$tanggal) {
                        $this->warn("Baris " . ($index + 2) . ": Tanggal tidak valid: {$row['tanggal']}");
                        $skippedCount++;
                        continue;
                    }

                    // Cari atau buat periode
                    $periode = $this->getOrCreatePeriode($tanggal);

                    // Tentukan akun berdasarkan jenis transaksi
                    $coaCode = $this->getCoaCode($row['jenis'], $row['tipe']);
                    $coa = Coa::where('kode_akun', $coaCode)->first();
                    
                    if (!$coa) {
                        $this->error("Baris " . ($index + 2) . ": COA tidak ditemukan: {$coaCode}");
                        $errorCount++;
                        continue;
                    }

                    // Tentukan jumlah
                    $jumlah = $row['debit'] > 0 ? $row['debit'] : $row['kredit'];
                    
                    if ($jumlah <= 0) {
                        $this->warn("Baris " . ($index + 2) . ": Jumlah 0, dilewati");
                        $skippedCount++;
                        continue;
                    }

                    // Buat jurnal entry
                    if (!$dryRun) {
                        $jurnal = $this->createJurnal($row, $tanggal, $periode, $coa, $jumlah, $userId);
                        if ($jurnal) {
                            $successCount++;
                        } else {
                            $errorCount++;
                        }
                    } else {
                        $this->line("  → {$row['tanggal']} | {$row['tipe']} | {$row['jenis']} | {$row['deskripsi']} | Rp " . number_format($jumlah, 0, ',', '.'));
                        $successCount++;
                    }
                } catch (\Exception $e) {
                    $this->error("Baris " . ($index + 2) . ": Error - " . $e->getMessage());
                    $errorCount++;
                }
            }

            if ($dryRun) {
                DB::rollBack();
                $this->info("\n=== RINGKASAN DRY RUN ===");
                $this->info("Berhasil: {$successCount}");
                $this->info("Error: {$errorCount}");
                $this->info("Dilewati: {$skippedCount}");
            } else {
                DB::commit();
                $this->info("\n=== IMPORT SELESAI ===");
                $this->info("Berhasil: {$successCount}");
                $this->info("Error: {$errorCount}");
                $this->info("Dilewati: {$skippedCount}");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error saat import: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function parseDate($dateString)
    {
        // Format: M/D/Y atau M-D-Y
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

        $periode = Periode::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        if (!$periode) {
            $start = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            
            $periode = Periode::create([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'status' => 'Open',
                'tanggal_mulai' => $start->toDateString(),
                'tanggal_selesai' => $end->toDateString(),
            ]);
            
            $this->info("Periode baru dibuat: {$tahun}-" . str_pad($bulan, 2, '0', STR_PAD_LEFT));
        }

        return $periode;
    }

    private function getCoaCode($jenis, $tipe)
    {
        // Jika Pemasukan, gunakan mapping pendapatan
        if (stripos($tipe, 'Pemasukan') !== false) {
            foreach ($this->coaMapping as $key => $code) {
                if (stripos($jenis, $key) !== false) {
                    return $code;
                }
            }
            return '4-1003'; // Pendapatan Lain-lain (default)
        }
        
        // Jika Pengeluaran, gunakan mapping beban
        if (stripos($tipe, 'Pengeluaran') !== false) {
            foreach ($this->coaMapping as $key => $code) {
                if (stripos($jenis, $key) !== false) {
                    return $code;
                }
            }
            return '5-1009'; // Beban Lain-lain (default)
        }

        return '5-1009'; // Default
    }

    private function createJurnal($row, $tanggal, $periode, $coa, $jumlah, $userId)
    {
        // Generate nomor bukti - cari nomor terakhir untuk bulan ini
        $date = Carbon::parse($tanggal);
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
            $this->warn("  Jurnal sudah ada: {$noBukti} - {$row['deskripsi']}");
            return null;
        }

        // Get Kas COA
        $kasCoa = Coa::where('kode_akun', $this->kasCoa)->first();
        if (!$kasCoa) {
            throw new \Exception("COA Kas tidak ditemukan");
        }

        // Tentukan posisi debit/kredit
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

    private function validateCoaMapping()
    {
        $this->info("Validasi COA mapping...");
        $allCodes = array_values($this->coaMapping);
        $allCodes[] = $this->kasCoa;
        
        foreach ($allCodes as $code) {
            $coa = Coa::where('kode_akun', $code)->first();
            if (!$coa) {
                $this->error("COA tidak ditemukan: {$code}");
            }
        }
    }
}

