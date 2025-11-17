# Dokumentasi: Bagian yang Menunjukkan Kepemilikan Jurnal

Dokumen ini menjelaskan semua bagian dalam sistem yang menunjukkan jurnal tersebut milik akun siapa.

## 📋 Daftar Isi

1. [Database (Migration)](#1-database-migration)
2. [Model (JurnalHeader)](#2-model-jurnalheader)
3. [Controller (JurnalController)](#3-controller-jurnalcontroller)
4. [View (Tampilan)](#4-view-tampilan)
5. [Query Filtering](#5-query-filtering)

---

## 1. Database (Migration)

### File: `database/migrations/2025_11_09_141638_create_jurnal_headers_table.php`

**Kolom `user_id` di tabel `jurnal_headers`:**

```php
$table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
```

**Penjelasan:**
- Kolom `user_id` menyimpan ID user yang memiliki jurnal tersebut
- Tipe: Foreign Key ke tabel `users`
- Nullable: Bisa NULL (untuk data lama yang belum ada user_id)
- On Delete: Set NULL (jika user dihapus, user_id menjadi NULL)

**Cara melihat di database:**
```sql
SELECT id, no_bukti, user_id, deskripsi 
FROM jurnal_headers 
WHERE user_id = 3; -- Contoh: jurnal milik user ID 3
```

---

## 2. Model (JurnalHeader)

### File: `app/Models/JurnalHeader.php`

**A. Fillable Property:**
```php
protected $fillable = [
    'no_bukti',
    'tanggal_transaksi',
    'periode_id',
    'deskripsi',
    'total_debit',
    'total_kredit',
    'status',
    'user_id'  // ← Menyimpan ID user pemilik jurnal
];
```

**B. Relasi ke User:**
```php
// Relasi ke user
public function user()
{
    return $this->belongsTo(User::class);
}
```

**Cara menggunakan:**
```php
$jurnal = JurnalHeader::find(1);
$pemilik = $jurnal->user; // Mengambil data user pemilik jurnal
echo $pemilik->name;      // Nama user
echo $pemilik->email;     // Email user
```

---

## 3. Controller (JurnalController)

### File: `app/Http/Controllers/JurnalController.php`

**A. Saat Membuat Jurnal Baru (Method `store`):**

```php
// Line 212-222
$jurnal = JurnalHeader::create([
    'no_bukti' => $validated['no_bukti'],
    'tanggal_transaksi' => $validated['tanggal_transaksi'],
    'periode_id' => $validated['periode_id'],
    'deskripsi' => $validated['deskripsi'],
    'total_debit' => $totalDebit,
    'total_kredit' => $totalKredit,
    'status' => 'Draft',
    'user_id' => auth()->id()  // ← Otomatis di-set ke user yang login
]);
```

**B. Filter Jurnal Berdasarkan User (Method `index`):**

```php
// Line 52-58
$query = JurnalHeader::with(['periode', 'user', 'details']);

// Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
$user = $this->currentUser();
if (!$user->is_owner) {
    $query = $query->where('user_id', $user->id);  // ← Filter berdasarkan user_id
}
```

**C. Validasi Akses (Method `show`, `edit`, `update`, `destroy`):**

```php
// Line 241-244
$user = $this->currentUser();
if (!$user->is_owner && $jurnal->user_id !== $user->id) {
    return redirect()->route('jurnal.index')
        ->with('error', 'Anda tidak memiliki akses untuk melihat jurnal ini');
}
```

---

## 4. View (Tampilan)

### A. Halaman Index Jurnal

**File: `resources/views/jurnal/index.blade.php`**

**Kolom "Pemilik" (hanya untuk Owner):**

```blade
@if(auth()->user()->is_owner)
<th>Pemilik</th>
@endif
```

**Menampilkan informasi pemilik:**

```blade
@if(auth()->user()->is_owner)
<td>
    <span class="badge bg-info text-white">
        <i class="bi bi-person"></i> {{ $jurnal->user->name ?? 'System' }}
    </span>
    <br>
    <small class="text-muted">{{ $jurnal->user->email ?? '-' }}</small>
</td>
@endif
```

**Penjelasan:**
- Kolom "Pemilik" hanya ditampilkan jika user adalah **owner**
- Menampilkan nama dan email user pemilik jurnal
- Non-owner tidak melihat kolom ini (karena hanya melihat jurnal miliknya sendiri)

### B. Halaman Detail Jurnal

**File: `resources/views/jurnal/show.blade.php`**

**Informasi "Dibuat Oleh":**

```blade
<tr>
    <th width="35%">Dibuat Oleh</th>
    <td>: {{ $jurnal->user->name ?? 'System' }}</td>
</tr>
```

**Penjelasan:**
- Menampilkan nama user yang membuat jurnal
- Jika `user_id` NULL, menampilkan "System"

---

## 5. Query Filtering

### A. Base Controller Method

**File: `app/Http/Controllers/Controller.php`**

**Method `scopeUser()`:**

```php
protected function scopeUser($query, $userId = null)
{
    $user = $userId ?? $this->currentUser();
    
    if ($user && !$user->is_owner) {
        return $query->where('user_id', $user->id);  // ← Filter berdasarkan user_id
    }
    
    return $query; // Owner tidak difilter, bisa lihat semua
}
```

**Penjelasan:**
- Jika user **bukan owner**, query akan difilter berdasarkan `user_id`
- Jika user **adalah owner**, query tidak difilter (bisa melihat semua data)

### B. Dashboard Controller

**File: `app/Http/Controllers/DashboardController.php`**

**Contoh penggunaan di Dashboard:**

```php
// Line 33-38
$jurnalQuery = JurnalHeader::whereIn('periode_id', $periodeTahunBerjalan);

// Owner bisa melihat semua jurnal, non-owner hanya jurnal miliknya
if (!$user->is_owner) {
    $jurnalQuery = $jurnalQuery->where('user_id', $user->id);
}
```

---

## 📊 Ringkasan

| Bagian | Lokasi | Fungsi |
|--------|--------|--------|
| **Database** | `jurnal_headers.user_id` | Menyimpan ID user pemilik jurnal |
| **Model** | `JurnalHeader::user()` | Relasi untuk mengakses data user |
| **Controller** | `JurnalController::store()` | Set `user_id` saat create jurnal |
| **Controller** | `JurnalController::index()` | Filter jurnal berdasarkan `user_id` |
| **View Index** | `jurnal/index.blade.php` | Kolom "Pemilik" (hanya owner) |
| **View Show** | `jurnal/show.blade.php` | Info "Dibuat Oleh" |
| **Base Controller** | `Controller::scopeUser()` | Helper method untuk filter query |

---

## 🔍 Cara Mengecek Kepemilikan Jurnal

### 1. Via Database Query:
```sql
SELECT 
    jh.id,
    jh.no_bukti,
    jh.user_id,
    u.name as pemilik,
    u.email
FROM jurnal_headers jh
LEFT JOIN users u ON jh.user_id = u.id
WHERE jh.id = 1;
```

### 2. Via Laravel Tinker:
```php
$jurnal = App\Models\JurnalHeader::find(1);
echo "Pemilik: " . $jurnal->user->name . " (" . $jurnal->user->email . ")";
```

### 3. Via Controller:
```php
$jurnal = JurnalHeader::with('user')->find(1);
$pemilik = $jurnal->user; // Data user pemilik
```

### 4. Via View:
```blade
{{ $jurnal->user->name ?? 'System' }}
{{ $jurnal->user->email ?? '-' }}
```

---

## ✅ Kesimpulan

**Kepemilikan jurnal ditentukan oleh:**
1. ✅ Kolom `user_id` di tabel `jurnal_headers` (database)
2. ✅ Relasi `user()` di model `JurnalHeader` (model)
3. ✅ Filter query berdasarkan `user_id` di controller (logic)
4. ✅ Tampilan informasi pemilik di view (UI)

**Owner (`wahid@tpmcmms.id`):**
- ✅ Bisa melihat semua jurnal dari semua user
- ✅ Kolom "Pemilik" ditampilkan di halaman index
- ✅ Tidak ada filter `user_id` di query

**Non-Owner:**
- ✅ Hanya bisa melihat jurnal miliknya sendiri
- ✅ Kolom "Pemilik" tidak ditampilkan
- ✅ Query selalu difilter berdasarkan `user_id`

