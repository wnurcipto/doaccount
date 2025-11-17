<?php

namespace Database\Seeders;

use App\Models\Coa;
use App\Models\JurnalDetail;
use App\Models\JurnalHeader;
use App\Models\Periode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FreeAccountJurnal2024Seeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat jurnal demo untuk free account: 5-8 transaksi per bulan di tahun 2024
     */
    public function run(): void
    {
        // Buat atau ambil user FREE
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

        $this->command->info("📝 Membuat jurnal demo untuk user: {$freeUser->email}");

        // Ambil COA yang diperlukan
        $coaKas = Coa::where('kode_akun', 'like', '1-1001%')->orWhere('nama_akun', 'like', '%kas%')->active()->first();
        $coaBank = Coa::where('kode_akun', 'like', '1-1002%')->orWhere('nama_akun', 'like', '%bank%')->active()->first();
        $coaPiutang = Coa::where('kode_akun', 'like', '1-2001%')->orWhere('nama_akun', 'like', '%piutang%')->active()->first();
        $coaUtang = Coa::where('kode_akun', 'like', '2-1001%')->orWhere('nama_akun', 'like', '%utang%')->active()->first();
        $coaPendapatan = Coa::where('tipe_akun', 'Pendapatan')->active()->first();
        $coaBeban = Coa::where('tipe_akun', 'Beban')->active()->first();
        $coaBebanGaji = Coa::where('nama_akun', 'like', '%gaji%')->orWhere('nama_akun', 'like', '%beban gaji%')->active()->first();
        $coaBebanSewa = Coa::where('nama_akun', 'like', '%sewa%')->active()->first();
        $coaBebanListrik = Coa::where('nama_akun', 'like', '%listrik%')->orWhere('nama_akun', 'like', '%utilitas%')->active()->first();
        $coaPersediaan = Coa::where('kode_akun', 'like', '1-3001%')->orWhere('nama_akun', 'like', '%persediaan%')->active()->first();
        $coaHPP = Coa::where('nama_akun', 'like', '%hpp%')->orWhere('nama_akun', 'like', '%harga pokok%')->active()->first();

        // Fallback jika COA tidak ditemukan, ambil COA pertama dari tipe yang sesuai
        if (!$coaKas) $coaKas = Coa::where('tipe_akun', 'Aset')->where('posisi_normal', 'Debit')->active()->first();
        if (!$coaPendapatan) $coaPendapatan = Coa::where('tipe_akun', 'Pendapatan')->active()->first();
        if (!$coaBeban) $coaBeban = Coa::where('tipe_akun', 'Beban')->active()->first();
        if (!$coaUtang) $coaUtang = Coa::where('tipe_akun', 'Liabilitas')->active()->first();

        // Buat periode untuk tahun 2024 (12 bulan)
        $periodes = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $start = Carbon::create(2024, $bulan, 1)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            
            $periode = Periode::firstOrCreate(
                [
                    'tahun' => 2024,
                    'bulan' => $bulan,
                    'user_id' => $freeUser->id,
                ],
                [
                    'status' => 'Closed', // Tahun 2024 sudah lewat
                    'tanggal_mulai' => $start->toDateString(),
                    'tanggal_selesai' => $end->toDateString(),
                ]
            );
            $periodes[] = $periode;
        }

        // Simpan semua COA dalam array untuk digunakan di closure
        $coas = [
            'kas' => $coaKas,
            'bank' => $coaBank,
            'piutang' => $coaPiutang,
            'utang' => $coaUtang,
            'pendapatan' => $coaPendapatan,
            'beban' => $coaBeban,
            'beban_gaji' => $coaBebanGaji ?: $coaBeban,
            'beban_sewa' => $coaBebanSewa ?: $coaBeban,
            'beban_listrik' => $coaBebanListrik ?: $coaBeban,
            'persediaan' => $coaPersediaan ?: $coaBeban,
            'hpp' => $coaHPP,
        ];

        // Template transaksi yang bervariasi
        $transaksiTemplates = [
            // 1. Penerimaan Pendapatan (Kas masuk)
            [
                'deskripsi' => 'Penerimaan Pendapatan Jasa',
                'details' => function($coas) {
                    $jumlah = rand(5000000, 15000000);
                    return [
                        ['coa' => $coas['kas'], 'posisi' => 'Debit', 'jumlah' => $jumlah, 'keterangan' => 'Penerimaan kas dari pendapatan jasa'],
                        ['coa' => $coas['pendapatan'], 'posisi' => 'Kredit', 'jumlah' => $jumlah, 'keterangan' => 'Pendapatan jasa'],
                    ];
                }
            ],
            // 2. Pembayaran Beban Gaji
            [
                'deskripsi' => 'Pembayaran Beban Gaji Karyawan',
                'details' => function($coas) {
                    $jumlah = rand(3000000, 8000000);
                    return [
                        ['coa' => $coas['beban_gaji'], 'posisi' => 'Debit', 'jumlah' => $jumlah, 'keterangan' => 'Beban gaji karyawan'],
                        ['coa' => $coas['kas'], 'posisi' => 'Kredit', 'jumlah' => $jumlah, 'keterangan' => 'Pembayaran gaji'],
                    ];
                }
            ],
            // 3. Pembayaran Beban Sewa
            [
                'deskripsi' => 'Pembayaran Beban Sewa Kantor',
                'details' => function($coas) {
                    $jumlah = rand(2000000, 5000000);
                    return [
                        ['coa' => $coas['beban_sewa'], 'posisi' => 'Debit', 'jumlah' => $jumlah, 'keterangan' => 'Beban sewa kantor'],
                        ['coa' => $coas['kas'], 'posisi' => 'Kredit', 'jumlah' => $jumlah, 'keterangan' => 'Pembayaran sewa'],
                    ];
                }
            ],
            // 4. Pembayaran Beban Listrik
            [
                'deskripsi' => 'Pembayaran Beban Listrik',
                'details' => function($coas) {
                    $jumlah = rand(500000, 2000000);
                    return [
                        ['coa' => $coas['beban_listrik'], 'posisi' => 'Debit', 'jumlah' => $jumlah, 'keterangan' => 'Beban listrik'],
                        ['coa' => $coas['kas'], 'posisi' => 'Kredit', 'jumlah' => $jumlah, 'keterangan' => 'Pembayaran listrik'],
                    ];
                }
            ],
            // 5. Penjualan Kredit (Piutang)
            [
                'deskripsi' => 'Penjualan Jasa Secara Kredit',
                'details' => function($coas) {
                    $jumlah = rand(8000000, 20000000);
                    $coaPiutangFinal = $coas['piutang'] ?: $coas['kas'];
                    return [
                        ['coa' => $coaPiutangFinal, 'posisi' => 'Debit', 'jumlah' => $jumlah, 'keterangan' => 'Piutang dari penjualan'],
                        ['coa' => $coas['pendapatan'], 'posisi' => 'Kredit', 'jumlah' => $jumlah, 'keterangan' => 'Pendapatan jasa'],
                    ];
                }
            ],
            // 6. Pelunasan Piutang
            [
                'deskripsi' => 'Pelunasan Piutang dari Pelanggan',
                'details' => function($coas) {
                    $jumlah = rand(5000000, 15000000);
                    $coaPiutangFinal = $coas['piutang'] ?: $coas['kas'];
                    return [
                        ['coa' => $coas['kas'], 'posisi' => 'Debit', 'jumlah' => $jumlah, 'keterangan' => 'Penerimaan pelunasan piutang'],
                        ['coa' => $coaPiutangFinal, 'posisi' => 'Kredit', 'jumlah' => $jumlah, 'keterangan' => 'Pelunasan piutang'],
                    ];
                }
            ],
            // 7. Pembelian Kredit (Utang)
            [
                'deskripsi' => 'Pembelian Barang Secara Kredit',
                'details' => function($coas) {
                    $jumlah = rand(3000000, 10000000);
                    $coaPersediaanFinal = $coas['persediaan'] ?: $coas['beban'];
                    return [
                        ['coa' => $coaPersediaanFinal, 'posisi' => 'Debit', 'jumlah' => $jumlah, 'keterangan' => 'Pembelian persediaan'],
                        ['coa' => $coas['utang'], 'posisi' => 'Kredit', 'jumlah' => $jumlah, 'keterangan' => 'Utang usaha'],
                    ];
                }
            ],
            // 8. Pembayaran Utang
            [
                'deskripsi' => 'Pembayaran Utang kepada Supplier',
                'details' => function($coas) {
                    $jumlah = rand(2000000, 8000000);
                    return [
                        ['coa' => $coas['utang'], 'posisi' => 'Debit', 'jumlah' => $jumlah, 'keterangan' => 'Pelunasan utang'],
                        ['coa' => $coas['kas'], 'posisi' => 'Kredit', 'jumlah' => $jumlah, 'keterangan' => 'Pembayaran utang'],
                    ];
                }
            ],
        ];

        $totalJurnal = 0;

        // Template yang memiliki pendapatan (untuk memastikan setiap bulan ada pendapatan)
        $pendapatanTemplates = [0, 4]; // Index 0: Penerimaan Pendapatan Jasa, Index 4: Penjualan Jasa Secara Kredit

        // Generate jurnal untuk setiap bulan di tahun 2024
        foreach ($periodes as $index => $periode) {
            $bulan = $index + 1;
            $jumlahTransaksi = rand(5, 8); // 5-8 transaksi per bulan
            
            $this->command->info("  📅 Bulan {$bulan}/2024: {$jumlahTransaksi} transaksi");

            // Pastikan minimal 1 transaksi pendapatan per bulan
            $hasPendapatan = false;
            
            for ($i = 1; $i <= $jumlahTransaksi; $i++) {
                // Jika ini transaksi terakhir dan belum ada pendapatan, paksa pilih template pendapatan
                if ($i === $jumlahTransaksi && !$hasPendapatan) {
                    $templateIndex = $pendapatanTemplates[array_rand($pendapatanTemplates)];
                    $template = $transaksiTemplates[$templateIndex];
                    $hasPendapatan = true;
                } else {
                    // Pilih template transaksi secara random
                    $randomIndex = array_rand($transaksiTemplates);
                    $template = $transaksiTemplates[$randomIndex];
                    // Cek apakah template ini memiliki pendapatan berdasarkan index
                    if (in_array($randomIndex, $pendapatanTemplates)) {
                        $hasPendapatan = true;
                    }
                }
                
                // Generate tanggal random dalam bulan tersebut
                $tanggal = Carbon::create(2024, $bulan, rand(1, 28));
                
                // Generate nomor bukti
                $noBukti = 'JRL-' . $tanggal->format('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                
                // Generate details dari template
                $details = $template['details']($coas);
                
                // Hitung total
                $totalDebit = array_sum(array_column($details, 'jumlah'));
                $totalKredit = array_sum(array_column($details, 'jumlah'));
                
                // Buat jurnal header
                $jurnal = JurnalHeader::firstOrCreate(
                    [
                        'no_bukti' => $noBukti,
                        'user_id' => $freeUser->id,
                    ],
                    [
                        'tanggal_transaksi' => $tanggal->toDateString(),
                        'periode_id' => $periode->id,
                        'deskripsi' => $template['deskripsi'],
                        'total_debit' => $totalDebit,
                        'total_kredit' => $totalKredit,
                        'status' => 'Posted', // Langsung Posted
                        'user_id' => $freeUser->id,
                    ]
                );

                // Buat jurnal details jika jurnal baru dibuat
                if ($jurnal->wasRecentlyCreated) {
                    foreach ($details as $detail) {
                        if ($detail['coa']) {
                            JurnalDetail::create([
                                'jurnal_header_id' => $jurnal->id,
                                'coa_id' => $detail['coa']->id,
                                'posisi' => $detail['posisi'],
                                'jumlah' => $detail['jumlah'],
                                'keterangan' => $detail['keterangan'],
                            ]);
                        }
                    }
                    $totalJurnal++;
                }
            }
        }

        $this->command->info("✅ Selesai! Total {$totalJurnal} jurnal berhasil dibuat untuk tahun 2024");
        $this->command->info("   Email: demo@free.com");
        $this->command->info("   Password: demo123");
    }
}

