# Referensi Pricing Model Berbasis Transaksi & Omset
## Do-Account - Sistem Akuntansi Modern

---

## 📊 **Model Pricing Berbasis Jumlah Transaksi**

### **1. Tiered Pricing (Harga Berjenjang)**

#### **Contoh Struktur:**
```
Paket Starter:
- 0-100 transaksi/bulan: Rp 500.000/bulan
- 101-500 transaksi/bulan: Rp 1.000.000/bulan
- 501-1.000 transaksi/bulan: Rp 1.500.000/bulan

Paket Professional:
- 0-1.000 transaksi/bulan: Rp 1.500.000/bulan
- 1.001-5.000 transaksi/bulan: Rp 3.000.000/bulan
- 5.001-10.000 transaksi/bulan: Rp 5.000.000/bulan

Paket Enterprise:
- Unlimited transaksi: Rp 5.000.000/bulan (flat rate)
```

#### **Kelebihan:**
- ✅ Prediktabilitas biaya untuk customer
- ✅ Mudah dipahami
- ✅ Cocok untuk bisnis dengan volume transaksi stabil

#### **Kekurangan:**
- ❌ Bisa tidak adil jika customer hanya sedikit melebihi limit
- ❌ Perlu monitoring penggunaan

---

### **2. Pay-Per-Transaction (Bayar per Transaksi)**

#### **Contoh Struktur:**
```
Base Fee: Rp 300.000/bulan (untuk akses platform)
+ Rp 500/transaksi untuk transaksi ke-1 sampai ke-100
+ Rp 400/transaksi untuk transaksi ke-101 sampai ke-500
+ Rp 300/transaksi untuk transaksi ke-501 sampai ke-1.000
+ Rp 200/transaksi untuk transaksi di atas 1.000

Contoh:
- 250 transaksi = Rp 300.000 + (100 × Rp 500) + (150 × Rp 400) = Rp 410.000
- 1.500 transaksi = Rp 300.000 + (100 × Rp 500) + (400 × Rp 400) + (1.000 × Rp 300) = Rp 660.000
```

#### **Kelebihan:**
- ✅ Sangat adil - customer hanya bayar sesuai penggunaan
- ✅ Fleksibel untuk bisnis dengan fluktuasi volume
- ✅ Revenue tumbuh seiring dengan pertumbuhan customer

#### **Kekurangan:**
- ❌ Biaya tidak bisa diprediksi customer
- ❌ Perlu sistem billing yang kompleks
- ❌ Revenue bisa tidak stabil

---

### **3. Hybrid Model (Kombinasi Flat + Usage)**

#### **Contoh Struktur:**
```
Paket Starter:
- Base: Rp 500.000/bulan
- Include: 100 transaksi/bulan
- Overage: Rp 2.000/transaksi tambahan

Paket Professional:
- Base: Rp 1.500.000/bulan
- Include: 1.000 transaksi/bulan
- Overage: Rp 1.500/transaksi tambahan

Paket Enterprise:
- Base: Rp 5.000.000/bulan
- Include: Unlimited transaksi
```

#### **Kelebihan:**
- ✅ Kombinasi prediktabilitas dan fleksibilitas
- ✅ Customer punya baseline yang jelas
- ✅ Revenue lebih stabil dengan potensi tambahan

---

## 💰 **Model Pricing Berbasis Omset**

### **1. Percentage of Revenue (Persentase dari Omset)**

#### **Contoh Struktur:**
```
Paket Starter:
- 0.5% dari omset bulanan (minimal Rp 500.000)
- Maksimal: Rp 2.000.000/bulan

Paket Professional:
- 0.3% dari omset bulanan (minimal Rp 1.500.000)
- Maksimal: Rp 10.000.000/bulan

Paket Enterprise:
- 0.2% dari omset bulanan (minimal Rp 5.000.000)
- Maksimal: Unlimited
```

#### **Contoh Perhitungan:**
```
Omset Rp 500.000.000/bulan:
- Starter: 0.5% × 500.000.000 = Rp 2.500.000 (capped at Rp 2.000.000)
- Professional: 0.3% × 500.000.000 = Rp 1.500.000
- Enterprise: 0.2% × 500.000.000 = Rp 1.000.000
```

#### **Kelebihan:**
- ✅ Aligned dengan kesuksesan customer
- ✅ Customer merasa fair karena bayar sesuai kemampuan
- ✅ Revenue tumbuh seiring pertumbuhan customer

#### **Kekurangan:**
- ❌ Revenue tidak stabil
- ❌ Perlu verifikasi omset (bisa kompleks)
- ❌ Customer mungkin tidak mau share data omset

---

### **2. Tiered Based on Revenue (Omset Berjenjang)**

#### **Contoh Struktur:**
```
Paket Starter:
- Omset 0-100 juta/bulan: Rp 500.000/bulan
- Omset 100-500 juta/bulan: Rp 1.000.000/bulan
- Omset 500 juta-1 M/bulan: Rp 1.500.000/bulan

Paket Professional:
- Omset 0-1 M/bulan: Rp 1.500.000/bulan
- Omset 1-5 M/bulan: Rp 3.000.000/bulan
- Omset 5-10 M/bulan: Rp 5.000.000/bulan

Paket Enterprise:
- Omset di atas 10 M/bulan: Custom pricing
```

#### **Kelebihan:**
- ✅ Prediktabilitas biaya
- ✅ Mudah dipahami customer
- ✅ Revenue lebih stabil

#### **Kekurangan:**
- ❌ Perlu verifikasi omset
- ❌ Bisa tidak adil di batas tier

---

### **3. Hybrid: Base + Revenue Share**

#### **Contoh Struktur:**
```
Paket Starter:
- Base: Rp 500.000/bulan
- + 0.1% dari omset di atas Rp 100 juta/bulan

Paket Professional:
- Base: Rp 1.500.000/bulan
- + 0.05% dari omset di atas Rp 500 juta/bulan

Paket Enterprise:
- Base: Rp 5.000.000/bulan
- + 0.02% dari omset di atas Rp 2 M/bulan
```

---

## 🔄 **Model Kombinasi: Transaksi + Omset**

### **Contoh Struktur:**
```
Paket Starter:
- Base: Rp 500.000/bulan
- Include: 100 transaksi/bulan
- Omset maksimal: Rp 500 juta/bulan
- Overage transaksi: Rp 2.000/transaksi
- Overage omset: 0.1% dari omset di atas limit

Paket Professional:
- Base: Rp 1.500.000/bulan
- Include: 1.000 transaksi/bulan
- Omset maksimal: Rp 5 M/bulan
- Overage transaksi: Rp 1.500/transaksi
- Overage omset: 0.05% dari omset di atas limit
```

---

## 📈 **Best Practices dari Aplikasi Akuntansi Lain**

### **1. QuickBooks (Intuit)**
- **Model:** Tiered pricing berdasarkan fitur + jumlah user
- **Pricing:** $15-$200/bulan
- **Note:** Tidak berdasarkan transaksi, tapi berdasarkan fitur

### **2. Xero**
- **Model:** Tiered pricing berdasarkan fitur
- **Pricing:** $13-$70/bulan
- **Note:** Flat rate, tidak berdasarkan volume

### **3. FreshBooks**
- **Model:** Tiered pricing berdasarkan jumlah client
- **Pricing:** $15-$50/bulan
- **Note:** Berbasis jumlah client, bukan transaksi

### **4. Wave Accounting**
- **Model:** Free untuk basic, paid untuk payment processing
- **Pricing:** Free + 2.9% + $0.30 per transaksi pembayaran
- **Note:** Revenue dari payment processing, bukan software

### **5. Zoho Books**
- **Model:** Tiered pricing berdasarkan fitur + jumlah user
- **Pricing:** $15-$240/bulan
- **Note:** Flat rate dengan limit user

---

## 💡 **Rekomendasi untuk Do-Account**

### **Opsi 1: Hybrid Model (Recommended)**
```
Paket Starter:
- Base: Rp 500.000/bulan
- Include: 100 transaksi/bulan
- Overage: Rp 2.000/transaksi
- Fitur: Jurnal, Buku Besar, Laba Rugi, Neraca

Paket Professional:
- Base: Rp 1.500.000/bulan
- Include: 1.000 transaksi/bulan
- Overage: Rp 1.500/transaksi
- Fitur: Semua Starter + Invoice, Arus Kas, Export PDF/Excel

Paket Enterprise:
- Base: Rp 5.000.000/bulan
- Include: Unlimited transaksi
- Fitur: Semua Professional + Custom Integration, Dedicated Support
```

**Alasan:**
- ✅ Prediktabilitas untuk customer (base fee)
- ✅ Fleksibilitas untuk growth (overage)
- ✅ Revenue stabil dengan potensi tambahan
- ✅ Mudah dipahami dan dijelaskan

---

### **Opsi 2: Revenue-Based (Untuk Enterprise)**
```
Paket Starter & Professional: Tetap seperti Opsi 1

Paket Enterprise:
- Base: Rp 3.000.000/bulan
- + 0.1% dari omset bulanan
- Maksimal: Rp 15.000.000/bulan
- Include: Unlimited transaksi
```

**Alasan:**
- ✅ Aligned dengan kesuksesan customer besar
- ✅ Customer enterprise biasanya lebih terbuka dengan revenue share
- ✅ Revenue tumbuh seiring pertumbuhan customer

---

## 📋 **Checklist Implementasi**

### **1. Sistem Tracking**
- [ ] Implementasi counter untuk jumlah transaksi per bulan
- [ ] Sistem untuk tracking omset (jika pakai revenue-based)
- [ ] Dashboard untuk customer melihat usage mereka
- [ ] Alert ketika mendekati limit

### **2. Billing System**
- [ ] Sistem untuk menghitung overage
- [ ] Invoice otomatis untuk overage
- [ ] Payment gateway integration
- [ ] Email notification untuk billing

### **3. Customer Communication**
- [ ] Halaman pricing yang jelas
- [ ] Calculator untuk estimasi biaya
- [ ] FAQ tentang pricing
- [ ] Transparansi tentang overage charges

### **4. Business Rules**
- [ ] Policy untuk upgrade/downgrade
- [ ] Grace period untuk overage
- [ ] Limit enforcement (soft vs hard limit)
- [ ] Refund policy

---

## 🎯 **Tips Implementasi**

1. **Mulai dengan Model Sederhana**
   - Mulai dengan tiered pricing yang simple
   - Tambahkan complexity seiring waktu jika diperlukan

2. **Monitor Usage Patterns**
   - Track bagaimana customer menggunakan sistem
   - Adjust pricing berdasarkan data real

3. **Transparansi adalah Kunci**
   - Jelas tentang apa yang termasuk dan tidak termasuk
   - Jelas tentang overage charges
   - Berikan tools untuk customer track usage mereka

4. **Flexibility untuk Enterprise**
   - Enterprise customer biasanya butuh custom pricing
   - Siapkan sales team untuk handle custom deals

5. **Test & Iterate**
   - Jangan takut untuk adjust pricing
   - Monitor churn rate dan customer feedback
   - A/B test pricing jika memungkinkan

---

## 📞 **Kontak untuk Diskusi Pricing**

Jika Anda ingin mendiskusikan implementasi pricing model ini lebih lanjut, silakan hubungi:
- Email: info@do-account.id
- Telepon: +62 21 1234 5678

---

**Dokumen ini dibuat sebagai referensi untuk Do-Account**
**Last Updated: 2025**

