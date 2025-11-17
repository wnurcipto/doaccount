<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JurnalHeader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixJurnalUserIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Memperbaiki user_id jurnal yang tercampur atau NULL
     */
    public function run(): void
    {
        $this->command->info('🔧 Memperbaiki user_id jurnal yang tercampur...');

        // Ambil user wahid
        $wahid = User::where('email', 'wahid@tpmcmms.id')->first();
        if (!$wahid) {
            $this->command->error('❌ User wahid@tpmcmms.id tidak ditemukan!');
            return;
        }

        // Ambil user demo/free
        $demoUser = User::where('email', 'demo@free.com')->first();
        if (!$demoUser) {
            $this->command->warn('⚠️  User demo@free.com tidak ditemukan, akan dibuat...');
            $demoUser = User::create([
                'name' => 'Demo Free Account',
                'email' => 'demo@free.com',
                'password' => bcrypt('demo123'),
                'email_verified_at' => now(),
                'plan' => 'free',
                'is_owner' => false,
            ]);
        }

        // Ambil user owner lainnya
        $admin = User::where('email', 'admin@ramaadvertize.com')->first();

        // Jurnal milik wahid: ID <= 195
        $jurnalWahid = JurnalHeader::where('id', '<=', 195)
            ->where(function($query) use ($wahid) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', '!=', $wahid->id);
            })
            ->get();

        $updatedWahid = 0;
        foreach ($jurnalWahid as $jurnal) {
            $jurnal->user_id = $wahid->id;
            $jurnal->save();
            $updatedWahid++;
        }

        $this->command->info("✅ Diperbaiki {$updatedWahid} jurnal untuk user wahid@tpmcmms.id (ID <= 195)");

        // Jurnal milik demo: ID > 195 dan tahun 2024
        $jurnalDemo = JurnalHeader::where('id', '>', 195)
            ->whereYear('tanggal_transaksi', 2024)
            ->where(function($query) use ($demoUser) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', '!=', $demoUser->id);
            })
            ->get();

        $updatedDemo = 0;
        foreach ($jurnalDemo as $jurnal) {
            $jurnal->user_id = $demoUser->id;
            $jurnal->save();
            $updatedDemo++;
        }

        $this->command->info("✅ Diperbaiki {$updatedDemo} jurnal untuk user demo@free.com (ID > 195, tahun 2024)");

        // Jurnal yang masih NULL atau tidak jelas kepemilikannya
        // Jika ada jurnal dengan user_id NULL, assign ke owner pertama yang ditemukan
        $jurnalNull = JurnalHeader::whereNull('user_id')->get();
        
        if ($jurnalNull->count() > 0) {
            $owner = $admin ?? $wahid;
            
            $updatedNull = 0;
            foreach ($jurnalNull as $jurnal) {
                // Jika tahun 2024 dan ID > 195, kemungkinan milik demo
                if ($jurnal->id > 195 && date('Y', strtotime($jurnal->tanggal_transaksi)) == 2024) {
                    $jurnal->user_id = $demoUser->id;
                } else {
                    // Default ke owner
                    $jurnal->user_id = $owner->id;
                }
                $jurnal->save();
                $updatedNull++;
            }
            
            $this->command->info("✅ Diperbaiki {$updatedNull} jurnal dengan user_id NULL");
        }

        // Statistik
        $totalJurnal = JurnalHeader::count();
        $jurnalDenganUserId = JurnalHeader::whereNotNull('user_id')->count();
        $jurnalTanpaUserId = JurnalHeader::whereNull('user_id')->count();

        $this->command->info("\n📊 Statistik Jurnal:");
        $this->command->info("   Total Jurnal: {$totalJurnal}");
        $this->command->info("   Dengan user_id: {$jurnalDenganUserId}");
        $this->command->info("   Tanpa user_id: {$jurnalTanpaUserId}");

        // Verifikasi per user
        $this->command->info("\n👥 Jurnal per User:");
        $users = User::all();
        foreach ($users as $user) {
            $count = JurnalHeader::where('user_id', $user->id)->count();
            if ($count > 0) {
                $this->command->info("   {$user->email}: {$count} jurnal");
            }
        }

        $this->command->info("\n✅ Selesai memperbaiki user_id jurnal!");
    }
}

