# Panduan Setup GitHub untuk Do-Account

## **Status Repository Saat Ini**

✅ Git repository sudah diinisialisasi  
✅ Remote GitHub sudah terhubung: `https://github.com/wnurcipto/doaccount.git`  
✅ Branch aktif: `Main`  
✅ Working tree clean

---

## **Perintah Git yang Sering Digunakan**

### **1. Cek Status Repository**
```bash
git status
```

### **2. Menambahkan File ke Staging**
```bash
# Tambahkan semua file yang berubah
git add .

# Atau tambahkan file spesifik
git add nama-file.php
```

### **3. Commit Perubahan**
```bash
git commit -m "Pesan commit yang deskriptif"
```

**Contoh pesan commit yang baik:**
- `"Add Customer and Supplier management feature"`
- `"Fix bug in jurnal validation"`
- `"Update dashboard chart display"`
- `"Add PDF export for reports"`

### **4. Push ke GitHub**
```bash
# Push ke branch Main
git push origin Main

# Atau jika branch lain
git push origin nama-branch
```

### **5. Pull dari GitHub (update dari remote)**
```bash
git pull origin Main
```

### **6. Melihat History Commit**
```bash
git log --oneline -10
```

### **7. Melihat Perbedaan File**
```bash
git diff
```

---

## **Workflow Standar untuk Update Code**

### **Langkah 1: Cek Status**
```bash
git status
```
**Output yang diharapkan:**
- Jika ada perubahan: akan menampilkan daftar file yang modified/untracked
- Jika clean: "nothing to commit, working tree clean"

### **Langkah 2: Tambahkan Perubahan**
```bash
# Tambahkan semua file yang berubah
git add .

# Atau tambahkan file spesifik
git add nama-file.php
git add app/Models/Customer.php
```

### **Langkah 3: Commit**
```bash
git commit -m "Deskripsi perubahan yang dilakukan"
```

**Contoh pesan commit yang baik:**
- `"Add Customer and Supplier management feature"`
- `"Fix validation error in jurnal form"`
- `"Update dashboard chart display"`
- `"Add PDF export for reports"`
- `"Fix bug in invoice calculation"`

### **Langkah 4: Push ke GitHub**
```bash
git push origin main
```

**Catatan:** Branch saat ini adalah `main` (lowercase), bukan `Main`

### **Langkah 5: Verifikasi**
```bash
# Cek apakah sudah ter-push
git status

# Atau cek log
git log --oneline -3
```

---

## **Panduan Lengkap: Push Update ke GitHub**

### **Skenario 1: Update Code Setelah Perubahan**

**Situasi:** Anda sudah membuat perubahan di code (menambah fitur, fix bug, dll)

**Langkah-langkah:**

1. **Cek perubahan yang ada:**
   ```bash
   git status
   ```
   Output akan menampilkan file yang modified/untracked

2. **Lihat detail perubahan (opsional):**
   ```bash
   git diff
   ```
   Ini akan menampilkan baris-baris yang berubah

3. **Tambahkan semua perubahan:**
   ```bash
   git add .
   ```

4. **Commit dengan pesan yang jelas:**
   ```bash
   git commit -m "Deskripsi perubahan"
   ```
   Contoh:
   ```bash
   git commit -m "Add customer management feature with CRUD operations"
   git commit -m "Fix validation error in jurnal form"
   git commit -m "Update dashboard to show monthly revenue chart"
   ```

5. **Push ke GitHub:**
   ```bash
   git push origin main
   ```

6. **Verifikasi:**
   ```bash
   git status
   ```
   Harus menampilkan: "Your branch is up to date with 'origin/main'"

---

### **Skenario 2: Update Setelah Pull dari GitHub**

**Situasi:** Ada perubahan di GitHub yang perlu di-pull dulu sebelum push

**Langkah-langkah:**

1. **Pull perubahan terbaru:**
   ```bash
   git pull origin main
   ```

2. **Jika ada konflik, selesaikan konflik:**
   - Buka file yang konflik
   - Edit manual untuk menyelesaikan
   - `git add nama-file.php`
   - `git commit -m "Resolve merge conflict"`

3. **Push perubahan Anda:**
   ```bash
   git push origin main
   ```

---

### **Skenario 3: Update File Spesifik**

**Situasi:** Hanya ingin push beberapa file tertentu, bukan semua

**Langkah-langkah:**

1. **Tambahkan file spesifik:**
   ```bash
   git add app/Models/Customer.php
   git add resources/views/customer/index.blade.php
   ```

2. **Commit:**
   ```bash
   git commit -m "Update customer model and view"
   ```

3. **Push:**
   ```bash
   git push origin main
   ```

---

### **Skenario 4: Update dengan Multiple Commits**

**Situasi:** Ada beberapa perubahan yang ingin di-commit terpisah

**Langkah-langkah:**

1. **Commit perubahan pertama:**
   ```bash
   git add app/Models/Customer.php
   git commit -m "Add Customer model"
   ```

2. **Commit perubahan kedua:**
   ```bash
   git add app/Http/Controllers/CustomerController.php
   git commit -m "Add CustomerController with CRUD"
   ```

3. **Commit perubahan ketiga:**
   ```bash
   git add resources/views/customer/
   git commit -m "Add customer views (index, create, edit, show)"
   ```

4. **Push semua commit sekaligus:**
   ```bash
   git push origin main
   ```

---

### **Skenario 5: Update Setelah Bekerja di Branch Lain**

**Situasi:** Anda bekerja di branch lain, lalu ingin merge ke main

**Langkah-langkah:**

1. **Commit perubahan di branch lain:**
   ```bash
   git checkout feature-branch
   git add .
   git commit -m "Add new feature"
   git push origin feature-branch
   ```

2. **Kembali ke main:**
   ```bash
   git checkout main
   ```

3. **Pull perubahan terbaru:**
   ```bash
   git pull origin main
   ```

4. **Merge branch:**
   ```bash
   git merge feature-branch
   ```

5. **Push ke GitHub:**
   ```bash
   git push origin main
   ```

---

## **Troubleshooting Push Update**

### **Error: "Updates were rejected because the remote contains work"**

**Penyebab:** Ada perubahan di GitHub yang belum di-pull

**Solusi:**
```bash
# Pull dulu
git pull origin main

# Selesaikan konflik jika ada
# Lalu push lagi
git push origin main
```

### **Error: "Permission denied"**

**Penyebab:** Masalah autentikasi GitHub

**Solusi:**
1. Cek kredensial GitHub
2. Atau gunakan Personal Access Token
3. Atau setup SSH key (lihat bagian Setup SSH Key)

### **Error: "Everything up-to-date"**

**Penyebab:** Tidak ada perubahan yang perlu di-push

**Ini normal!** Artinya semua sudah ter-sinkronisasi.

### **Error: "Failed to push some refs"**

**Penyebab:** Ada masalah dengan remote atau network

**Solusi:**
```bash
# Cek remote
git remote -v

# Coba push lagi
git push origin main

# Atau dengan force (HATI-HATI, hanya jika yakin!)
git push --force origin main
```

---

## **Best Practices untuk Push Update**

### **1. Commit Sering-sering**
- Jangan menunggu sampai banyak perubahan
- Commit setiap fitur yang selesai
- Lebih mudah untuk track dan rollback jika perlu

### **2. Pesan Commit yang Jelas**
✅ **Baik:**
- `"Add customer management feature with CRUD operations"`
- `"Fix validation error in jurnal form when customer is selected"`
- `"Update dashboard to show monthly revenue chart for 2024"`

❌ **Buruk:**
- `"Update"`
- `"Fix"`
- `"Changes"`

### **3. Pull Sebelum Push**
```bash
git pull origin main
git push origin main
```
Ini menghindari konflik dan memastikan code terbaru.

### **4. Test Sebelum Push**
- Pastikan code berjalan dengan baik
- Test fitur yang diubah
- Jangan push code yang broken

### **5. Review Perubahan**
```bash
# Lihat apa yang akan di-commit
git diff --staged

# Lihat history commit
git log --oneline -5
```

---

## **Quick Reference: Push Update**

| Situasi | Perintah |
|---------|----------|
| Push semua perubahan | `git add .` → `git commit -m "pesan"` → `git push origin main` |
| Push file spesifik | `git add file.php` → `git commit -m "pesan"` → `git push origin main` |
| Pull dulu, lalu push | `git pull origin main` → `git push origin main` |
| Cek status | `git status` |
| Lihat perubahan | `git diff` |
| Lihat history | `git log --oneline -10` |
| Cek remote | `git remote -v` |

---

## **Contoh Workflow Lengkap**

### **Contoh: Menambah Fitur Baru**

```bash
# 1. Cek status
git status

# 2. Buat perubahan (edit file, tambah file, dll)
# ... bekerja di code ...

# 3. Cek perubahan
git status
git diff

# 4. Tambahkan perubahan
git add .

# 5. Commit
git commit -m "Add new feature: customer management"

# 6. Pull dulu (untuk memastikan tidak ada konflik)
git pull origin main

# 7. Push ke GitHub
git push origin main

# 8. Verifikasi
git status
```

### **Contoh: Fix Bug**

```bash
# 1. Fix bug di code
# ... edit file ...

# 2. Test fix
# ... test aplikasi ...

# 3. Commit fix
git add .
git commit -m "Fix validation error in jurnal form"

# 4. Push
git push origin main
```

---

## **Tips Tambahan**

### **1. Gunakan Git GUI (Opsional)**
Jika tidak nyaman dengan command line, bisa gunakan:
- GitHub Desktop
- SourceTree
- GitKraken
- VS Code Git Extension

### **2. Backup Sebelum Push**
```bash
# Buat backup branch
git branch backup-before-push

# Lalu push
git push origin main
```

### **3. Tag Release (Opsional)**
Untuk menandai versi penting:
```bash
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

---

## **Repository GitHub Saat Ini**

**URL:** https://github.com/wnurcipto/doaccount.git  
**Branch:** `main`  
**Status:** ✅ Terhubung dan tersinkronisasi

**Cek Repository:** https://github.com/wnurcipto/doaccount

---

## **Membuat Branch Baru (untuk Fitur Baru)**

### **1. Buat Branch Baru**
```bash
git checkout -b nama-branch-baru
```

### **2. Bekerja di Branch Baru**
Lakukan perubahan seperti biasa, lalu:
```bash
git add .
git commit -m "Pesan commit"
```

### **3. Push Branch Baru ke GitHub**
```bash
git push origin nama-branch-baru
```

### **4. Kembali ke Branch Main**
```bash
git checkout Main
```

### **5. Merge Branch ke Main (jika sudah selesai)**
```bash
git merge nama-branch-baru
git push origin Main
```

---

## **Mengatasi Konflik**

Jika ada konflik saat pull atau merge:

### **1. Pull dengan rebase**
```bash
git pull --rebase origin Main
```

### **2. Jika ada konflik, edit file yang konflik**
Buka file yang konflik, cari tanda `<<<<<<<`, `=======`, `>>>>>>>`

### **3. Setelah selesai edit, lanjutkan rebase**
```bash
git add .
git rebase --continue
```

---

## **Mengabaikan File (tidak di-commit)**

File yang sudah ada di `.gitignore` tidak akan di-track oleh Git:
- `.env` (file environment)
- `vendor/` (dependencies)
- `node_modules/` (node packages)
- `storage/*.key` (file kunci)
- dll

Jika ada file yang ingin ditambahkan ke `.gitignore`:
1. Buka file `.gitignore`
2. Tambahkan nama file/folder
3. Simpan

---

## **Melihat Remote Repository**

```bash
# Lihat remote yang terhubung
git remote -v

# Tambahkan remote baru (jika perlu)
git remote add origin https://github.com/username/repo.git

# Ubah URL remote
git remote set-url origin https://github.com/username/repo-baru.git
```

---

## **Best Practices**

### **1. Commit Sering-sering**
- Commit setiap fitur yang selesai
- Jangan menunggu sampai banyak perubahan

### **2. Pesan Commit yang Jelas**
- Gunakan bahasa Indonesia atau Inggris yang konsisten
- Jelaskan apa yang diubah, bukan bagaimana

**Contoh baik:**
- ✅ `"Add customer management feature"`
- ✅ `"Fix validation error in jurnal form"`
- ❌ `"Update"`
- ❌ `"Fix bug"`

### **3. Jangan Commit File Sensitif**
- Jangan commit `.env`
- Jangan commit file dengan password/API key
- Pastikan `.gitignore` sudah benar

### **4. Pull Sebelum Push**
```bash
git pull origin Main
git push origin Main
```

---

## **Troubleshooting**

### **Error: "Your branch is ahead of 'origin/Main' by X commits"**
**Solusi:** Push perubahan ke GitHub
```bash
git push origin Main
```

### **Error: "Your branch is behind 'origin/Main' by X commits"**
**Solusi:** Pull perubahan dari GitHub
```bash
git pull origin Main
```

### **Error: "Permission denied"**
**Solusi:** 
1. Cek kredensial GitHub
2. Atau gunakan Personal Access Token
3. Atau setup SSH key

### **Error: "Merge conflict"**
**Solusi:**
1. Buka file yang konflik
2. Edit manual untuk menyelesaikan konflik
3. `git add .`
4. `git commit -m "Resolve merge conflict"`

---

## **Setup SSH Key (Opsional, untuk lebih aman)**

### **1. Generate SSH Key**
```bash
ssh-keygen -t ed25519 -C "your_email@example.com"
```

### **2. Copy Public Key**
```bash
cat ~/.ssh/id_ed25519.pub
```

### **3. Tambahkan ke GitHub**
1. Login ke GitHub
2. Settings → SSH and GPG keys
3. New SSH key
4. Paste public key
5. Save

### **4. Ubah Remote URL ke SSH**
```bash
git remote set-url origin git@github.com:wnurcipto/doaccount.git
```

---

## **Quick Reference**

| Perintah | Deskripsi |
|----------|-----------|
| `git status` | Cek status repository |
| `git add .` | Tambahkan semua perubahan |
| `git commit -m "pesan"` | Commit perubahan |
| `git push origin Main` | Push ke GitHub |
| `git pull origin Main` | Update dari GitHub |
| `git log --oneline` | Lihat history commit |
| `git diff` | Lihat perbedaan file |
| `git checkout -b nama-branch` | Buat branch baru |
| `git branch` | Lihat semua branch |

---

## **Catatan Penting**

⚠️ **JANGAN commit file `.env`** - file ini berisi kredensial database dan konfigurasi sensitif

⚠️ **JANGAN commit file `vendor/`** - file ini bisa di-generate ulang dengan `composer install`

✅ **SELALU pull sebelum push** untuk menghindari konflik

✅ **Commit message yang jelas** membantu tracking perubahan

---

## **Repository GitHub Saat Ini**

**URL:** https://github.com/wnurcipto/doaccount.git  
**Branch:** Main  
**Status:** ✅ Terhubung

---

## **Butuh Bantuan?**

Jika ada masalah dengan Git/GitHub:
1. Cek status dengan `git status`
2. Lihat error message dengan detail
3. Cek dokumentasi GitHub: https://docs.github.com
4. Atau tanyakan ke tim development

