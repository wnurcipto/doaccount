<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BantuanController extends Controller
{
    public function index()
    {
        // Check feature access - hanya Enterprise yang bisa akses Bantuan
        $user = $this->currentUser();
        if (!$user->is_owner && $user->plan !== 'enterprise') {
            return redirect()->route('dashboard')
                ->with('error', 'Menu Bantuan hanya tersedia untuk plan Enterprise. Silakan upgrade plan Anda.');
        }

        // Daftar panduan yang tersedia
        $panduan = [
            'umum' => [
                'title' => 'Panduan Umum',
                'file' => 'DOKUMENTASI.md',
                'icon' => 'bi-book',
                'description' => 'Dokumentasi lengkap sistem akuntansi'
            ],
            'jurnal' => [
                'title' => 'Panduan Jurnal',
                'file' => null,
                'icon' => 'bi-journal-text',
                'description' => 'Cara membuat dan mengelola jurnal'
            ],
            'import-csv' => [
                'title' => 'Import CSV ke Jurnal',
                'file' => 'PANDUAN_IMPORT_CSV.md',
                'icon' => 'bi-file-earmark-spreadsheet',
                'description' => 'Cara mengimport data dari file CSV ke jurnal'
            ],
            'penutupan-tahun' => [
                'title' => 'Penutupan Akhir Tahun',
                'file' => 'PANDUAN_PENUTUPAN_AKHIR_TAHUN.md',
                'icon' => 'bi-calendar-check',
                'description' => 'Langkah-langkah penutupan akhir tahun'
            ],
            'inventori' => [
                'title' => 'Modul Inventori',
                'file' => 'PANDUAN_INVENTORI.md',
                'icon' => 'bi-box-seam',
                'description' => 'Panduan penggunaan modul inventori'
            ],
            'invoice' => [
                'title' => 'Panduan Invoice & Dokumen',
                'file' => null,
                'icon' => 'bi-receipt',
                'description' => 'Cara menggunakan fitur Invoice, Offering, dan Surat Jalan'
            ],
            'fitur-prioritas' => [
                'title' => 'Fitur Prioritas Tinggi',
                'file' => null,
                'icon' => 'bi-star-fill',
                'description' => 'Dokumentasi fitur-fitur prioritas tinggi (Duplicate, Status, Export PDF, Arus Kas)'
            ],
            'melihat-aset' => [
                'title' => 'Panduan Melihat Aset',
                'file' => 'CARA_MELIHAT_ASET_DARI_AWAL_BISNIS.md',
                'icon' => 'bi-building',
                'description' => 'Cara melihat aset dari awal bisnis sampai sekarang'
            ],
            'hutang-piutang' => [
                'title' => 'Panduan Hutang & Piutang',
                'file' => 'PANDUAN_HUTANG_PIUTANG.md',
                'icon' => 'bi-arrow-left-right',
                'description' => 'Cara melihat dan mengelola hutang piutang'
            ],
            'rekap-hutang-piutang' => [
                'title' => 'Rekap Hutang & Piutang',
                'file' => 'PANDUAN_REKAP_HUTANG_PIUTANG.md',
                'icon' => 'bi-list-check',
                'description' => 'Cara merekap hutang per supplier dan piutang per customer'
            ],
        ];

        return view('bantuan.index', compact('panduan'));
    }

    public function show($slug)
    {
        // Check feature access - hanya Enterprise yang bisa akses Bantuan
        $user = $this->currentUser();
        if (!$user->is_owner && $user->plan !== 'enterprise') {
            return redirect()->route('dashboard')
                ->with('error', 'Menu Bantuan hanya tersedia untuk plan Enterprise. Silakan upgrade plan Anda.');
        }

        $panduan = $this->getPanduanBySlug($slug);
        
        if (!$panduan) {
            abort(404, 'Panduan tidak ditemukan');
        }

        // Load content dari file markdown jika ada, atau gunakan content yang sudah ada
        if ($panduan['file']) {
            $filePath = base_path($panduan['file']);
            if (File::exists($filePath)) {
                $panduan['content'] = File::get($filePath);
            } else {
                $panduan['content'] = "File panduan tidak ditemukan: {$panduan['file']}";
            }
        } elseif ($slug == 'jurnal') {
            // Untuk panduan jurnal, ambil dari method
            $panduan['content'] = $this->getPanduanJurnal();
        } elseif ($slug == 'invoice') {
            // Untuk panduan invoice, ambil dari method
            $panduan['content'] = $this->getPanduanInvoice();
        } elseif ($slug == 'fitur-prioritas') {
            // Untuk panduan fitur prioritas, ambil dari method
            $panduan['content'] = $this->getPanduanFiturPrioritas();
        } elseif ($slug == 'melihat-aset') {
            // Untuk panduan melihat aset, ambil dari file
            $filePath = base_path('CARA_MELIHAT_ASET_DARI_AWAL_BISNIS.md');
            if (File::exists($filePath)) {
                $panduan['content'] = File::get($filePath);
            } else {
                $panduan['content'] = "File panduan tidak ditemukan: CARA_MELIHAT_ASET_DARI_AWAL_BISNIS.md";
            }
        } elseif ($slug == 'hutang-piutang') {
            // Untuk panduan hutang piutang, ambil dari file
            $filePath = base_path('PANDUAN_HUTANG_PIUTANG.md');
            if (File::exists($filePath)) {
                $panduan['content'] = File::get($filePath);
            } else {
                $panduan['content'] = "File panduan tidak ditemukan: PANDUAN_HUTANG_PIUTANG.md";
            }
        } elseif ($slug == 'rekap-hutang-piutang') {
            // Untuk panduan rekap hutang piutang, ambil dari file
            $filePath = base_path('PANDUAN_REKAP_HUTANG_PIUTANG.md');
            if (File::exists($filePath)) {
                $panduan['content'] = File::get($filePath);
            } else {
                $panduan['content'] = "File panduan tidak ditemukan: PANDUAN_REKAP_HUTANG_PIUTANG.md";
            }
        } else {
            $panduan['content'] = 'Panduan tidak tersedia.';
        }

        // Convert markdown to HTML
        $panduan['html'] = $this->markdownToHtml($panduan['content'] ?? 'Panduan tidak tersedia.');

        return view('bantuan.show', compact('panduan', 'slug'));
    }

    private function getPanduanBySlug($slug)
    {
        $panduanList = [
            'umum' => [
                'title' => 'Panduan Umum',
                'file' => 'DOKUMENTASI.md',
                'icon' => 'bi-book',
                'description' => 'Dokumentasi lengkap sistem akuntansi'
            ],
            'jurnal' => [
                'title' => 'Panduan Jurnal',
                'file' => null,
                'icon' => 'bi-journal-text',
                'description' => 'Cara membuat dan mengelola jurnal'
            ],
            'import-csv' => [
                'title' => 'Import CSV ke Jurnal',
                'file' => 'PANDUAN_IMPORT_CSV.md',
                'icon' => 'bi-file-earmark-spreadsheet',
                'description' => 'Cara mengimport data dari file CSV ke jurnal'
            ],
            'penutupan-tahun' => [
                'title' => 'Penutupan Akhir Tahun',
                'file' => 'PANDUAN_PENUTUPAN_AKHIR_TAHUN.md',
                'icon' => 'bi-calendar-check',
                'description' => 'Langkah-langkah penutupan akhir tahun'
            ],
            'inventori' => [
                'title' => 'Modul Inventori',
                'file' => 'PANDUAN_INVENTORI.md',
                'icon' => 'bi-box-seam',
                'description' => 'Panduan penggunaan modul inventori'
            ],
            'invoice' => [
                'title' => 'Panduan Invoice & Dokumen',
                'file' => null,
                'icon' => 'bi-receipt',
                'description' => 'Cara menggunakan fitur Invoice, Offering, dan Surat Jalan'
            ],
            'fitur-prioritas' => [
                'title' => 'Fitur Prioritas Tinggi',
                'file' => null,
                'icon' => 'bi-star-fill',
                'description' => 'Dokumentasi fitur-fitur prioritas tinggi (Duplicate, Status, Export PDF, Arus Kas)'
            ],
            'melihat-aset' => [
                'title' => 'Panduan Melihat Aset',
                'file' => 'CARA_MELIHAT_ASET_DARI_AWAL_BISNIS.md',
                'icon' => 'bi-building',
                'description' => 'Cara melihat aset dari awal bisnis sampai sekarang'
            ],
            'hutang-piutang' => [
                'title' => 'Panduan Hutang & Piutang',
                'file' => 'PANDUAN_HUTANG_PIUTANG.md',
                'icon' => 'bi-arrow-left-right',
                'description' => 'Cara melihat dan mengelola hutang piutang'
            ],
            'rekap-hutang-piutang' => [
                'title' => 'Rekap Hutang & Piutang',
                'file' => 'PANDUAN_REKAP_HUTANG_PIUTANG.md',
                'icon' => 'bi-list-check',
                'description' => 'Cara merekap hutang per supplier dan piutang per customer'
            ],
        ];

        return $panduanList[$slug] ?? null;
    }

    private function getPanduanJurnal()
    {
        return <<<'MARKDOWN'
# PANDUAN MEMBUAT JURNAL
## Sistem Akuntansi - PT. Rama Advertize

---

## 📋 PENGERTIAN JURNAL

Jurnal adalah catatan transaksi keuangan yang dicatat secara kronologis. Setiap jurnal harus **balance**, artinya **Total Debit = Total Kredit**.

---

## 🎯 CARA MEMBUAT JURNAL

### **Langkah 1: Buka Menu Jurnal**
1. Klik menu **Jurnal** di sidebar
2. Klik tombol **"Buat Jurnal Baru"**

### **Langkah 2: Isi Header Jurnal**
1. **No. Bukti**: Sistem akan generate otomatis (format: JRN/YYYY/MM/XXXX)
   - Atau bisa diisi manual jika diperlukan
2. **Tanggal Transaksi**: Pilih tanggal transaksi
3. **Periode**: Pilih periode akuntansi (hanya periode dengan status Open)
4. **Deskripsi**: Tulis deskripsi transaksi secara ringkas

### **Langkah 3: Tambah Detail Jurnal**
1. Klik tombol **"Tambah Baris"** untuk menambahkan detail
2. Untuk setiap baris, isi:
   - **Akun**: Pilih akun dari Chart of Accounts
   - **Posisi**: Pilih Debit atau Kredit
   - **Jumlah**: Masukkan nominal transaksi
   - **Keterangan**: (Opsional) Keterangan detail transaksi
3. Tambah baris lagi sesuai kebutuhan (minimal 2 baris)

### **Langkah 4: Pastikan Balance**
- Perhatikan **Total Debit** dan **Total Kredit** di bagian bawah form
- Jika balance, akan muncul badge hijau **"✓ Balance"**
- Jika tidak balance, akan muncul badge merah **"✗ Tidak Balance"**
- **Jurnal tidak bisa disimpan jika tidak balance**

### **Langkah 5: Simpan Jurnal**
1. Klik tombol **"Simpan Jurnal"**
2. Jurnal akan tersimpan dengan status **Draft**
3. Anda bisa edit jurnal yang masih berstatus Draft

---

## 📝 CONTOH TRANSAKSI

### **Contoh 1: Pembelian Barang Secara Tunai**

**Transaksi**: Membeli laptop seharga Rp 10.000.000 secara tunai

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Peralatan (1-1005) | Debit | 10.000.000 |
| Kas (1-1001) | Kredit | 10.000.000 |

**Penjelasan**: 
- Aset (Peralatan) bertambah → Debit
- Aset (Kas) berkurang → Kredit

---

### **Contoh 2: Menerima Pendapatan**

**Transaksi**: Menerima pembayaran jasa konsultasi Rp 5.000.000

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 5.000.000 |
| Pendapatan Jasa (4-1001) | Kredit | 5.000.000 |

**Penjelasan**:
- Aset (Kas) bertambah → Debit
- Pendapatan bertambah → Kredit

---

### **Contoh 3: Membayar Beban Gaji**

**Transaksi**: Membayar gaji karyawan Rp 3.000.000

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Beban Gaji (5-1001) | Debit | 3.000.000 |
| Kas (1-1001) | Kredit | 3.000.000 |

**Penjelasan**:
- Beban bertambah → Debit
- Aset (Kas) berkurang → Kredit

---

### **Contoh 4: Transaksi dengan 3 Akun (Kompleks)**

**Transaksi**: Membeli barang secara kredit Rp 5.000.000, dengan DP 2.000.000

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Persediaan Barang (1-1004) | Debit | 5.000.000 |
| Kas (1-1001) | Kredit | 2.000.000 |
| Utang Usaha (2-1001) | Kredit | 3.000.000 |

**Penjelasan**:
- Aset (Persediaan) bertambah → Debit 5.000.000
- Aset (Kas) berkurang → Kredit 2.000.000
- Liabilitas (Utang) bertambah → Kredit 3.000.000
- Total: Debit 5.000.000 = Kredit 5.000.000 ✓

---

### **Contoh 5: Penjualan dengan Diskon (Invoice)**

**Transaksi**: Menjual barang/jasa dengan invoice, subtotal Rp 4.588.000, diskon 6,3% = Rp 288.000, total dibayar Rp 4.300.000

**Skenario A: Penjualan Tunai (Langsung Dibayar)**

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 4.300.000 |
| Diskon Penjualan (5-1007) | Debit | 288.000 |
| Pendapatan Penjualan (4-1002) | Kredit | 4.588.000 |

**Penjelasan**:
- Kas bertambah → Debit 4.300.000
- Diskon Penjualan (beban) → Debit 288.000
- Pendapatan Penjualan → Kredit 4.588.000
- Total: Debit 4.588.000 = Kredit 4.588.000 ✓

**Catatan**: 
- Pendapatan dicatat sebesar **subtotal** (sebelum diskon)
- Diskon dicatat sebagai **beban terpisah** untuk analisis
- Jika belum ada akun "Diskon Penjualan", buat dulu di Chart of Accounts (Tipe: Beban, Posisi Normal: Debit)

---

**Skenario B: Penjualan Kredit (Piutang)**

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Piutang Usaha (1-1102) | Debit | 4.300.000 |
| Diskon Penjualan (5-1007) | Debit | 288.000 |
| Pendapatan Penjualan (4-1002) | Kredit | 4.588.000 |

**Penjelasan**:
- Piutang bertambah → Debit 4.300.000
- Diskon Penjualan (beban) → Debit 288.000
- Pendapatan Penjualan → Kredit 4.588.000
- Total: Debit 4.588.000 = Kredit 4.588.000 ✓

**Saat Pelunasan Piutang** (jurnal terpisah):

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 4.300.000 |
| Piutang Usaha (1-1102) | Kredit | 4.300.000 |

---

**Skenario C: Alternatif Sederhana (Tanpa Akun Diskon Terpisah)**

Jika tidak ingin memisahkan diskon, bisa langsung mencatat pendapatan bersih:

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas/Piutang | Debit | 4.300.000 |
| Pendapatan Penjualan | Kredit | 4.300.000 |

**Catatan**: Cara ini lebih sederhana, tapi diskon tidak terlihat terpisah untuk analisis.

**Langkah-langkah Input di Aplikasi**:

1. Buka menu **Jurnal** → **Buat Jurnal Baru**
2. Isi header:
   - **No. Bukti**: Misal `JU-001` atau sesuai format
   - **Tanggal**: Tanggal invoice
   - **Periode**: Pilih periode yang sesuai
   - **Deskripsi**: "Penjualan Invoice [NO_INVOICE]"
3. Tambah detail jurnal sesuai skenario di atas
4. Pastikan **balance** (Total Debit = Total Kredit)
5. **Simpan** dan **Post** jurnal

**Tips**:
- Pastikan akun "Diskon Penjualan" sudah ada di Chart of Accounts
- Jika menggunakan invoice dengan PPN, tambahkan baris untuk PPN
- Gunakan deskripsi yang jelas untuk memudahkan tracking

---

### **Contoh 5A: Contoh Spesifik Invoice Transaksi**

**Transaksi**: Invoice RA\X-28\25\002 ke Rama Teknik (Ibu. Rosiana)
- **Tanggal**: 28 Oktober 2025
- **Item**: 
  - Stiker Tanah Milik Ahliwaris (30 pcs) = Rp 4.500.000
  - Banner Ayam Sambal Ijo Bang Iyan (1 pcs) = Rp 44.000
  - Banner Menerima Qurban Al Fitroh (1 pcs) = Rp 44.000
- **Subtotal**: Rp 4.588.000
- **Diskon**: 6,3% = Rp 288.000
- **Total Payment**: Rp 4.300.000

**Jurnal untuk Penjualan Kredit (Piutang)**:

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Piutang Usaha (1-1102) | Debit | 4.300.000 |
| Diskon Penjualan (5-1007) | Debit | 288.000 |
| Pendapatan Penjualan (4-1002) | Kredit | 4.588.000 |

**Langkah-langkah Input di Aplikasi**:

1. Buka menu **Jurnal** → **Buat Jurnal Baru**
2. Isi header:
   - **No. Bukti**: `JU-RA-X-28-25-002` (atau sesuai format perusahaan)
   - **Tanggal**: 28-10-2025
   - **Periode**: Pilih periode Oktober 2025
   - **Deskripsi**: "Penjualan Invoice RA\X-28\25\002 ke Rama Teknik (Ibu. Rosiana)"
3. Tambah detail jurnal (3 baris):
   - **Baris 1**: Piutang Usaha (1-1102) - Debit = 4.300.000
   - **Baris 2**: Diskon Penjualan (5-1007) - Debit = 288.000
   - **Baris 3**: Pendapatan Penjualan (4-1002) - Kredit = 4.588.000
4. Pastikan **balance** (Total Debit = Total Kredit = 4.588.000)
5. **Simpan** dan **Post** jurnal

**Saat Pelunasan Piutang** (jurnal terpisah ketika dibayar):

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 4.300.000 |
| Piutang Usaha (1-1102) | Kredit | 4.300.000 |

**Catatan Penting**:
- Pastikan akun "Diskon Penjualan" sudah ada di Chart of Accounts
- Pendapatan dicatat sebesar **subtotal** (sebelum diskon) untuk akurasi laporan
- Diskon dicatat terpisah sebagai beban untuk analisis keuangan
- Jika pembayaran langsung tunai, ganti "Piutang Usaha" dengan "Kas"

---

### **Contoh 5B: Invoice dengan DP (Down Payment / Uang Muka)**

**Transaksi**: Invoice dengan subtotal Rp 4.588.000, diskon 6,3% = Rp 288.000, total Rp 4.300.000, DP Rp 1.000.000, sisa tagihan Rp 3.300.000

**Skenario A: DP Diterima Sebelum Invoice Dibuat**

**Jurnal 1: Saat Menerima DP** (tanggal sebelum invoice):

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 1.000.000 |
| Uang Muka Diterima (2-2002) | Kredit | 1.000.000 |

**Penjelasan**:
- Kas bertambah → Debit 1.000.000
- Uang Muka Diterima (liabilitas) bertambah → Kredit 1.000.000
- Total: Debit 1.000.000 = Kredit 1.000.000 ✓

**Jurnal 2: Saat Membuat Invoice** (tanggal invoice):

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Piutang Usaha (1-1102) | Debit | 3.300.000 |
| Uang Muka Diterima (2-2002) | Debit | 1.000.000 |
| Diskon Penjualan (5-1007) | Debit | 288.000 |
| Pendapatan Penjualan (4-1002) | Kredit | 4.588.000 |

**Penjelasan**:
- Piutang (sisa tagihan) → Debit 3.300.000
- Uang Muka Diterima dihapus (sudah digunakan) → Debit 1.000.000
- Diskon Penjualan (beban) → Debit 288.000
- Pendapatan Penjualan → Kredit 4.588.000
- Total: Debit 4.588.000 = Kredit 4.588.000 ✓

**Saat Pelunasan Sisa Tagihan** (jurnal terpisah):

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 3.300.000 |
| Piutang Usaha (1-1102) | Kredit | 3.300.000 |

---

**Skenario B: DP Diterima Bersamaan dengan Invoice**

**Jurnal untuk Invoice dengan DP**:

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 1.000.000 |
| Piutang Usaha (1-1102) | Debit | 3.300.000 |
| Diskon Penjualan (5-1007) | Debit | 288.000 |
| Pendapatan Penjualan (4-1002) | Kredit | 4.588.000 |

**Penjelasan**:
- Kas (DP yang diterima) → Debit 1.000.000
- Piutang (sisa tagihan) → Debit 3.300.000
- Diskon Penjualan (beban) → Debit 288.000
- Pendapatan Penjualan → Kredit 4.588.000
- Total: Debit 4.588.000 = Kredit 4.588.000 ✓

**Saat Pelunasan Sisa Tagihan** (jurnal terpisah):

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 3.300.000 |
| Piutang Usaha (1-1102) | Kredit | 3.300.000 |

---

**Skenario C: DP Diterima Setelah Invoice Dibuat**

**Jurnal 1: Saat Membuat Invoice** (tanggal invoice):

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Piutang Usaha (1-1102) | Debit | 4.300.000 |
| Diskon Penjualan (5-1007) | Debit | 288.000 |
| Pendapatan Penjualan (4-1002) | Kredit | 4.588.000 |

**Jurnal 2: Saat Menerima DP** (tanggal terima DP):

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 1.000.000 |
| Piutang Usaha (1-1102) | Kredit | 1.000.000 |

**Jurnal 3: Saat Pelunasan Sisa Tagihan** (tanggal pelunasan):

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 3.300.000 |
| Piutang Usaha (1-1102) | Kredit | 3.300.000 |

---

**Langkah-langkah Input di Aplikasi**:

1. **Buat Invoice dengan DP**
   - Isi field **DP (Uang Muka)** di form invoice
   - Sistem akan otomatis menghitung **Sisa Tagihan** = Total - DP

2. **Input Jurnal sesuai Skenario**
   - **Skenario A**: Buat 2 jurnal (DP terlebih dahulu, lalu invoice)
   - **Skenario B**: Buat 1 jurnal (DP + Piutang)
   - **Skenario C**: Buat 3 jurnal (Invoice, DP, Pelunasan)

3. **Pastikan Balance**
   - Setiap jurnal harus balance (Total Debit = Total Kredit)

**Catatan Penting**:
- Pastikan akun "Uang Muka Diterima" sudah ada di Chart of Accounts (Tipe: Liabilitas, Posisi Normal: Kredit)
- DP mengurangi piutang yang harus ditagih
- Sisa tagihan = Total Invoice - DP
- Pendapatan tetap dicatat sebesar subtotal (sebelum diskon)

---

### **Contoh 6: Pembelian dengan PPN**

**Transaksi**: Membeli barang seharga Rp 10.000.000 + PPN 11% = Rp 1.100.000, total Rp 11.100.000 secara kredit

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Persediaan Barang (1-1004) | Debit | 10.000.000 |
| PPN Masukan (1-1201) | Debit | 1.100.000 |
| Utang Usaha (2-1001) | Kredit | 11.100.000 |

**Penjelasan**:
- Aset (Persediaan) bertambah → Debit 10.000.000
- Aset (PPN Masukan) bertambah → Debit 1.100.000
- Liabilitas (Utang) bertambah → Kredit 11.100.000
- Total: Debit 11.100.000 = Kredit 11.100.000 ✓

---

### **Contoh 7: Penjualan dengan PPN**

**Transaksi**: Menjual barang seharga Rp 5.000.000 + PPN 11% = Rp 550.000, total Rp 5.550.000 tunai

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 5.550.000 |
| Pendapatan Penjualan (4-1002) | Kredit | 5.000.000 |
| PPN Keluaran (2-2001) | Kredit | 550.000 |

**Penjelasan**:
- Kas bertambah → Debit 5.550.000
- Pendapatan bertambah → Kredit 5.000.000
- Liabilitas (PPN Keluaran) bertambah → Kredit 550.000
- Total: Debit 5.550.000 = Kredit 5.550.000 ✓

---

### **Contoh 8: Membayar Utang**

**Transaksi**: Membayar utang usaha sebesar Rp 3.000.000

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Utang Usaha (2-1001) | Debit | 3.000.000 |
| Kas (1-1001) | Kredit | 3.000.000 |

**Penjelasan**:
- Liabilitas (Utang) berkurang → Debit
- Aset (Kas) berkurang → Kredit

---

### **Contoh 9: Menerima Pelunasan Piutang**

**Transaksi**: Menerima pembayaran piutang sebesar Rp 2.500.000

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 2.500.000 |
| Piutang Usaha (1-1102) | Kredit | 2.500.000 |

**Penjelasan**:
- Aset (Kas) bertambah → Debit
- Aset (Piutang) berkurang → Kredit

---

### **Contoh 10: Membayar Beban Listrik, Air, Telepon**

**Transaksi**: Membayar tagihan listrik Rp 500.000, air Rp 200.000, telepon Rp 300.000 (total Rp 1.000.000)

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Beban Listrik (5-2001) | Debit | 500.000 |
| Beban Air (5-2002) | Debit | 200.000 |
| Beban Telepon (5-2003) | Debit | 300.000 |
| Kas (1-1001) | Kredit | 1.000.000 |

**Penjelasan**:
- Beban Listrik bertambah → Debit 500.000
- Beban Air bertambah → Debit 200.000
- Beban Telepon bertambah → Debit 300.000
- Aset (Kas) berkurang → Kredit 1.000.000
- Total: Debit 1.000.000 = Kredit 1.000.000 ✓

---

### **Contoh 11: Membeli Peralatan dengan DP dan Kredit**

**Transaksi**: Membeli peralatan seharga Rp 20.000.000, DP 30% = Rp 6.000.000, sisanya kredit

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Peralatan (1-1005) | Debit | 20.000.000 |
| Kas (1-1001) | Kredit | 6.000.000 |
| Utang Usaha (2-1001) | Kredit | 14.000.000 |

**Penjelasan**:
- Aset (Peralatan) bertambah → Debit 20.000.000
- Aset (Kas) berkurang → Kredit 6.000.000
- Liabilitas (Utang) bertambah → Kredit 14.000.000
- Total: Debit 20.000.000 = Kredit 20.000.000 ✓

---

### **Contoh 12: Menerima Pendapatan Jasa dengan Piutang**

**Transaksi**: Menyelesaikan pekerjaan jasa seharga Rp 8.000.000, baru diterima 50% = Rp 4.000.000, sisanya piutang

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 4.000.000 |
| Piutang Usaha (1-1102) | Debit | 4.000.000 |
| Pendapatan Jasa (4-1001) | Kredit | 8.000.000 |

**Penjelasan**:
- Aset (Kas) bertambah → Debit 4.000.000
- Aset (Piutang) bertambah → Debit 4.000.000
- Pendapatan bertambah → Kredit 8.000.000
- Total: Debit 8.000.000 = Kredit 8.000.000 ✓

---

### **Contoh 13: Membayar Beban Sewa dan Asuransi**

**Transaksi**: Membayar sewa kantor 6 bulan Rp 12.000.000 dan asuransi 1 tahun Rp 6.000.000

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Sewa Dibayar di Muka (1-1301) | Debit | 12.000.000 |
| Asuransi Dibayar di Muka (1-1302) | Debit | 6.000.000 |
| Kas (1-1001) | Kredit | 18.000.000 |

**Penjelasan**:
- Aset (Sewa Dibayar di Muka) bertambah → Debit 12.000.000
- Aset (Asuransi Dibayar di Muka) bertambah → Debit 6.000.000
- Aset (Kas) berkurang → Kredit 18.000.000
- Total: Debit 18.000.000 = Kredit 18.000.000 ✓

**Catatan**: Sewa dan asuransi dibayar di muka adalah aset, bukan beban. Beban akan diakui setiap bulan melalui jurnal penyesuaian.

---

### **Contoh 14: Menerima Pinjaman Bank**

**Transaksi**: Menerima pinjaman bank sebesar Rp 50.000.000

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Kas (1-1001) | Debit | 50.000.000 |
| Utang Bank (2-2001) | Kredit | 50.000.000 |

**Penjelasan**:
- Aset (Kas) bertambah → Debit
- Liabilitas (Utang Bank) bertambah → Kredit

---

### **Contoh 15: Membayar Bunga Pinjaman**

**Transaksi**: Membayar bunga pinjaman bank sebesar Rp 1.000.000

| Akun | Posisi | Jumlah |
|------|--------|--------|
| Beban Bunga (5-3001) | Debit | 1.000.000 |
| Kas (1-1001) | Kredit | 1.000.000 |

**Penjelasan**:
- Beban Bunga bertambah → Debit
- Aset (Kas) berkurang → Kredit

---

## ⚠️ ATURAN PENTING

1. **Setiap jurnal HARUS balance** (Total Debit = Total Kredit)
2. **Minimal 2 baris detail** (1 Debit, 1 Kredit)
3. **Jurnal dengan status Draft bisa diedit**
4. **Jurnal dengan status Posted tidak bisa diedit** (harus Void dulu)
5. **Hanya jurnal Posted yang muncul di Buku Besar dan Laporan**

---

## 🔄 POSTING JURNAL

Setelah jurnal dibuat dan sudah benar:

1. Buka halaman detail jurnal (klik pada No. Bukti)
2. Klik tombol **"Post Jurnal"**
3. Status akan berubah dari **Draft** menjadi **Posted**
4. Jurnal yang sudah Posted akan muncul di:
   - Buku Besar
   - Laporan Laba Rugi
   - Laporan Neraca

---

## ✏️ EDIT JURNAL

1. Buka halaman detail jurnal
2. Klik tombol **"Edit"** (hanya untuk status Draft)
3. Ubah data yang diperlukan
4. Pastikan tetap balance
5. Klik **"Update Jurnal"**

---

## 🗑️ HAPUS JURNAL

1. Buka halaman detail jurnal
2. Klik tombol **"Hapus"** (hanya untuk status Draft)
3. Konfirmasi penghapusan

---

## 📋 DUPLICATE JURNAL

Fitur untuk menduplikasi jurnal yang sudah ada, memudahkan pembuatan jurnal serupa tanpa harus input ulang dari awal.

### Cara Menggunakan:

1. **Buka Halaman Daftar Jurnal**
   - Menu **Jurnal** → Daftar Jurnal

2. **Pilih Jurnal yang Akan Diduplikasi**
   - Di tabel jurnal, klik tombol **"Duplicate"** (ikon files) pada jurnal yang diinginkan
   - Tombol duplicate tersedia untuk semua status jurnal (Draft, Posted, Void)

3. **Jurnal Baru Akan Dibuat**
   - Nomor bukti otomatis dibuat baru dengan format: `JRN/YYYY/MM/XXXX`
   - Tanggal diubah ke tanggal hari ini
   - Periode diubah ke periode aktif bulan ini (jika ada), atau tetap menggunakan periode asal
   - Status diubah ke **Draft**
   - Deskripsi ditambahkan dengan teks " (Copy)"
   - Semua detail jurnal (akun, posisi, jumlah, keterangan) di-copy

4. **Edit Jurnal Baru**
   - Langsung diarahkan ke halaman edit
   - Ubah informasi sesuai kebutuhan:
     - Nomor bukti (jika perlu)
     - Tanggal transaksi
     - Periode
     - Deskripsi
     - Detail jurnal (tambah, edit, atau hapus baris)
   - Pastikan balance (Total Debit = Total Kredit)
   - Simpan jurnal baru

### Kegunaan:
- ✅ Membuat jurnal serupa dengan cepat
- ✅ Template untuk jurnal berulang (contoh: gaji bulanan, sewa bulanan)
- ✅ Menghemat waktu input data
- ✅ Konsistensi format jurnal
- ✅ Bisa diduplikasi dari jurnal Posted untuk referensi

### Catatan:
- Jurnal yang diduplikasi tetap tidak berubah
- Jurnal baru memiliki nomor bukti unik
- Status jurnal baru selalu **Draft** (bisa diedit)
- Pastikan balance sebelum menyimpan jurnal baru

---

## 💡 TIPS

1. **Gunakan deskripsi yang jelas** untuk memudahkan tracking
2. **Periksa akun yang dipilih** sebelum menyimpan
3. **Pastikan periode sudah benar** sebelum posting
4. **Backup data secara berkala**
5. **Review jurnal sebelum posting** untuk menghindari kesalahan
6. **Gunakan fitur Duplicate** untuk jurnal berulang (gaji bulanan, sewa, dll) agar lebih cepat

---

## ❓ TROUBLESHOOTING

### **Jurnal tidak bisa disimpan**
- Pastikan Total Debit = Total Kredit
- Pastikan minimal ada 2 baris detail
- Pastikan semua field wajib sudah diisi

### **Jurnal tidak muncul di Buku Besar**
- Pastikan jurnal sudah di-Post (status = Posted)
- Pastikan periode jurnal sudah benar
- Cek filter tanggal di Buku Besar

### **Tidak bisa edit jurnal**
- Hanya jurnal dengan status Draft yang bisa diedit
- Jurnal Posted harus di-Void dulu jika ingin diubah

---

**Selamat menggunakan sistem jurnal!** 🎉
MARKDOWN;
    }

    private function getPanduanInvoice()
    {
        return <<<'MARKDOWN'
# Panduan Invoice & Dokumen

## 📋 Daftar Isi
1. [Membuat Invoice Baru](#membuat-invoice-baru)
2. [Mengelola Invoice](#mengelola-invoice)
3. [Status Invoice](#status-invoice)
4. [Duplicate Invoice](#duplicate-invoice)
5. [Term & Condition dan Payment Terms](#term--condition-dan-payment-terms)
6. [Template Invoice](#template-invoice)
7. [Offering dan Surat Jalan](#offering-dan-surat-jalan)

---

## Membuat Invoice Baru

### Langkah-langkah:

1. **Akses Menu Invoice**
   - Klik menu **Invoice** di sidebar atau melalui **Profile Modal** → **Invoice**

2. **Klik Tombol "Buat Invoice Baru"**
   - Tombol berada di pojok kanan atas halaman index invoice

3. **Isi Header Invoice**
   - **No. Invoice**: Otomatis terisi, bisa diubah sesuai kebutuhan
   - **Tanggal**: Pilih tanggal invoice
   - **Status**: Pilih status (Draft, Sent, Paid, Overdue)

4. **Isi Informasi Penerima**
   - **Nama**: Nama customer/penerima
   - **Alamat**: Alamat lengkap
   - **Kota**: Kota customer
   - **Telepon**: Nomor telepon

5. **Tambah Item**
   - Klik tombol **"Tambah Item"** untuk menambah baris item
   - Isi:
     - **Nama Item**: Nama produk/jasa
     - **Deskripsi**: Detail item (opsional)
     - **Qty**: Jumlah
     - **Satuan**: Unit (pcs, kg, m, dll)
     - **Harga**: Harga per unit
     - **Total**: Otomatis terhitung (Qty × Harga)
   - Untuk menghapus item, klik tombol **"Hapus"** di baris item

6. **Isi Summary**
   - **Subtotal**: Otomatis terhitung dari total semua item
   - **Diskon**: Masukkan jumlah diskon (opsional)
   - **PPN**: Masukkan jumlah PPN (opsional)
   - **DP (Uang Muka)**: Masukkan jumlah uang muka yang diterima (opsional)
   - **Total**: Otomatis terhitung (Subtotal - Diskon + PPN)
   - **Sisa Tagihan**: Otomatis terhitung (Total - DP)

7. **Term & Condition dan Payment Terms**
   - **Term & Condition**: Syarat dan ketentuan (opsional)
     - Klik **"Load Template"** untuk menggunakan template default
   - **Payment Terms**: Aturan pembayaran (opsional)
     - Klik **"Load Template"** untuk menggunakan template default

8. **Signature**
   - **Nama Penandatangan**: Nama yang akan muncul di bawah signature (opsional)

9. **Simpan Invoice**
   - Klik tombol **"Simpan Invoice"**
   - Invoice akan tersimpan dengan status yang dipilih

---

## Mengelola Invoice

### Melihat Daftar Invoice
- Menu **Invoice** menampilkan semua invoice
- Tabel menampilkan: No. Invoice, Tanggal, Kepada, Status, Total, dan Aksi

### Melihat Detail Invoice
- Klik tombol **"Lihat"** (ikon mata) pada invoice yang diinginkan
- Halaman detail menampilkan semua informasi invoice
- Tersedia 2 template:
  - **Template Baru**: Template dengan layout modern
  - **Template V2**: Template sederhana

### Edit Invoice
- Klik tombol **"Edit"** (ikon pensil)
- Hanya invoice dengan status **Draft** yang bisa diedit
- Ubah informasi sesuai kebutuhan
- Klik **"Update Invoice"** untuk menyimpan perubahan

### Hapus Invoice
- Klik tombol **"Hapus"** (ikon trash)
- Konfirmasi penghapusan
- **Peringatan**: Hapus invoice akan menghapus semua item terkait

---

## Status Invoice

Invoice memiliki 4 status:

1. **Draft** (Abu-abu)
   - Invoice baru dibuat, belum dikirim
   - Masih bisa diedit

2. **Sent** (Biru)
   - Invoice sudah dikirim ke customer
   - Tidak bisa diedit lagi

3. **Paid** (Hijau)
   - Invoice sudah dibayar
   - Final status

4. **Overdue** (Merah)
   - Invoice sudah melewati tanggal jatuh tempo
   - Belum dibayar

### Mengubah Status
- Edit invoice dan pilih status baru
- Atau ubah langsung di form edit

---

## Duplicate Invoice

### Cara Duplicate Invoice:

1. **Pilih Invoice yang Akan Diduplikasi**
   - Di halaman index invoice, klik tombol **"Duplicate"** (ikon files)

2. **Invoice Baru Akan Dibuat**
   - Nomor invoice otomatis dibuat baru
   - Tanggal diubah ke tanggal hari ini
   - Status diubah ke **Draft**
   - Semua item dan informasi lainnya di-copy

3. **Edit Invoice Baru**
   - Langsung diarahkan ke halaman edit
   - Ubah informasi sesuai kebutuhan
   - Simpan invoice baru

### Kegunaan:
- Membuat invoice serupa dengan cepat
- Template untuk invoice berulang
- Menghemat waktu input

---

## DP (Down Payment / Uang Muka)

### Deskripsi
Fitur untuk mencatat uang muka yang diterima dari customer sebelum atau saat invoice dibuat. DP akan mengurangi sisa tagihan yang harus dibayar.

### Cara Menggunakan

1. **Isi Field DP di Invoice**
   - Di form create/edit invoice, bagian **Summary**
   - Masukkan jumlah **DP (Uang Muka)** yang diterima
   - Sistem akan otomatis menghitung **Sisa Tagihan** = Total - DP

2. **Sisa Tagihan Otomatis Terhitung**
   - **Sisa Tagihan** ditampilkan di bawah Total
   - Warna biru (info) untuk memudahkan identifikasi
   - Update otomatis saat DP diubah

3. **Tampilan di Invoice Print**
   - DP ditampilkan di summary pembayaran
   - Sisa Tagihan ditampilkan dengan warna kuning (warning)
   - Memudahkan customer melihat berapa yang sudah dibayar dan berapa sisa

### Contoh Perhitungan

- **Subtotal**: Rp 4.588.000
- **Diskon**: Rp 288.000
- **PPN**: Rp 0
- **Total**: Rp 4.300.000
- **DP**: Rp 1.000.000
- **Sisa Tagihan**: Rp 3.300.000

### Skenario Penggunaan

#### 1. **DP Diterima Sebelum Invoice**
- Customer membayar DP terlebih dahulu
- Invoice dibuat kemudian dengan DP yang sudah diterima
- Sisa tagihan = Total - DP

#### 2. **DP Diterima Bersamaan dengan Invoice**
- Customer membayar DP saat invoice dibuat
- Invoice langsung mencatat DP
- Sisa tagihan = Total - DP

#### 3. **DP Diterima Setelah Invoice**
- Invoice dibuat tanpa DP
- DP diterima kemudian
- Edit invoice untuk menambahkan DP
- Sisa tagihan = Total - DP

### Input Jurnal untuk DP

Untuk panduan lengkap cara input jurnal dengan DP, lihat:
**Menu Bantuan** → **Panduan Jurnal** → **Contoh 5B: Invoice dengan DP**

Panduan mencakup 3 skenario lengkap dengan contoh jurnal untuk setiap skenario.

### Catatan Penting

- ✅ DP mengurangi piutang yang harus ditagih
- ✅ Sisa Tagihan = Total Invoice - DP
- ✅ DP bisa diisi atau diubah kapan saja (sebelum, saat, atau setelah invoice dibuat)
- ✅ Pendapatan tetap dicatat sebesar subtotal (sebelum diskon)
- ✅ Pastikan akun "Uang Muka Diterima" sudah ada di Chart of Accounts (jika menggunakan Skenario A)

---

## Term & Condition dan Payment Terms

### Term & Condition
- Syarat dan ketentuan yang berlaku untuk invoice
- Contoh: Syarat pengiriman, garansi produk, kebijakan retur

### Payment Terms
- Aturan pembayaran
- Contoh: Metode pembayaran, informasi rekening bank, jangka waktu pembayaran

### Menggunakan Template
- Klik tombol **"Load Template"** untuk menggunakan template default
- Template bisa diubah sesuai kebutuhan
- Template akan muncul di print layout

---

## Template Invoice

Aplikasi menyediakan 2 template invoice:

### Template Baru (Default)
- Layout modern dengan header lengkap
- Logo perusahaan di kiri, informasi di kanan
- Item table dengan kolom: No, Description, Price, Qty, Total
- Payment summary di kanan
- Term & Condition dan Payment Terms di footer
- Signature area dengan logo watermark

### Template V2
- Layout sederhana
- Header dengan informasi dasar
- Item table lengkap dengan deskripsi
- Term & Condition dan Payment Terms horizontal
- Signature area

### Mengganti Template
- Di halaman detail invoice
- Klik tombol **"Template V2"** atau **"Template Baru"**
- Template akan langsung berubah

---

## Offering dan Surat Jalan

### Offering (Penawaran)
- Mirip dengan Invoice
- Digunakan untuk membuat penawaran harga
- Memiliki field **Tanggal Berlaku**
- Tidak memiliki status pembayaran

### Surat Jalan
- Dokumen pengiriman barang
- Memiliki informasi: Dari, Kepada, No. Kendaraan, Nama Supir
- Item tidak memiliki harga (hanya qty dan satuan)

### Cara Menggunakan
- Akses melalui **Profile Modal**
- Klik **"Offering"** atau **"Surat Jalan"**
- Proses sama dengan membuat Invoice

---

**Selamat menggunakan fitur Invoice & Dokumen!** 🎉
MARKDOWN;
    }

    private function getPanduanFiturPrioritas()
    {
        return <<<'MARKDOWN'
# Fitur Prioritas Tinggi

## 📋 Daftar Isi
1. [Duplicate/Copy Invoice](#duplicatecopy-invoice)
2. [Status & Tracking Invoice](#status--tracking-invoice)
3. [Export PDF untuk Laporan](#export-pdf-untuk-laporan)
4. [Laporan Arus Kas](#laporan-arus-kas)

---

## Duplicate/Copy Invoice

### Deskripsi
Fitur untuk menduplikasi invoice yang sudah ada, memudahkan pembuatan invoice serupa tanpa harus input ulang dari awal.

### Cara Menggunakan

1. **Buka Halaman Invoice**
   - Menu **Invoice** → atau melalui **Profile Modal** → **Invoice**

2. **Pilih Invoice yang Akan Diduplikasi**
   - Di tabel invoice, klik tombol **"Duplicate"** (ikon files) pada invoice yang diinginkan

3. **Invoice Baru Akan Dibuat**
   - Nomor invoice otomatis dibuat baru dengan format: `INV/YYYY/MM/XXXX`
   - Tanggal diubah ke tanggal hari ini
   - Status diubah ke **Draft**
   - Semua item, informasi customer, term & condition, dan payment terms di-copy

4. **Edit Invoice Baru**
   - Langsung diarahkan ke halaman edit
   - Ubah informasi sesuai kebutuhan
   - Simpan invoice baru

### Kegunaan
- ✅ Membuat invoice serupa dengan cepat
- ✅ Template untuk invoice berulang
- ✅ Menghemat waktu input data
- ✅ Konsistensi format invoice

---

## Status & Tracking Invoice

### Deskripsi
Sistem tracking status invoice untuk memantau progress pembayaran dan pengiriman invoice.

### Status yang Tersedia

#### 1. **Draft** (Abu-abu)
- Invoice baru dibuat, belum dikirim
- Masih bisa diedit dan dihapus

#### 2. **Sent** (Biru)
- Invoice sudah dikirim ke customer
- Tidak bisa diedit lagi

#### 3. **Paid** (Hijau)
- Invoice sudah dibayar
- Status final

#### 4. **Overdue** (Merah)
- Invoice sudah melewati tanggal jatuh tempo
- Belum dibayar

### Cara Menggunakan

#### Mengubah Status saat Membuat Invoice
1. Di form create/edit invoice
2. Pilih status dari dropdown **"Status"**
3. Simpan invoice

#### Mengubah Status Invoice yang Sudah Ada
1. Buka detail invoice
2. Klik tombol **"Edit"**
3. Ubah status di dropdown
4. Klik **"Update Invoice"**

#### Melihat Status di Tabel
- Status ditampilkan sebagai badge berwarna di tabel invoice
- Mudah melihat status semua invoice sekaligus

### Workflow yang Disarankan

1. **Buat Invoice** → Status: **Draft**
2. **Kirim Invoice** → Status: **Sent**
3. **Terima Pembayaran** → Status: **Paid**
4. **Jika Jatuh Tempo** → Status: **Overdue**

---

## Export PDF untuk Laporan

### Deskripsi
Fitur untuk mengekspor laporan keuangan ke format PDF, memudahkan sharing dan arsip dokumen.

### Laporan yang Bisa Diekspor

#### 1. **Laporan Laba Rugi**
- Menampilkan Pendapatan dan Beban
- Periode tertentu
- Perhitungan Laba/Rugi Bersih

#### 2. **Laporan Neraca**
- Menampilkan Aset, Liabilitas, dan Ekuitas
- Per tanggal tertentu
- Termasuk Laba Rugi Tahun Berjalan

#### 3. **Buku Besar**
- Mutasi per akun
- Periode tertentu
- Saldo awal dan saldo akhir
- Running balance

### Cara Menggunakan

#### Export Laporan Laba Rugi
1. Buka menu **Laporan** → **Laba Rugi**
2. Pilih **Tanggal Mulai** dan **Tanggal Selesai**
3. Klik **"Tampilkan Laporan"**
4. Setelah laporan muncul, klik tombol **"Export PDF"** (ikon file PDF merah)
5. File PDF akan terunduh

#### Export Laporan Neraca
1. Buka menu **Laporan** → **Neraca**
2. Pilih **Per Tanggal**
3. Klik **"Tampilkan Laporan"**
4. Setelah laporan muncul, klik tombol **"Export PDF"**
5. File PDF akan terunduh

#### Export Buku Besar
1. Buka menu **Buku Besar**
2. Pilih **Akun**, **Tanggal Mulai**, dan **Tanggal Selesai**
3. Klik **"Tampilkan"**
4. Di halaman detail buku besar, klik tombol **"Export PDF"**
5. File PDF akan terunduh

### Format PDF
- Header dengan logo dan informasi perusahaan
- Watermark di tengah (logo perusahaan)
- Footer dengan tanggal cetak
- Layout profesional untuk print

---

## Laporan Arus Kas

### Deskripsi
Laporan arus kas (Cash Flow Statement) menampilkan pergerakan kas perusahaan dalam 3 aktivitas: Operasi, Investasi, dan Pendanaan.

### Akses Menu
- Menu **Laporan** → **Arus Kas**

### Cara Menggunakan

1. **Buka Laporan Arus Kas**
   - Klik menu **Laporan** → **Arus Kas**

2. **Pilih Periode**
   - **Tanggal Mulai**: Tanggal awal periode
   - **Tanggal Selesai**: Tanggal akhir periode
   - Klik **"Tampilkan Laporan"**

3. **Laporan Akan Ditampilkan**
   - Aktivitas Operasi
   - Aktivitas Investasi
   - Aktivitas Pendanaan
   - Perubahan Kas Bersih
   - Saldo Kas Awal dan Akhir

### Komponen Laporan

#### 1. **Aktivitas Operasi**
- **Pendapatan**: Total pendapatan dalam periode
- **Beban**: Total beban dalam periode
- **Kas dari Aktivitas Operasi**: Pendapatan - Beban

#### 2. **Aktivitas Investasi**
- **Pembelian Aset Tetap**: Pembelian tanah, bangunan, peralatan, kendaraan, mesin
- **Kas dari Aktivitas Investasi**: Total investasi (negatif karena mengurangi kas)

#### 3. **Aktivitas Pendanaan**
- **Perubahan Ekuitas**: Penambahan modal, laba ditahan
- **Perubahan Liabilitas**: Penambahan utang
- **Kas dari Aktivitas Pendanaan**: Total pendanaan

#### 4. **Perubahan Kas Bersih**
- Total dari 3 aktivitas di atas
- Menunjukkan perubahan kas dalam periode

#### 5. **Saldo Kas**
- **Saldo Kas Awal Periode**: Saldo kas di tanggal mulai
- **Perubahan Kas Bersih**: Perubahan dari aktivitas
- **Saldo Kas Akhir Periode**: Saldo kas di tanggal selesai

### Interpretasi Laporan

#### Kas dari Operasi Positif
- ✅ Perusahaan menghasilkan kas dari operasi
- ✅ Operasi sehat dan profitable

#### Kas dari Operasi Negatif
- ⚠️ Perusahaan menggunakan kas untuk operasi
- ⚠️ Perlu evaluasi operasi

---

**Selamat menggunakan fitur prioritas tinggi!** 🎉
MARKDOWN;
    }

    private function markdownToHtml($markdown)
    {
        $html = $markdown;
        
        // Headers
        $html = preg_replace('/^# (.*?)$/m', '<h1>$1</h1>', $html);
        $html = preg_replace('/^## (.*?)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^### (.*?)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^#### (.*?)$/m', '<h4>$1</h4>', $html);
        $html = preg_replace('/^##### (.*?)$/m', '<h5>$1</h5>', $html);
        $html = preg_replace('/^###### (.*?)$/m', '<h6>$1</h6>', $html);
        
        // Bold
        $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
        $html = preg_replace('/__(.*?)__/', '<strong>$1</strong>', $html);
        
        // Italic
        $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);
        $html = preg_replace('/_(.*?)_/', '<em>$1</em>', $html);
        
        // Code blocks
        $html = preg_replace('/```(\w+)?\n(.*?)```/s', '<pre><code>$2</code></pre>', $html);
        $html = preg_replace('/`(.*?)`/', '<code>$1</code>', $html);
        
        // Links
        $html = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2">$1</a>', $html);
        
        // Horizontal rule
        $html = preg_replace('/^---$/m', '<hr>', $html);
        $html = preg_replace('/^\*\*\*$/m', '<hr>', $html);
        
        // Lists
        $lines = explode("\n", $html);
        $inList = false;
        $inOrderedList = false;
        $result = [];
        
        foreach ($lines as $line) {
            if (preg_match('/^[\*\-\+]\s+(.*)$/', $line, $matches)) {
                if (!$inList) {
                    if ($inOrderedList) {
                        $result[] = '</ol>';
                        $inOrderedList = false;
                    }
                    $result[] = '<ul>';
                    $inList = true;
                }
                $result[] = '<li>' . trim($matches[1]) . '</li>';
            } elseif (preg_match('/^\d+\.\s+(.*)$/', $line, $matches)) {
                if (!$inOrderedList) {
                    if ($inList) {
                        $result[] = '</ul>';
                        $inList = false;
                    }
                    $result[] = '<ol>';
                    $inOrderedList = true;
                }
                $result[] = '<li>' . trim($matches[1]) . '</li>';
            } else {
                if ($inList) {
                    $result[] = '</ul>';
                    $inList = false;
                }
                if ($inOrderedList) {
                    $result[] = '</ol>';
                    $inOrderedList = false;
                }
                $result[] = $line;
            }
        }
        
        if ($inList) {
            $result[] = '</ul>';
        }
        if ($inOrderedList) {
            $result[] = '</ol>';
        }
        
        $html = implode("\n", $result);
        
        // Tables
        $html = preg_replace_callback('/\|(.+)\|/m', function($matches) {
            $cells = explode('|', trim($matches[1]));
            $row = '<tr>';
            foreach ($cells as $cell) {
                $cell = trim($cell);
                if (empty($cell) || strpos($cell, '---') !== false) {
                    continue;
                }
                $row .= '<td>' . $cell . '</td>';
            }
            $row .= '</tr>';
            return $row;
        }, $html);
        
        // Wrap tables
        $html = preg_replace('/(<tr>.*?<\/tr>)/s', '<table class="table table-bordered">$1</table>', $html);
        
        // Paragraphs
        $html = preg_replace('/\n\n+/', '</p><p>', $html);
        $html = '<p>' . $html . '</p>';
        $html = preg_replace('/<p><(h[1-6]|ul|ol|table|pre|hr)/', '<$1', $html);
        $html = preg_replace('/(<\/h[1-6]|<\/ul>|<\/ol>|<\/table>|<\/pre>|<\/hr>)<\/p>/', '$1', $html);
        $html = preg_replace('/<p><\/p>/', '', $html);
        $html = preg_replace('/<p>\s*<\/p>/', '', $html);
        
        return $html;
    }
}

