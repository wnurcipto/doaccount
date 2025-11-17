# IMPLEMENTASI MULTI-TENANT & SUBSCRIPTION PLAN

## ✅ Yang Sudah Dilakukan

### 1. Database Structure
- ✅ Migration untuk menambahkan `plan`, `is_owner`, `plan_expires_at` ke tabel `users`
- ✅ Migration untuk menambahkan `user_id` ke tabel:
  - `periodes`
  - `barangs`
  - `invoices`
  - `offerings`
  - `surat_jalans`
  - `company_infos`
- ✅ Update unique constraint untuk include `user_id` (periode, barang, invoice, offering, surat_jalan)

### 2. Models
- ✅ Update `User` model dengan:
  - Fillable: `plan`, `is_owner`, `plan_expires_at`
  - Relationships ke semua model
  - Helper methods: `hasFeature()`, `getLimit()`, `isPlanActive()`, `getPlanDisplayName()`
- ✅ Update model untuk menambahkan `user_id` ke fillable dan relationship:
  - `Periode`
  - `Barang`
  - `Invoice`
  - `Offering`
  - `SuratJalan`
  - `CompanyInfo` (update `getInfo()` untuk per-user)

### 3. Services
- ✅ `FeatureAccess` service dengan definisi fitur per plan:
  - `free`: Basic features, max 3 periode, max 50 jurnal/bulan
  - `starter`: Sama dengan free, max 6 periode, max 200 jurnal/bulan
  - `professional`: Semua fitur, max 12 periode, max 500 jurnal/bulan
  - `enterprise`: Unlimited semua

### 4. Middleware
- ✅ `CheckFeatureAccess` middleware untuk protect routes berdasarkan feature

### 5. Controllers
- ✅ Base `Controller` dengan helper method `scopeUser()` dan `currentUser()`
- ✅ Update `PeriodeController` untuk filter berdasarkan user_id
- ⚠️ **PENTING**: Controller lain perlu diupdate dengan pattern yang sama:
  - `JurnalController`
  - `BarangController`
  - `InvoiceController`
  - `OfferingController`
  - `SuratJalanController`
  - `BukuBesarController`
  - `LaporanController`
  - `DashboardController`
  - `AboutController` (CompanyInfo)

### 6. Seeders
- ✅ Update `UserSeeder` untuk set user existing sebagai owner
- ✅ `FreeAccountDemoSeeder` untuk membuat akun demo dengan data sample

### 7. Components
- ✅ `FeatureGate` Blade component untuk hide/show fitur di views

## 📋 Yang Perlu Dilakukan

### 1. Update Controllers (PRIORITAS TINGGI)

Semua controller perlu diupdate dengan pattern berikut:

```php
// Di method index() atau query apapun
$query = Model::query();
$query = $this->scopeUser($query);
$data = $query->get();

// Di method store()
$validated['user_id'] = $this->currentUser()->id;
Model::create($validated);

// Di method update() / destroy()
$user = $this->currentUser();
if (!$user->is_owner && $model->user_id !== $user->id) {
    return redirect()->back()
        ->with('error', 'Anda tidak memiliki akses');
}
```

**Controller yang perlu diupdate:**
1. `JurnalController` - filter jurnal, set user_id saat create
2. `BarangController` - filter barang, set user_id
3. `InvoiceController` - filter invoice, set user_id
4. `OfferingController` - filter offering, set user_id
5. `SuratJalanController` - filter surat_jalan, set user_id
6. `BukuBesarController` - filter berdasarkan jurnal user
7. `LaporanController` - filter berdasarkan jurnal user
8. `DashboardController` - filter statistik berdasarkan user
9. `AboutController` - filter company_info berdasarkan user
10. `StokMasukController` - filter stok_masuk, set user_id
11. `StokKeluarController` - filter stok_keluar, set user_id
12. `KartuStokController` - filter berdasarkan barang user

### 2. Update Views

**Navigation Menu** (`resources/views/layouts/app.blade.php`):
```blade
@if(auth()->user()->hasFeature('laporan_neraca'))
    <li><a href="{{ route('laporan.neraca') }}">Neraca</a></li>
@else
    <li>
        <a href="#" class="text-muted">
            Neraca <span class="badge bg-warning">Pro</span>
        </a>
    </li>
@endif
```

**Dashboard** - Tambahkan info plan dan upgrade prompt

**Semua views** - Gunakan `<x-feature-gate>` untuk fitur premium

### 3. Update Routes

Tambahkan middleware untuk protect routes premium:
```php
Route::middleware(['auth', 'feature:laporan_neraca'])->group(function () {
    Route::get('laporan/neraca', [LaporanController::class, 'neraca']);
});
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Run Seeders

```bash
# Update user existing menjadi owner
php artisan db:seed --class=UserSeeder

# Buat akun demo FREE
php artisan db:seed --class=FreeAccountDemoSeeder
```

## 🎯 Konsep Multi-Tenant

1. **COA (Chart of Accounts)**: **GLOBAL** - Dipakai semua user (tidak ada user_id)
2. **Data Lainnya**: **PER USER** - Setiap user hanya melihat data miliknya sendiri
3. **Owner**: User dengan `is_owner = true` bisa melihat semua data (untuk admin/CV Rama Advertize)
4. **Free Account**: User dengan `plan = 'free'` untuk trial dengan data demo

## 🔐 Feature Access

- **Free**: COA, Jurnal, Buku Besar, Laba Rugi (basic)
- **Starter**: Sama dengan Free + limit lebih besar
- **Professional**: Semua fitur (Neraca, Arus Kas, Inventory, Invoice, Export, dll)
- **Enterprise**: Unlimited semua fitur

## 📝 Catatan Penting

1. **COA tetap GLOBAL** - Tidak perlu user_id, dipakai semua user
2. **Owner selalu punya akses penuh** - Bisa lihat semua data
3. **User biasa hanya lihat data miliknya** - Filter berdasarkan user_id
4. **Seeder Free Account** - Buat data demo untuk trial
5. **Migration perlu dijalankan** - Pastikan semua migration sudah dijalankan

## 🚀 Next Steps

1. Update semua controller dengan pattern `scopeUser()`
2. Update views untuk show/hide fitur premium
3. Test dengan akun free dan owner
4. Buat halaman subscription/upgrade (opsional)
5. Update dokumentasi user

