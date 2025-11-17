# PANDUAN PENGGUNAAN MODUL INVENTORI
## PT. Rama Advertize - Sistem Akuntansi

---

## 📋 PERSIAPAN AWAL

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Update Data COA
Jalankan ulang seeder untuk menambah akun inventori:
```bash
php artisan db:seed --class=CoaSeeder
```

**Akun yang ditambahkan:**
- `1-1004` - Persediaan Barang (Aset)
- `4-1002` - Pendapatan Penjualan (Pendapatan)
- `5-1001` - Harga Pokok Penjualan/HPP (Beban)

### 3. Seed Data Barang (Opsional)
```bash
php artisan db:seed --class=BarangSeeder
```

---

## 🎯 CARA MENGGUNAKAN MODUL INVENTORI

### A. MASTER BARANG

#### 1. Tambah Barang Baru
📍 **Menu:** Inventori → Master Barang → Tambah Barang

**Langkah:**
1. Klik tombol **"Tambah Barang"**
2. Isi form:
   - **Kode Barang**: Misal `BRG-001` (unique, tidak boleh sama)
   - **Nama Barang**: Misal `Laptop HP Core i5`
   - **Kategori**: Misal `Elektronik` (opsional)
   - **Satuan**: Misal `UNIT`, `PCS`, `KG`, dll
   - **Harga Beli**: Harga beli per unit
   - **Harga Jual**: Harga jual per unit
   - **Stok Minimal**: Batas minimum untuk alert (misal: 5)
   - **Keterangan**: Deskripsi tambahan (opsional)
   - **Status Aktif**: Centang jika barang aktif
3. Klik **"Simpan"**

**Contoh Input:**
```
Kode Barang    : LAPTOP-001
Nama Barang    : Laptop HP Core i5 8GB RAM
Kategori       : Komputer
Satuan         : UNIT
Harga Beli     : 5.000.000
Harga Jual     : 6.500.000
Stok Minimal   : 3
Status         : ✓ Aktif
```

#### 2. Edit Barang
- Klik tombol **Edit** (icon pensil) pada barang yang ingin diubah
- Update data yang diperlukan
- Klik **"Update"**

#### 3. Lihat Detail Barang
- Klik tombol **Lihat** (icon mata) untuk melihat:
  - Informasi barang lengkap
  - Stok tersedia saat ini
  - 10 transaksi stok masuk terakhir
  - 10 transaksi stok keluar terakhir

#### 4. Hapus Barang
- Klik tombol **Hapus** (icon tempat sampah)
- ⚠️ **CATATAN**: Barang tidak bisa dihapus jika sudah ada transaksi

---

### B. STOK MASUK (PEMBELIAN BARANG)

#### 1. Input Stok Masuk
📍 **Menu:** Inventori → Stok Masuk → Tambah Stok Masuk

**Langkah:**
1. Klik **"Tambah Stok Masuk"**
2. Isi form:
   - **No. Bukti**: Otomatis generate (misal: SM-202511-0001)
   - **Tanggal Masuk**: Pilih tanggal transaksi
   - **Periode**: Pilih periode yang sedang **OPEN**
   - **Barang**: Pilih barang dari dropdown
   - **Supplier**: Nama supplier (opsional)
   - **Qty**: Jumlah barang yang masuk
   - **Harga**: Harga beli per unit
   - **Subtotal**: Otomatis hitung (Qty × Harga)
   - **Metode Bayar**: 
     - **Tunai** → Jurnal otomatis: Kredit ke Kas
     - **Kredit** → Jurnal otomatis: Kredit ke Utang Usaha
   - **Keterangan**: Catatan tambahan
3. Klik **"Simpan"**

**Contoh Transaksi:**
```
PEMBELIAN TUNAI:
─────────────────────────────────
No. Bukti      : SM-202511-0001
Tanggal        : 09 November 2025
Periode        : November 2025 (Open)
Barang         : Laptop HP Core i5
Supplier       : PT. Maju Jaya
Qty            : 10 UNIT
Harga          : 5.000.000
Subtotal       : 50.000.000
Metode Bayar   : TUNAI
Keterangan     : Pembelian untuk stok
```

**📊 JURNAL OTOMATIS YANG TERBUAT:**
```
No. Bukti: JU-SM-202511-0001
Tanggal: 09 November 2025
Deskripsi: Pembelian Laptop HP Core i5 - SM-202511-0001

┌────────────────────────┬────────────────┬────────────────┐
│ Akun                   │ Debit          │ Kredit         │
├────────────────────────┼────────────────┼────────────────┤
│ 1-1004 Persediaan      │ Rp 50.000.000  │ -              │
│ 1-1001 Kas             │ -              │ Rp 50.000.000  │
├────────────────────────┼────────────────┼────────────────┤
│ TOTAL                  │ Rp 50.000.000  │ Rp 50.000.000  │
└────────────────────────┴────────────────┴────────────────┘
```

**Jika KREDIT (Utang):**
```
┌────────────────────────┬────────────────┬────────────────┐
│ Akun                   │ Debit          │ Kredit         │
├────────────────────────┼────────────────┼────────────────┤
│ 1-1004 Persediaan      │ Rp 50.000.000  │ -              │
│ 2-1001 Utang Usaha     │ -              │ Rp 50.000.000  │
└────────────────────────┴────────────────┴────────────────┘
```

#### 2. Efek Stok Masuk
✅ Stok barang **OTOMATIS BERTAMBAH**
✅ Jurnal **OTOMATIS TERBUAT** (status: Posted)
✅ Persediaan di neraca **BERTAMBAH**

---

### C. STOK KELUAR (PENJUALAN BARANG)

#### 1. Input Stok Keluar
📍 **Menu:** Inventori → Stok Keluar → Tambah Stok Keluar

**Langkah:**
1. Klik **"Tambah Stok Keluar"**
2. Isi form:
   - **No. Bukti**: Otomatis generate (misal: SK-202511-0001)
   - **Tanggal Keluar**: Pilih tanggal transaksi
   - **Periode**: Pilih periode yang sedang **OPEN**
   - **Barang**: Pilih barang dari dropdown
   - **Customer**: Nama customer (opsional)
   - **Qty**: Jumlah barang yang keluar
   - **Harga Jual**: Harga jual per unit
   - **Subtotal**: Otomatis hitung (Qty × Harga)
   - **Metode Terima**: 
     - **Tunai** → Jurnal otomatis: Debit ke Kas
     - **Kredit** → Jurnal otomatis: Debit ke Piutang Usaha
   - **Keterangan**: Catatan tambahan
3. Klik **"Simpan"**

**Contoh Transaksi:**
```
PENJUALAN TUNAI:
─────────────────────────────────
No. Bukti      : SK-202511-0001
Tanggal        : 10 November 2025
Periode        : November 2025 (Open)
Barang         : Laptop HP Core i5
Customer       : PT. Sentosa Abadi
Qty            : 5 UNIT
Harga Jual     : 6.500.000
Subtotal       : 32.500.000
Metode Terima  : TUNAI
Keterangan     : Penjualan ke customer
```

**📊 JURNAL OTOMATIS YANG TERBUAT (2 JURNAL):**

**Jurnal 1 - PENJUALAN:**
```
No. Bukti: JU-SK-202511-0001-JUAL
Tanggal: 10 November 2025
Deskripsi: Penjualan Laptop HP Core i5 - SK-202511-0001

┌────────────────────────┬────────────────┬────────────────┐
│ Akun                   │ Debit          │ Kredit         │
├────────────────────────┼────────────────┼────────────────┤
│ 1-1001 Kas             │ Rp 32.500.000  │ -              │
│ 4-1002 Penjualan       │ -              │ Rp 32.500.000  │
└────────────────────────┴────────────────┴────────────────┘
```

**Jurnal 2 - HPP (Harga Pokok Penjualan):**
```
No. Bukti: JU-SK-202511-0001-HPP
Tanggal: 10 November 2025
Deskripsi: HPP Penjualan Laptop HP Core i5 - SK-202511-0001

Hitung: 5 unit × Rp 5.000.000 = Rp 25.000.000

┌────────────────────────┬────────────────┬────────────────┐
│ Akun                   │ Debit          │ Kredit         │
├────────────────────────┼────────────────┼────────────────┤
│ 5-1001 HPP             │ Rp 25.000.000  │ -              │
│ 1-1004 Persediaan      │ -              │ Rp 25.000.000  │
└────────────────────────┴────────────────┴────────────────┘
```

**💰 ANALISIS LABA:**
```
Penjualan        : Rp 32.500.000
HPP              : Rp 25.000.000
─────────────────────────────────
Laba Kotor       : Rp  7.500.000  ✅
```

#### 2. Efek Stok Keluar
✅ Stok barang **OTOMATIS BERKURANG**
✅ **2 Jurnal OTOMATIS TERBUAT** (Penjualan + HPP)
✅ Pendapatan di Laba Rugi **BERTAMBAH**
✅ Persediaan di Neraca **BERKURANG**
✅ HPP di Laba Rugi **BERTAMBAH**

---

### D. KARTU STOK (LAPORAN)

#### 1. Lihat Kartu Stok
📍 **Menu:** Inventori → Kartu Stok

**Langkah:**
1. Pilih **Barang** dari dropdown
2. Pilih **Tanggal Mulai** dan **Tanggal Selesai**
3. Klik **"Tampilkan"**

**Tampilan Kartu Stok:**
```
═══════════════════════════════════════════════════════════
KARTU STOK
Barang: Laptop HP Core i5 8GB RAM
Periode: 01 Nov 2025 - 30 Nov 2025
═══════════════════════════════════════════════════════════

┌────────────┬─────────────┬─────────────┬────────┬────────┬────────┬────────┐
│ Tanggal    │ No. Bukti   │ Keterangan  │ Masuk  │ Keluar │ Saldo  │ Nilai  │
├────────────┼─────────────┼─────────────┼────────┼────────┼────────┼────────┤
│ 01-11-2025 │ -           │ Saldo Awal  │ -      │ -      │ 0      │ Rp 0   │
│ 09-11-2025 │ SM-202511-1 │ Pembelian   │ 10     │ -      │ 10     │ 50jt   │
│ 10-11-2025 │ SK-202511-1 │ Penjualan   │ -      │ 5      │ 5      │ 25jt   │
│ 15-11-2025 │ SM-202511-2 │ Pembelian   │ 20     │ -      │ 25     │ 125jt  │
│ 20-11-2025 │ SK-202511-2 │ Penjualan   │ -      │ 8      │ 17     │ 85jt   │
└────────────┴─────────────┴─────────────┴────────┴────────┴────────┴────────┘

Saldo Akhir: 17 UNIT
Nilai Persediaan: Rp 85.000.000
```

#### 2. Cetak/Export
- Klik tombol **"Cetak"** untuk print
- Klik tombol **"Export Excel"** untuk download (jika ada)

---

## 🔍 CEK INTEGRASI DENGAN AKUNTANSI

### 1. Cek di Jurnal
📍 Menu: **Jurnal → Daftar Jurnal**
- Cari jurnal dengan prefix `JU-SM-` atau `JU-SK-`
- Status harus **Posted**
- Total Debit = Total Kredit

### 2. Cek di Buku Besar
📍 Menu: **Buku Besar**
- Pilih akun **1-1004 Persediaan Barang**
- Lihat mutasi dari transaksi stok masuk & keluar
- Saldo akhir harus sesuai dengan total nilai stok

### 3. Cek di Laporan Laba Rugi
📍 Menu: **Laporan → Laba Rugi**
- Lihat **Pendapatan Penjualan** (dari stok keluar)
- Lihat **HPP** (dari stok keluar)
- **Laba Kotor = Penjualan - HPP**

### 4. Cek di Neraca
📍 Menu: **Laporan → Neraca**
- Lihat **Aset Lancar → Persediaan Barang**
- Nilai harus sesuai dengan saldo stok × harga beli

---

## ⚠️ PENTING! HAL YANG PERLU DIPERHATIKAN

### 1. Periode Harus OPEN
❌ **TIDAK BISA** input stok masuk/keluar jika periode **CLOSED**
✅ Buka periode terlebih dahulu di menu **Periode**

### 2. Stok Harus Cukup
❌ **TIDAK BISA** stok keluar jika stok tidak cukup
✅ Cek stok tersedia di Master Barang sebelum input stok keluar

### 3. Jurnal Tidak Bisa Diedit
❌ Stok masuk/keluar yang **SUDAH DIJURNAL** tidak bisa edit/hapus
✅ Jika ada kesalahan, buat jurnal koreksi manual

### 4. Harga Beli untuk HPP
💡 HPP dihitung dari **Harga Beli** yang ada di Master Barang
💡 Pastikan harga beli selalu update

### 5. Alert Stok Minimal
⚠️ **Warna Kuning**: Stok <= Stok Minimal (Perlu Restock)
🔴 **Warna Merah**: Stok = 0 (Habis!)

---

## � PANDUAN JURNAL LENGKAP & CONTOH TRANSAKSI

### A. TRANSAKSI PEMBELIAN BARANG

#### 1. Pembelian Tunai (Cash)

**Contoh:** Beli 10 unit Laptop @ Rp 5.000.000 secara tunai

**Input di Sistem:**
```
Menu: Stok Masuk → Tambah
─────────────────────────────────
Barang         : Laptop HP Core i5
Qty            : 10 UNIT
Harga Beli     : 5.000.000
Subtotal       : 50.000.000
Metode Bayar   : TUNAI
```

**Jurnal Otomatis:**
```
No. Bukti: JU-SM-202511-0001
Tanggal: 09 November 2025
Deskripsi: Pembelian Laptop HP Core i5 - PT. Maju Jaya

┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 1-1004│ Persediaan Barang      │ Rp 50.000.000  │ -              │
│ 1-1001│ Kas                    │ -              │ Rp 50.000.000  │
├───────┼────────────────────────┼────────────────┼────────────────┤
│       │ TOTAL                  │ Rp 50.000.000  │ Rp 50.000.000  │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan:
- Persediaan Barang (DEBIT): Aset bertambah karena barang masuk
- Kas (KREDIT): Aset berkurang karena uang keluar untuk bayar
```

**Dampak:**
- ✅ Stok barang bertambah 10 unit
- ✅ Kas berkurang Rp 50.000.000
- ✅ Nilai persediaan di Neraca bertambah Rp 50.000.000

---

#### 2. Pembelian Kredit (Utang)

**Contoh:** Beli 20 unit Laptop @ Rp 5.000.000 secara kredit (belum bayar)

**Input di Sistem:**
```
Menu: Stok Masuk → Tambah
─────────────────────────────────
Barang         : Laptop HP Core i5
Qty            : 20 UNIT
Harga Beli     : 5.000.000
Subtotal       : 100.000.000
Metode Bayar   : KREDIT (Utang)
```

**Jurnal Otomatis:**
```
No. Bukti: JU-SM-202511-0002
Tanggal: 15 November 2025
Deskripsi: Pembelian Laptop HP Core i5 - PT. Maju Jaya

┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 1-1004│ Persediaan Barang      │ Rp 100.000.000 │ -              │
│ 2-1001│ Utang Usaha            │ -              │ Rp 100.000.000 │
├───────┼────────────────────────┼────────────────┼────────────────┤
│       │ TOTAL                  │ Rp 100.000.000 │ Rp 100.000.000 │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan:
- Persediaan Barang (DEBIT): Aset bertambah karena barang masuk
- Utang Usaha (KREDIT): Kewajiban bertambah karena belum bayar
```

**Dampak:**
- ✅ Stok barang bertambah 20 unit
- ✅ Utang usaha bertambah Rp 100.000.000
- ✅ Nilai persediaan di Neraca bertambah Rp 100.000.000

---

#### 3. Pelunasan Utang Pembelian

**Contoh:** Bayar utang pembelian sebelumnya Rp 100.000.000

**Input Manual di Jurnal:**
```
Menu: Jurnal → Tambah Jurnal
─────────────────────────────────
No. Bukti      : JU-BAY-202511-001
Tanggal        : 20 November 2025
Periode        : November 2025
Deskripsi      : Pelunasan utang pembelian Laptop ke PT. Maju Jaya

Detail Jurnal:
┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 2-1001│ Utang Usaha            │ Rp 100.000.000 │ -              │
│ 1-1001│ Kas                    │ -              │ Rp 100.000.000 │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan:
- Utang Usaha (DEBIT): Kewajiban berkurang karena dibayar
- Kas (KREDIT): Aset berkurang karena uang keluar
```

**Dampak:**
- ✅ Utang usaha berkurang Rp 100.000.000
- ✅ Kas berkurang Rp 100.000.000
- ✅ Stok barang tidak berubah (hanya pembayaran)

---

### B. TRANSAKSI PENJUALAN BARANG

#### 1. Penjualan Tunai (Cash)

**Contoh:** Jual 5 unit Laptop @ Rp 6.500.000 secara tunai

**Input di Sistem:**
```
Menu: Stok Keluar → Tambah
─────────────────────────────────
Barang         : Laptop HP Core i5
Qty            : 5 UNIT
Harga Jual     : 6.500.000
Subtotal       : 32.500.000
Metode Terima  : TUNAI
Customer       : PT. Sentosa Abadi
```

**Sistem Otomatis Membuat 2 Jurnal:**

**Jurnal 1 - Pencatatan Penjualan:**
```
No. Bukti: JU-SK-202511-0001-JUAL
Tanggal: 10 November 2025
Deskripsi: Penjualan Laptop HP Core i5 - SK-202511-0001

┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 1-1001│ Kas                    │ Rp 32.500.000  │ -              │
│ 4-1002│ Pendapatan Penjualan   │ -              │ Rp 32.500.000  │
├───────┼────────────────────────┼────────────────┼────────────────┤
│       │ TOTAL                  │ Rp 32.500.000  │ Rp 32.500.000  │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan:
- Kas (DEBIT): Aset bertambah karena terima uang dari penjualan
- Pendapatan Penjualan (KREDIT): Pendapatan bertambah
```

**Jurnal 2 - Pencatatan HPP (Harga Pokok Penjualan):**
```
No. Bukti: JU-SK-202511-0001-HPP
Tanggal: 10 November 2025
Deskripsi: HPP Penjualan Laptop HP Core i5 - SK-202511-0001

Hitung HPP: 5 unit × Rp 5.000.000 = Rp 25.000.000

┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 5-1001│ Harga Pokok Penjualan  │ Rp 25.000.000  │ -              │
│ 1-1004│ Persediaan Barang      │ -              │ Rp 25.000.000  │
├───────┼────────────────────────┼────────────────┼────────────────┤
│       │ TOTAL                  │ Rp 25.000.000  │ Rp 25.000.000  │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan:
- HPP (DEBIT): Beban bertambah sebesar modal barang yang terjual
- Persediaan Barang (KREDIT): Aset berkurang karena barang keluar
```

**Analisis Laba:**
```
═══════════════════════════════════════════════
ANALISIS LABA PENJUALAN
═══════════════════════════════════════════════
Penjualan (Revenue)     : Rp 32.500.000
HPP (Cost)              : Rp 25.000.000
───────────────────────────────────────────────
Laba Kotor (Gross)      : Rp  7.500.000  ✅
Margin Laba             : 23.08%
═══════════════════════════════════════════════
```

**Dampak:**
- ✅ Stok barang berkurang 5 unit
- ✅ Kas bertambah Rp 32.500.000
- ✅ Pendapatan di Laba Rugi bertambah Rp 32.500.000
- ✅ HPP di Laba Rugi bertambah Rp 25.000.000
- ✅ Persediaan di Neraca berkurang Rp 25.000.000

---

#### 2. Penjualan Kredit (Piutang)

**Contoh:** Jual 8 unit Laptop @ Rp 6.500.000 secara kredit (belum terima uang)

**Input di Sistem:**
```
Menu: Stok Keluar → Tambah
─────────────────────────────────
Barang         : Laptop HP Core i5
Qty            : 8 UNIT
Harga Jual     : 6.500.000
Subtotal       : 52.000.000
Metode Terima  : KREDIT (Piutang)
Customer       : PT. Berkah Jaya
```

**Sistem Otomatis Membuat 2 Jurnal:**

**Jurnal 1 - Pencatatan Penjualan:**
```
No. Bukti: JU-SK-202511-0002-JUAL
Tanggal: 20 November 2025
Deskripsi: Penjualan Laptop HP Core i5 - SK-202511-0002

┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 1-1003│ Piutang Usaha          │ Rp 52.000.000  │ -              │
│ 4-1002│ Pendapatan Penjualan   │ -              │ Rp 52.000.000  │
├───────┼────────────────────────┼────────────────┼────────────────┤
│       │ TOTAL                  │ Rp 52.000.000  │ Rp 52.000.000  │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan:
- Piutang Usaha (DEBIT): Aset bertambah (tagihan ke customer)
- Pendapatan Penjualan (KREDIT): Pendapatan bertambah
```

**Jurnal 2 - Pencatatan HPP:**
```
No. Bukti: JU-SK-202511-0002-HPP
Tanggal: 20 November 2025
Deskripsi: HPP Penjualan Laptop HP Core i5 - SK-202511-0002

Hitung HPP: 8 unit × Rp 5.000.000 = Rp 40.000.000

┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 5-1001│ Harga Pokok Penjualan  │ Rp 40.000.000  │ -              │
│ 1-1004│ Persediaan Barang      │ -              │ Rp 40.000.000  │
└───────┴────────────────────────┴────────────────┴────────────────┘
```

**Analisis Laba:**
```
═══════════════════════════════════════════════
ANALISIS LABA PENJUALAN
═══════════════════════════════════════════════
Penjualan (Revenue)     : Rp 52.000.000
HPP (Cost)              : Rp 40.000.000
───────────────────────────────────────────────
Laba Kotor (Gross)      : Rp 12.000.000  ✅
Margin Laba             : 23.08%
═══════════════════════════════════════════════
```

**Dampak:**
- ✅ Stok barang berkurang 8 unit
- ✅ Piutang usaha bertambah Rp 52.000.000
- ✅ Pendapatan di Laba Rugi bertambah Rp 52.000.000
- ✅ HPP di Laba Rugi bertambah Rp 40.000.000
- ✅ Persediaan di Neraca berkurang Rp 40.000.000

---

#### 3. Penerimaan Pembayaran Piutang

**Contoh:** Terima pembayaran piutang dari PT. Berkah Jaya Rp 52.000.000

**Input Manual di Jurnal:**
```
Menu: Jurnal → Tambah Jurnal
─────────────────────────────────
No. Bukti      : JU-TERIMA-202511-001
Tanggal        : 25 November 2025
Periode        : November 2025
Deskripsi      : Penerimaan piutang dari PT. Berkah Jaya

Detail Jurnal:
┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 1-1001│ Kas                    │ Rp 52.000.000  │ -              │
│ 1-1003│ Piutang Usaha          │ -              │ Rp 52.000.000  │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan:
- Kas (DEBIT): Aset bertambah karena terima uang
- Piutang Usaha (KREDIT): Aset berkurang karena piutang tertagih
```

**Dampak:**
- ✅ Kas bertambah Rp 52.000.000
- ✅ Piutang berkurang Rp 52.000.000
- ✅ Stok barang tidak berubah (hanya penerimaan kas)

---

### C. TRANSAKSI RETUR & KOREKSI

#### 1. Retur Pembelian (Barang Rusak/Dikembalikan ke Supplier)

**Contoh:** Kembalikan 2 unit Laptop rusak ke supplier, uang dikembalikan tunai

**Input Manual di Jurnal:**
```
Menu: Jurnal → Tambah Jurnal
─────────────────────────────────
No. Bukti      : JU-RETUR-BLI-001
Tanggal        : 12 November 2025
Periode        : November 2025
Deskripsi      : Retur pembelian 2 unit Laptop rusak ke PT. Maju Jaya

Detail Jurnal:
┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 1-1001│ Kas                    │ Rp 10.000.000  │ -              │
│ 1-1004│ Persediaan Barang      │ -              │ Rp 10.000.000  │
├───────┼────────────────────────┼────────────────┼────────────────┤
│       │ TOTAL                  │ Rp 10.000.000  │ Rp 10.000.000  │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan:
- Kas (DEBIT): Uang kembali dari supplier
- Persediaan (KREDIT): Nilai persediaan berkurang

PENTING: Kurangi stok manual di Master Barang!
```

---

#### 2. Retur Penjualan (Customer Kembalikan Barang)

**Contoh:** Customer kembalikan 1 unit Laptop, uang dikembalikan

**Input Manual di Jurnal:**

**Jurnal 1 - Kembalikan Pendapatan:**
```
No. Bukti      : JU-RETUR-JUAL-001-A
Tanggal        : 22 November 2025
Deskripsi      : Retur penjualan 1 unit Laptop dari PT. Sentosa Abadi

┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 4-1002│ Pendapatan Penjualan   │ Rp 6.500.000   │ -              │
│ 1-1001│ Kas                    │ -              │ Rp 6.500.000   │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan: Balikkan jurnal penjualan
```

**Jurnal 2 - Kembalikan Persediaan:**
```
No. Bukti      : JU-RETUR-JUAL-001-B
Tanggal        : 22 November 2025
Deskripsi      : Pengembalian persediaan retur

┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 1-1004│ Persediaan Barang      │ Rp 5.000.000   │ -              │
│ 5-1001│ Harga Pokok Penjualan  │ -              │ Rp 5.000.000   │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan: Balikkan jurnal HPP

PENTING: Tambahkan stok manual di Master Barang!
```

---

### D. TRANSAKSI PENYESUAIAN STOK

#### 1. Stok Opname - Barang Hilang/Rusak

**Contoh:** Stok fisik 15 unit, di sistem 17 unit. Selisih 2 unit hilang/rusak.

**Input Manual di Jurnal:**
```
Menu: Jurnal → Tambah Jurnal
─────────────────────────────────
No. Bukti      : JU-ADJ-202511-001
Tanggal        : 30 November 2025
Periode        : November 2025
Deskripsi      : Penyesuaian stok opname - 2 unit Laptop hilang/rusak

Detail Jurnal:
┌────────────────────────────────┬────────────────┬────────────────┐
│ Kode  │ Nama Akun              │ Debit          │ Kredit         │
├───────┼────────────────────────┼────────────────┼────────────────┤
│ 6-1003│ Beban Lain-lain        │ Rp 10.000.000  │ -              │
│ 1-1004│ Persediaan Barang      │ -              │ Rp 10.000.000  │
├───────┼────────────────────────┼────────────────┼────────────────┤
│       │ TOTAL                  │ Rp 10.000.000  │ Rp 10.000.000  │
└───────┴────────────────────────┴────────────────┴────────────────┘

Penjelasan:
- Beban Lain-lain (DEBIT): Catat sebagai kerugian/beban
- Persediaan (KREDIT): Kurangi nilai persediaan

PENTING: Kurangi stok manual di Master Barang dari 17 → 15!
```

---

## �📊 CONTOH KASUS LENGKAP - SIKLUS BISNIS 1 BULAN

### Transaksi Bulan November 2025

**01 Nov:** Saldo awal Laptop = 0 unit, Kas = Rp 200.000.000

**09 Nov:** Beli 10 unit Laptop @ Rp 5.000.000 (Tunai)
```
→ Stok: 10 unit
→ Kas: 200jt - 50jt = 150jt
→ Persediaan: 50jt
→ Jurnal: Persediaan (D) 50jt, Kas (K) 50jt
```

**10 Nov:** Jual 5 unit Laptop @ Rp 6.500.000 (Tunai)
```
→ Stok: 5 unit
→ Kas: 150jt + 32,5jt = 182,5jt
→ Persediaan: 50jt - 25jt = 25jt
→ Jurnal 1: Kas (D) 32,5jt, Penjualan (K) 32,5jt
→ Jurnal 2: HPP (D) 25jt, Persediaan (K) 25jt
→ Laba: 32,5jt - 25jt = 7,5jt
```

**15 Nov:** Beli 20 unit Laptop @ Rp 5.000.000 (Kredit)
```
→ Stok: 25 unit
→ Kas: 182,5jt (tidak berubah)
→ Persediaan: 25jt + 100jt = 125jt
→ Utang: 100jt
→ Jurnal: Persediaan (D) 100jt, Utang (K) 100jt
```

**20 Nov:** Jual 8 unit Laptop @ Rp 6.500.000 (Kredit/Piutang)
```
→ Stok: 17 unit
→ Piutang: 52jt
→ Persediaan: 125jt - 40jt = 85jt
→ Jurnal 1: Piutang (D) 52jt, Penjualan (K) 52jt
→ Jurnal 2: HPP (D) 40jt, Persediaan (K) 40jt
→ Laba: 52jt - 40jt = 12jt
```

**25 Nov:** Bayar utang pembelian ke supplier
```
→ Kas: 182,5jt - 100jt = 82,5jt
→ Utang: 0
→ Jurnal: Utang (D) 100jt, Kas (K) 100jt
```

**28 Nov:** Terima pembayaran piutang dari customer
```
→ Kas: 82,5jt + 52jt = 134,5jt
→ Piutang: 0
→ Jurnal: Kas (D) 52jt, Piutang (K) 52jt
```

**Ringkasan Akhir Bulan (30 November):**
```
═══════════════════════════════════════════════════════════
LAPORAN POSISI KEUANGAN (NERACA)
═══════════════════════════════════════════════════════════
ASET:
  Kas                    : Rp 134.500.000
  Piutang Usaha          : Rp          0
  Persediaan Barang      : Rp  85.000.000  (17 unit)
  ─────────────────────────────────────────────
  TOTAL ASET             : Rp 219.500.000

KEWAJIBAN:
  Utang Usaha            : Rp          0
  ─────────────────────────────────────────────
  TOTAL KEWAJIBAN        : Rp          0

EKUITAS:
  Modal Awal             : Rp 200.000.000
  Laba Bulan Ini         : Rp  19.500.000
  ─────────────────────────────────────────────
  TOTAL EKUITAS          : Rp 219.500.000

═══════════════════════════════════════════════════════════
TOTAL KEWAJIBAN + EKUITAS : Rp 219.500.000  ✅ BALANCE
═══════════════════════════════════════════════════════════

═══════════════════════════════════════════════════════════
LAPORAN LABA RUGI
═══════════════════════════════════════════════════════════
PENDAPATAN:
  Pendapatan Penjualan   : Rp  84.500.000
  ─────────────────────────────────────────────
  Total Pendapatan       : Rp  84.500.000

BEBAN:
  Harga Pokok Penjualan  : Rp  65.000.000
  ─────────────────────────────────────────────
  Total Beban            : Rp  65.000.000
  
  ─────────────────────────────────────────────
  LABA KOTOR             : Rp  19.500.000  ✅
  Margin Laba            : 23.08%
═══════════════════════════════════════════════════════════

STOK FISIK:
  Laptop HP Core i5      : 17 UNIT
  Nilai @ Rp 5.000.000   : Rp 85.000.000
```

---

## 🆘 TROUBLESHOOTING

### 1. Error: "COA tidak ditemukan"
**Solusi**: Jalankan ulang seeder
```bash
php artisan db:seed --class=CoaSeeder
```

### 2. Stok tidak update otomatis
**Solusi**: Cek Model Barang, pastikan method `updateStok()` dipanggil di event `booted()`

### 3. Jurnal tidak terbuat
**Solusi**: 
- Cek apakah akun 1-1004, 1-1001, 2-1001, 4-1002, 5-1001 sudah ada
- Cek log error di `storage/logs/laravel.log`

### 4. Tidak bisa input transaksi
**Solusi**: Pastikan periode dalam status **OPEN**

---

## 📞 SUPPORT

Jika ada pertanyaan atau error, hubungi tim IT atau cek dokumentasi lengkap di:
- `MODUL_INVENTORI.md`
- `DOKUMENTASI.md`

---

**Selamat Menggunakan! 🎉**
**PT. Rama Advertize - Sistem Akuntansi v1.0**
