# IAM Admin Panel 🛡️

IAM Admin Panel adalah platform manajemen **Identity & Access Management (IAM)**, **Multi-Tenant SaaS Management**, dan **Database Provisioning** berbasis PHP (Customized CodeIgniter 3) dengan desain modern dark-theme menggunakan Tailwind CSS dan VyrnForge UI.

---

## 🚀 Fitur Utama

- 🔐 **Identity & Access Management (IAM):**
  - Autentikasi Admin yang aman dengan Role-Based Access Control (RBAC) granular.
  - Manajemen Role, Permissions, dan Multi-user Access.
  - Audit Logs & Security History untuk melacak seluruh aktivitas platform.
- 🏢 **Multi-Tenant SaaS & Applications Registry:**
  - Pendaftaran aplikasi/tenant internal & eksternal.
  - Konfigurasi metadata aplikasi, secret keys, dan integrasi API Key.
- 🗄️ **Dynamic Database Provisioner & Explorer:**
  - Otomasi pembuatan database per-tenant (Database-per-tenant strategy).
  - Manajemen kredensial database terenkripsi.
  - Interactive Database Explorer & Query Runner langsung dari admin panel.
- 📝 **Dynamic Content Management (CMS):**
  - Kelola konten dinamis per aplikasi secara terisolasi.
  - Konfigurasi visual dan pengaturan parameter proyek (misal: 3D Portfolio Config).
- 🤖 **AI Advisor & CI3 Sync:**
  - AI Assistant untuk saran arsitektur dan optimasi sistem.
  - CI3 Sync Tool untuk migrasi dan sinkronisasi struktur model & database.
- 🎨 **Modern Futuristic Dark UI:**
  - Dibangun dengan palet dark mode premium, Micro-animations, Lucide Icons, dan VyrnForge UI components.

---

## 🛠️ Tech Stack & Prasyarat

- **PHP**: >= 7.4 / 8.x
- **Web Server**: Apache (XAMPP / Laragon / Nginx) dengan modul `mod_rewrite` aktif
- **Database**: MySQL / MariaDB
- **UI / Frontend**: Tailwind CSS (CDN), VyrnForge UI Core, Lucide Icons, Plus Jakarta Sans & JetBrains Mono fonts

---

## 📦 Panduan Instalasi & Setup

### 1. Clone Repositori
Clone project ke direktori web server Anda (misal: `htdocs` pada XAMPP):
```bash
git clone https://github.com/bocildev/admin-iam-panel.git iam-admin-panel
cd iam-admin-panel
```

### 2. Setup Database
1. Buat database baru di MySQL/MariaDB (misal: `toonhub_iam` atau `iam_admin_panel`).
2. Import schema dan seed data yang ada pada direktori `sql/`:
   ```bash
   # Import skema awal
   mysql -u root -p toonhub_iam < sql/toonhub_iam.sql
   
   # Import patch skema & seed (jika diperlukan)
   mysql -u root -p toonhub_iam < sql/001_refactor_saas_schema.sql
   mysql -u root -p toonhub_iam < sql/002_seed_3d_portfolio.sql
   mysql -u root -p toonhub_iam < sql/003_create_portfolio_config.sql
   ```

### 3. Konfigurasi Aplikasi
Buka file `application/config/database.php` dan sesuaikan kredensial database Anda:
```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'toonhub_iam',
'dbdriver' => 'mysqli',
```

Sesuaikan `base_url` di `application/config/config.php` jika diperlukan:
```php
$config['base_url'] = 'http://localhost/iam-admin-panel/';
```

Pastikan file `.htaccess` aktif untuk mendukung clean URL routing.

### 4. Akses Admin Panel
Buka browser dan arahkan ke:
```
http://localhost/iam-admin-panel/
```

---

## 📂 Struktur Direktori

```text
iam-admin-panel/
├── .agents/                    # Panduan & rules untuk Agentic AI pair programming
├── application/
│   ├── config/                 # Konfigurasi aplikasi, routes, hooks, dan database
│   ├── controllers/            # Controller IAM, Apps, DB Manager, Auth, Dashboard, dll.
│   ├── core/                   # Core controllers & extensions (MY_Controller)
│   ├── helpers/                # Custom helper (iam_helper.php)
│   ├── hooks/                  # Hooks (IAM_Hook.php)
│   ├── libraries/              # Service libraries (Auth_service, Database_provisioner)
│   ├── models/                 # Model database per entitas
│   └── views/                  # UI Views (Dark theme layout, komponen VyrnForge)
├── sql/                        # Migration scripts & initial SQL dumps
├── system/                     # Lightweight custom CodeIgniter 3 core
├── design-system.md            # Dokumentasi standar UI/UX Design System
├── PRD.md                      # Product Requirement Document
├── index.php                   # Entry point aplikasi
└── README.md                   # Dokumentasi proyek
```

---

## 🎨 Design System & UI Guidelines

Proyek ini menerapkan aturan desain yang ketat sesuai panduan di [design-system.md](file:///c:/xampp/htdocs/iam-admin-panel/design-system.md):
- **Exclusively Dark Mode**: Background `#0A0E1A`, Card `#131B2E`, Border `#1E293B`.
- **Aksen Warna**: Emerald (`#10B981`) sebagai primary dan Cyan (`#06B6D4`) sebagai secondary.
- **Komponen**: Selalu prioritaskan class VyrnForge UI (`vf-button`, `vf-input`, `vf-panel`).

---

## 📄 Lisensi

Proyek ini dikembangkan secara internal untuk manajemen IAM dan ekosistem multi-tenant. Hak cipta dilindungi.
