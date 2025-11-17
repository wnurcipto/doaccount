# Panduan Melihat Hutang dan Piutang di Do-Account

Panduan ini menjelaskan cara melihat dan mengelola hutang (liabilitas) dan piutang (aset) di sistem Do-Account, dari awal bisnis sampai sekarang.

---

## **1. Pengertian Hutang dan Piutang**

### **Piutang (Accounts Receivable)**
- **Definisi**: Uang yang **harus diterima** dari customer/pelanggan
- **Tipe Akun**: Aset (karena akan menjadi kas di masa depan)
- **Posisi Normal**: Debit (bertambah di Debit, berkurang di Kredit)
- **Contoh**: 
  - Penjualan barang/jasa secara kredit
  - Tagihan yang belum dibayar customer

### **Hutang (Accounts Payable)**
- **Definisi**: Uang yang **harus dibayar** ke supplier/kreditur
- **Tipe Akun**: Liabilitas (kewajiban yang harus dibayar)
- **Posisi Normal**: Kredit (bertambah di Kredit, berkurang di Debit)
- **Contoh**:
  - Pembelian barang secara kredit
  - Utang usaha ke supplier
  - Utang gaji karyawan
  - Utang pajak

---

## **2. Melihat Piutang di Laporan Neraca**

### **Cara Akses:**
1. Login ke aplikasi Do-Account
2. Klik menu **"Laporan"** → **"Neraca"**
3. Pilih tanggal yang ingin dilihat (misalnya: **17 November 2025**)
4. Klik **"Tampilkan Laporan"**

### **Informasi yang Ditampilkan:**
- **Kolom Kiri (Aset)**: Menampilkan semua akun dengan tipe **"Aset"**, termasuk **Piutang**
- Setiap piutang menampilkan:
  - Kode Akun (contoh: `1-1003`)
  - Nama Akun (contoh: `Piutang Usaha`)
  - Saldo per tanggal yang dipilih

### **Contoh Output (Data Real dari admin@ramaadvertize.com):**
```
ASET
1-1001 - Kas                            Rp 7.707.000
1-1003 - Piutang Usaha                  Rp 0
1-1004 - Persediaan Barang              Rp 1.605.000

TOTAL ASET                               Rp 9.312.000
```

**Catatan:** 
- Data di atas adalah data real dari akun **Administrator (admin@ramaadvertize.com)** per tanggal **17 November 2025**
- Piutang Usaha saat ini saldonya **Rp 0** (tidak ada piutang aktif)
- Jika ada piutang, akan muncul dengan saldo > 0

---

## **3. Melihat Hutang di Laporan Neraca**

### **Cara Akses:**
1. Login ke aplikasi Do-Account
2. Klik menu **"Laporan"** → **"Neraca"**
3. Pilih tanggal yang ingin dilihat (misalnya: **17 November 2025**)
4. Klik **"Tampilkan Laporan"**

### **Informasi yang Ditampilkan:**
- **Kolom Kanan (Liabilitas)**: Menampilkan semua akun dengan tipe **"Liabilitas"**, termasuk **Hutang**
- Setiap hutang menampilkan:
  - Kode Akun (contoh: `2-1001`)
  - Nama Akun (contoh: `Utang Usaha`)
  - Saldo per tanggal yang dipilih

### **Contoh Output:**
```
LIABILITAS
2-1001 - Utang Usaha                    Rp 0
2-1002 - Utang Gaji                     Rp 0
2-1003 - Utang Pajak                    Rp 0
2-2001 - Utang Bank                     Rp 0

TOTAL LIABILITAS                         Rp 0
```

**Catatan:** 
- Data di atas adalah data real dari akun **Administrator (admin@ramaadvertize.com)** per tanggal **17 November 2025**
- Semua hutang saat ini saldonya **Rp 0** (tidak ada hutang aktif)
- Jika ada hutang, akan muncul dengan saldo > 0

---

## **4. Melihat Detail Piutang di Buku Besar**

### **Cara Akses:**
1. Login ke aplikasi Do-Account
2. Klik menu **"Buku Besar"**
3. Pilih:
   - **Kode Akun** atau **Nama Akun**: Pilih akun piutang (misalnya: `1-1003 - Piutang Usaha`)
   - **Tanggal Mulai**: **01 Agustus 2024** (awal bisnis)
   - **Tanggal Selesai**: **17 November 2025** (atau tanggal hari ini)
4. Klik **"Tampilkan"**

### **Informasi yang Ditampilkan:**
- **Saldo Awal**: Saldo piutang di awal periode
- **Transaksi Detail**: Semua transaksi yang mempengaruhi piutang:
  - **Debit**: Penambahan piutang (penjualan kredit, tagihan baru)
  - **Kredit**: Pengurangan piutang (penerimaan pembayaran, pelunasan)
  - **Saldo**: Saldo setelah setiap transaksi
- **Saldo Akhir**: Saldo piutang di akhir periode

### **Contoh Output Buku Besar Piutang:**
```
Kode Akun: 1-1003
Nama Akun: Piutang Usaha
Periode: 01/08/2024 s/d 17/11/2025

Saldo Awal: Rp 0

Tanggal    | No. Bukti        | Deskripsi                      | Debit        | Kredit       | Saldo
-----------|------------------|--------------------------------|--------------|--------------|------------------
15/08/2024 | JRN/2024/08/0010| Penjualan Kredit ke Customer A | 5.000.000    |              | 5.000.000
20/08/2024 | JRN/2024/08/0015| Penjualan Kredit ke Customer B | 3.000.000    |              | 8.000.000
25/08/2024 | JRN/2024/08/0020| Pelunasan Customer A           |              | 5.000.000    | 3.000.000
30/09/2024 | JRN/2024/09/0010| Penjualan Kredit ke Customer C | 2.500.000    |              | 5.500.000
... (transaksi berlanjut) ...

Saldo Akhir: Rp 5.500.000
```

**Penjelasan:**
- **Debit** = Piutang bertambah (ada tagihan baru)
- **Kredit** = Piutang berkurang (ada pembayaran/pelunasan)
- **Saldo** = Total piutang yang masih harus diterima

---

## **5. Melihat Detail Hutang di Buku Besar**

### **Cara Akses:**
1. Login ke aplikasi Do-Account
2. Klik menu **"Buku Besar"**
3. Pilih:
   - **Kode Akun** atau **Nama Akun**: Pilih akun hutang (misalnya: `2-1001 - Utang Usaha`)
   - **Tanggal Mulai**: **01 Agustus 2024** (awal bisnis)
   - **Tanggal Selesai**: **17 November 2025** (atau tanggal hari ini)
4. Klik **"Tampilkan"**

### **Informasi yang Ditampilkan:**
- **Saldo Awal**: Saldo hutang di awal periode
- **Transaksi Detail**: Semua transaksi yang mempengaruhi hutang:
  - **Kredit**: Penambahan hutang (pembelian kredit, utang baru)
  - **Debit**: Pengurangan hutang (pembayaran utang, pelunasan)
  - **Saldo**: Saldo setelah setiap transaksi
- **Saldo Akhir**: Saldo hutang di akhir periode

### **Contoh Output Buku Besar Hutang:**
```
Kode Akun: 2-1001
Nama Akun: Utang Usaha
Periode: 01/08/2024 s/d 17/11/2025

Saldo Awal: Rp 0

Tanggal    | No. Bukti        | Deskripsi                      | Debit        | Kredit       | Saldo
-----------|------------------|--------------------------------|--------------|--------------|------------------
10/08/2024 | JRN/2024/08/0005| Pembelian Kredit dari Supplier A|              | 10.000.000   | 10.000.000
15/08/2024 | JRN/2024/08/0010| Pembelian Kredit dari Supplier B|              | 5.000.000    | 15.000.000
20/08/2024 | JRN/2024/08/0015| Pelunasan Utang ke Supplier A   | 10.000.000   |              | 5.000.000
25/09/2024 | JRN/2024/09/0005| Pembelian Kredit dari Supplier C|              | 3.000.000    | 8.000.000
... (transaksi berlanjut) ...

Saldo Akhir: Rp 8.000.000
```

**Penjelasan:**
- **Kredit** = Hutang bertambah (ada utang baru)
- **Debit** = Hutang berkurang (ada pembayaran/pelunasan)
- **Saldo** = Total hutang yang masih harus dibayar

---

## **6. Daftar Akun Piutang dan Hutang**

### **Akun Piutang (Tipe: Aset, Posisi Normal: Debit):**
- `1-1003 - Piutang Usaha` (piutang dari penjualan)
- `1-1004 - Piutang Lain-lain` (piutang selain usaha)
- (dan akun piutang lainnya sesuai COA)

### **Akun Hutang (Tipe: Liabilitas, Posisi Normal: Kredit):**
- `2-1001 - Utang Usaha` (utang ke supplier)
- `2-1002 - Utang Gaji` (utang gaji karyawan)
- `2-1003 - Utang Pajak` (utang pajak)
- `2-2001 - Utang Bank` (utang ke bank)
- (dan akun hutang lainnya sesuai COA)

**Catatan:** 
- Kode akun di atas mengikuti struktur COA yang digunakan di sistem
- Untuk melihat daftar lengkap, buka menu **Chart of Accounts (COA)**

---

## **7. Cara Mencatat Piutang di Jurnal**

### **Skenario: Penjualan Kredit (Piutang Bertambah)**

**Transaksi**: Menjual barang/jasa seharga Rp 5.000.000 secara kredit

**Jurnal:**
```
| Akun | Posisi | Jumlah |
|------|--------|--------|
| Piutang Usaha (1-1003) | Debit | 5.000.000 |
| Pendapatan Penjualan (4-1002) | Kredit | 5.000.000 |
```

**Penjelasan:**
- Piutang bertambah → Debit
- Pendapatan bertambah → Kredit

### **Skenario: Pelunasan Piutang (Piutang Berkurang)**

**Transaksi**: Menerima pembayaran piutang sebesar Rp 5.000.000

**Jurnal:**
```
| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 5.000.000 |
| Piutang Usaha (1-1003) | Kredit | 5.000.000 |
```

**Penjelasan:**
- Kas bertambah → Debit
- Piutang berkurang → Kredit

---

## **8. Cara Mencatat Hutang di Jurnal**

### **Skenario: Pembelian Kredit (Hutang Bertambah)**

**Transaksi**: Membeli barang seharga Rp 10.000.000 secara kredit

**Jurnal:**
```
| Akun | Posisi | Jumlah |
|------|--------|--------|
| Persediaan Barang (1-1004) | Debit | 10.000.000 |
| Utang Usaha (2-1001) | Kredit | 10.000.000 |
```

**Penjelasan:**
- Persediaan bertambah → Debit
- Hutang bertambah → Kredit

### **Skenario: Pelunasan Hutang (Hutang Berkurang)**

**Transaksi**: Membayar utang sebesar Rp 10.000.000

**Jurnal:**
```
| Akun | Posisi | Jumlah |
|------|--------|--------|
| Utang Usaha (2-1001) | Debit | 10.000.000 |
| Kas (1-1001) | Kredit | 10.000.000 |
```

**Penjelasan:**
- Hutang berkurang → Debit
- Kas berkurang → Kredit

---

## **9. Melihat Piutang dan Hutang yang Masih Aktif**

### **Cara 1: Melihat di Neraca**
- Piutang dan Hutang yang **masih aktif** akan muncul di Neraca jika saldonya **> 0**
- Piutang dan Hutang yang sudah **lunas** (saldo = 0) **tidak akan muncul**

### **Cara 2: Melihat di Buku Besar**
1. Buka **Buku Besar**
2. Pilih periode **terbaru** (misalnya: 17 November 2025)
3. Pilih akun piutang atau hutang yang ingin dicek
4. Lihat **Saldo Akhir**:
   - Jika **Saldo Akhir > 0** → Masih ada piutang/hutang aktif
   - Jika **Saldo Akhir = 0** → Sudah lunas

---

## **10. Melihat History Piutang dan Hutang dari Awal Bisnis**

### **Langkah-langkah:**
1. Buka **Buku Besar**
2. Pilih akun piutang atau hutang yang ingin dilihat
3. Pilih rentang waktu panjang:
   - **Tanggal Mulai**: **01 Agustus 2024** (awal bisnis)
   - **Tanggal Selesai**: **17 November 2025** (tanggal terbaru)
4. Klik **"Tampilkan"**
5. Anda akan melihat:
   - **Saldo Awal** (dari Agustus 2024)
   - **Semua transaksi** dari awal bisnis sampai sekarang
   - **Saldo Akhir** (saldo terbaru)

### **Informasi yang Bisa Dilihat:**
- Kapan piutang/hutang pertama kali muncul
- Berapa kali ada penambahan piutang/hutang
- Berapa kali ada pelunasan
- Siapa customer/supplier yang memiliki piutang/hutang terbesar
- Piutang/hutang mana yang sudah lama belum dilunasi

---

## **11. Tips Praktis**

### **A. Memantau Piutang yang Belum Lunas**
- **Gunakan Neraca** dengan tanggal terbaru untuk melihat total piutang
- **Gunakan Buku Besar** untuk melihat detail per customer
- **Perhatikan piutang yang sudah lama** (lebih dari 30/60/90 hari) untuk follow-up

### **B. Memantau Hutang yang Harus Dibayar**
- **Gunakan Neraca** dengan tanggal terbaru untuk melihat total hutang
- **Gunakan Buku Besar** untuk melihat detail per supplier
- **Perhatikan jatuh tempo** hutang untuk perencanaan pembayaran

### **C. Analisis Aging Piutang/Hutang**
- Lihat **Buku Besar** dengan rentang waktu panjang
- Identifikasi piutang/hutang yang sudah lama
- Prioritaskan pelunasan berdasarkan umur piutang/hutang

### **D. Export dan Print**
- **Export PDF** untuk dokumentasi (hanya untuk plan Professional/Enterprise)
- **Print** untuk laporan ke supplier/customer

---

## **12. Contoh Skenario Praktis**

### **Skenario 1: "Saya ingin tahu berapa total piutang yang masih harus diterima"**

**Langkah 1: Lihat di Neraca**
- Buka **Neraca** → Tanggal: **17 November 2025**
- Lihat di kolom **Aset** → Cari akun **Piutang Usaha**
- **Hasil**: Saldo piutang saat ini

**Langkah 2: Lihat Detail di Buku Besar**
- Buka **Buku Besar**
- Pilih akun **1-1003 - Piutang Usaha**
- Tanggal Mulai: **01 Agustus 2024**
- Tanggal Selesai: **17 November 2025**
- **Hasil**: Semua transaksi piutang dari awal bisnis

---

### **Skenario 2: "Saya ingin tahu berapa total hutang yang masih harus dibayar"**

**Langkah 1: Lihat di Neraca**
- Buka **Neraca** → Tanggal: **17 November 2025**
- Lihat di kolom **Liabilitas** → Cari akun **Utang Usaha**, **Utang Gaji**, dll
- **Hasil**: Total hutang saat ini

**Langkah 2: Lihat Detail di Buku Besar**
- Buka **Buku Besar**
- Pilih akun hutang yang ingin dilihat (misalnya: **2-1001 - Utang Usaha**)
- Tanggal Mulai: **01 Agustus 2024**
- Tanggal Selesai: **17 November 2025**
- **Hasil**: Semua transaksi hutang dari awal bisnis

---

### **Skenario 3: "Saya ingin tahu piutang mana yang sudah lama belum dilunasi"**

**Langkah-langkah:**
1. Buka **Buku Besar** → Pilih akun **Piutang Usaha**
2. Tanggal Mulai: **01 Agustus 2024**
3. Tanggal Selesai: **17 November 2025**
4. Lihat transaksi dengan **Debit** (penambahan piutang)
5. Cek apakah ada transaksi **Kredit** (pelunasan) setelahnya
6. Jika tidak ada pelunasan, berarti piutang tersebut belum dilunasi

---

## **13. FAQ**

### Q: Apakah semua piutang/hutang dari awal bisnis akan muncul di Neraca sekarang?
**A:** Tidak. Hanya piutang/hutang dengan **saldo > 0** yang muncul di Neraca. Piutang/hutang yang sudah **lunas** (saldo = 0) tidak akan muncul.

### Q: Bagaimana melihat piutang/hutang yang sudah lunas?
**A:** Gunakan **Buku Besar** dengan rentang waktu dari awal bisnis sampai sekarang. Anda akan melihat transaksi pelunasan dan saldo menjadi 0.

### Q: Apakah bisa melihat piutang/hutang per customer/supplier?
**A:** Saat ini sistem menampilkan piutang/hutang per akun. Untuk melihat detail per customer/supplier, lihat **Buku Besar** dan perhatikan deskripsi jurnal yang biasanya menyebutkan nama customer/supplier.

### Q: Bagaimana membedakan piutang yang sudah dibayar dan belum dibayar?
**A:** 
- Di **Buku Besar**, lihat transaksi:
  - **Debit** = Penambahan piutang (tagihan baru)
  - **Kredit** = Pengurangan piutang (pembayaran)
- **Saldo Akhir** = Total piutang yang masih harus diterima
- Jika saldo akhir > 0, berarti masih ada piutang yang belum dibayar

### Q: Bagaimana membedakan hutang yang sudah dibayar dan belum dibayar?
**A:**
- Di **Buku Besar**, lihat transaksi:
  - **Kredit** = Penambahan hutang (utang baru)
  - **Debit** = Pengurangan hutang (pembayaran)
- **Saldo Akhir** = Total hutang yang masih harus dibayar
- Jika saldo akhir > 0, berarti masih ada hutang yang belum dibayar

---

## **14. Kesimpulan**

Untuk melihat hutang dan piutang di Do-Account:

1. **Neraca dengan tanggal terbaru** → Lihat total piutang/hutang aktif (cepat)
2. **Buku Besar dengan rentang waktu panjang** → Lihat history lengkap piutang/hutang tertentu
3. **Bandingkan Neraca di berbagai periode** → Lihat perkembangan piutang/hutang

**Catatan Penting:**
- **Piutang** = Aset (uang yang akan diterima) → Posisi Normal: Debit
- **Hutang** = Liabilitas (uang yang harus dibayar) → Posisi Normal: Kredit
- Saldo > 0 = Masih aktif, Saldo = 0 = Sudah lunas

Semua metode ini akan membantu Anda memahami dan mengelola hutang piutang dari awal bisnis sampai sekarang.

