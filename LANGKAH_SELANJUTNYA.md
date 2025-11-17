# Langkah-Langkah Selanjutnya - Multi-Tenant Implementation

## ✅ Yang Sudah Selesai
1. ✅ Update semua controller untuk multi-tenancy
2. ✅ Migrations sudah dibuat untuk menambahkan `user_id` ke berbagai tabel
3. ✅ Models sudah diupdate dengan relationships
4. ✅ FeatureAccess service sudah dibuat
5. ✅ CheckFeatureAccess middleware sudah dibuat
6. ✅ FeatureGate component sudah dibuat
7. ✅ UserSeeder sudah diupdate

## 📋 Langkah-Langkah yang Harus Dilakukan

### 1. Register Middleware (Jika Belum)
Pastikan `CheckFeatureAccess` middleware sudah terdaftar di `bootstrap/app.php` atau `app/Http/Kernel.php`.

### 2. Jalankan Migrations
```bash
php artisan migrate
```

Ini akan menambahkan kolom:
- `plan`, `is_owner`, `plan_expires_at` ke tabel `users`
- `user_id` ke tabel: `periodes`, `barangs`, `invoices`, `offerings`, `surat_jalans`, `company_infos`

### 3. Update Data Existing (Jika Ada)
Jika sudah ada data di database, perlu update `user_id` untuk data existing agar tidak hilang.

**Opsi A: Set semua data existing ke user owner**
```sql
-- Set user owner (ganti dengan ID user owner Anda)
UPDATE periodes SET user_id = 1 WHERE user_id IS NULL;
UPDATE barangs SET user_id = 1 WHERE user_id IS NULL;
UPDATE invoices SET user_id = 1 WHERE user_id IS NULL;
UPDATE offerings SET user_id = 1 WHERE user_id IS NULL;
UPDATE surat_jalans SET user_id = 1 WHERE user_id IS NULL;
UPDATE company_infos SET user_id = 1 WHERE user_id IS NULL;
```

**Opsi B: Jalankan seeder untuk set user owner**
```bash
php artisan db:seed --class=UserSeeder
```

### 4. Jalankan Seeders
```bash
php artisan db:seed
```

Atau jalankan seeder tertentu:
```bash
php artisan db:seed --class=UserSeeder
```

### 5. Test Aplikasi
1. Login sebagai user owner (admin@ramaadvertize.com)
2. Pastikan semua data masih terlihat
3. Buat user baru dengan plan 'free'
4. Login sebagai user baru
5. Pastikan user baru hanya melihat data miliknya sendiri
6. Test feature access (free user tidak bisa akses fitur premium)

### 6. (Opsional) Tambahkan Middleware ke Routes
Jika ingin proteksi di level route juga, tambahkan middleware ke routes yang memerlukan feature access:

```php
// Di routes/web.php
Route::middleware(['auth', 'feature:laporan_neraca'])->group(function () {
    Route::get('laporan/neraca', [LaporanController::class, 'neraca'])->name('laporan.neraca');
    Route::get('laporan/neraca/export-pdf', [LaporanController::class, 'exportNeracaPdf'])->name('laporan.neraca.export-pdf');
});

Route::middleware(['auth', 'feature:laporan_arus_kas'])->group(function () {
    Route::get('laporan/arus-kas', [LaporanController::class, 'arusKas'])->name('laporan.arus-kas');
});
```

## ⚠️ Catatan Penting

1. **Backup Database**: Sebelum migrate, backup database Anda!
2. **Data Existing**: Pastikan semua data existing sudah di-assign ke user owner
3. **COA Global**: COA tetap global, tidak perlu `user_id`
4. **Owner Access**: User dengan `is_owner = true` bisa akses semua data
5. **Feature Access**: Fitur premium hanya bisa diakses oleh plan Professional/Enterprise

## 🔍 Checklist Sebelum Production

- [ ] Backup database
- [ ] Jalankan migrations
- [ ] Update data existing dengan user_id
- [ ] Jalankan seeders
- [ ] Test login sebagai owner
- [ ] Test login sebagai user baru (free plan)
- [ ] Test feature access restrictions
- [ ] Test data isolation (user hanya lihat data sendiri)
- [ ] Test export PDF (hanya untuk Professional/Enterprise)
- [ ] Test laporan Neraca dan Arus Kas (hanya untuk Professional/Enterprise)

## 📝 Catatan Tambahan

Jika ada error saat migrate, kemungkinan:
1. Ada constraint yang conflict - perlu drop constraint dulu
2. Ada data yang tidak valid - perlu cleanup dulu
3. Foreign key constraint - pastikan user_id yang di-assign sudah ada di tabel users

