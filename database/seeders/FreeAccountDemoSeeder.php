<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\JurnalDetail;
use App\Models\JurnalHeader;
use App\Models\Periode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FreeAccountDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat akun FREE dengan data demo untuk trial
     */
    public function run(): void
    {
        // Buat user FREE jika belum ada
        $freeUser = User::firstOrCreate(
            ['email' => 'demo@free.com'],
            [
                'name' => 'Demo Free Account',
                'email' => 'demo@free.com',
                'password' => Hash::make('demo123'),
                'email_verified_at' => now(),
                'plan' => 'free',
                'is_owner' => false,
            ]
        );

        // Pastikan plan adalah 'free'
        $freeUser->update(['plan' => 'free']);

        $now = Carbon::now();
        $tahun = (int) $now->format('Y');

        // Buat 3 periode untuk user FREE (sesuai limit free plan)
        $periodes = [];
        for ($m = 1; $m <= 3; $m++) {
            $start = Carbon::create($tahun, $m, 1)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            
            $periode = Periode::firstOrCreate(
                [
                    'tahun' => $tahun,
                    'bulan' => $m,
                    'user_id' => $freeUser->id,
                ],
                [
                    'status' => $m === (int) $now->format('m') ? 'Open' : 'Closed',
                    'tanggal_mulai' => $start->toDateString(),
                    'tanggal_selesai' => $end->toDateString(),
                ]
            );
            $periodes[] = $periode;
        }

        // Buat beberapa barang demo untuk user FREE
        $barangs = [
            [
                'kode_barang' => 'DEMO-001',
                'nama_barang' => 'Produk Demo A',
                'kategori' => 'Kategori 1',
                'satuan' => 'PCS',
                'harga_beli' => 100000,
                'harga_jual' => 150000,
                'stok' => 10,
                'stok_minimal' => 5,
                'keterangan' => 'Produk demo untuk trial',
                'is_active' => true,
                'user_id' => $freeUser->id,
            ],
            [
                'kode_barang' => 'DEMO-002',
                'nama_barang' => 'Produk Demo B',
                'kategori' => 'Kategori 2',
                'satuan' => 'UNIT',
                'harga_beli' => 200000,
                'harga_jual' => 300000,
                'stok' => 5,
                'stok_minimal' => 3,
                'keterangan' => 'Produk demo untuk trial',
                'is_active' => true,
                'user_id' => $freeUser->id,
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::firstOrCreate(
                [
                    'kode_barang' => $barang['kode_barang'],
                    'user_id' => $freeUser->id,
                ],
                $barang
            );
        }

        // Buat beberapa jurnal demo untuk user FREE
        $periodeAktif = $periodes[2] ?? $periodes[0]; // Gunakan periode terakhir atau pertama

        $jurnals = [
            [
                'no_bukti' => 'JRL-' . date('Ymd') . '-001',
                'tanggal_transaksi' => now()->subDays(5),
                'periode_id' => $periodeAktif->id,
                'deskripsi' => 'Jurnal Demo - Pembayaran Beban Operasional',
                'total_debit' => 5000000,
                'total_kredit' => 5000000,
                'status' => 'Posted',
                'user_id' => $freeUser->id,
            ],
            [
                'no_bukti' => 'JRL-' . date('Ymd') . '-002',
                'tanggal_transaksi' => now()->subDays(3),
                'periode_id' => $periodeAktif->id,
                'deskripsi' => 'Jurnal Demo - Penerimaan Pendapatan',
                'total_debit' => 10000000,
                'total_kredit' => 10000000,
                'status' => 'Posted',
                'user_id' => $freeUser->id,
            ],
        ];

        foreach ($jurnals as $jurnalData) {
            $jurnal = JurnalHeader::firstOrCreate(
                [
                    'no_bukti' => $jurnalData['no_bukti'],
                    'user_id' => $freeUser->id,
                ],
                $jurnalData
            );

            // Buat detail jurnal untuk jurnal pertama
            if ($jurnal->wasRecentlyCreated && $jurnal->no_bukti === $jurnals[0]['no_bukti']) {
                // Jurnal 1: Beban Gaji
                JurnalDetail::create([
                    'jurnal_header_id' => $jurnal->id,
                    'coa_id' => 1, // Asumsi COA ID 1 adalah Kas (COA global)
                    'posisi' => 'Debit',
                    'jumlah' => 3000000,
                    'keterangan' => 'Pembayaran gaji karyawan',
                ]);
                JurnalDetail::create([
                    'jurnal_header_id' => $jurnal->id,
                    'coa_id' => 2, // Asumsi COA ID 2 adalah Beban Gaji
                    'posisi' => 'Kredit',
                    'jumlah' => 3000000,
                    'keterangan' => 'Beban gaji',
                ]);
                JurnalDetail::create([
                    'jurnal_header_id' => $jurnal->id,
                    'coa_id' => 1,
                    'posisi' => 'Debit',
                    'jumlah' => 2000000,
                    'keterangan' => 'Pembayaran beban sewa',
                ]);
                JurnalDetail::create([
                    'jurnal_header_id' => $jurnal->id,
                    'coa_id' => 3, // Asumsi COA ID 3 adalah Beban Sewa
                    'posisi' => 'Kredit',
                    'jumlah' => 2000000,
                    'keterangan' => 'Beban sewa',
                ]);
            }

            // Buat detail jurnal untuk jurnal kedua
            if ($jurnal->wasRecentlyCreated && $jurnal->no_bukti === $jurnals[1]['no_bukti']) {
                // Jurnal 2: Pendapatan
                JurnalDetail::create([
                    'jurnal_header_id' => $jurnal->id,
                    'coa_id' => 1, // Kas
                    'posisi' => 'Debit',
                    'jumlah' => 10000000,
                    'keterangan' => 'Penerimaan pendapatan jasa',
                ]);
                JurnalDetail::create([
                    'jurnal_header_id' => $jurnal->id,
                    'coa_id' => 4, // Asumsi COA ID 4 adalah Pendapatan Jasa
                    'posisi' => 'Kredit',
                    'jumlah' => 10000000,
                    'keterangan' => 'Pendapatan jasa',
                ]);
            }
        }

        $this->command->info("✅ Demo data untuk akun FREE berhasil dibuat!");
        $this->command->info("   Email: demo@free.com");
        $this->command->info("   Password: demo123");
    }
}

