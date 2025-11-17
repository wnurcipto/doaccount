# MODUL INVENTORI - SISTEM PEMBUKUAN STOK BARANG

## Overview
Modul Inventori terintegrasi dengan sistem akuntansi untuk mencatat keluar masuk barang dengan jurnal otomatis.

## Fitur Utama

### 1. Master Barang
- Kode Barang (unique)
- Nama Barang
- Kategori
- Satuan (PCS, KG, UNIT, dll)
- Harga Beli
- Harga Jual
- Stok (auto-calculate dari transaksi)
- Stok Minimal (untuk alert)
- Status: Active/Inactive

### 2. Stok Masuk (Pembelian)
- No. Bukti
- Tanggal Masuk
- Barang
- Supplier
- Qty
- Harga (per unit)
- Subtotal (auto-calculate)
- **Jurnal Otomatis:**
  ```
  Persediaan Barang (D)   xxx
      Kas/Utang Usaha (K)     xxx
  ```

### 3. Stok Keluar (Penjualan)
- No. Bukti
- Tanggal Keluar
- Barang
- Customer
- Qty
- Harga Jual (per unit)
- Subtotal (auto-calculate)
- **Jurnal Otomatis:**
  ```
  Kas/Piutang (D)             xxx
      Penjualan (K)               xxx
  
  HPP (D)                     xxx
      Persediaan Barang (K)       xxx
  ```

### 4. Kartu Stok
- Laporan per barang
- Tanggal, No. Bukti, Keterangan
- Masuk, Keluar, Saldo
- Running balance
- Filter by periode

## Database Schema

### Table: barangs
```sql
- id
- kode_barang (unique)
- nama_barang
- kategori
- satuan
- harga_beli
- harga_jual
- stok (auto-update)
- stok_minimal
- keterangan
- is_active
- timestamps
```

### Table: stok_masuks
```sql
- id
- no_bukti (unique)
- tanggal_masuk
- barang_id (FK)
- periode_id (FK)
- supplier
- qty
- harga
- subtotal
- keterangan
- user_id (FK)
- jurnal_header_id (FK, nullable)
- timestamps
```

### Table: stok_keluars
```sql
- id
- no_bukti (unique)
- tanggal_keluar
- barang_id (FK)
- periode_id (FK)
- customer
- qty
- harga
- subtotal
- keterangan
- user_id (FK)
- jurnal_header_id (FK, nullable)
- timestamps
```

## Integrasi dengan COA

### Akun yang Diperlukan
1. **1-1401** - Persediaan Barang (Aset Lancar)
2. **4-1102** - Penjualan Barang (Pendapatan)
3. **5-1201** - Harga Pokok Penjualan/HPP (Beban)

## Cara Kerja

### Saat Stok Masuk:
1. User input data stok masuk
2. Sistem otomatis membuat jurnal:
   - Debit: Persediaan Barang
   - Kredit: Kas (jika tunai) atau Utang Usaha (jika kredit)
3. Stok barang bertambah

### Saat Stok Keluar:
1. User input data stok keluar
2. Sistem otomatis membuat 2 jurnal:
   
   **Jurnal Penjualan:**
   - Debit: Kas/Piutang Usaha
   - Kredit: Penjualan Barang
   
   **Jurnal HPP:**
   - Debit: HPP
   - Kredit: Persediaan Barang
3. Stok barang berkurang

### Auto-Calculate Stok:
```php
Stok Akhir = Total Stok Masuk - Total Stok Keluar
```

## Status Stok
- **Tersedia**: Stok > Stok Minimal
- **Rendah**: Stok <= Stok Minimal (warning)
- **Habis**: Stok = 0 (danger)

## Langkah Implementasi

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Tambah COA untuk Inventori
Jalankan seeder atau tambah manual:
- 1-1401 Persediaan Barang (Aset)
- 4-1102 Penjualan Barang (Pendapatan)
- 5-1201 Harga Pokok Penjualan (Beban)

### 3. Seed Data Barang (Optional)
```bash
php artisan db:seed --class=BarangSeeder
```

### 4. Tambah Menu di Sidebar
```php
<div class="px-3 py-2 text-white-50 small">INVENTORI</div>
<a href="{{ route('barang.index') }}">Master Barang</a>
<a href="{{ route('stok-masuk.index') }}">Stok Masuk</a>
<a href="{{ route('stok-keluar.index') }}">Stok Keluar</a>
<a href="{{ route('kartu-stok.index') }}">Kartu Stok</a>
```

## Routes
```php
Route::resource('barang', BarangController::class);
Route::resource('stok-masuk', StokMasukController::class);
Route::resource('stok-keluar', StokKeluarController::class);
Route::get('kartu-stok', [KartuStokController::class, 'index'])->name('kartu-stok.index');
Route::get('kartu-stok/{barang}', [KartuStokController::class, 'show'])->name('kartu-stok.show');
```

## Contoh Penggunaan

### 1. Pembelian Barang (Stok Masuk)
**Transaksi:** Beli 10 unit Laptop @ Rp 5.000.000

**Input:**
- Barang: Laptop
- Qty: 10
- Harga: 5.000.000
- Subtotal: 50.000.000

**Jurnal Otomatis:**
```
Persediaan Barang (D)  50.000.000
    Kas (K)                 50.000.000
```

### 2. Penjualan Barang (Stok Keluar)
**Transaksi:** Jual 5 unit Laptop @ Rp 6.000.000 (HPP @ 5.000.000)

**Input:**
- Barang: Laptop
- Qty: 5
- Harga Jual: 6.000.000
- Subtotal: 30.000.000

**Jurnal Otomatis:**
```
Jurnal 1 - Penjualan:
Kas (D)                30.000.000
    Penjualan (K)           30.000.000

Jurnal 2 - HPP:
HPP (D)                25.000.000
    Persediaan (K)          25.000.000
```

**Hasil:**
- Pendapatan: Rp 30.000.000
- HPP: Rp 25.000.000
- Laba Kotor: Rp 5.000.000

## Best Practices

1. **Tentukan Metode Penilaian Persediaan:**
   - FIFO (First In First Out) - Recommended
   - Average (Rata-rata)
   - LIFO (First In Last Out)

2. **Periode Tutup Buku:**
   - Stok masuk/keluar hanya bisa input pada periode OPEN
   - Saat periode CLOSED, tidak bisa input transaksi

3. **Stock Opname:**
   - Lakukan pengecekan fisik berkala
   - Cocokkan dengan laporan Kartu Stok

4. **Alert Stok Minimal:**
   - Set stok minimal untuk setiap barang
   - Monitor barang dengan status "Rendah"

## Laporan yang Tersedia

1. **Master Data Barang**
   - Daftar semua barang
   - Stok tersedia
   - Harga beli & jual

2. **Kartu Stok**
   - History per barang
   - Masuk, Keluar, Saldo
   - Filter by date range

3. **Rekap Stok**
   - Total nilai persediaan
   - Barang dengan stok rendah
   - Barang paling laku

4. **Laporan Laba Rugi**
   - Otomatis include Penjualan & HPP
   - Laba Kotor = Penjualan - HPP

## Next Features (Future)

- [ ] Batch/Lot tracking
- [ ] Serial Number tracking
- [ ] Multiple warehouse
- [ ] Transfer antar gudang
- [ ] Retur pembelian
- [ ] Retur penjualan
- [ ] Diskon & pajak
- [ ] Barcode scanning
- [ ] Stock forecast

---

**Status:** ✅ Migration & Models DONE
**Next:** Controllers, Routes, Views
