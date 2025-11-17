# Cara Melihat Aset dari Awal Bisnis (Agustus 2024) sampai Sekarang (2025)

Panduan ini menjelaskan cara melihat semua aset yang dimiliki dari awal bisnis (Agustus 2024) sampai sekarang (tahun 2025).

---

## **Metode 1: Melihat Aset yang Masih Ada (Aktif) - Via Neraca**

### Langkah-langkah:
1. **Login** ke aplikasi Do-Account
2. Klik menu **"Laporan"** → **"Neraca"**
3. Pilih **tanggal terbaru** (misalnya: **17 November 2025** atau tanggal hari ini)
4. Klik **"Tampilkan Laporan"**
5. Di **kolom kiri** akan muncul semua aset yang **masih ada** (saldo > 0)

### Informasi yang Didapat:
- ✅ **Daftar semua aset aktif** yang masih dimiliki
- ✅ **Saldo setiap aset** per tanggal yang dipilih
- ✅ **Total aset** yang dimiliki

### Contoh Output (Data Real dari admin@ramaadvertize.com):
```
ASET
1-1001 - Kas                            Rp 7.707.000
1-1004 - Persediaan Barang              Rp 1.605.000

TOTAL ASET                               Rp 9.312.000
```

**Catatan:** 
- Data di atas adalah data real dari akun **Administrator (admin@ramaadvertize.com)** per tanggal **17 November 2025**
- Hanya aset dengan saldo > 0 yang ditampilkan
- Aset yang sudah habis/disusutkan (saldo = 0) tidak akan muncul
- Ini adalah snapshot aset **saat ini** (per tanggal yang dipilih)

---

## **Metode 2: Melihat History Lengkap Aset dari Agustus 2024 - Via Buku Besar**

### Langkah-langkah:
1. **Login** ke aplikasi Do-Account
2. Klik menu **"Buku Besar"**
3. Pilih:
   - **Kode Akun** atau **Nama Akun**: Pilih akun aset yang ingin dilihat (misalnya: `1-4.02 - Kendaraan`)
   - **Tanggal Mulai**: **01 Agustus 2024** (awal bisnis)
   - **Tanggal Selesai**: **17 November 2025** (atau tanggal hari ini)
4. Klik **"Tampilkan"**

### Informasi yang Didapat:
- ✅ **Saldo Awal** (dari Agustus 2024)
- ✅ **Semua transaksi** yang mempengaruhi aset tersebut:
  - Pembelian aset
  - Penyusutan
  - Penjualan/disposal
  - Penyesuaian lainnya
- ✅ **Saldo Akhir** (sampai sekarang)
- ✅ **Running balance** setelah setiap transaksi

### Contoh Output Buku Besar (Data Real dari admin@ramaadvertize.com):
```
Kode Akun: 1-1001
Nama Akun: Kas
Periode: 01/08/2024 s/d 17/11/2025

Saldo Awal: Rp 0

Tanggal    | No. Bukti        | Deskripsi                      | Debit        | Kredit       | Saldo
-----------|------------------|--------------------------------|--------------|--------------|------------------
03/08/2024 | JRN/2024/08/0001| Akomodasi Febri                |              | 150.000      | -150.000
08/08/2024 | JRN/2024/08/0002| Tagihan Bengkel Juni-Juli       | 12.145.000   |              | 11.995.000
08/08/2024 | JRN/2024/08/0003| Laminasi                        |              | 1.521.000    | 10.474.000
08/08/2024 | JRN/2024/08/0004| Sticker Glossy 1,27             |              | 1.587.500    | 8.886.500
08/08/2024 | JRN/2024/08/0005| Sticker Transparan 1.07         |              | 1.605.000    | 7.281.500
09/08/2024 | JRN/2024/08/0006| Sticker Transparant 1,27 2 roll |              | 3.810.000    | 3.471.500
12/08/2024 | JRN/2024/08/0007| Tinta CMYK                      |              | 1.200.000    | 2.271.500
... (transaksi berlanjut sampai November 2025) ...
30/10/2025 | JRN/2025/10/0009| Tagihan Ramatek Oktober 2025    | 6.017.000    |              | 7.707.000
07/11/2025 | JRN/2025/11/0001| Stiker Glossy + Laminasi Doff 1m|              | 165.000      | 7.542.000
09/11/2025 | JRN/2025/11/0003| Head PRint XP600                |              | 4.500.000    | 3.042.000

Saldo Akhir: Rp 7.707.000
```

**Catatan:**
- Data di atas adalah data real dari akun **Administrator (admin@ramaadvertize.com)**
- Menampilkan beberapa transaksi pertama dan terakhir untuk memberikan gambaran history
- Saldo akhir menunjukkan saldo per 17 November 2025

---

## **Metode 3: Melihat Semua Aset (Termasuk yang Sudah Tidak Ada) - Via Buku Besar per Akun**

Jika Anda ingin melihat **semua aset yang pernah dimiliki** (termasuk yang sudah dijual/habis), Anda perlu melihat **setiap akun aset** satu per satu di Buku Besar.

### Langkah-langkah:
1. **Login** ke aplikasi Do-Account
2. Klik menu **"Buku Besar"**
3. Untuk **setiap akun aset**, lakukan:
   - Pilih **akun aset** (misalnya: `1-4.01 - Peralatan Kantor`)
   - **Tanggal Mulai**: **01 Agustus 2024**
   - **Tanggal Selesai**: **17 November 2025** (atau tanggal hari ini)
   - Klik **"Tampilkan"**
4. Ulangi untuk semua akun aset yang ingin dilihat

### Daftar Akun Aset yang Perlu Dicek:
- **Aset Lancar:**
  - `1-1001 - Kas`
  - `1-1002 - Bank`
  - `1-1003 - Piutang Usaha`
  - `1-1004 - Persediaan Barang`
  - `1-1005 - Perlengkapan`
  - (dan akun aset lancar lainnya)

- **Aset Tetap:**
  - `1-2001 - Tanah`
  - `1-2002 - Bangunan`
  - `1-2003 - Peralatan`
  - `1-2004 - Kendaraan`
  - `1-2005 - Akumulasi Penyusutan Bangunan`
  - `1-2006 - Akumulasi Penyusutan Peralatan`
  - `1-2007 - Akumulasi Penyusutan Kendaraan`
  - (dan akun aset tetap lainnya)

**Catatan:** 
- Kode akun di atas mengikuti struktur COA yang digunakan di sistem
- Untuk melihat daftar lengkap, buka menu **Chart of Accounts (COA)**

---

## **Metode 4: Melihat Perbandingan Aset di Berbagai Periode**

Untuk melihat **perkembangan aset** dari waktu ke waktu, bandingkan Neraca di berbagai tanggal:

### Langkah-langkah:
1. **Neraca 31 Agustus 2024** (akhir bulan pertama)
   - Menu **"Laporan"** → **"Neraca"**
   - Tanggal: **31 Agustus 2024**
   - Catat daftar aset dan saldonya

2. **Neraca 31 Desember 2024** (akhir tahun pertama)
   - Menu **"Laporan"** → **"Neraca"**
   - Tanggal: **31 Desember 2024**
   - Catat daftar aset dan saldonya

3. **Neraca 17 November 2025** (saat ini)
   - Menu **"Laporan"** → **"Neraca"**
   - Tanggal: **17 November 2025**
   - Catat daftar aset dan saldonya
   - **Contoh Real (admin@ramaadvertize.com)**:
     ```
     ASET
     1-1001 - Kas                            Rp 7.707.000
     1-1004 - Persediaan Barang              Rp 1.605.000
     
     TOTAL ASET                               Rp 9.312.000
     ```

4. **Bandingkan** ketiga laporan untuk melihat:
   - Aset baru yang dibeli
   - Aset yang dijual/habis
   - Perubahan saldo aset
   - **Contoh Real (admin@ramaadvertize.com)**: 
     - Di awal (Agustus 2024): Belum ada aset (saldo = 0)
     - Di akhir tahun 2024: Mulai ada transaksi kas dan persediaan
     - Di November 2025: Kas Rp 7.707.000 dan Persediaan Rp 1.605.000

---

## **Rekomendasi: Workflow Terbaik**

### Untuk Melihat Aset yang Masih Ada (Cepat):
1. ✅ Gunakan **Metode 1 (Neraca)** dengan tanggal terbaru
2. ✅ Ini akan menampilkan semua aset aktif dalam sekali lihat

### Untuk Melihat History Lengkap Aset Tertentu:
1. ✅ Gunakan **Metode 2 (Buku Besar)** dengan rentang waktu dari Agustus 2024 sampai sekarang
2. ✅ Pilih akun aset yang ingin dilihat detailnya
3. ✅ Anda akan melihat semua transaksi dari awal sampai sekarang

### Untuk Analisis Perkembangan Aset:
1. ✅ Gunakan **Metode 4 (Perbandingan Neraca)**
2. ✅ Bandingkan Neraca di berbagai periode untuk melihat tren

---

## **Tips Praktis**

### 1. Export dan Simpan Laporan
- **Export PDF** Neraca untuk dokumentasi (hanya untuk plan Professional/Enterprise)
- Simpan laporan per periode untuk referensi

### 2. Fokus pada Aset Tetap
- Aset tetap (kendaraan, peralatan, dll) biasanya lebih penting untuk dilacak
- Lihat juga **akumulasi penyusutan** untuk mengetahui nilai bersih

### 3. Perhatikan Aset yang Sudah Tidak Ada
- Jika aset tidak muncul di Neraca terbaru, cek di Buku Besar dengan rentang waktu panjang
- Mungkin aset sudah dijual atau disusutkan habis

### 4. Gunakan Filter yang Tepat
- Pastikan **tanggal mulai** = **01 Agustus 2024** (awal bisnis)
- Pastikan **tanggal selesai** = **tanggal terbaru** (saat ini)

---

## **Contoh Skenario Praktis**

### Skenario: "Saya ingin tahu semua aset yang saya punya sekarang dan bagaimana sejarahnya"

**Contoh Real dari akun admin@ramaadvertize.com:**

**Langkah 1: Lihat Aset Aktif (Sekarang)**
- Buka **Neraca** → Tanggal: **17 November 2025**
- Hasil yang muncul:
  ```
  ASET
  1-1001 - Kas                            Rp 7.707.000
  1-1004 - Persediaan Barang              Rp 1.605.000
  
  TOTAL ASET                               Rp 9.312.000
  ```
- **Kesimpulan**: Ada 2 aset aktif, yaitu Kas dan Persediaan Barang

**Langkah 2: Lihat History Aset Penting**
- Buka **Buku Besar**
- Pilih akun **1-1001 - Kas**
- Tanggal Mulai: **01 Agustus 2024**
- Tanggal Selesai: **17 November 2025**
- Hasil: Menampilkan semua transaksi dari Agustus 2024 sampai November 2025
  - **Saldo Awal**: Rp 0 (tidak ada saldo di awal Agustus 2024)
  - **Transaksi Pertama**: 03/08/2024 - Akomodasi Febri (Kredit Rp 150.000)
  - **Transaksi Terbesar**: 08/08/2024 - Tagihan Bengkel Juni-Juli (Debit Rp 12.145.000)
  - **Transaksi Terakhir**: 09/11/2025 - Head PRint XP600 (Kredit Rp 4.500.000)
  - **Saldo Akhir**: Rp 7.707.000

**Langkah 3: Analisis**
- **Saldo Awal** (Agustus 2024): Rp 0
- **Saldo Akhir** (November 2025): Rp 7.707.000
- **Perubahan**: Kas bertambah sebesar Rp 7.707.000 dari awal bisnis
- **Transaksi Terbesar (Debit)**: Penerimaan tagihan bengkel sebesar Rp 12.145.000 (08/08/2024)
- **Transaksi Terbesar (Kredit)**: Pembelian Head Print XP600 sebesar Rp 4.500.000 (09/11/2025)
- **Jenis Transaksi**: 
  - Penerimaan kas (tagihan, pendapatan)
  - Pengeluaran kas (pembelian bahan, peralatan, akomodasi)
- **Kesimpulan**: Meskipun ada banyak pengeluaran, kas masih positif karena ada penerimaan yang lebih besar

---

## **FAQ**

### Q: Apakah semua aset dari Agustus 2024 akan muncul di Neraca sekarang?
**A:** Tidak. Hanya aset dengan **saldo > 0** yang muncul di Neraca. Aset yang sudah dijual/habis/disusutkan (saldo = 0) tidak akan muncul.

### Q: Bagaimana melihat aset yang sudah dijual?
**A:** Gunakan **Buku Besar** dengan rentang waktu dari Agustus 2024 sampai sekarang. Anda akan melihat transaksi penjualan dan saldo menjadi 0.

### Q: Apakah bisa melihat semua aset sekaligus?
**A:** Ya, di **Neraca** Anda akan melihat semua aset aktif sekaligus. Untuk detail history, gunakan **Buku Besar** per akun.

### Q: Bagaimana melihat nilai bersih aset setelah penyusutan?
**A:** Di Neraca, lihat:
- Akun aset (contoh: `1-2004 - Kendaraan`) → Nilai perolehan
- Akumulasi penyusutan (contoh: `1-2007 - Akumulasi Penyusutan Kendaraan`) → Total penyusutan
- **Nilai bersih** = Nilai perolehan - Akumulasi penyusutan

**Contoh Real (admin@ramaadvertize.com):**
- Saat ini tidak ada aset tetap dengan penyusutan (kendaraan, peralatan, dll)
- Jika ada, akan muncul di Neraca dengan format:
  ```
  1-2004 - Kendaraan                      Rp 80.000.000
  1-2007 - Akumulasi Penyusutan Kendaraan Rp -10.000.000
  ```
- Nilai bersih kendaraan = Rp 80.000.000 - Rp 10.000.000 = Rp 70.000.000

---

## **Kesimpulan**

Untuk melihat aset dari awal bisnis (Agustus 2024) sampai sekarang (2025):

1. **Neraca dengan tanggal terbaru** → Lihat aset yang masih ada (cepat)
2. **Buku Besar dengan rentang waktu panjang** → Lihat history lengkap aset tertentu
3. **Bandingkan Neraca di berbagai periode** → Lihat perkembangan aset

Semua metode ini akan membantu Anda memahami aset yang dimiliki dari awal bisnis sampai sekarang.

