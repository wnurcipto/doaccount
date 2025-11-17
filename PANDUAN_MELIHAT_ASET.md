# Panduan Melihat Aset di Do-Account

Dokumen ini menjelaskan cara membaca dan melihat aset di sistem Do-Account, terutama untuk aset yang masih ada dan aset yang sudah berjalan beberapa tahun.

---

## 1. Melihat Aset di Laporan Neraca

### Cara Akses:
1. Login ke aplikasi Do-Account
2. Klik menu **"Laporan"** → **"Neraca"**
3. Pilih tanggal yang ingin dilihat (misalnya: 31 Desember 2024)
4. Klik **"Tampilkan Laporan"**

### Informasi yang Ditampilkan:
- **Kolom Kiri**: Menampilkan semua akun dengan tipe **"Aset"**
- Setiap aset menampilkan:
  - Kode Akun (contoh: `1-1.01`)
  - Nama Akun (contoh: `Kas di Bank`)
  - Saldo per tanggal yang dipilih

### Catatan Penting:
- **Aset yang masih ada** akan ditampilkan jika saldonya **tidak nol** (saldo > 0)
- **Aset yang sudah tidak ada** (sudah dijual/disusutkan habis) akan **tidak ditampilkan** jika saldonya = 0
- Saldo dihitung **sampai tanggal yang dipilih** (menggunakan `getDataByTipeAkunUpTo`)

### Contoh:
```
ASET
1-1.01 - Kas di Bank                    Rp 50.000.000
1-1.02 - Kas di Tangan                  Rp 5.000.000
1-2.01 - Piutang Usaha                  Rp 25.000.000
1-3.01 - Persediaan Barang              Rp 30.000.000
1-4.01 - Peralatan Kantor               Rp 15.000.000
1-4.02 - Kendaraan                      Rp 80.000.000
1-4.03 - Akumulasi Penyusutan Kendaraan Rp -10.000.000

TOTAL ASET                               Rp 195.000.000
```

---

## 2. Melihat Detail Aset di Buku Besar

### Cara Akses:
1. Login ke aplikasi Do-Account
2. Klik menu **"Buku Besar"**
3. Pilih:
   - **Periode** (bulan dan tahun)
   - **Tanggal Mulai** dan **Tanggal Selesai**
   - **Kode Akun** atau **Nama Akun** (untuk aset tertentu)
4. Klik **"Tampilkan"**

### Informasi yang Ditampilkan:
- **Saldo Awal**: Saldo aset di awal periode
- **Transaksi Detail**: Semua transaksi yang mempengaruhi aset tersebut:
  - Tanggal transaksi
  - No. Bukti
  - Deskripsi
  - Debit (penambahan aset)
  - Kredit (pengurangan aset)
  - Saldo setelah transaksi
- **Saldo Akhir**: Saldo aset di akhir periode

### Contoh Buku Besar Aset:
```
Kode Akun: 1-4.02
Nama Akun: Kendaraan
Periode: Desember 2024

Saldo Awal: Rp 80.000.000

Tanggal    | No. Bukti        | Deskripsi              | Debit        | Kredit       | Saldo
-----------|------------------|------------------------|--------------|--------------|------------------
01/12/2024 | JRN/2024/12/001 | Pembelian Kendaraan    | 80.000.000   |              | 80.000.000
15/12/2024 | JRN/2024/12/010 | Penyusutan Kendaraan  |              | 10.000.000   | 70.000.000

Saldo Akhir: Rp 70.000.000
```

---

## 3. Melihat Aset yang Masih Ada (Belum Dihapus/Disusutkan)

### Cara 1: Melihat di Neraca
- Aset yang **masih ada** akan muncul di Neraca jika saldonya **> 0**
- Aset yang sudah **habis/disusutkan** (saldo = 0) **tidak akan muncul**

### Cara 2: Melihat di Buku Besar
1. Buka **Buku Besar**
2. Pilih periode **terbaru** (misalnya: Desember 2024)
3. Pilih akun aset yang ingin dicek
4. Lihat **Saldo Akhir**:
   - Jika **Saldo Akhir > 0** → Aset masih ada
   - Jika **Saldo Akhir = 0** → Aset sudah tidak ada (habis/disusutkan)

### Cara 3: Filter di Neraca
- Gunakan tanggal **terbaru** (misalnya: 31 Desember 2024)
- Hanya aset dengan saldo > 0 yang akan ditampilkan

---

## 4. Melihat Aset yang Sudah Berjalan Beberapa Tahun

### Masalah:
Aset yang dibeli di tahun sebelumnya (misalnya: 2023) dan masih ada di tahun sekarang (2024) perlu dilihat saldonya di berbagai periode.

### Solusi:

#### A. Melihat Saldo Aset di Tahun Berbeda (Neraca)
1. Buka **Laporan Neraca**
2. Pilih tanggal di **tahun yang berbeda**:
   - **31 Desember 2023** → Lihat saldo aset di akhir tahun 2023
   - **31 Desember 2024** → Lihat saldo aset di akhir tahun 2024
3. Bandingkan saldo antara tahun untuk melihat perubahan

#### B. Melihat History Aset (Buku Besar)
1. Buka **Buku Besar**
2. Pilih **periode yang berbeda**:
   - **Desember 2023** → Lihat transaksi dan saldo di tahun 2023
   - **Desember 2024** → Lihat transaksi dan saldo di tahun 2024
3. Lihat **Saldo Awal** di periode 2024 untuk melihat saldo akhir dari tahun 2023

#### C. Melihat Seluruh History (Buku Besar dengan Rentang Waktu Panjang)
1. Buka **Buku Besar**
2. Pilih:
   - **Tanggal Mulai**: 1 Januari 2023 (atau tanggal pembelian aset)
   - **Tanggal Selesai**: 31 Desember 2024 (tanggal terbaru)
   - **Kode Akun**: Pilih akun aset yang ingin dilihat
3. Klik **"Tampilkan"**
4. Anda akan melihat **semua transaksi** dari tahun 2023 sampai 2024

### Contoh:
```
Kode Akun: 1-4.02 - Kendaraan

Neraca 31 Desember 2023:
- Kendaraan: Rp 80.000.000
- Akumulasi Penyusutan: Rp -5.000.000
- Nilai Bersih: Rp 75.000.000

Neraca 31 Desember 2024:
- Kendaraan: Rp 80.000.000
- Akumulasi Penyusutan: Rp -10.000.000
- Nilai Bersih: Rp 70.000.000

Perubahan: Penyusutan bertambah Rp 5.000.000 (dari Rp 5.000.000 menjadi Rp 10.000.000)
```

---

## 5. Tips dan Best Practices

### A. Melihat Aset yang Masih Aktif
- **Gunakan tanggal terbaru** di Neraca untuk melihat aset yang masih ada
- **Hanya aset dengan saldo > 0** yang ditampilkan

### B. Melihat History Aset
- **Gunakan Buku Besar** dengan rentang waktu panjang untuk melihat seluruh history
- **Bandingkan Neraca** di tanggal berbeda untuk melihat perubahan saldo

### C. Memahami Penyusutan Aset
- Aset tetap biasanya memiliki **akun penyusutan** (contoh: `1-4.03 - Akumulasi Penyusutan Kendaraan`)
- **Nilai bersih aset** = Nilai aset - Akumulasi penyusutan
- Lihat kedua akun (aset dan akumulasi penyusutan) untuk mendapatkan nilai bersih

### D. Export dan Print
- **Export PDF** untuk menyimpan laporan aset (hanya untuk plan Professional/Enterprise)
- **Print** untuk dokumentasi fisik

---

## 6. FAQ (Frequently Asked Questions)

### Q: Kenapa aset saya tidak muncul di Neraca?
**A:** Kemungkinan:
- Saldo aset = 0 (sudah habis/disusutkan)
- Belum ada transaksi yang mempengaruhi aset tersebut
- Tanggal yang dipilih belum ada transaksi

### Q: Bagaimana melihat aset yang dibeli di tahun sebelumnya?
**A:** 
1. Buka **Buku Besar**
2. Pilih rentang waktu dari tahun pembelian sampai sekarang
3. Pilih akun aset yang ingin dilihat

### Q: Bagaimana melihat nilai bersih aset setelah penyusutan?
**A:**
1. Lihat **Neraca** untuk melihat:
   - Nilai aset (contoh: Rp 80.000.000)
   - Akumulasi penyusutan (contoh: Rp -10.000.000)
2. **Nilai bersih** = Nilai aset - Akumulasi penyusutan (contoh: Rp 70.000.000)

### Q: Apakah bisa melihat aset per kategori?
**A:** Ya, di Neraca aset ditampilkan per akun. Setiap akun mewakili kategori aset tertentu (contoh: Kas, Piutang, Persediaan, Aset Tetap).

---

## 7. Struktur Akun Aset (COA)

Sistem menggunakan struktur COA standar untuk aset:

```
1-1.xx - Aset Lancar
  1-1.01 - Kas di Bank
  1-1.02 - Kas di Tangan
  1-2.xx - Piutang
    1-2.01 - Piutang Usaha
    1-2.02 - Piutang Lain-lain
  1-3.xx - Persediaan
    1-3.01 - Persediaan Barang
1-4.xx - Aset Tetap
  1-4.01 - Peralatan Kantor
  1-4.02 - Kendaraan
  1-4.03 - Akumulasi Penyusutan Kendaraan
  1-4.04 - Tanah
  1-4.05 - Bangunan
```

---

## 8. Kesimpulan

Untuk melihat aset di Do-Account:

1. **Laporan Neraca**: Untuk melihat saldo aset per tanggal tertentu
2. **Buku Besar**: Untuk melihat detail transaksi dan history aset
3. **Gunakan tanggal terbaru** untuk melihat aset yang masih ada
4. **Gunakan rentang waktu panjang** di Buku Besar untuk melihat history aset yang sudah berjalan beberapa tahun

Jika ada pertanyaan lebih lanjut, silakan hubungi tim support atau lihat dokumentasi lainnya.

