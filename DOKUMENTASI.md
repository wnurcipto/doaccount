# Aplikasi Akuntansi Berbasis Web

Aplikasi akuntansi lengkap berbasis Laravel dengan arsitektur Three-Tier yang mencakup:
- Chart of Accounts (COA)
- Jurnal Umum dengan validasi Debit = Kredit
- Buku Besar
- Laporan Keuangan (Laba Rugi & Neraca)

## 🚀 Fitur Utama

### 1. **Chart of Accounts (COA)**
- CRUD lengkap untuk mengelola daftar akun
- Mendukung struktur hierarki (parent-child)
- 5 tipe akun: Aset, Liabilitas, Ekuitas, Pendapatan, Beban
- Posisi normal (Debit/Kredit) untuk setiap akun
- Data COA standar sudah tersedia

### 2. **Manajemen Periode**
- Kelola periode akuntansi (bulanan)
- Status Open/Closed untuk kontrol transaksi
- Validasi periode unik per bulan/tahun

### 3. **Jurnal Umum**
- Input jurnal dengan multiple detail
- **Validasi otomatis: Total Debit harus sama dengan Total Kredit**
- Status: Draft, Posted, Void
- Nomor bukti otomatis
- Edit hanya untuk status Draft
- Posting jurnal untuk finalisasi

### 4. **Buku Besar**
- Tampilkan mutasi per akun
- Filter berdasarkan periode
- Hitung saldo berjalan (running balance)
- Saldo awal otomatis

### 5. **Laporan Keuangan**

#### Laporan Laba Rugi
- Menampilkan Pendapatan dan Beban
- Filter berdasarkan periode
- Hitung laba/rugi bersih
- Fitur cetak

#### Laporan Neraca
- Menampilkan Aset, Liabilitas, dan Ekuitas
- Per tanggal tertentu
- Termasuk laba rugi tahun berjalan
- Validasi balance otomatis
- Fitur cetak

## 📋 Struktur Database

### Tabel: `coas` (Chart of Accounts)
- `id`: Primary key
- `kode_akun`: Kode unik akun (e.g., "1-1001")
- `nama_akun`: Nama akun (e.g., "Kas")
- `tipe_akun`: Aset, Liabilitas, Ekuitas, Pendapatan, Beban
- `posisi_normal`: Debit atau Kredit
- `parent_id`: Untuk hierarki
- `level`: Level akun (1-5)
- `is_active`: Status aktif
- `deskripsi`: Deskripsi opsional

### Tabel: `periodes`
- `id`: Primary key
- `tahun`: Tahun periode
- `bulan`: Bulan periode (1-12)
- `status`: Open atau Closed
- `tanggal_mulai`: Tanggal mulai periode
- `tanggal_selesai`: Tanggal akhir periode

### Tabel: `jurnal_headers`
- `id`: Primary key
- `no_bukti`: Nomor bukti unik
- `tanggal_transaksi`: Tanggal transaksi
- `periode_id`: Foreign key ke periodes
- `deskripsi`: Deskripsi transaksi
- `total_debit`: Total debit
- `total_kredit`: Total kredit
- `status`: Draft, Posted, atau Void
- `user_id`: User yang membuat

### Tabel: `jurnal_details`
- `id`: Primary key
- `jurnal_header_id`: Foreign key ke jurnal_headers
- `coa_id`: Foreign key ke coas
- `posisi`: Debit atau Kredit
- `jumlah`: Nominal transaksi
- `keterangan`: Keterangan detail

## 🛠️ Instalasi

### Prasyarat
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- XAMPP (jika menggunakan Windows)

### Langkah Instalasi

1. **Clone atau Download Project**
```bash
cd c:\xampp\htdocs\seb\akuntan
```

2. **Konfigurasi Database**
Edit file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=akuntan_db
DB_USERNAME=root
DB_PASSWORD=
```

3. **Buat Database**
```bash
c:\xampp\mysql\bin\mysql -u root -e "CREATE DATABASE akuntan_db"
```

4. **Jalankan Migration**
```bash
php artisan migrate
```

5. **Seed Data COA**
```bash
php artisan db:seed --class=CoaSeeder
```

6. **Jalankan Server**
```bash
php artisan serve
```

7. **Akses Aplikasi**
Buka browser: `http://localhost:8000`

## 📖 Cara Penggunaan

### 1. Setup Awal

#### a. Buat Periode Akuntansi
1. Buka menu **Periode**
2. Klik **Tambah Periode**
3. Isi tahun, bulan, dan tanggal mulai/selesai
4. Set status **Open**
5. Simpan

#### b. Kelola Chart of Accounts (Opsional)
COA standar sudah tersedia. Anda bisa menambah/edit sesuai kebutuhan:
1. Buka menu **Chart of Accounts**
2. Klik **Tambah COA** untuk menambah akun baru
3. Edit atau hapus akun yang ada

### 2. Input Jurnal

1. Buka menu **Jurnal** → **Buat Jurnal Baru**
2. Isi:
   - No. Bukti (otomatis generate)
   - Tanggal Transaksi
   - Periode
   - Deskripsi
3. Tambah detail jurnal:
   - Pilih Akun
   - Pilih Posisi (Debit/Kredit)
   - Isi Jumlah
   - Keterangan (opsional)
4. Pastikan **Total Debit = Total Kredit** (ada validasi otomatis)
5. Klik **Simpan Jurnal**

**Contoh Jurnal:**
```
Deskripsi: Penerimaan Modal Awal
Detail:
- Kas (Debit): Rp 100.000.000
- Modal Pemilik (Kredit): Rp 100.000.000
```

### 3. Posting Jurnal

1. Buka **Daftar Jurnal**
2. Cari jurnal dengan status **Draft**
3. Klik tombol **Post** (✓)
4. Jurnal dengan status **Posted** tidak bisa diedit/dihapus

### 4. Lihat Buku Besar

1. Buka menu **Buku Besar**
2. Pilih akun yang ingin dilihat
3. Pilih tanggal mulai dan selesai
4. Klik **Tampilkan**
5. Akan muncul:
   - Saldo awal
   - Detail transaksi
   - Saldo berjalan per transaksi

### 5. Laporan Keuangan

#### Laporan Laba Rugi
1. Buka **Laporan** → **Laba Rugi**
2. Pilih periode (tanggal mulai - selesai)
3. Klik **Tampilkan Laporan**
4. Akan menampilkan:
   - Total Pendapatan
   - Total Beban
   - Laba/Rugi Bersih

#### Laporan Neraca
1. Buka **Laporan** → **Neraca**
2. Pilih per tanggal
3. Klik **Tampilkan Laporan**
4. Akan menampilkan:
   - Total Aset
   - Total Liabilitas
   - Total Ekuitas + Laba Rugi Tahun Berjalan
   - Validasi balance

### 6. Cetak Laporan

Pada halaman laporan, klik tombol **Cetak** atau gunakan Ctrl+P untuk mencetak laporan.

## 🎯 Validasi & Aturan Bisnis

### Validasi Jurnal
✅ Total Debit harus sama dengan Total Kredit
✅ Minimal 2 baris detail (Debit & Kredit)
✅ Periode harus dalam status Open
✅ Nomor bukti harus unik

### Aturan Edit/Hapus
✅ Jurnal hanya bisa diedit/dihapus jika status **Draft**
✅ Jurnal **Posted** tidak bisa diubah (immutable)
✅ COA tidak bisa dihapus jika sudah digunakan
✅ Periode tidak bisa dihapus jika sudah ada jurnal

### Integritas Data
✅ Foreign key constraints
✅ Cascade delete untuk detail jurnal
✅ Restrict delete untuk COA dan Periode yang digunakan

## 🏗️ Arsitektur

### Three-Tier Architecture

#### 1. **Presentation Layer** (Frontend)
- Blade Templates
- Bootstrap 5
- JavaScript untuk form dinamis
- Responsive design

#### 2. **Logic Layer** (Backend)
- Laravel Controllers
- Model dengan Eloquent ORM
- Validasi di Controller
- Business logic (validasi Debit=Kredit, perhitungan saldo)

#### 3. **Data Layer** (Database)
- MySQL dengan struktur relasional
- Foreign keys untuk integritas referensial
- Indexes untuk performa

## 📊 Contoh Data COA Standar

### Aset (1-xxxx)
- 1-1001: Kas
- 1-1002: Bank
- 1-1003: Piutang Usaha
- 1-2001: Tanah
- 1-2002: Bangunan
- 1-2003: Peralatan

### Liabilitas (2-xxxx)
- 2-1001: Utang Usaha
- 2-1002: Utang Gaji
- 2-2001: Utang Bank

### Ekuitas (3-xxxx)
- 3-1001: Modal Pemilik
- 3-1002: Prive
- 3-1003: Laba Ditahan

### Pendapatan (4-xxxx)
- 4-1001: Pendapatan Jasa
- 4-1002: Pendapatan Penjualan

### Beban (5-xxxx)
- 5-1001: Beban Gaji
- 5-1002: Beban Sewa
- 5-1003: Beban Listrik

## 🔧 Troubleshooting

### Error "Database not found"
Pastikan database sudah dibuat:
```bash
c:\xampp\mysql\bin\mysql -u root -e "CREATE DATABASE akuntan_db"
```

### Error "Class 'Coa' not found"
Jalankan:
```bash
composer dump-autoload
```

### Jurnal tidak balance
Pastikan total Debit = total Kredit sebelum menyimpan.
Aplikasi akan menampilkan error jika tidak balance.

### Periode tidak bisa dipilih
Pastikan ada periode dengan status **Open** di menu Periode.

## 📝 Lisensi

Open Source - Silakan digunakan untuk belajar dan pengembangan.

## 👨‍💻 Developer

Dibuat dengan Laravel 11 dan konsep akuntansi standar.

---

**Selamat menggunakan Aplikasi Akuntansi! 🎉**

Untuk pertanyaan atau saran, silakan hubungi tim development.
