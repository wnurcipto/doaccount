<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Periode;
use App\Models\Barang;
use App\Models\Invoice;
use App\Models\Offering;
use App\Models\SuratJalan;
use App\Models\CompanyInfo;
use App\Models\JurnalHeader;
use App\Models\StokMasuk;
use App\Models\StokKeluar;

class UpdateExistingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari user owner
        $owner = User::where('is_owner', true)->first();
        
        if (!$owner) {
            $this->command->error('No owner user found! Please run UserSeeder first.');
            return;
        }
        
        $this->command->info("Updating existing data to owner: {$owner->email} (ID: {$owner->id})");
        
        // Update periodes
        $count = Periode::whereNull('user_id')->count();
        if ($count > 0) {
            Periode::whereNull('user_id')->update(['user_id' => $owner->id]);
            $this->command->info("Updated {$count} periodes");
        }
        
        // Update barangs
        $count = Barang::whereNull('user_id')->count();
        if ($count > 0) {
            Barang::whereNull('user_id')->update(['user_id' => $owner->id]);
            $this->command->info("Updated {$count} barangs");
        }
        
        // Update invoices
        $count = Invoice::whereNull('user_id')->count();
        if ($count > 0) {
            Invoice::whereNull('user_id')->update(['user_id' => $owner->id]);
            $this->command->info("Updated {$count} invoices");
        }
        
        // Update offerings
        $count = Offering::whereNull('user_id')->count();
        if ($count > 0) {
            Offering::whereNull('user_id')->update(['user_id' => $owner->id]);
            $this->command->info("Updated {$count} offerings");
        }
        
        // Update surat_jalans
        $count = SuratJalan::whereNull('user_id')->count();
        if ($count > 0) {
            SuratJalan::whereNull('user_id')->update(['user_id' => $owner->id]);
            $this->command->info("Updated {$count} surat_jalans");
        }
        
        // Update company_infos
        $count = CompanyInfo::whereNull('user_id')->count();
        if ($count > 0) {
            CompanyInfo::whereNull('user_id')->update(['user_id' => $owner->id]);
            $this->command->info("Updated {$count} company_infos");
        }
        
        // Update jurnal_headers (jika ada kolom user_id)
        if (\Schema::hasColumn('jurnal_headers', 'user_id')) {
            $count = JurnalHeader::whereNull('user_id')->count();
            if ($count > 0) {
                JurnalHeader::whereNull('user_id')->update(['user_id' => $owner->id]);
                $this->command->info("Updated {$count} jurnal_headers");
            }
        }
        
        // Update stok_masuks (jika ada kolom user_id)
        if (\Schema::hasColumn('stok_masuks', 'user_id')) {
            $count = StokMasuk::whereNull('user_id')->count();
            if ($count > 0) {
                StokMasuk::whereNull('user_id')->update(['user_id' => $owner->id]);
                $this->command->info("Updated {$count} stok_masuks");
            }
        }
        
        // Update stok_keluars (jika ada kolom user_id)
        if (\Schema::hasColumn('stok_keluars', 'user_id')) {
            $count = StokKeluar::whereNull('user_id')->count();
            if ($count > 0) {
                StokKeluar::whereNull('user_id')->update(['user_id' => $owner->id]);
                $this->command->info("Updated {$count} stok_keluars");
            }
        }
        
        $this->command->info('Done! All existing data has been assigned to owner.');
    }
}

