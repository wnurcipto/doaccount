# Panduan Migrate Multi-Tenant

## ⚠️ PENTING: Backup Database Dulu!

Sebelum menjalankan migrate, **WAJIB backup database** Anda!

## Langkah 1: Register Middleware (Sudah Otomatis)

Middleware sudah terdaftar di `bootstrap/app.php`.

## Langkah 2: Jalankan Migrations

```bash
php artisan migrate
```

Migrations yang akan dijalankan:
1. `2025_11_16_051208_add_plan_to_users_table.php` - Menambahkan kolom plan, is_owner, plan_expires_at
2. `2025_11_16_051209_add_user_id_to_periodes_table.php` - Menambahkan user_id ke periodes
3. `2025_11_16_051210_add_user_id_to_barangs_table.php` - Menambahkan user_id ke barangs
4. `2025_11_16_051211_add_user_id_to_invoices_table.php` - Menambahkan user_id ke invoices
5. `2025_11_16_051351_add_user_id_to_offerings_table.php` - Menambahkan user_id ke offerings
6. `2025_11_16_051352_add_user_id_to_company_infos_table.php` - Menambahkan user_id ke company_infos
7. `2025_11_16_051440_add_user_id_to_surat_jalans_table.php` - Menambahkan user_id ke surat_jalans

## Langkah 3: Update Data Existing

Jika Anda sudah punya data di database, perlu assign `user_id` ke data existing.

### Cara 1: Via Seeder (Recommended)
```bash
php artisan db:seed --class=UserSeeder
```

Seeder ini akan:
- Set user `admin@ramaadvertize.com` sebagai owner dengan plan enterprise
- Set user `wahid@tpmcmms.id` sebagai owner dengan plan enterprise (jika ada)

### Cara 2: Via SQL Manual
Jika ingin manual, jalankan SQL ini (ganti `1` dengan ID user owner Anda):

```sql
-- Cek ID user owner dulu
SELECT id, email, name FROM users WHERE email = 'admin@ramaadvertize.com';

-- Set semua data existing ke user owner (ganti 1 dengan ID user owner)
UPDATE periodes SET user_id = 1 WHERE user_id IS NULL;
UPDATE barangs SET user_id = 1 WHERE user_id IS NULL;
UPDATE invoices SET user_id = 1 WHERE user_id IS NULL;
UPDATE offerings SET user_id = 1 WHERE user_id IS NULL;
UPDATE surat_jalans SET user_id = 1 WHERE user_id IS NULL;
UPDATE company_infos SET user_id = 1 WHERE user_id IS NULL;

-- Update jurnal_headers juga (jika ada)
UPDATE jurnal_headers SET user_id = 1 WHERE user_id IS NULL;

-- Update stok_masuks dan stok_keluars (jika ada)
UPDATE stok_masuks SET user_id = 1 WHERE user_id IS NULL;
UPDATE stok_keluars SET user_id = 1 WHERE user_id IS NULL;
```

## Langkah 4: Test Aplikasi

1. **Login sebagai Owner**
   - Email: `admin@ramaadvertize.com`
   - Password: `password123`
   - Pastikan semua data masih terlihat

2. **Buat User Baru (Free Plan)**
   - Register user baru atau buat via seeder
   - Login sebagai user baru
   - Pastikan hanya melihat data kosong (karena baru dibuat)

3. **Test Feature Access**
   - Free user tidak bisa akses Laporan Neraca
   - Free user tidak bisa akses Laporan Arus Kas
   - Free user tidak bisa Export PDF
   - Professional/Enterprise user bisa akses semua fitur

## Langkah 5: (Opsional) Buat Demo Account

Jika ingin membuat demo account untuk testing:

```bash
php artisan db:seed --class=FreeAccountDemoSeeder
```

## Troubleshooting

### Error: Foreign key constraint fails
**Solusi**: Pastikan semua `user_id` yang di-assign sudah ada di tabel `users`

### Error: Duplicate entry for unique constraint
**Solusi**: Unique constraint sudah diupdate untuk include `user_id`, jadi tidak akan conflict

### Data hilang setelah migrate
**Solusi**: Pastikan sudah assign `user_id` ke semua data existing sebelum migrate

## Checklist

- [ ] Backup database
- [ ] Jalankan `php artisan migrate`
- [ ] Jalankan `php artisan db:seed --class=UserSeeder`
- [ ] Test login sebagai owner
- [ ] Test login sebagai user baru
- [ ] Test feature access restrictions
- [ ] Test data isolation

