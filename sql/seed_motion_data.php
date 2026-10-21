<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'saas_iam_db');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Projects Data
$projects = [
    [
        'project_key' => 'mango-hcm',
        'number' => '01',
        'title' => 'MANGO — Human Capital Management Online',
        'subtitle' => 'Enterprise Organization Structure & Employee Ecosystem',
        'company' => 'PT. Sigma Cipta Caraka (Telkomsigma)',
        'period' => '2020 — Present',
        'category' => 'enterprise',
        'roles' => json_encode(['frontend', 'fullstack']),
        'cover_image' => '/work/sigma/mango/dashboard.png',
        'logo' => '/work/sigma/mango/logo.png',
        'description' => "Aplikasi enterprise komprehensif untuk memetakan struktur organisasi perusahaan dari pucuk pimpinan (CEO) hingga seluruh lini jabatan di bawahnya, dilengkapi pengelolaan siklus hidup data karyawan, pengajuan & konfigurasi cuti (Leave Management), PCN, serta laporan kepegawaian.",
        'impact_highlights' => json_encode([
            "Visualisasi interaktif Position Organization Chart & Position Tree Grid secara real-time.",
            "Sentralisasi data profil karyawan lengkap, riwayat jabatan, manajemen akses user, dan persetujuan PCN.",
            "Modul pengelolaan dan kuota cuti karyawan terintegrasi (Leave Dashboard & Config Leave)."
        ]),
        'tech_stack' => json_encode(['NuxtJS', 'Vue.js', 'Buefy', 'JavaScript', 'CSS3', 'REST API', 'Oracle / MySQL']),
        'team' => json_encode(['Moch. Adam Athallahrich (Frontend/Fullstack)', 'Mardi Jumari (Team Lead)', 'Almaratus Sholicha (Backend)']),
        'live_url' => '',
        'repo_url' => '',
        'demo_credentials' => '',
        'gallery' => json_encode([
            ['title' => 'MANGO Executive Dashboard', 'src' => '/work/sigma/mango/dashboard.png'],
            ['title' => 'Position Organization Chart (Interactive Tree)', 'src' => '/work/sigma/mango/treeorgposition.png'],
            ['title' => 'Position Tree Grid View', 'src' => '/work/sigma/mango/treegridposition.png'],
            ['title' => 'Modal Edit Position & Hierarchy', 'src' => '/work/sigma/mango/modaleditposition.png'],
            ['title' => 'Complete Employee Detail & Background', 'src' => '/work/sigma/mango/employeedetail.png'],
            ['title' => 'Employee Directory Management', 'src' => '/work/sigma/mango/employee.png'],
            ['title' => 'Leave Management Dashboard', 'src' => '/work/sigma/mango/leavedashboard.png'],
            ['title' => 'Employee Leave Balance & Check', 'src' => '/work/sigma/mango/checkleaveemployee.png'],
            ['title' => 'Leave Configuration System', 'src' => '/work/sigma/mango/configleave.png'],
            ['title' => 'PCN Workflow Management', 'src' => '/work/sigma/mango/pcn.png'],
            ['title' => 'PCN Review & Approval', 'src' => '/work/sigma/mango/reviewpcn.png'],
            ['title' => 'Enterprise Master Data', 'src' => '/work/sigma/mango/masterdata.png'],
            ['title' => 'Analytics & HR Reporting', 'src' => '/work/sigma/mango/report.png'],
            ['title' => 'User & Role Access Management', 'src' => '/work/sigma/mango/usermanagement.png']
        ]),
        'display_order' => 1
    ],
    [
        'project_key' => 'sigma-pa',
        'number' => '02',
        'title' => 'PA — Performance Appraisal & Bukusaku',
        'subtitle' => 'KPI-Based Annual Employee Assessment Platform',
        'company' => 'PT. Sigma Cipta Caraka (Telkomsigma)',
        'period' => '2020 — Present',
        'category' => 'enterprise',
        'roles' => json_encode(['fullstack', 'frontend']),
        'cover_image' => '/work/sigma/pa/dashboard.png',
        'logo' => '/work/sigma/pa/logo.png',
        'description' => "Sistem penilaian kinerja tahunan karyawan (Performance Appraisal) yang berjalan sesuai periode aktual korporasi untuk memantau perkembangan kompetensi karyawan berdasarkan indikator KPI yang dinilai berjenjang oleh atasan (Supervisor/Manager).",
        'impact_highlights' => json_encode([
            "Alur penilaian berjenjang antara karyawan dan supervisor berdasarkan bobot KPI terukur.",
            "Dashboard monitoring status penilaian, rekapitulasi nilai akhir, dan histori performa.",
            "Manajemen System Master, daftar pengguna PA, dan ekspor laporan evaluasi tahunan."
        ]),
        'tech_stack' => json_encode(['CodeIgniter 3', 'PHP Native', 'HTML5', 'CSS3', 'JavaScript', 'jQuery', 'Oracle / MySQL']),
        'team' => json_encode(['Moch. Adam Athallahrich (Fullstack Developer)', 'Mardi Jumari (Team Lead)', 'Anindyka Elras Saputri (Backup)']),
        'live_url' => '',
        'repo_url' => '',
        'demo_credentials' => '',
        'gallery' => json_encode([
            ['title' => 'Performance Appraisal Dashboard', 'src' => '/work/sigma/pa/dashboard.png'],
            ['title' => 'Detail Penilaian KPI Karyawan', 'src' => '/work/sigma/pa/penilaianemployee.png'],
            ['title' => 'Halaman Evaluasi & Penilaian Supervisor', 'src' => '/work/sigma/pa/penilaianspv.png'],
            ['title' => 'Detail Matriks Performance Appraisal', 'src' => '/work/sigma/pa/detailpa.png'],
            ['title' => 'Daftar PA Karyawan', 'src' => '/work/sigma/pa/listpaemployee.png'],
            ['title' => 'Daftar PA Supervisor', 'src' => '/work/sigma/pa/listpaspv.png'],
            ['title' => 'Laporan Evaluasi Kinerja (PA Report)', 'src' => '/work/sigma/pa/listreport.png'],
            ['title' => 'Konfigurasi System Master PA', 'src' => '/work/sigma/pa/listsystemmaster.png'],
            ['title' => 'Manajemen Peserta & User PA', 'src' => '/work/sigma/pa/listuserpa.png']
        ]),
        'display_order' => 2
    ],
    [
        'project_key' => 'casier-coffeeshop',
        'number' => '03',
        'title' => 'Casier Coffee Shop POS',
        'subtitle' => 'Web-Based Cafe Point of Sale & Order Management',
        'company' => 'Independent Fullstack Project',
        'period' => 'Production Web App',
        'category' => 'personal',
        'roles' => json_encode(['fullstack', 'frontend']),
        'cover_image' => '/project-ss/coffeeshop/dashboard.png',
        'logo' => '',
        'description' => "Aplikasi web Point of Sale (POS) untuk operasional Coffee Shop yang memungkinkan kasir mencatat pesanan pelanggan dengan cepat serta menyediakan panel Admin untuk mengelola menu, transaksi, dan laporan penjualan harian.",
        'impact_highlights' => json_encode([
            "Dual-role akses terpisah untuk Kasir (pencatatan transaksi cepat) dan Admin (manajemen menu & laporan).",
            "Antarmuka pemilihan menu interaktif dengan kalkulasi keranjang dan cetak struk instan.",
            "Tersedia secara live beserta repositori kode sumber terbuka di GitLab."
        ]),
        'tech_stack' => json_encode(['CodeIgniter 3', 'PHP', 'JavaScript', 'jQuery', 'HTML5', 'CSS3', 'MySQL']),
        'team' => json_encode(['Moch. Adam Athallahrich (Fullstack Developer)']),
        'live_url' => 'https://sistempossederhana.my.id/coffeeshop',
        'repo_url' => 'https://gitlab.com/athallahrich/coffeeshop',
        'demo_credentials' => 'Kasir: kasir / Password1! · Admin: admin / Password1!',
        'gallery' => json_encode([
            ['title' => 'Casier Coffee Shop — Dashboard & Order View', 'src' => '/project-ss/coffeeshop/dashboard.png']
        ]),
        'display_order' => 3
    ],
    [
        'project_key' => 'warehouse-sales',
        'number' => '04',
        'title' => 'Warehouse Sales & Order System',
        'subtitle' => 'End-to-End Sales Order to Warehouse Inventory Pipeline',
        'company' => 'Commercial Web Application',
        'period' => 'Production Web App',
        'category' => 'personal',
        'roles' => json_encode(['fullstack', 'frontend']),
        'cover_image' => '/project-ss/sales/dashboard.png',
        'logo' => '',
        'description' => "Aplikasi web manajemen pemesanan dan distribusi barang yang menghubungkan alur kerja tim Sales (pembuatan order penjualan) hingga pengelolaan stok dan penyimpanan di gudang (Warehouse Storage).",
        'impact_highlights' => json_encode([
            "Integrasi alur order dari pencatatan tim sales hingga pemenuhan stok gudang.",
            "Dashboard analitik ringkasan produk, status pesanan, dan pergerakan inventaris.",
            "Arsitektur terstruktur berbasis CodeIgniter 3 yang ringan dan cepat."
        ]),
        'tech_stack' => json_encode(['CodeIgniter 3', 'PHP', 'jQuery', 'JavaScript', 'HTML5', 'CSS3', 'MySQL']),
        'team' => json_encode(['Moch. Adam Athallahrich (Fullstack Developer)']),
        'live_url' => 'https://sistempossederhana.my.id/sales',
        'repo_url' => '',
        'demo_credentials' => '',
        'gallery' => json_encode([
            ['title' => 'Warehouse Sales — Order & Inventory Dashboard', 'src' => '/project-ss/sales/dashboard.png']
        ]),
        'display_order' => 4
    ],
    [
        'project_key' => 'pos-diginov',
        'number' => '05',
        'title' => 'POS App Diginov (Android Native)',
        'subtitle' => 'Native Android Cashier & Real-Time Ingredient Tracker',
        'company' => 'PT. Ara Infra Indo / Diginov Project',
        'period' => 'Android Native App',
        'category' => 'personal',
        'roles' => json_encode(['mobile']),
        'cover_image' => '/project-ss/diginov/diginov.png',
        'logo' => '',
        'description' => "Aplikasi kasir (Point of Sale) berbasis Android Native yang dirancang untuk memproses pemesanan menu secara mobile sekaligus memantau status pesanan dan sisa stok bahan baku (ingredients) secara langsung.",
        'impact_highlights' => json_encode([
            "Dibangun secara native menggunakan Java dan XML untuk performa optimal pada perangkat tablet/smartphone kasir.",
            "Fitur pelacakan status pesanan aktif dan kalkulasi otomatis sisa bahan baku dapur.",
            "Antarmuka kasir ergonomis dengan alur transaksi multi-layar."
        ]),
        'tech_stack' => json_encode(['Android Native', 'Java', 'XML', 'Mobile POS Architecture']),
        'team' => json_encode(['Moch. Adam Athallahrich (Android Developer)']),
        'live_url' => '',
        'repo_url' => 'https://gitlab.com/athallahrich/apps-pos-diginov',
        'demo_credentials' => '',
        'gallery' => json_encode([
            ['title' => 'POS App Diginov — Overview', 'src' => '/project-ss/diginov/diginov.png'],
            ['title' => 'Mobile Screen — Menu & Category Selection', 'src' => '/project-ss/diginov/Screenshot 2025-04-10 232234.png'],
            ['title' => 'Mobile Screen — Order Cart & Calculation', 'src' => '/project-ss/diginov/Screenshot 2025-04-10 232325.png'],
            ['title' => 'Mobile Screen — Transaction & Status', 'src' => '/project-ss/diginov/Screenshot 2025-04-10 232405.png'],
            ['title' => 'Mobile Screen — Ingredient & Stock View', 'src' => '/project-ss/diginov/Screenshot 2025-04-10 232437.png'],
            ['title' => 'Mobile Screen — Receipt & Summary', 'src' => '/project-ss/diginov/Screenshot 2025-04-10 232506.png']
        ]),
        'display_order' => 5
    ]
];

$stmt = $mysqli->prepare("INSERT INTO motion_projects (project_key, number, title, subtitle, company, period, category, roles, cover_image, logo, description, impact_highlights, tech_stack, team, live_url, repo_url, demo_credentials, gallery, display_order) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE 
number=VALUES(number), title=VALUES(title), subtitle=VALUES(subtitle), company=VALUES(company), period=VALUES(period), category=VALUES(category), roles=VALUES(roles), cover_image=VALUES(cover_image), logo=VALUES(logo), description=VALUES(description), impact_highlights=VALUES(impact_highlights), tech_stack=VALUES(tech_stack), team=VALUES(team), live_url=VALUES(live_url), repo_url=VALUES(repo_url), demo_credentials=VALUES(demo_credentials), gallery=VALUES(gallery), display_order=VALUES(display_order)");

foreach ($projects as $p) {
    $stmt->bind_param("ssssssssssssssssssi", 
        $p['project_key'], $p['number'], $p['title'], $p['subtitle'], $p['company'], $p['period'], $p['category'], 
        $p['roles'], $p['cover_image'], $p['logo'], $p['description'], $p['impact_highlights'], $p['tech_stack'], 
        $p['team'], $p['live_url'], $p['repo_url'], $p['demo_credentials'], $p['gallery'], $p['display_order']
    );
    $stmt->execute();
}
$stmt->close();

// 2. Config Data
$configs = [
    'profile' => json_encode([
        'name' => 'Moch. Adam Athallahrich',
        'shortName' => 'ADAM.ATH',
        'birthInfo' => '09 Mei 2001 · Surabaya',
        'location' => 'Malang / Indonesia',
        'currentCompany' => 'PT. Sigma Cipta Caraka (Telkomsigma)',
        'roleTitle' => 'Fullstack & Frontend Developer',
        'education' => [
            'school' => 'SMK Telkom Malang',
            'major' => 'Software Engineering (Rekayasa Perangkat Lunak)',
            'period' => '2016 — 2019'
        ],
        'bioId' => 'Lulusan Rekayasa Perangkat Lunak dari SMK Telkom Malang yang saat ini berkarya sebagai Fullstack & Frontend Developer sekaligus Technical Researcher di PT. Sigma Cipta Caraka (Telkomsigma). Berfokus merancang aplikasi enterprise berskala besar, arsitektur frontend interaktif berbasis ekosistem Vue/Nuxt, sistem backend PHP/CodeIgniter/Laravel & database Oracle/MySQL, hingga aplikasi mobile Flutter & Android Native.',
        'phone' => '+6285335769655',
        'whatsappUrl' => 'https://wa.me/6285335769655',
        'email' => 'athallahrich@gmail.com',
        'instagram' => 'https://instagram.com/adamath_',
        'instagramHandle' => '@adamath_',
        'gitlab' => 'https://gitlab.com/athallahrich',
        'avatar' => '/profile-pics/profile1.webp'
    ], JSON_UNESCAPED_UNICODE),

    'skills' => json_encode([
        ['name' => 'Vue 2 & Vue 3', 'category' => 'frontend', 'level' => 85, 'dots' => 4, 'logo' => '/logos/vuejs-original.svg', 'experience' => 'Production Enterprise (MANGO HCM & Web Apps)', 'highlight' => 'State management, komponen modular, org-chart & tree grid interaktif'],
        ['name' => 'Nuxt 2 & Nuxt 3', 'category' => 'frontend', 'level' => 85, 'dots' => 4, 'logo' => '/logos/nuxtjs-original.svg', 'experience' => 'SSR/SPA Enterprise & Technical Research', 'highlight' => 'Arsitektur aplikasi korporasi dengan NuxtJS, Buefy, dan TailwindCSS'],
        ['name' => 'PHP Native', 'category' => 'fullstack', 'level' => 85, 'dots' => 4, 'logo' => '/logos/javascript-original.svg', 'experience' => 'Core Backend Logic & Enterprise Integration', 'highlight' => 'Pemrosesan transaksi, laporan KPI, dan integrasi RDBMS'],
        ['name' => 'CodeIgniter 3', 'category' => 'fullstack', 'level' => 85, 'dots' => 4, 'logo' => '/logos/nodejs-original.svg', 'experience' => 'Performance Appraisal, Bukusaku, CoffeeShop & Sales', 'highlight' => 'Framework andalan untuk sistem internal enterprise dan aplikasi POS/Gudang'],
        ['name' => 'Flutter', 'category' => 'mobile', 'level' => 65, 'dots' => 3, 'logo' => '/logos/flutter.png', 'experience' => 'PT. Visionet Data Internasional & Mobile Projects', 'highlight' => 'Pembuatan aplikasi Company Profile & antarmuka mobile multi-platform'],
        ['name' => 'React JS', 'category' => 'frontend', 'level' => 65, 'dots' => 3, 'logo' => '/logos/react-original.svg', 'experience' => 'Modern Component UI & Frontend Exploration', 'highlight' => 'Single Page Application dan eksplorasi ekosistem React modern'],
        ['name' => 'Laravel', 'category' => 'fullstack', 'level' => 65, 'dots' => 3, 'logo' => '/logos/git-original.svg', 'experience' => 'Modern PHP Fullstack Architecture', 'highlight' => 'RESTful API, autentikasi, dan manajemen skema database relasional'],
        ['name' => 'Android Native (Java/XML)', 'category' => 'mobile', 'level' => 65, 'dots' => 3, 'logo' => '/logos/java.svg', 'experience' => 'POS App Diginov & Online Payment UI', 'highlight' => 'Aplikasi kasir Android native dengan pelacakan stok bahan baku real-time'],
        ['name' => 'TailwindCSS & Buefy', 'category' => 'frontend', 'level' => 90, 'dots' => 4, 'logo' => '/logos/tailwindcss-plain.svg', 'experience' => 'Design System & Responsive Dashboard', 'highlight' => 'Implementasi UI/UX responsif, komponen dashboard, dan animasi modern'],
        ['name' => 'AngularTS & .NET', 'category' => 'fullstack', 'level' => 70, 'dots' => 3, 'logo' => '/logos/typescript-original.svg', 'experience' => 'PT. Visionet Data Internasional (Meikarta App)', 'highlight' => 'Admin dashboard AngularTS serta bug fixing & code review pada aplikasi .NET'],
        ['name' => 'Oracle, MySQL & SQL Server', 'category' => 'fullstack', 'level' => 85, 'dots' => 4, 'logo' => '/logos/firebase-plain.svg', 'experience' => 'Enterprise Database Operations (Telkomsigma & Visionet)', 'highlight' => 'Operasional query kompleks menggunakan SSMS, Toad SQL, DBeaver & Navicat'],
        ['name' => 'HTML5, CSS3 & JavaScript/jQuery', 'category' => 'frontend', 'level' => 90, 'dots' => 5, 'logo' => '/logos/html5-original.svg', 'experience' => 'Web Standards & Interactive DOM Manipulation', 'highlight' => 'Fondasi utama seluruh aplikasi web, landing page, dan sistem POS']
    ], JSON_UNESCAPED_UNICODE),

    'software_tools' => json_encode([
        ['name' => 'Visual Studio', 'group' => 'IDE & AI Editors', 'badge' => 'Primary IDE'],
        ['name' => 'Cursor', 'group' => 'IDE & AI Editors', 'badge' => 'AI Workflow'],
        ['name' => 'Antigravity', 'group' => 'IDE & AI Editors', 'badge' => 'Agentic Coding'],
        ['name' => 'Kiro', 'group' => 'IDE & AI Editors', 'badge' => 'Spec Driven'],
        ['name' => 'Qoder', 'group' => 'IDE & AI Editors', 'badge' => 'AI Assistant'],
        ['name' => 'Toad SQL', 'group' => 'Database & SQL', 'badge' => 'Oracle DB'],
        ['name' => 'DBeaver', 'group' => 'Database & SQL', 'badge' => 'Multi-DB'],
        ['name' => 'Navicat', 'group' => 'Database & SQL', 'badge' => 'MySQL/MariaDB'],
        ['name' => 'SSMS', 'group' => 'Database & SQL', 'badge' => 'SQL Server'],
        ['name' => 'Docker', 'group' => 'DevOps & Server', 'badge' => 'Container'],
        ['name' => 'MobaXterm', 'group' => 'DevOps & Server', 'badge' => 'SSH & Server'],
        ['name' => 'Sourcetree', 'group' => 'Version Control & API', 'badge' => 'Git GUI'],
        ['name' => 'Postman', 'group' => 'Version Control & API', 'badge' => 'API Testing'],
        ['name' => 'Laragon & XAMPP', 'group' => 'Local Environment', 'badge' => 'PHP Stack']
    ], JSON_UNESCAPED_UNICODE)
];

$stmtConfig = $mysqli->prepare("INSERT INTO motion_config (config_key, config_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE config_value=VALUES(config_value)");
foreach ($configs as $k => $v) {
    $stmtConfig->bind_param("ss", $k, $v);
    $stmtConfig->execute();
}
$stmtConfig->close();

echo "SEED_SUCCESSFUL: 5 projects and config imported into saas_iam_db";
