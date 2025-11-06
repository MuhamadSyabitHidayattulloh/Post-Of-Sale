# Kasir App - UI/UX Design Documentation

## 📋 Overview
Website kasir modern dengan 3 role (Admin, Kasir, Member) yang dibangun menggunakan Laravel, Tailwind CSS, Alpine.js, dan Chart.js. Design menggunakan color palette hitam-putih dengan style modern dan minimalis.

## 🎨 Design Features

### Color Palette
- **Primary**: Hitam (`#000000`) & Putih (`#FFFFFF`)
- **Background**: Neutral-950 (`#0a0a0a`)
- **Cards**: Neutral-900 (`#171717`)
- **Borders**: Neutral-800 (`#262626`)
- **Text**: White primary, Neutral-400 secondary
- **Accents**: Green, Blue, Purple, Orange, Yellow (untuk status & badges)

### Layout Components
- ✅ Responsive sidebar (collapsible)
- ✅ Modern navbar dengan search & notifications
- ✅ Reusable card components
- ✅ Modal dialogs
- ✅ Dynamic tables dengan pagination
- ✅ Interactive charts (Chart.js)

## 📁 File Structure

```
resources/views/
├── layouts/
│   ├── main.blade.php          # Base layout dengan CDN imports
│   └── dashboard.blade.php     # Dashboard layout dengan sidebar
├── components/
│   ├── sidebar.blade.php       # Sidebar component (dynamic menu)
│   ├── navbar.blade.php        # Top navbar component
│   └── card.blade.php          # Reusable card component
├── home.blade.php              # Landing page
├── login.blade.php             # Login page untuk semua role
├── admin/
│   ├── dashboard.blade.php     # Admin dashboard dengan charts & stats
│   ├── products.blade.php      # Product management (grid/list view)
│   ├── users.blade.php         # User & tier management (tabs)
│   └── reports.blade.php       # Reports dengan export Excel/PDF
├── kasir/
│   ├── dashboard.blade.php     # Kasir dashboard
│   └── pos.blade.php           # Point of Sale sistem (UTAMA!)
└── member/
    └── dashboard.blade.php     # Member dashboard & history
```

## 🚀 Pages & Features

### 1. Landing Page (`/`)
- Hero section dengan CTA
- Product showcase
- Stats section
- Features highlight
- Responsive navigation

### 2. Login Page (`/login`)
- Form login dengan email/password
- Show/hide password toggle
- Demo role selection (admin/kasir/member)
- Social login buttons (Google, Facebook)
- Remember me & forgot password

### 3. Admin Dashboard (`/admin/dashboard`)
**Stats Cards:**
- Total Penjualan
- Total Produk
- Transaksi Hari Ini
- Total Kasir & Member

**Charts:**
- Line chart penjualan 7 hari
- Bar chart penjualan bulanan
- Member tier distribution

**Lists:**
- Produk populer
- Transaksi terbaru

### 4. Product Management (`/admin/products`)
**Features:**
- Grid & List view toggle
- Advanced filters (search, category, stock)
- Add/Edit product modal
- Product card dengan hover actions
- Table view dengan inline actions
- Pagination

**Fields:**
- Product image upload
- Name, SKU, Category
- Price, Stock, Barcode
- Description
- Status (active/draft)

### 5. User Management (`/admin/users`)
**Two Tabs:**
1. **User Management**
   - List admin, kasir, member
   - Role badges
   - Status indicators
   - Add/Edit user modal

2. **Member Tiers**
   - Bronze (5% discount)
   - Silver (10% discount)  
   - Gold (15% discount)
   - Tier benefits & settings
   - Auto-upgrade toggle

### 6. Reports (`/admin/reports`)
**Report Types:**
- Sales Report
- Products Report
- Transactions Report
- Members Report

**Features:**
- Date range filter
- Export to Excel
- Export to PDF
- Print preview
- Dynamic charts
- Summary cards

### 7. Kasir Dashboard (`/kasir/dashboard`)
- Quick actions (POS, Add Member, Products)
- Daily stats
- Recent transactions
- Top products today
- Hourly sales chart

### 8. Point of Sale (`/kasir/pos`) ⭐ FITUR UTAMA
**Product Selection:**
- Barcode scanner input
- Manual search
- Category quick filters
- Product grid dengan click to add

**Shopping Cart (Alpine.js):**
- Dynamic item list
- Quantity +/- controls
- Remove item
- Auto-calculate subtotal

**Checkout:**
- Member selection (optional)
- Auto-apply member discount
- Tax calculation (11%)
- Payment method (Cash/Transfer)
- Cash input dengan kembalian
- Clear & Checkout buttons

**Calculations:**
```javascript
Subtotal = Σ (price × quantity)
Discount = Subtotal × tier_rate (5%/10%/15%)
Tax = (Subtotal - Discount) × 11%
Total = Subtotal - Discount + Tax
Change = Cash Received - Total
```

### 9. Member Dashboard (`/member/dashboard`)
**Member Info Card:**
- Member avatar & name
- Tier badge (Bronze/Silver/Gold)
- Member since date
- Stats (points, transactions)

**Stats:**
- Total pembelian
- Total belanja
- Total hemat (dari diskon)
- Poin rewards

**Tier Benefits:**
- Current tier benefits
- Discount percentage
- Poin multiplier
- Free shipping
- VIP support

**Points & Rewards:**
- Current points
- Progress bar
- Redeem vouchers
- Point requirements

**Purchase History:**
- Transaction table
- Export to Excel/PDF
- Filter by date range
- Search transactions

## 🎯 Role Capabilities

### Admin
✅ Full dashboard access
✅ Manage products (CRUD)
✅ Manage users (admin, kasir, member)
✅ Configure member tiers
✅ View & export reports
✅ Access all statistics

### Kasir
✅ Kasir dashboard
✅ Point of Sale (POS)
✅ Add new members
✅ View products (read-only)
✅ Generate reports
❌ Cannot manage users/tiers

### Member
✅ View personal dashboard
✅ Check tier & benefits
✅ View purchase history
✅ Redeem points for rewards
✅ Export purchase history
❌ No access to admin features

## 🛠️ Technologies

- **Laravel 11** - Backend framework
- **Tailwind CSS** (CDN) - Styling
- **Alpine.js** (CDN) - JavaScript interactivity
- **Chart.js** (CDN) - Data visualization
- **Font Awesome** (CDN) - Icons
- **Google Fonts** - Inter font family

## 📱 Responsive Design

- Mobile first approach
- Breakpoints: sm (640px), md (768px), lg (1024px), xl (1280px)
- Collapsible sidebar on desktop
- Mobile drawer menu
- Touch-friendly buttons
- Responsive tables (horizontal scroll)
- Flexible grid layouts

## 🎨 UI Components

### Buttons
- Primary (white bg, black text)
- Secondary (neutral-800 bg)
- Danger (red)
- Success (green)
- Icon buttons
- Loading states

### Forms
- Text inputs dengan icons
- Select dropdowns
- Checkboxes & radios
- Number inputs
- File uploads
- Search bars

### Cards
- Basic card
- Stat card dengan icon
- Product card
- Transaction card
- Gradient cards (tier badges)

### Tables
- Sortable headers
- Hover effects
- Action buttons
- Status badges
- Pagination

### Modals
- Add/Edit forms
- Confirmation dialogs
- Member selection
- Slide-in animations

## 🚀 Getting Started

1. **Install Dependencies**
```bash
composer install
npm install
```

2. **Configure Environment**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Run Migrations** (when ready to add database)
```bash
php artisan migrate
```

4. **Start Development Server**
```bash
php artisan serve
```

5. **Access Pages**
- Home: `http://localhost:8000/`
- Login: `http://localhost:8000/login`
- Admin: `http://localhost:8000/admin/dashboard`
- Kasir: `http://localhost:8000/kasir/dashboard`
- Member: `http://localhost:8000/member/dashboard`

## 📝 Routes

```php
// Public
GET  /              → home.blade.php
GET  /login         → login.blade.php

// Admin
GET  /admin/dashboard   → admin.dashboard
GET  /admin/products    → admin.products
GET  /admin/users       → admin.users
GET  /admin/reports     → admin.reports

// Kasir
GET  /kasir/dashboard   → kasir.dashboard
GET  /kasir/pos         → kasir.pos
GET  /kasir/products    → admin.products (reused)
GET  /kasir/reports     → admin.reports (reused)

// Member
GET  /member/dashboard  → member.dashboard
GET  /member/history    → member.dashboard (scroll)
GET  /member/tier       → member.dashboard (scroll)
```

## 🔧 Customization

### Colors
Edit Tailwind classes in blade files:
- `bg-neutral-900` → Background
- `text-white` → Text
- `border-neutral-800` → Borders

### Sidebar Menu
Edit `components/sidebar.blade.php`:
```php
$menus = [
    'admin' => [...],
    'kasir' => [...],
    'member' => [...]
];
```

### Charts
Edit Chart.js config in dashboard files:
```javascript
new Chart(ctx, {
    type: 'line',
    data: {...},
    options: {...}
});
```

## 💡 Next Steps (Development)

1. **Authentication**
   - Implement Laravel Breeze/Jetstream
   - Add role-based middleware
   - Session management

2. **Database**
   - Create migrations
   - Setup models & relationships
   - Seeders untuk dummy data

3. **Backend Logic**
   - Controllers untuk CRUD
   - API endpoints
   - Form validation

4. **Real Functions**
   - Product management
   - Transaction processing
   - Report generation
   - Payment integration

5. **Features**
   - Real barcode scanning
   - Receipt printing
   - Email notifications
   - File uploads

## 📸 Screenshots

### Admin Dashboard
- Clean stats overview
- Interactive charts
- Recent transactions

### POS System
- Product grid dengan search
- Live shopping cart
- Smart checkout dengan discount

### Member Dashboard
- Tier showcase
- Points & rewards
- Complete purchase history

## 🎯 Design Principles

1. **Minimalist** - Clean interface, no clutter
2. **Consistent** - Reusable components
3. **Responsive** - Works on all devices
4. **Fast** - CDN resources, optimized code
5. **Accessible** - Clear labels, good contrast
6. **Modern** - Contemporary UI patterns

## 📄 License

This is a UI/UX design template for educational purposes.

---

**Created with ❤️ using Laravel + Tailwind CSS**
