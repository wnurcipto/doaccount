# Quick Start - Deploy Laravel di Hostinger
## Ringkasan Langkah Cepat

---

## 🚀 **Langkah Cepat (5 Menit)**

### **1. Upload File**

```
/home/username/
├── public_html/          ← Upload isi folder public Laravel ke sini
└── laravel-core/         ← Upload semua file Laravel (kecuali public) ke sini
```

### **2. Buat File index.php di public_html**

Copy file `public_html_index.php` ke `public_html/index.php`

### **3. Setup via SSH**

```bash
cd /home/username/laravel-core

# Install dependencies
composer install --no-dev --optimize-autoloader

# Setup storage
php artisan storage:link
chmod -R 775 storage bootstrap/cache

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **4. Update .env**

Edit `/home/username/laravel-core/.env`:
- `APP_DEBUG=false`
- `APP_URL=https://yourdomain.com`
- Database credentials dari Hostinger

### **5. Protect laravel-core**

Buat file `.htaccess` di `laravel-core/` dengan isi dari `laravel-core_htaccess.txt`

---

## ✅ **Selesai!**

Buka `https://yourdomain.com` untuk test.

---

**Lihat `PANDUAN_DEPLOY_HOSTINGER.md` untuk detail lengkap.**

