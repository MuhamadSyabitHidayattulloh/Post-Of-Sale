# 🚀 Quick Start Guide - Kasir App UI

## Cara Menjalankan

```bash
# 1. Masuk ke direktori project
cd c:\.syabit\laravelProject\kasir

# 2. Install dependencies (jika belum)
composer install

# 3. Setup environment (jika belum)
cp .env.example .env
php artisan key:generate

# 4. Jalankan server
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

## 📑 Halaman yang Tersedia

### Public Pages
- **Home**: http://localhost:8000/
- **Login**: http://localhost:8000/login

### Admin Pages
- **Dashboard**: http://localhost:8000/admin/dashboard
- **Produk**: http://localhost:8000/admin/products
- **User Management**: http://localhost:8000/admin/users
- **Laporan**: http://localhost:8000/admin/reports

### Kasir Pages
- **Dashboard**: http://localhost:8000/kasir/dashboard
- **Point of Sale**: http://localhost:8000/kasir/pos ⭐ (UTAMA!)
- **Produk**: http://localhost:8000/kasir/products
- **Laporan**: http://localhost:8000/kasir/reports

### Member Pages
- **Dashboard**: http://localhost:8000/member/dashboard

## 🎯 Fitur Utama per Role

### 👨‍💼 Admin
1. Dashboard dengan chart & statistik
2. CRUD Produk (tambah, edit, hapus)
3. Management User (admin, kasir, member)
4. Konfigurasi Tier Member (Bronze, Silver, Gold)
5. Laporan lengkap dengan export Excel/PDF

### 💰 Kasir
1. Dashboard kasir
2. **POS System** (scan barcode, keranjang dinamis, checkout)
3. Tambah member baru
4. Lihat produk
5. Generate laporan

### 👤 Member
1. Dashboard personal
2. Lihat tier & benefit
3. Redeem poin untuk voucher
4. Riwayat pembelian lengkap
5. Export history ke Excel/PDF

## 🎨 Fitur UI/UX

✅ **Responsive Design** - Mobile, tablet, desktop
✅ **Dark Theme** - Hitam putih modern
✅ **Sidebar Collapsible** - Hemat space
✅ **Search & Filter** - Di semua halaman
✅ **Modal Dialogs** - Form add/edit
✅ **Live Charts** - Chart.js
✅ **Alpine.js** - Interactive components
✅ **Smooth Animations** - Transitions & hover effects

## 🛒 Cara Menggunakan POS

1. Buka: http://localhost:8000/kasir/pos
2. **Scan Barcode** atau klik produk di grid
3. Produk masuk ke **keranjang** (kanan)
4. Atur **quantity** dengan tombol +/-
5. Pilih **member** (optional) untuk dapat diskon
6. Pilih **metode bayar** (Cash/Transfer)
7. Masukkan **uang diterima** (jika cash)
8. Klik **Checkout**
9. Transaksi selesai!

### Perhitungan Otomatis:
- Subtotal = Total harga × quantity
- Diskon = Bronze 5%, Silver 10%, Gold 15%
- Pajak = 11%
- Kembalian = Uang diterima - Total

## 📱 Testing Responsive

### Desktop (>1024px)
- Sidebar terbuka penuh
- Grid 4 kolom
- Chart penuh

### Tablet (768-1023px)
- Sidebar collapsible
- Grid 2-3 kolom
- Chart adaptif

### Mobile (<768px)
- Sidebar drawer
- Grid 1-2 kolom
- Table horizontal scroll

## 🎨 Color Reference

```css
/* Backgrounds */
bg-neutral-950  → #0a0a0a (body)
bg-neutral-900  → #171717 (cards)
bg-neutral-800  → #262626 (inputs)

/* Borders */
border-neutral-800 → #262626
border-neutral-700 → #404040

/* Text */
text-white      → #ffffff
text-neutral-400 → #a3a3a3

/* Accents */
text-green-500  → Success
text-red-500    → Error
text-blue-500   → Info
text-yellow-500 → Gold tier
```

## 📝 Struktur File Penting

```
resources/views/
├── layouts/
│   ├── main.blade.php       → Base layout
│   └── dashboard.blade.php  → Dashboard wrapper
├── components/
│   ├── sidebar.blade.php    → Dynamic sidebar
│   ├── navbar.blade.php     → Top navbar
│   └── card.blade.php       → Reusable card
├── home.blade.php           → Landing page
├── login.blade.php          → Login page
├── admin/                   → Admin pages
│   ├── dashboard.blade.php
│   ├── products.blade.php
│   ├── users.blade.php
│   └── reports.blade.php
├── kasir/                   → Kasir pages
│   ├── dashboard.blade.php
│   └── pos.blade.php        ⭐ IMPORTANT!
└── member/                  → Member pages
    └── dashboard.blade.php

routes/
└── web.php                  → All routes

resources/css/
└── app.css                  → Custom styles
```

## 🔄 Mengubah Role di Sidebar

Edit file: `resources/views/components/sidebar.blade.php`

```php
@php
    $role = 'admin'; // Ganti dengan 'kasir' atau 'member'
@endphp
```

Atau di masing-masing halaman dashboard, set variable:
```php
@php
    $role = 'admin';
    $userName = 'Nama User';
@endphp
```

## 🎯 Langkah Pengembangan Selanjutnya

1. **Setup Database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

2. **Implement Authentication**
   - Install Laravel Breeze
   - Setup middleware
   - Role-based access

3. **Create Controllers**
   ```bash
   php artisan make:controller Admin/ProductController
   php artisan make:controller Kasir/PosController
   php artisan make:controller Member/DashboardController
   ```

4. **Add Real Functions**
   - Product CRUD
   - Transaction processing
   - Report generation
   - Payment integration

5. **Add Features**
   - Real barcode scanner
   - Receipt printer
   - Email notifications
   - Image upload

## 📞 Support

Jika ada kendala atau pertanyaan:
1. Cek file `UI_DOCUMENTATION.md` untuk detail lengkap
2. Lihat kode di masing-masing blade file
3. Test di browser dengan inspect element

## ✅ Checklist Fitur

### ✓ Sudah Dibuat (UI Only)
- [x] Landing page
- [x] Login page  
- [x] Admin dashboard dengan charts
- [x] Product management (grid/list)
- [x] User & tier management
- [x] Reports dengan export
- [x] Kasir dashboard
- [x] **POS system dinamis** ⭐
- [x] Member dashboard
- [x] Responsive design
- [x] Dark theme
- [x] Reusable components

### ⏳ Perlu Dikembangkan (Backend)
- [ ] Authentication & authorization
- [ ] Database migrations
- [ ] API endpoints
- [ ] Real CRUD operations
- [ ] Payment processing
- [ ] Report export (Excel/PDF)
- [ ] Receipt printing
- [ ] Email notifications
- [ ] Image upload
- [ ] Barcode scanning integration

---

**Happy Coding! 🚀**
