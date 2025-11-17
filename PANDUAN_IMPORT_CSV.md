# PANDUAN IMPORT CSV KE JURNAL
## Sistem Akuntansi - PT. Rama Advertize

---

## 📋 PERSIAPAN

### 1. Pastikan File CSV Tersedia
File CSV harus memiliki format berikut:
```
Timestamp,Tanggal,Pemasukan/Pengeluaran,Jenis,Deskripsi,Debit,Kredit
2/12/2025 3:08,8/3/2024,Pengeluaran,Trasportasi -,Akomodasi Febri,0,150000
...
```

### 2. Pastikan COA Sudah Ada
Jalankan seeder COA jika belum:
```bash
php artisan db:seed --class=CoaSeeder
```

### 3. Pastikan User ID Tersedia
Default user ID adalah 1. Jika menggunakan user lain, gunakan parameter `--user-id`.

---

## 🚀 CARA MENGGUNAKAN

### **Import Normal (Menyimpan Data)**

```bash
php artisan jurnal:import-csv "Keuangan Rama Advertize.csv"
```

Atau dengan path lengkap:
```bash
php artisan jurnal:import-csv "C:\xampp\htdocs\seb\akuntan\Keuangan Rama Advertize.csv"
```

### **Import dengan User ID Tertentu**

```bash
php artisan jurnal:import-csv "Keuangan Rama Advertize.csv" --user-id=2
```

### **Dry Run (Preview tanpa Menyimpan)**

Untuk melihat preview data yang akan diimport tanpa menyimpan:
```bash
php artisan jurnal:import-csv "Keuangan Rama Advertize.csv" --dry-run
```

---

## 📊 MAPPING TRANSAKSI KE COA

Command ini secara otomatis memetakan transaksi ke akun COA:

| Jenis Transaksi | COA | Keterangan |
|----------------|-----|------------|
| **Pemasukan** | | |
| Penjualan Barang | 4-1002 | Pendapatan Penjualan |
| Penjualan Jasa / Pejualan Jasa | 4-1001 | Pendapatan Jasa |
| Lain-lain | 4-1003 | Pendapatan Lain-lain |
| **Pengeluaran** | | |
| Belanja | 5-1001 | HPP (Harga Pokok Penjualan) |
| Trasportasi | 5-1009 | Beban Lain-lain |
| Perbaikan | 5-1009 | Beban Lain-lain |
| Kantor | 5-1008 | Beban Administrasi |
| Hadiah | 5-1009 | Beban Lain-lain |
| Lain-lain | 5-1009 | Beban Lain-lain |

**Catatan**: Semua transaksi menggunakan akun **Kas (1-1001)** sebagai pasangan.

---

## 🔄 LOGIKA JURNAL

### **Untuk Pemasukan (Income)**
- **Debit**: Kas (1-1001)
- **Kredit**: Akun Pendapatan (sesuai jenis)

### **Untuk Pengeluaran (Expense)**
- **Debit**: Akun Beban (sesuai jenis)
- **Kredit**: Kas (1-1001)

---

## ⚙️ FITUR

1. **Auto-detect Periode**: Command akan otomatis membuat periode jika belum ada
2. **Auto-generate No. Bukti**: Format: `JRN/YYYY/MM/NNNN`
3. **Skip Duplicate**: Jika jurnal dengan deskripsi dan tanggal yang sama sudah ada, akan dilewati
4. **Validasi Balance**: Setiap jurnal otomatis balance (Debit = Kredit)

---

## 📝 CONTOH OUTPUT

```
Membaca file CSV: Keuangan Rama Advertize.csv
Ditemukan 104 transaksi
Validasi COA mapping...
Periode baru dibuat: 2024-08
Periode baru dibuat: 2024-09
...

=== IMPORT SELESAI ===
Berhasil: 104
Error: 0
Dilewati: 0
```

---

## ⚠️ CATATAN PENTING

1. **Status Jurnal**: Semua jurnal yang diimport akan memiliki status **Draft**. Anda perlu memposting secara manual melalui menu Jurnal.

2. **Periode**: Pastikan periode untuk tanggal transaksi sudah dibuat atau akan dibuat otomatis dengan status **Open**.

3. **Format Tanggal**: Command mendukung format tanggal:
   - `M/D/Y` (contoh: 8/3/2024)
   - `M-D-Y` (contoh: 8-3-2024)
   - `n/j/Y` (contoh: 8/3/2024 tanpa leading zero)

4. **Backup Database**: Disarankan untuk backup database sebelum import:
   ```bash
   mysqldump -u root akuntan_db > backup_sebelum_import.sql
   ```

5. **Review Jurnal**: Setelah import, review semua jurnal di menu Jurnal untuk memastikan data sudah benar sebelum diposting.

---

## 🔍 TROUBLESHOOTING

### **Error: File tidak ditemukan**
- Pastikan path file benar
- Gunakan path lengkap jika perlu
- Pastikan file ada di direktori yang benar

### **Error: COA tidak ditemukan**
- Jalankan: `php artisan db:seed --class=CoaSeeder`
- Pastikan semua akun COA sudah ada

### **Error: Periode tidak ditemukan**
- Command akan otomatis membuat periode
- Jika masih error, buat periode manual melalui menu Periode

### **Jurnal tidak balance**
- Command otomatis membuat jurnal yang balance
- Jika ada masalah, periksa format CSV

### **Transaksi terlewat**
- Periksa log output command
- Pastikan format CSV sesuai
- Periksa apakah ada transaksi dengan jumlah 0 (akan dilewati)

---

## ✅ CHECKLIST SETELAH IMPORT

- [ ] Semua transaksi berhasil diimport
- [ ] Review jurnal di menu Jurnal
- [ ] Pastikan semua jurnal balance
- [ ] Post jurnal yang sudah benar
- [ ] Cek Buku Besar untuk memastikan saldo benar
- [ ] Cek Laporan Laba Rugi dan Neraca

---

## 📞 BANTUAN

Jika mengalami masalah:
1. Gunakan `--dry-run` untuk preview tanpa menyimpan
2. Periksa log output command
3. Periksa format CSV
4. Pastikan semua COA sudah ada

---

**Selamat menggunakan!** 🎉

