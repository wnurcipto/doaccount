# Status Akun Customer/Supplier di Sistem Do-Account

## **Situasi Saat Ini**

### **Sistem Menggunakan Akun Umum:**
- **Piutang**: `1-1003 - Piutang Usaha` (untuk semua customer)
- **Hutang**: `2-1001 - Utang Usaha` (untuk semua supplier)

### **Tidak Ada Akun Terpisah per Customer/Supplier:**
- ❌ Tidak ada `1-1003.001 - Piutang Customer ABC`
- ❌ Tidak ada `2-1001.001 - Utang Supplier XYZ`
- ❌ Tidak ada sub-account atau detail account per customer/supplier

### **Cara Tracking Saat Ini:**
- Informasi customer/supplier ada di **deskripsi jurnal** atau **keterangan detail**
- User perlu melihat di **Buku Besar** dan membaca deskripsi untuk mengetahui customer/supplier mana
- Rekap dilakukan secara manual dengan melihat deskripsi jurnal

---

## **Cara Melihat History Hutang/Piutang per Customer/Supplier Saat Ini**

### **Metode yang Tersedia:**

1. **Buku Besar dengan Filter Deskripsi**
   - Buka Buku Besar untuk akun Piutang/Hutang
   - Lihat deskripsi jurnal untuk identifikasi customer/supplier
   - Rekap manual berdasarkan deskripsi

2. **Daftar Jurnal dengan Search**
   - Buka menu Jurnal
   - Gunakan filter Search untuk mencari nama customer/supplier
   - Lihat semua jurnal terkait

3. **Export dan Analisis di Excel**
   - Export data Buku Besar (jika fitur tersedia)
   - Filter dan analisis di Excel
   - Buat pivot table untuk rekap

---

## **Keterbatasan Sistem Saat Ini**

### **Yang Tidak Bisa Dilakukan:**
- ❌ Klik akun langsung melihat rincian per customer/supplier
- ❌ Filter otomatis per customer/supplier di Buku Besar
- ❌ Laporan aging piutang/hutang per customer/supplier otomatis
- ❌ Dashboard hutang/piutang per customer/supplier

### **Yang Bisa Dilakukan:**
- ✅ Melihat semua transaksi di Buku Besar
- ✅ Mencari berdasarkan deskripsi jurnal
- ✅ Rekap manual dengan melihat deskripsi
- ✅ Export data untuk analisis lebih lanjut

---

## **Rekomendasi untuk Rekap yang Lebih Mudah**

### **1. Gunakan Deskripsi Jurnal yang Konsisten**
Format yang disarankan:
- "Penjualan Kredit ke [Nama Customer]"
- "Pembelian Kredit dari [Nama Supplier]"
- "Pelunasan Piutang dari [Nama Customer]"
- "Pelunasan Utang ke [Nama Supplier]"

### **2. Gunakan Keterangan Detail**
Saat membuat jurnal, isi **Keterangan** di detail jurnal dengan:
- Nama customer/supplier
- No. Invoice/PO
- Tanggal jatuh tempo (jika ada)

### **3. Buat Jurnal Terpisah untuk Setiap Customer/Supplier**
- Jangan menggabungkan beberapa customer/supplier dalam satu jurnal
- Lebih mudah untuk tracking dan rekap

---

## **Opsi Pengembangan (Jika Diperlukan)**

Jika Anda ingin fitur akun per customer/supplier, ada beberapa opsi:

### **Opsi 1: Sub-Account di COA**
- Membuat akun detail di bawah akun utama
- Contoh:
  - `1-1003 - Piutang Usaha` (parent)
    - `1-1003.001 - Piutang Customer ABC`
    - `1-1003.002 - Piutang Customer XYZ`
- **Kelebihan**: Terintegrasi dengan sistem COA yang ada
- **Kekurangan**: Perlu membuat banyak akun jika customer/supplier banyak

### **Opsi 2: Field Customer/Supplier di Jurnal Detail**
- Menambahkan field `customer_id` dan `supplier_id` di tabel `jurnal_details`
- Membuat master data Customer dan Supplier
- **Kelebihan**: Lebih fleksibel, tidak perlu membuat banyak akun
- **Kekurangan**: Perlu modifikasi database dan fitur baru

### **Opsi 3: Modul Customer/Supplier Terpisah**
- Membuat modul master Customer dan Supplier
- Setiap transaksi piutang/hutang di-link ke customer/supplier
- **Kelebihan**: Fitur lengkap dengan laporan per customer/supplier
- **Kekurangan**: Perlu development yang lebih kompleks

---

## **Kesimpulan**

**Saat ini sistem TIDAK memiliki akun terpisah per customer/supplier.**

Untuk melihat history hutang/piutang per customer/supplier:
1. Gunakan **Buku Besar** dengan akun umum (Piutang Usaha/Utang Usaha)
2. Lihat **deskripsi jurnal** untuk identifikasi customer/supplier
3. Lakukan **rekap manual** berdasarkan deskripsi

**Rekomendasi:**
- Gunakan deskripsi jurnal yang konsisten dan jelas
- Gunakan keterangan detail untuk informasi tambahan
- Buat jurnal terpisah untuk setiap customer/supplier

Jika Anda ingin fitur akun per customer/supplier, saya bisa membantu mengembangkan fitur tersebut.

