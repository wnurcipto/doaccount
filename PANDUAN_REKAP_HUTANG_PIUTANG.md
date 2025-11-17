# Panduan Rekap Hutang & Piutang per Supplier/Customer

Panduan ini menjelaskan cara merekap hutang per supplier dan piutang per customer di sistem Do-Account, serta contoh pembuatan jurnal untuk berbagai skenario transaksi.

---

## **⚠️ PENTING: Status Sistem Saat Ini**

**Sistem Do-Account saat ini TIDAK memiliki akun terpisah per customer/supplier.**

- Sistem menggunakan akun umum: `1-1003 - Piutang Usaha` (untuk semua customer) dan `2-1001 - Utang Usaha` (untuk semua supplier)
- Informasi customer/supplier hanya ada di **deskripsi jurnal** atau **keterangan detail**
- Untuk melihat history per customer/supplier, perlu melihat di **Buku Besar** dan membaca deskripsi jurnal
- Rekap dilakukan secara manual dengan melihat deskripsi jurnal

**Cara yang Tersedia:**
- ✅ Melihat semua transaksi di Buku Besar
- ✅ Mencari berdasarkan deskripsi jurnal
- ✅ Rekap manual dengan melihat deskripsi
- ❌ Tidak bisa klik akun langsung melihat rincian per customer/supplier (fitur belum ada)

---

---

## **1. Cara Merekap Hutang per Supplier**

### **Metode 1: Melihat di Buku Besar dengan Deskripsi Jurnal**

**Langkah-langkah:**
1. Login ke aplikasi Do-Account
2. Klik menu **"Buku Besar"**
3. Pilih akun hutang (misalnya: `2-1001 - Utang Usaha`)
4. Pilih rentang waktu (misalnya: 01 Agustus 2024 sampai 17 November 2025)
5. Klik **"Tampilkan"**
6. Di kolom **"Deskripsi"**, lihat nama supplier yang disebutkan

**Cara Membaca:**
- Setiap jurnal biasanya menyebutkan nama supplier di deskripsi
- Contoh: "Pembelian Kredit dari Supplier ABC", "Utang ke Toko XYZ"
- Jumlahkan hutang per supplier berdasarkan deskripsi

**Contoh Output:**
```
Tanggal    | No. Bukti        | Deskripsi                      | Debit        | Kredit       | Saldo
-----------|------------------|--------------------------------|--------------|--------------|------------------
10/08/2024 | JRN/2024/08/0005| Pembelian Kredit dari Supplier A|              | 10.000.000   | 10.000.000
15/08/2024 | JRN/2024/08/0010| Pembelian Kredit dari Supplier B|              | 5.000.000    | 15.000.000
20/08/2024 | JRN/2024/08/0015| Pelunasan Utang ke Supplier A   | 10.000.000   |              | 5.000.000
25/09/2024 | JRN/2024/09/0005| Pembelian Kredit dari Supplier A|              | 3.000.000    | 8.000.000

Rekap Hutang:
- Supplier A: Rp 3.000.000 (dari saldo akhir)
- Supplier B: Rp 5.000.000
```

### **Metode 2: Menggunakan Filter Search di Buku Besar**

**Langkah-langkah:**
1. Buka **Buku Besar** untuk akun hutang
2. Gunakan fitur **Search** (jika ada) untuk mencari nama supplier
3. Atau export data dan filter di Excel/Spreadsheet

### **Metode 3: Melihat di Daftar Jurnal**

**Langkah-langkah:**
1. Klik menu **"Jurnal"**
2. Gunakan filter **Search** untuk mencari nama supplier
3. Filter berdasarkan akun hutang (misalnya: `2-1001 - Utang Usaha`)
4. Lihat semua jurnal yang terkait dengan supplier tersebut

---

## **2. Cara Merekap Piutang per Customer**

### **Metode 1: Melihat di Buku Besar dengan Deskripsi Jurnal**

**Langkah-langkah:**
1. Login ke aplikasi Do-Account
2. Klik menu **"Buku Besar"**
3. Pilih akun piutang (misalnya: `1-1003 - Piutang Usaha`)
4. Pilih rentang waktu (misalnya: 01 Agustus 2024 sampai 17 November 2025)
5. Klik **"Tampilkan"**
6. Di kolom **"Deskripsi"**, lihat nama customer yang disebutkan

**Cara Membaca:**
- Setiap jurnal biasanya menyebutkan nama customer di deskripsi
- Contoh: "Penjualan Kredit ke Customer ABC", "Tagihan ke PT XYZ"
- Jumlahkan piutang per customer berdasarkan deskripsi

**Contoh Output:**
```
Tanggal    | No. Bukti        | Deskripsi                      | Debit        | Kredit       | Saldo
-----------|------------------|--------------------------------|--------------|--------------|------------------
15/08/2024 | JRN/2024/08/0010| Penjualan Kredit ke Customer A | 5.000.000    |              | 5.000.000
20/08/2024 | JRN/2024/08/0015| Penjualan Kredit ke Customer B | 3.000.000    |              | 8.000.000
25/08/2024 | JRN/2024/08/0020| Pelunasan Customer A           |              | 5.000.000    | 3.000.000
30/09/2024 | JRN/2024/09/0010| Penjualan Kredit ke Customer A | 2.500.000    |              | 5.500.000

Rekap Piutang:
- Customer A: Rp 2.500.000 (dari saldo akhir)
- Customer B: Rp 3.000.000
```

### **Metode 2: Menggunakan Filter Search di Buku Besar**

**Langkah-langkah:**
1. Buka **Buku Besar** untuk akun piutang
2. Gunakan fitur **Search** (jika ada) untuk mencari nama customer
3. Atau export data dan filter di Excel/Spreadsheet

### **Metode 3: Melihat di Daftar Jurnal**

**Langkah-langkah:**
1. Klik menu **"Jurnal"**
2. Gunakan filter **Search** untuk mencari nama customer
3. Filter berdasarkan akun piutang (misalnya: `1-1003 - Piutang Usaha`)
4. Lihat semua jurnal yang terkait dengan customer tersebut

---

## **3. Tips untuk Rekap yang Lebih Mudah**

### **A. Gunakan Deskripsi Jurnal yang Konsisten**

**Format yang Disarankan:**
- **Untuk Hutang**: "Pembelian Kredit dari [Nama Supplier]" atau "Utang ke [Nama Supplier]"
- **Untuk Piutang**: "Penjualan Kredit ke [Nama Customer]" atau "Tagihan ke [Nama Customer]"
- **Untuk Pelunasan**: "Pelunasan Utang ke [Nama Supplier]" atau "Pelunasan Piutang dari [Nama Customer]"

**Contoh:**
- ✅ "Pembelian Kredit dari Supplier ABC"
- ✅ "Penjualan Kredit ke Customer XYZ"
- ✅ "Pelunasan Utang ke Supplier ABC"
- ❌ "Pembelian" (tidak jelas supplier siapa)
- ❌ "Penjualan" (tidak jelas customer siapa)

### **B. Gunakan Keterangan Detail di Jurnal**

Saat membuat jurnal, isi **Keterangan** di detail jurnal dengan informasi tambahan:
- Nama supplier/customer
- No. Invoice/PO
- Tanggal jatuh tempo (jika ada)

### **C. Export ke Excel untuk Analisis**

1. Buka **Buku Besar** untuk akun hutang/piutang
2. Export data (jika fitur tersedia)
3. Filter dan analisis di Excel/Spreadsheet
4. Buat pivot table untuk rekap per supplier/customer

---

## **4. Contoh Pembuatan Jurnal untuk Berbagai Skenario**

### **Contoh 1: Pembelian Barang Secara Kredit (Hutang Bertambah)**

**Skenario**: Membeli barang dari Supplier ABC seharga Rp 10.000.000 secara kredit

**Jurnal:**
```
No. Bukti: JRN/2024/08/0005
Tanggal: 10 Agustus 2024
Deskripsi: Pembelian Kredit dari Supplier ABC

| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| Persediaan Barang (1-1004) | Debit | 10.000.000 | Barang dari Supplier ABC |
| Utang Usaha (2-1001) | Kredit | 10.000.000 | Utang ke Supplier ABC |
```

**Penjelasan:**
- Persediaan bertambah → Debit
- Utang bertambah → Kredit
- **Deskripsi jelas**: "Pembelian Kredit dari Supplier ABC" → Mudah direkap

---

### **Contoh 2: Penjualan Barang Secara Kredit (Piutang Bertambah)**

**Skenario**: Menjual barang ke Customer XYZ seharga Rp 5.000.000 secara kredit

**Jurnal:**
```
No. Bukti: JRN/2024/08/0010
Tanggal: 15 Agustus 2024
Deskripsi: Penjualan Kredit ke Customer XYZ

| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| Piutang Usaha (1-1003) | Debit | 5.000.000 | Tagihan ke Customer XYZ |
| Pendapatan Penjualan (4-1002) | Kredit | 5.000.000 | Penjualan ke Customer XYZ |
```

**Penjelasan:**
- Piutang bertambah → Debit
- Pendapatan bertambah → Kredit
- **Deskripsi jelas**: "Penjualan Kredit ke Customer XYZ" → Mudah direkap

---

### **Contoh 3: Pelunasan Hutang ke Supplier**

**Skenario**: Membayar utang ke Supplier ABC sebesar Rp 10.000.000

**Jurnal:**
```
No. Bukti: JRN/2024/08/0015
Tanggal: 20 Agustus 2024
Deskripsi: Pelunasan Utang ke Supplier ABC

| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| Utang Usaha (2-1001) | Debit | 10.000.000 | Pelunasan ke Supplier ABC |
| Kas (1-1001) | Kredit | 10.000.000 | Pembayaran ke Supplier ABC |
```

**Penjelasan:**
- Utang berkurang → Debit
- Kas berkurang → Kredit
- **Deskripsi jelas**: "Pelunasan Utang ke Supplier ABC" → Mudah direkap

---

### **Contoh 4: Pelunasan Piutang dari Customer**

**Skenario**: Menerima pembayaran dari Customer XYZ sebesar Rp 5.000.000

**Jurnal:**
```
No. Bukti: JRN/2024/08/0020
Tanggal: 25 Agustus 2024
Deskripsi: Pelunasan Piutang dari Customer XYZ

| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| Kas (1-1001) | Debit | 5.000.000 | Pembayaran dari Customer XYZ |
| Piutang Usaha (1-1003) | Kredit | 5.000.000 | Pelunasan dari Customer XYZ |
```

**Penjelasan:**
- Kas bertambah → Debit
- Piutang berkurang → Kredit
- **Deskripsi jelas**: "Pelunasan Piutang dari Customer XYZ" → Mudah direkap

---

### **Contoh 5: Pemesanan Produk - Dikirim Dulu dengan Modal Belanja dan Harga Total**

**Skenario**: 
- Menerima pesanan dari Customer ABC untuk produk seharga Rp 15.000.000
- Produk dikirim dulu (barang sudah dikirim ke customer)
- Modal belanja produk tersebut adalah Rp 10.000.000
- Customer akan membayar kemudian (kredit)

**Jurnal yang Dibuat:**

#### **Jurnal 1: Saat Mengirim Barang (Penjualan Kredit)**

**Tanggal**: 10 Agustus 2024
**Deskripsi**: Penjualan Kredit ke Customer ABC - Produk Dikirim

```
| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| Piutang Usaha (1-1003) | Debit | 15.000.000 | Tagihan ke Customer ABC |
| Pendapatan Penjualan (4-1002) | Kredit | 15.000.000 | Penjualan ke Customer ABC |
```

**Penjelasan:**
- Piutang bertambah (customer berhutang) → Debit
- Pendapatan bertambah → Kredit
- **Catatan**: Barang sudah keluar dari persediaan (akan dicatat di jurnal terpisah untuk HPP)

#### **Jurnal 2: Mencatat HPP (Harga Pokok Penjualan)**

**Tanggal**: 10 Agustus 2024 (sama dengan jurnal 1)
**Deskripsi**: HPP Penjualan ke Customer ABC

```
| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| HPP (5-1001) | Debit | 10.000.000 | Modal belanja produk |
| Persediaan Barang (1-1004) | Kredit | 10.000.000 | Barang keluar untuk Customer ABC |
```

**Penjelasan:**
- HPP bertambah (beban) → Debit
- Persediaan berkurang → Kredit
- **Modal belanja** = Harga pokok produk yang dijual

#### **Ringkasan Transaksi:**
- **Harga Jual**: Rp 15.000.000 (dicatat sebagai Pendapatan)
- **Modal Belanja**: Rp 10.000.000 (dicatat sebagai HPP)
- **Laba Kotor**: Rp 15.000.000 - Rp 10.000.000 = Rp 5.000.000
- **Piutang**: Rp 15.000.000 (akan diterima dari customer)

---

### **Contoh 6: Pemesanan Produk - Dikirim Dulu dengan DP dan Sisa Kredit**

**Skenario**: 
- Menerima pesanan dari Customer XYZ untuk produk seharga Rp 20.000.000
- Customer membayar DP sebesar Rp 5.000.000
- Sisa Rp 15.000.000 dibayar kemudian (kredit)
- Modal belanja produk adalah Rp 12.000.000
- Produk dikirim dulu

**Jurnal yang Dibuat:**

#### **Jurnal 1: Saat Menerima DP (Sebelum Pengiriman)**

**Tanggal**: 05 Agustus 2024
**Deskripsi**: DP dari Customer XYZ untuk Pemesanan Produk

```
| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| Kas (1-1001) | Debit | 5.000.000 | DP dari Customer XYZ |
| Uang Muka Diterima (2-2002) | Kredit | 5.000.000 | DP dari Customer XYZ |
```

**Penjelasan:**
- Kas bertambah → Debit
- Uang Muka Diterima (liabilitas) bertambah → Kredit

#### **Jurnal 2: Saat Mengirim Barang (Penjualan dengan DP)**

**Tanggal**: 10 Agustus 2024
**Deskripsi**: Penjualan Kredit ke Customer XYZ - Produk Dikirim (DP Sudah Diterima)

```
| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| Piutang Usaha (1-1003) | Debit | 15.000.000 | Sisa tagihan ke Customer XYZ |
| Uang Muka Diterima (2-2002) | Debit | 5.000.000 | DP digunakan |
| Pendapatan Penjualan (4-1002) | Kredit | 20.000.000 | Penjualan ke Customer XYZ |
```

**Penjelasan:**
- Piutang (sisa tagihan) bertambah → Debit
- Uang Muka Diterima dihapus (sudah digunakan) → Debit
- Pendapatan bertambah → Kredit

#### **Jurnal 3: Mencatat HPP**

**Tanggal**: 10 Agustus 2024 (sama dengan jurnal 2)
**Deskripsi**: HPP Penjualan ke Customer XYZ

```
| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| HPP (5-1001) | Debit | 12.000.000 | Modal belanja produk |
| Persediaan Barang (1-1004) | Kredit | 12.000.000 | Barang keluar untuk Customer XYZ |
```

**Penjelasan:**
- HPP bertambah → Debit
- Persediaan berkurang → Kredit

#### **Jurnal 4: Saat Pelunasan Sisa Tagihan**

**Tanggal**: 25 Agustus 2024
**Deskripsi**: Pelunasan Sisa Tagihan dari Customer XYZ

```
| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| Kas (1-1001) | Debit | 15.000.000 | Pelunasan dari Customer XYZ |
| Piutang Usaha (1-1003) | Kredit | 15.000.000 | Pelunasan dari Customer XYZ |
```

**Penjelasan:**
- Kas bertambah → Debit
- Piutang berkurang → Kredit

#### **Ringkasan Transaksi:**
- **Harga Jual**: Rp 20.000.000
- **DP Diterima**: Rp 5.000.000
- **Sisa Tagihan**: Rp 15.000.000 (piutang)
- **Modal Belanja**: Rp 12.000.000
- **Laba Kotor**: Rp 20.000.000 - Rp 12.000.000 = Rp 8.000.000

---

### **Contoh 7: Pembelian Barang dengan Modal Sendiri, Lalu Dijual Kredit**

**Skenario**: 
- Membeli barang dengan modal sendiri (tunai) seharga Rp 8.000.000
- Lalu menjual barang tersebut secara kredit ke Customer ABC seharga Rp 12.000.000
- Barang dikirim dulu

**Jurnal yang Dibuat:**

#### **Jurnal 1: Pembelian Barang (Tunai)**

**Tanggal**: 05 Agustus 2024
**Deskripsi**: Pembelian Barang untuk Dijual - Modal Belanja

```
| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| Persediaan Barang (1-1004) | Debit | 8.000.000 | Pembelian barang |
| Kas (1-1001) | Kredit | 8.000.000 | Pembayaran tunai |
```

**Penjelasan:**
- Persediaan bertambah → Debit
- Kas berkurang → Kredit

#### **Jurnal 2: Penjualan Kredit (Barang Dikirim)**

**Tanggal**: 10 Agustus 2024
**Deskripsi**: Penjualan Kredit ke Customer ABC - Produk Dikirim

```
| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| Piutang Usaha (1-1003) | Debit | 12.000.000 | Tagihan ke Customer ABC |
| Pendapatan Penjualan (4-1002) | Kredit | 12.000.000 | Penjualan ke Customer ABC |
```

**Penjelasan:**
- Piutang bertambah → Debit
- Pendapatan bertambah → Kredit

#### **Jurnal 3: Mencatat HPP**

**Tanggal**: 10 Agustus 2024 (sama dengan jurnal 2)
**Deskripsi**: HPP Penjualan ke Customer ABC

```
| Akun | Posisi | Jumlah | Keterangan |
|------|--------|--------|------------|
| HPP (5-1001) | Debit | 8.000.000 | Modal belanja produk |
| Persediaan Barang (1-1004) | Kredit | 8.000.000 | Barang keluar untuk Customer ABC |
```

**Penjelasan:**
- HPP bertambah → Debit
- Persediaan berkurang → Kredit

#### **Ringkasan Transaksi:**
- **Modal Belanja**: Rp 8.000.000 (dibayar tunai)
- **Harga Jual**: Rp 12.000.000 (kredit)
- **Laba Kotor**: Rp 12.000.000 - Rp 8.000.000 = Rp 4.000.000
- **Piutang**: Rp 12.000.000 (akan diterima dari customer)

---

## **5. Template Deskripsi Jurnal untuk Rekap yang Mudah**

### **Untuk Hutang:**
- ✅ "Pembelian Kredit dari [Nama Supplier]"
- ✅ "Utang ke [Nama Supplier] - [No. Invoice/PO]"
- ✅ "Pelunasan Utang ke [Nama Supplier]"
- ✅ "Pembayaran Utang ke [Nama Supplier] - [No. Invoice]"

### **Untuk Piutang:**
- ✅ "Penjualan Kredit ke [Nama Customer]"
- ✅ "Tagihan ke [Nama Customer] - [No. Invoice]"
- ✅ "Pelunasan Piutang dari [Nama Customer]"
- ✅ "Penerimaan Pembayaran dari [Nama Customer] - [No. Invoice]"

### **Untuk Pemesanan dengan Pengiriman:**
- ✅ "Penjualan Kredit ke [Nama Customer] - Produk Dikirim"
- ✅ "HPP Penjualan ke [Nama Customer]"
- ✅ "DP dari [Nama Customer] untuk Pemesanan [No. PO]"
- ✅ "Pelunasan Sisa Tagihan dari [Nama Customer]"

---

## **6. Workflow Rekap Hutang Piutang**

### **Workflow Rekap Hutang per Supplier:**

1. **Buka Buku Besar** → Pilih akun `2-1001 - Utang Usaha`
2. **Pilih Rentang Waktu** → Dari awal bisnis sampai sekarang
3. **Lihat Deskripsi Jurnal** → Identifikasi nama supplier
4. **Rekap Manual**:
   - Buat daftar supplier
   - Jumlahkan hutang per supplier berdasarkan transaksi
   - Hitung saldo akhir per supplier

### **Workflow Rekap Piutang per Customer:**

1. **Buka Buku Besar** → Pilih akun `1-1003 - Piutang Usaha`
2. **Pilih Rentang Waktu** → Dari awal bisnis sampai sekarang
3. **Lihat Deskripsi Jurnal** → Identifikasi nama customer
4. **Rekap Manual**:
   - Buat daftar customer
   - Jumlahkan piutang per customer berdasarkan transaksi
   - Hitung saldo akhir per customer

---

## **7. Contoh Rekap Lengkap**

### **Contoh Rekap Hutang per Supplier (dari Buku Besar):**

```
Akun: 2-1001 - Utang Usaha
Periode: 01/08/2024 s/d 17/11/2025

Transaksi:
10/08/2024 | Pembelian Kredit dari Supplier A | Kredit 10.000.000
15/08/2024 | Pembelian Kredit dari Supplier B | Kredit 5.000.000
20/08/2024 | Pelunasan Utang ke Supplier A   | Debit 10.000.000
25/09/2024 | Pembelian Kredit dari Supplier A | Kredit 3.000.000
30/09/2024 | Pembelian Kredit dari Supplier C | Kredit 7.000.000
05/10/2024 | Pelunasan Utang ke Supplier B   | Debit 5.000.000

Rekap Hutang per Supplier:
- Supplier A: 
  * Penambahan: 10.000.000 + 3.000.000 = 13.000.000
  * Pelunasan: 10.000.000
  * Saldo: 13.000.000 - 10.000.000 = 3.000.000
  
- Supplier B:
  * Penambahan: 5.000.000
  * Pelunasan: 5.000.000
  * Saldo: 5.000.000 - 5.000.000 = 0 (Lunas)
  
- Supplier C:
  * Penambahan: 7.000.000
  * Pelunasan: 0
  * Saldo: 7.000.000

Total Hutang Aktif: Rp 10.000.000
```

### **Contoh Rekap Piutang per Customer (dari Buku Besar):**

```
Akun: 1-1003 - Piutang Usaha
Periode: 01/08/2024 s/d 17/11/2025

Transaksi:
15/08/2024 | Penjualan Kredit ke Customer A | Debit 5.000.000
20/08/2024 | Penjualan Kredit ke Customer B | Debit 3.000.000
25/08/2024 | Pelunasan Customer A           | Kredit 5.000.000
30/09/2024 | Penjualan Kredit ke Customer A | Debit 2.500.000
10/10/2024 | Penjualan Kredit ke Customer C | Debit 4.000.000
15/10/2024 | Pelunasan Customer B           | Kredit 3.000.000

Rekap Piutang per Customer:
- Customer A:
  * Penambahan: 5.000.000 + 2.500.000 = 7.500.000
  * Pelunasan: 5.000.000
  * Saldo: 7.500.000 - 5.000.000 = 2.500.000
  
- Customer B:
  * Penambahan: 3.000.000
  * Pelunasan: 3.000.000
  * Saldo: 3.000.000 - 3.000.000 = 0 (Lunas)
  
- Customer C:
  * Penambahan: 4.000.000
  * Pelunasan: 0
  * Saldo: 4.000.000

Total Piutang Aktif: Rp 6.500.000
```

---

## **8. Tips untuk Rekap yang Lebih Efisien**

### **A. Gunakan Format Deskripsi yang Konsisten**
- Selalu sertakan nama supplier/customer di deskripsi
- Gunakan format yang sama untuk semua jurnal
- Contoh: "Pembelian Kredit dari [Nama]" atau "Penjualan Kredit ke [Nama]"

### **B. Gunakan Keterangan Detail**
- Isi **Keterangan** di detail jurnal dengan informasi lengkap
- Sertakan No. Invoice, No. PO, atau referensi lainnya
- Memudahkan tracking dan rekap

### **C. Buat Jurnal Terpisah untuk Setiap Supplier/Customer**
- Jangan menggabungkan beberapa supplier/customer dalam satu jurnal
- Lebih mudah untuk rekap dan tracking

### **D. Export dan Analisis di Excel**
- Export data Buku Besar ke Excel (jika fitur tersedia)
- Gunakan Pivot Table untuk rekap otomatis
- Filter berdasarkan nama supplier/customer

---

## **9. FAQ**

### Q: Apakah sistem bisa otomatis merekap hutang/piutang per supplier/customer?
**A:** Saat ini sistem menampilkan hutang/piutang per akun. Untuk rekap per supplier/customer, perlu melihat deskripsi jurnal di Buku Besar dan melakukan rekap manual.

### Q: Bagaimana jika satu jurnal mempengaruhi beberapa supplier/customer?
**A:** Buat jurnal terpisah untuk setiap supplier/customer, atau gunakan keterangan detail untuk menjelaskan pembagian.

### Q: Apakah bisa melihat aging piutang/hutang?
**A:** Ya, dengan melihat Buku Besar dan menganalisis tanggal transaksi. Piutang/hutang yang sudah lama (lebih dari 30/60/90 hari) perlu diprioritaskan.

### Q: Bagaimana menghitung total hutang/piutang per supplier/customer?
**A:** 
1. Buka Buku Besar untuk akun hutang/piutang
2. Filter atau cari berdasarkan nama supplier/customer di deskripsi
3. Jumlahkan semua transaksi Debit (untuk piutang) atau Kredit (untuk hutang)
4. Kurangi dengan pelunasan (Kredit untuk piutang, Debit untuk hutang)
5. Hasilnya adalah saldo per supplier/customer

---

## **10. Kesimpulan**

Untuk merekap hutang per supplier dan piutang per customer:

1. **Gunakan Buku Besar** dengan rentang waktu panjang
2. **Lihat deskripsi jurnal** untuk identifikasi supplier/customer
3. **Rekap manual** berdasarkan deskripsi
4. **Gunakan format deskripsi yang konsisten** untuk memudahkan rekap
5. **Export ke Excel** (jika perlu) untuk analisis lebih lanjut

**Kunci Sukses:**
- ✅ Deskripsi jurnal yang jelas dan konsisten
- ✅ Keterangan detail yang informatif
- ✅ Jurnal terpisah untuk setiap supplier/customer
- ✅ Dokumentasi yang baik untuk tracking

Semua metode ini akan membantu Anda merekap dan mengelola hutang piutang dengan lebih efektif.

