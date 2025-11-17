# Panduan Deploy Laravel di Hostinger
## Struktur Folder Aman (Public di public_html, Core di Luar)

---

## 📋 **Persiapan**

### **1. Struktur Folder yang Akan Dibuat**

```
/home/username/
├── public_html/              (Folder yang diakses public)
│   ├── index.php            (File baru yang akan dibuat)
│   ├── .htaccess           (File dari Laravel public)
│   └── assets/             (Jika ada assets tambahan)
│
└── laravel-core/            (Folder utama Laravel - AMAN)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/              (Folder ini akan di-copy isinya ke public_html)
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── .env
    ├── artisan
    └── composer.json
```

---

## 🚀 **Langkah-langkah Deployment**

### **Langkah 1: Upload File Laravel ke Server**

#### **1.1. Upload Folder Laravel ke `laravel-core`**

Gunakan FTP/SFTP client (FileZilla, WinSCP, dll) atau cPanel File Manager:

1. **Buat folder `laravel-core`** di level yang sama dengan `public_html`
   ```
   /home/username/laravel-core/
   ```

2. **Upload semua file Laravel** (kecuali folder `public`) ke `laravel-core`:
   - app/
   - bootstrap/
   - config/
   - database/
   - resources/
   - routes/
   - storage/
   - vendor/ (atau install via composer di server)
   - .env
   - artisan
   - composer.json
   - composer.lock
   - package.json
   - dll

3. **JANGAN upload folder `public`** ke `laravel-core`

---

### **Langkah 2: Setup Folder public_html**

#### **2.1. Copy Isi Folder `public` Laravel ke `public_html`**

1. **Upload semua file dari folder `public` Laravel** ke `public_html`:
   - index.php (akan diganti nanti)
   - .htaccess
   - assets/ (jika ada)
   - build/ (jika ada)
   - storage/ (symlink, akan di-setup nanti)

2. **Atau gunakan command di server:**
   ```bash
   cd /home/username
   cp -r laravel-core/public/* public_html/
   ```

---

### **Langkah 3: Buat File index.php Baru di public_html**

#### **3.1. Hapus index.php Lama**

Hapus file `index.php` yang ada di `public_html` (dari folder public Laravel).

#### **3.2. Buat File index.php Baru**

Buat file baru `index.php` di `public_html` dengan isi berikut:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../laravel-core/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../laravel-core/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../laravel-core/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

**Penjelasan:**
- `__DIR__` = `/home/username/public_html`
- `__DIR__.'/../laravel-core'` = `/home/username/laravel-core`
- File ini mengarahkan semua request ke folder Laravel di luar `public_html`

---

### **Langkah 4: Update File .env**

#### **4.1. Edit File .env di laravel-core**

Edit file `/home/username/laravel-core/.env`:

```env
APP_NAME="Do-Account"
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Storage
FILESYSTEM_DISK=public
```

**Penting:**
- Set `APP_DEBUG=false` untuk production
- Set `APP_URL` sesuai domain Anda
- Update database credentials dari Hostinger

---

### **Langkah 5: Setup Storage Link**

#### **5.1. Buat Storage Link**

Masuk ke server via SSH atau Terminal di cPanel, lalu jalankan:

```bash
cd /home/username/laravel-core
php artisan storage:link
```

**Jika tidak bisa via SSH, buat symlink manual:**

1. **Via cPanel File Manager:**
   - Buka File Manager
   - Masuk ke folder `public_html`
   - Klik "Link" atau "Create Symbolic Link"
   - Source: `/home/username/laravel-core/storage/app/public`
   - Link name: `storage`
   - Target: `/home/username/public_html/storage`

2. **Atau buat file `.htaccess` di `public_html/storage`** (alternatif jika symlink tidak bisa):
   ```apache
   Options -Indexes
   RewriteEngine On
   RewriteRule ^(.*)$ /home/username/laravel-core/storage/app/public/$1 [L]
   ```

---

### **Langkah 6: Set Permissions**

#### **6.1. Set Permission Folder dan File**

Via SSH atau Terminal:

```bash
cd /home/username/laravel-core

# Set permission untuk storage dan cache
chmod -R 775 storage bootstrap/cache
chown -R username:username storage bootstrap/cache

# Set permission untuk vendor (jika perlu)
chmod -R 755 vendor
```

**Via cPanel File Manager:**
- Klik kanan folder `storage` → Change Permissions → `775`
- Klik kanan folder `bootstrap/cache` → Change Permissions → `775`

---

### **Langkah 7: Install Dependencies**

#### **7.1. Install Composer Dependencies**

Via SSH:

```bash
cd /home/username/laravel-core
composer install --no-dev --optimize-autoloader
```

**Jika composer tidak tersedia di server:**
- Upload folder `vendor/` dari local development
- Atau install Composer di server terlebih dahulu

#### **7.2. Install NPM Dependencies (Jika Perlu)**

```bash
cd /home/username/laravel-core
npm install
npm run build
```

**Atau upload folder `public/build/` dari local development**

---

### **Langkah 8: Generate Application Key**

#### **8.1. Generate APP_KEY**

Via SSH:

```bash
cd /home/username/laravel-core
php artisan key:generate
```

**Atau copy APP_KEY dari `.env` local development**

---

### **Langkah 9: Run Migrations**

#### **9.1. Run Database Migrations**

Via SSH:

```bash
cd /home/username/laravel-core
php artisan migrate --force
```

**Penting:** Backup database terlebih dahulu!

---

### **Langkah 10: Optimize Laravel**

#### **10.1. Optimize untuk Production**

Via SSH:

```bash
cd /home/username/laravel-core

# Clear dan cache config
php artisan config:clear
php artisan config:cache

# Clear dan cache routes
php artisan route:clear
php artisan route:cache

# Clear dan cache views
php artisan view:clear
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

---

### **Langkah 11: Setup .htaccess di public_html**

#### **11.1. Pastikan .htaccess Ada dan Benar**

File `.htaccess` di `public_html` harus ada (dari folder `public` Laravel):

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## 🔒 **Keamanan Tambahan**

### **1. Protect Folder laravel-core**

Buat file `.htaccess` di folder `laravel-core`:

```apache
# Deny all access
Order deny,allow
Deny from all
```

**Atau via cPanel:**
- Masuk ke folder `laravel-core`
- Buat file `.htaccess` dengan isi di atas

### **2. Protect File .env**

Pastikan file `.env` tidak bisa diakses via browser:

```apache
# Di public_html/.htaccess, tambahkan:
<FilesMatch "\.env$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### **3. Hide Laravel Version**

Edit `laravel-core/.env`:
```env
APP_DEBUG=false
```

---

## 📝 **Checklist Deployment**

- [ ] Upload semua file Laravel ke `laravel-core` (kecuali `public`)
- [ ] Copy isi folder `public` ke `public_html`
- [ ] Buat file `index.php` baru di `public_html` dengan path yang benar
- [ ] Update file `.env` dengan konfigurasi production
- [ ] Setup storage link (symlink atau alternatif)
- [ ] Set permissions untuk `storage` dan `bootstrap/cache` (775)
- [ ] Install composer dependencies (`composer install --no-dev`)
- [ ] Build assets (`npm run build` atau upload `public/build/`)
- [ ] Generate APP_KEY (`php artisan key:generate`)
- [ ] Run migrations (`php artisan migrate --force`)
- [ ] Optimize Laravel (config:cache, route:cache, view:cache)
- [ ] Test aplikasi di browser
- [ ] Setup SSL certificate (jika belum)
- [ ] Setup backup otomatis

---

## 🐛 **Troubleshooting**

### **Error: "No application encryption key has been specified"**

**Solusi:**
```bash
cd /home/username/laravel-core
php artisan key:generate
```

---

### **Error: "The stream or file could not be opened"**

**Solusi:**
```bash
cd /home/username/laravel-core
chmod -R 775 storage bootstrap/cache
```

---

### **Error: "Class not found" atau "Autoload error"**

**Solusi:**
```bash
cd /home/username/laravel-core
composer dump-autoload --optimize
```

---

### **Error: 500 Internal Server Error**

**Cek:**
1. File `index.php` path sudah benar
2. Permissions folder sudah benar
3. `.env` file ada dan benar
4. Storage link sudah dibuat
5. Cek error log di `laravel-core/storage/logs/laravel.log`

---

### **Gambar/File Tidak Muncul**

**Solusi:**
1. Pastikan storage link sudah dibuat:
   ```bash
   cd /home/username/laravel-core
   php artisan storage:link
   ```

2. Atau buat symlink manual di `public_html/storage` → `laravel-core/storage/app/public`

3. Cek permissions:
   ```bash
   chmod -R 775 laravel-core/storage
   ```

---

### **Route Not Found (404)**

**Solusi:**
```bash
cd /home/username/laravel-core
php artisan route:clear
php artisan route:cache
```

---

## 📞 **Support**

Jika mengalami masalah, cek:
1. Error log: `laravel-core/storage/logs/laravel.log`
2. Server error log di cPanel
3. Pastikan semua langkah di checklist sudah dilakukan

---

## ✅ **Verifikasi Setelah Deployment**

1. **Test Homepage:** `https://yourdomain.com`
2. **Test Login:** `https://yourdomain.com/login`
3. **Test Upload File:** Upload gambar/logo perusahaan
4. **Test Export PDF:** Export laporan ke PDF
5. **Cek Storage:** Pastikan file tersimpan di `laravel-core/storage/app/public`
6. **Cek Performance:** Test kecepatan loading

---

**Dokumen ini dibuat untuk Do-Account**
**Last Updated: 2025**

