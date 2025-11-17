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

### **Langkah 2: Tambahkan Perubahan**
```bash
git add .
```

### **Langkah 3: Commit**
```bash
git commit -m "Deskripsi perubahan yang dilakukan"
```

### **Langkah 4: Push ke GitHub**
```bash
git push origin Main
```

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

