# PANDUAN PENUTUPAN AKHIR TAHUN
## Sistem Akuntansi - PT. Rama Advertize

---

## 📋 PENGERTIAN PENUTUPAN AKHIR TAHUN

Penutupan akhir tahun adalah proses akuntansi untuk:
1. **Menutup akun-akun sementara** (Pendapatan dan Beban) ke akun Laba Ditahan
2. **Memastikan neraca tetap balance** di awal tahun baru
3. **Menyiapkan saldo awal** untuk tahun akuntansi berikutnya

---

## ⚠️ PENTING: SEBELUM MEMULAI

1. **Pastikan semua transaksi tahun berjalan sudah diinput**
2. **Pastikan semua jurnal sudah diposting** (status = Posted)
3. **Backup database** sebelum melakukan penutupan
4. **Tutup semua periode tahun berjalan** (ubah status menjadi Closed)
5. **Pastikan akun Ikhtisar Laba Rugi sudah ada di COA** (lihat cara membuat di bawah)

---

## 🔧 MEMBUAT AKUN COA UNTUK IKHTISAR LABA RUGI

Sebelum melakukan penutupan akhir tahun, pastikan akun **Ikhtisar Laba Rugi** sudah tersedia di Chart of Accounts (COA). Jika belum ada, ikuti langkah berikut:

### **Cara 1: Melalui Menu Aplikasi (Manual)**

1. **Buka Menu Chart of Accounts**
   - Klik menu **Chart of Accounts** di sidebar
   - Klik tombol **"Tambah COA"**

2. **Isi Form COA**
   - **Kode Akun**: `3-2001`
   - **Nama Akun**: `Ikhtisar Laba Rugi`
   - **Tipe Akun**: Pilih **Ekuitas**
   - **Posisi Normal**: Pilih **Debit** ⚠️ (WAJIB Debit, jangan pilih Kredit)
   - **Level**: `2`
   - **Parent**: Pilih **EKUITAS (3-0000)**
   - **Status Aktif**: Centang ✓
   - **Deskripsi**: (Opsional) `Akun untuk penutupan akhir tahun`

3. **Simpan**
   - Klik tombol **"Simpan"**
   - Pastikan akun berhasil ditambahkan

### **Cara 2: Melalui Seeder (Otomatis)**

Jika akun belum ada dan ingin membuatnya secara otomatis:

1. **Jalankan Seeder COA**
   ```bash
   php artisan db:seed --class=CoaSeeder
   ```

2. **Verifikasi**
   - Buka menu **Chart of Accounts**
   - Cari akun dengan kode `3-2001`
   - Pastikan nama akun adalah **"Ikhtisar Laba Rugi"**

### **Detail Akun Ikhtisar Laba Rugi:**

| Field | Nilai |
|-------|-------|
| **Kode Akun** | `3-2001` |
| **Nama Akun** | `Ikhtisar Laba Rugi` |
| **Tipe Akun** | `Ekuitas` |
| **Posisi Normal** | **`Debit`** ⚠️ (WAJIB) |
| **Level** | `2` |
| **Parent ID** | `3-0000` (EKUITAS) |
| **Status** | `Aktif` |

**Catatan Penting:**
- ⚠️ **Posisi Normal HARUS Debit**, jangan pilih Kredit
- Akun ini bersifat **sementara** dan hanya digunakan saat penutupan akhir tahun
- Setelah penutupan, saldo akun ini akan menjadi **0**
- Akun ini tidak digunakan untuk transaksi harian

**Penjelasan Posisi Normal Debit:**
- Dalam jurnal penutupan, akun ini akan menerima:
  - **Kredit** dari pendapatan (menambah saldo kredit)
  - **Debit** dari beban (menambah saldo debit)
- Jika **Laba**: Saldo akhir akan **Kredit** (pendapatan > beban)
- Jika **Rugi**: Saldo akhir akan **Debit** (beban > pendapatan)
- Posisi normal **Debit** digunakan untuk konsistensi dengan sistem

---

## 📝 LANGKAH-LANGKAH PENUTUPAN AKHIR TAHUN

### **LANGKAH 1: Verifikasi Data**

#### 1.1. Cek Laporan Laba Rugi
- Buka menu **Laporan → Laba Rugi**
- Pilih periode: **1 Januari - 31 Desember [Tahun Berjalan]**
- Catat:
  - **Total Pendapatan**: Rp __________
  - **Total Beban**: Rp __________
  - **Laba/Rugi Bersih**: Rp __________

#### 1.2. Cek Neraca
- Buka menu **Laporan → Neraca**
- Pilih tanggal: **31 Desember [Tahun Berjalan]**
- Pastikan: **Total Aset = Total Liabilitas + Total Ekuitas**
- Jika tidak balance, periksa kembali semua jurnal

#### 1.3. Tutup Semua Periode Tahun Berjalan
- Buka menu **Periode**
- Untuk setiap periode tahun berjalan (Januari - Desember):
  - Klik **Edit**
  - Ubah **Status** menjadi **Closed**
  - **Simpan**

---

### **LANGKAH 2: Buat Jurnal Penutupan**

Jurnal penutupan terdiri dari 4 jenis jurnal:

#### **Jurnal 1: Menutup Akun Pendapatan**

**Tujuan**: Memindahkan saldo semua akun pendapatan ke akun Ikhtisar Laba Rugi

**Contoh Jurnal:**
```
Tanggal: 31 Desember [Tahun Berjalan]
No. Bukti: JRN/[Tahun]/12/CLOSE-001
Deskripsi: Penutupan Akun Pendapatan

Detail:
- Debit: Pendapatan Penjualan (4-1002)     Rp [Saldo Pendapatan]
- Kredit: Ikhtisar Laba Rugi (3-2001)      Rp [Saldo Pendapatan]
```

**Cara Input:**
1. Buka menu **Jurnal → Buat Jurnal Baru**
2. Isi:
   - **No. Bukti**: `JRN/[Tahun]/12/CLOSE-001`
   - **Tanggal**: `31 Desember [Tahun Berjalan]`
   - **Periode**: Pilih periode Desember [Tahun Berjalan]
   - **Deskripsi**: `Penutupan Akun Pendapatan`
3. Tambah detail:
   - **Akun**: Pilih semua akun Pendapatan (4-1001, 4-1002, 4-1003)
   - **Posisi**: **Debit** (untuk menutup saldo kredit)
   - **Jumlah**: Masukkan saldo masing-masing akun
4. Tambah detail untuk Ikhtisar Laba Rugi:
   - **Akun**: Ikhtisar Laba Rugi (3-2001) - jika belum ada, buat dulu di COA
   - **Posisi**: **Kredit**
   - **Jumlah**: Total semua pendapatan
5. Pastikan **Total Debit = Total Kredit**
6. **Simpan** dan **Post**

---

#### **Jurnal 2: Menutup Akun Beban**

**Tujuan**: Memindahkan saldo semua akun beban ke akun Ikhtisar Laba Rugi

**Contoh Jurnal:**
```
Tanggal: 31 Desember [Tahun Berjalan]
No. Bukti: JRN/[Tahun]/12/CLOSE-002
Deskripsi: Penutupan Akun Beban

Detail:
- Debit: Ikhtisar Laba Rugi (3-2001)       Rp [Total Beban]
- Kredit: HPP (5-1001)                      Rp [Saldo HPP]
- Kredit: Beban Gaji (5-1002)               Rp [Saldo Beban Gaji]
- Kredit: Beban Lain-lain (5-1009)          Rp [Saldo Beban Lain-lain]
- ... (semua akun beban)
```

**Cara Input:**
1. Buat jurnal baru dengan:
   - **No. Bukti**: `JRN/[Tahun]/12/CLOSE-002`
   - **Tanggal**: `31 Desember [Tahun Berjalan]`
   - **Deskripsi**: `Penutupan Akun Beban`
2. Tambah detail untuk Ikhtisar Laba Rugi:
   - **Akun**: Ikhtisar Laba Rugi (3-2001)
   - **Posisi**: **Debit**
   - **Jumlah**: Total semua beban
3. Tambah detail untuk setiap akun beban:
   - **Akun**: Pilih akun beban (5-1001, 5-1002, dll)
   - **Posisi**: **Kredit** (untuk menutup saldo debit)
   - **Jumlah**: Masukkan saldo masing-masing akun
4. Pastikan balance, **Simpan** dan **Post**

---

#### **Jurnal 3: Menutup Ikhtisar Laba Rugi ke Laba Ditahan**

**Tujuan**: Memindahkan saldo Ikhtisar Laba Rugi (Laba/Rugi bersih) ke Laba Ditahan

**Jika Laba:**
```
Tanggal: 31 Desember [Tahun Berjalan]
No. Bukti: JRN/[Tahun]/12/CLOSE-003
Deskripsi: Penutupan Ikhtisar Laba Rugi ke Laba Ditahan

Detail:
- Debit: Ikhtisar Laba Rugi (3-2001)        Rp [Laba Bersih]
- Kredit: Laba Ditahan (3-1003)             Rp [Laba Bersih]
```

**Jika Rugi:**
```
Tanggal: 31 Desember [Tahun Berjalan]
No. Bukti: JRN/[Tahun]/12/CLOSE-003
Deskripsi: Penutupan Ikhtisar Laba Rugi ke Laba Ditahan

Detail:
- Debit: Laba Ditahan (3-1003)              Rp [Rugi Bersih]
- Kredit: Ikhtisar Laba Rugi (3-2001)       Rp [Rugi Bersih]
```

**Cara Input:**
1. Buat jurnal baru dengan:
   - **No. Bukti**: `JRN/[Tahun]/12/CLOSE-003`
   - **Tanggal**: `31 Desember [Tahun Berjalan]`
   - **Deskripsi**: `Penutupan Ikhtisar Laba Rugi ke Laba Ditahan`
2. Input sesuai kondisi (Laba atau Rugi)
3. Pastikan balance, **Simpan** dan **Post**

---

#### **Jurnal 4: Menutup Akun Prive (jika ada)**

**Tujuan**: Memindahkan saldo Prive ke Modal Pemilik

**Contoh Jurnal:**
```
Tanggal: 31 Desember [Tahun Berjalan]
No. Bukti: JRN/[Tahun]/12/CLOSE-004
Deskripsi: Penutupan Akun Prive

Detail:
- Debit: Modal Pemilik (3-1001)             Rp [Saldo Prive]
- Kredit: Prive (3-1002)                    Rp [Saldo Prive]
```

**Cara Input:**
1. Buat jurnal baru dengan:
   - **No. Bukti**: `JRN/[Tahun]/12/CLOSE-004`
   - **Tanggal**: `31 Desember [Tahun Berjalan]`
   - **Deskripsi**: `Penutupan Akun Prive`
2. Input sesuai contoh di atas
3. Pastikan balance, **Simpan** dan **Post**

---

### **LANGKAH 3: Verifikasi Setelah Penutupan**

#### 3.1. Cek Saldo Akun Pendapatan
- Buka menu **Buku Besar**
- Pilih akun pendapatan (4-1001, 4-1002, 4-1003)
- Periode: **1 Januari - 31 Desember [Tahun Berjalan]**
- **Pastikan saldo akhir = 0** (sudah tertutup)

#### 3.2. Cek Saldo Akun Beban
- Buka menu **Buku Besar**
- Pilih akun beban (5-1001, 5-1002, dll)
- Periode: **1 Januari - 31 Desember [Tahun Berjalan]**
- **Pastikan saldo akhir = 0** (sudah tertutup)

#### 3.3. Cek Saldo Ikhtisar Laba Rugi
- Buka menu **Buku Besar**
- Pilih akun Ikhtisar Laba Rugi (3-2001)
- Periode: **1 Januari - 31 Desember [Tahun Berjalan]**
- **Pastikan saldo akhir = 0** (sudah tertutup)

#### 3.4. Cek Neraca Akhir Tahun
- Buka menu **Laporan → Neraca**
- Pilih tanggal: **31 Desember [Tahun Berjalan]**
- **Pastikan tetap balance**

---

### **LANGKAH 4: Persiapan Tahun Baru**

#### 4.1. Buat Periode Tahun Baru
- Buka menu **Periode**
- Klik **Tambah Periode**
- Buat periode untuk **Januari [Tahun Baru]**:
  - **Tahun**: [Tahun Baru]
  - **Bulan**: 1 (Januari)
  - **Tanggal Mulai**: 1 Januari [Tahun Baru]
  - **Tanggal Selesai**: 31 Januari [Tahun Baru]
  - **Status**: **Open**
- Ulangi untuk semua bulan tahun baru (Februari - Desember)

#### 4.2. Verifikasi Saldo Awal Tahun Baru

**Cek Neraca 1 Januari Tahun Baru:**
- Buka menu **Laporan → Neraca**
- Pilih tanggal: **1 Januari [Tahun Baru]**
- **Pastikan:**
  - Saldo Aset = Saldo akhir tahun sebelumnya
  - Saldo Liabilitas = Saldo akhir tahun sebelumnya
  - Saldo Ekuitas = Saldo akhir tahun sebelumnya (termasuk Laba Ditahan)
  - **Total Aset = Total Liabilitas + Total Ekuitas** ✅

---

## 📊 CONTOH LENGKAP PENUTUPAN

### **Data Awal (31 Desember 2024):**

**Saldo Akun Pendapatan:**
- Pendapatan Penjualan (4-1002): Rp 150.000.000 (Kredit)
- Pendapatan Jasa (4-1001): Rp 50.000.000 (Kredit)

**Saldo Akun Beban:**
- HPP (5-1001): Rp 80.000.000 (Debit)
- Beban Gaji (5-1002): Rp 30.000.000 (Debit)
- Beban Lain-lain (5-1009): Rp 20.000.000 (Debit)

**Laba Bersih = (150.000.000 + 50.000.000) - (80.000.000 + 30.000.000 + 20.000.000)**
**Laba Bersih = Rp 70.000.000**

---

### **Jurnal Penutupan:**

#### **Jurnal 1: Penutupan Pendapatan**
```
No. Bukti: JRN/2024/12/CLOSE-001
Tanggal: 31 Desember 2024

Debit:
- Pendapatan Penjualan (4-1002)     Rp 150.000.000
- Pendapatan Jasa (4-1001)           Rp  50.000.000

Kredit:
- Ikhtisar Laba Rugi (3-2001)        Rp 200.000.000
```

#### **Jurnal 2: Penutupan Beban**
```
No. Bukti: JRN/2024/12/CLOSE-002
Tanggal: 31 Desember 2024

Debit:
- Ikhtisar Laba Rugi (3-2001)        Rp 130.000.000

Kredit:
- HPP (5-1001)                       Rp  80.000.000
- Beban Gaji (5-1002)                Rp  30.000.000
- Beban Lain-lain (5-1009)           Rp  20.000.000
```

#### **Jurnal 3: Penutupan Ikhtisar Laba Rugi**
```
No. Bukti: JRN/2024/12/CLOSE-003
Tanggal: 31 Desember 2024

Debit:
- Ikhtisar Laba Rugi (3-2001)        Rp  70.000.000

Kredit:
- Laba Ditahan (3-1003)              Rp  70.000.000
```

---

## ⚙️ CATATAN PENTING

### **1. Akun Ikhtisar Laba Rugi**
Jika akun **Ikhtisar Laba Rugi (3-2001)** belum ada di COA, buat terlebih dahulu dengan mengikuti panduan di bagian **"MEMBUAT AKUN COA UNTUK IKHTISAR LABA RUGI"** di atas.

**Detail Akun:**
- **Kode Akun**: `3-2001`
- **Nama Akun**: `Ikhtisar Laba Rugi`
- **Tipe Akun**: `Ekuitas`
- **Posisi Normal**: **`Debit`** ⚠️ (WAJIB, jangan pilih Kredit)
- **Level**: `2`
- **Parent**: `3-0000` (EKUITAS)

**Mengapa Posisi Normal Harus Debit?**
- Akun ini akan menerima pendapatan (Kredit) dan beban (Debit)
- Posisi normal Debit memudahkan perhitungan saldo di sistem
- Sudah diset sebagai Debit di seeder standar

### **2. Saldo Awal Tahun Baru**
Setelah penutupan, saldo akun-akun **Aset, Liabilitas, dan Ekuitas** akan otomatis menjadi saldo awal tahun baru. Tidak perlu membuat jurnal khusus untuk saldo awal.

### **3. Akun Pendapatan dan Beban**
Setelah penutupan, akun pendapatan dan beban akan memiliki saldo **0** dan siap digunakan untuk transaksi tahun baru.

### **4. Backup Database**
**SELALU backup database** sebelum melakukan penutupan akhir tahun:
```bash
# Backup MySQL
mysqldump -u root akuntan_db > backup_sebelum_penutupan_2024.sql
```

---

## 🔍 TROUBLESHOOTING

### **Masalah: Neraca tidak balance setelah penutupan**

**Solusi:**
1. Cek apakah semua jurnal penutupan sudah diposting
2. Pastikan semua akun pendapatan dan beban sudah tertutup (saldo = 0)
3. Periksa kembali perhitungan Ikhtisar Laba Rugi
4. Pastikan tidak ada transaksi yang terlewat

### **Masalah: Saldo awal tahun baru tidak sesuai**

**Solusi:**
1. Pastikan semua jurnal penutupan sudah diposting
2. Cek Buku Besar untuk setiap akun Aset, Liabilitas, dan Ekuitas
3. Pastikan tidak ada transaksi yang masuk ke periode tahun sebelumnya setelah penutupan

### **Masalah: Akun pendapatan/beban masih memiliki saldo**

**Solusi:**
1. Pastikan jurnal penutupan sudah dibuat dengan benar
2. Pastikan jumlah yang diinput di jurnal penutupan sesuai dengan saldo akun
3. Periksa kembali posisi debit/kredit di jurnal penutupan

---

## ✅ CHECKLIST PENUTUPAN AKHIR TAHUN

- [ ] Semua transaksi tahun berjalan sudah diinput
- [ ] Semua jurnal sudah diposting
- [ ] Backup database sudah dibuat
- [ ] Semua periode tahun berjalan sudah ditutup (Closed)
- [ ] Jurnal penutupan pendapatan sudah dibuat dan diposting
- [ ] Jurnal penutupan beban sudah dibuat dan diposting
- [ ] Jurnal penutupan Ikhtisar Laba Rugi sudah dibuat dan diposting
- [ ] Jurnal penutupan Prive sudah dibuat dan diposting (jika ada)
- [ ] Saldo akun pendapatan = 0 (sudah tertutup)
- [ ] Saldo akun beban = 0 (sudah tertutup)
- [ ] Saldo Ikhtisar Laba Rugi = 0 (sudah tertutup)
- [ ] Neraca 31 Desember tetap balance
- [ ] Periode tahun baru sudah dibuat
- [ ] Neraca 1 Januari tahun baru balance dan sesuai

---

## 📞 BANTUAN

Jika mengalami kesulitan, periksa:
1. **Buku Besar** untuk melihat saldo setiap akun
2. **Laporan Laba Rugi** untuk melihat total pendapatan dan beban
3. **Laporan Neraca** untuk memastikan balance

---

**Selamat melakukan penutupan akhir tahun!** 🎉

