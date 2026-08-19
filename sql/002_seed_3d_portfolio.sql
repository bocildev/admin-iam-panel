-- Migration: Create portofolio table & Seed 8 Projects from E:\Portofolio\3d-portofolio
USE `saas_iam_db`;

CREATE TABLE IF NOT EXISTS `portofolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `src` text NOT NULL,
  `bg` varchar(20) DEFAULT '#040614',
  `lightBg` varchar(20) DEFAULT '#0f172a',
  `nebula1` varchar(20) DEFAULT '#6366f1',
  `nebula2` varchar(20) DEFAULT '#06b6d4',
  `aura` varchar(20) DEFAULT '#818cf8',
  `description` text,
  `description_id` text,
  `features` text,
  `features_id` text,
  `techStack` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

TRUNCATE TABLE `portofolio`;

INSERT INTO `portofolio` (`id`, `name`, `src`, `bg`, `lightBg`, `nebula1`, `nebula2`, `aura`, `description`, `description_id`, `features`, `features_id`, `techStack`) VALUES
(1, 'DASHBOARD', 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?q=80&w=800&auto=format&fit=crop', '#040614', '#0f172a', '#6366f1', '#06b6d4', '#818cf8', 
'A comprehensive analytics dashboard for tracking key performance indicators and user engagement metrics in real-time. Designed to provide instant visibility into organizational health.', 
'Dashboard analitik komprehensif untuk memantau indikator kinerja utama dan metrik keterlibatan pengguna secara real-time. Dirancang untuk memberikan transparansi instan terhadap performa organisasi.', 
'["Real-time data visualization & charts", "Customizable widget layouts", "Automated reporting (PDF/CSV)", "Role-based access control"]', 
'["Visualisasi data & grafik real-time", "Tata letak widget dapat disesuaikan", "Pelaporan otomatis (PDF/CSV)", "Kontrol akses berbasis peran"]', 
'["React", "TypeScript", "Tailwind CSS", "Recharts", "Node.js"]'),

(2, 'FINANCE APP', 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?q=80&w=800&auto=format&fit=crop', '#0d041a', '#1e1035', '#a855f7', '#ec4899', '#c084fc', 
'Personal finance management application that helps users track expenses, set budgets, and achieve financial goals through intuitive interfaces and smart notifications.', 
'Aplikasi manajemen keuangan pribadi yang membantu pengguna melacak pengeluaran, mengatur anggaran, dan mencapai tujuan finansial melalui antarmuka intuitif dan notifikasi pintar.', 
'["Automated expense categorization", "Monthly budget planning", "Investment portfolio tracking", "Smart alerts & insights"]', 
'["Kategorisasi pengeluaran otomatis", "Perencanaan anggaran bulanan", "Pelacakan portofolio investasi", "Peringatan & wawasan pintar"]', 
'["React Native", "Expo", "GraphQL", "PostgreSQL", "Stripe"]'),

(3, 'ANALYTICS', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop', '#02120d', '#062c21', '#10b981', '#0284c7', '#34d399', 
'Advanced data analytics platform for enterprise customers to process large datasets, execute complex queries, and generate actionable business insights efficiently.', 
'Platform analitik data tingkat lanjut untuk pelanggan enterprise dalam memproses himpunan data besar, menjalankan kueri kompleks, dan menghasilkan wawasan bisnis secara efisien.', 
'["Predictive modeling algorithms", "A/B test analysis tracking", "Cohort retention monitoring", "Custom visual query builder"]', 
'["Algoritma pemodelan prediktif", "Pelacakan analisis uji A/B", "Pemantauan retensi kohort", "Pembuat kueri visual kustom"]', 
'["Vue.js", "Python", "FastAPI", "Snowflake", "D3.js"]'),

(4, 'SOCIAL PLATFORM', 'https://6a65b0772a4b54c07b285154.imgix.net/snowboard.jpg', '#170603', '#331208', '#f97316', '#d946ef', '#fb923c', 
'Community-driven social platform focused on connecting professionals in the creative industry to collaborate on projects, share portfolios, and host virtual events.', 
'Platform sosial berbasis komunitas yang berfokus menghubungkan para profesional di industri kreatif untuk berkolaborasi dalam proyek, berbagi portofolio, dan mengadakan acara virtual.', 
'["Interactive portfolio showcasing", "Real-time direct messaging", "AI-powered project matching", "Virtual event streaming"]', 
'["Pameran portofolio interaktif", "Pesan langsung real-time", "Pencocokan proyek bertenaga AI", "Streaming acara virtual"]', 
'["Next.js", "Tailwind CSS", "Socket.io", "MongoDB", "AWS"]'),

(5, 'AI WORKSPACE', 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=800&auto=format&fit=crop', '#110022', '#220044', '#8b5cf6', '#06b6d4', '#a78bfa', 
'AI-assisted workspace enabling creative teams to synthesize documents, automate task pipelines, and generate multimodal insights seamlessly.', 
'Ruang kerja berbantu AI yang memungkinkan tim kreatif menyintesis dokumen, mengotomatisasi alur kerja tugas, dan menghasilkan wawasan multimodal dengan lancar.', 
'["Multi-modal AI assistant", "Interactive document synthesis", "Workflow automation nodes", "Contextual code generation"]', 
'["Asisten AI multi-modal", "Sintesis dokumen interaktif", "Simpul otomasi alur kerja", "Generasi kode kontekstual"]', 
'["React", "TypeScript", "Gemini API", "Express", "Tailwind CSS"]'),

(6, 'SPATIAL SHOPPING', 'https://images.unsplash.com/photo-1556742049-0a67d2f928e2?q=80&w=800&auto=format&fit=crop', '#1a030a', '#360615', '#f43f5e', '#f97316', '#fb7185', 
'Next-generation e-commerce platform featuring high-fidelity 3D product previews, interactive virtual fitting, and effortless single-tap purchasing.', 
'Platform e-commerce generasi berikutnya dengan pratinjau produk 3D berkualitas tinggi, uji coba virtual interaktif, dan pembelian sekali ketuk yang mudah.', 
'["3D interactive product models", "Virtual try-on preview", "Dynamic pricing engine", "One-click checkout integration"]', 
'["Model produk 3D interaktif", "Pratinjau uji coba virtual", "Mesin harga dinamis", "Integrasi pembayaran satu klik"]', 
'["Three.js", "React", "Tailwind CSS", "Stripe", "Node.js"]'),

(7, 'HEALTH & VITALITY', 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=800&auto=format&fit=crop', '#001a14', '#003328', '#14b8a6', '#10b981', '#2dd4bf', 
'Proactive biometric health monitor tracking daily activity, sleep metrics, cardiovascular recovery, and personal coaching recommendations.', 
'Pemantau kesehatan biometrik proaktif yang melacak aktivitas harian, metrik tidur, pemulihan kardiovaskular, dan rekomendasi pelatihan pribadi.', 
'["Wearable device synchronization", "AI readiness & recovery score", "Custom workout planner", "Hydration & sleep analytics"]', 
'["Sinkronisasi perangkat sandang (wearable)", "Skor kesiapan & pemulihan AI", "Perencana latihan kustom", "Analitik hidrasi & tidur"]', 
'["Swift", "React Native", "GraphQL", "HealthKit", "Firebase"]'),

(8, 'CYBER SHIELD', 'https://images.unsplash.com/photo-1563986768609-322da13575f3?q=80&w=800&auto=format&fit=crop', '#020b18', '#051d38', '#3b82f6', '#6366f1', '#60a5fa', 
'Enterprise security operations hub offering automated vulnerability detection, zero-trust access control, and live threat neutralization.', 
'Pusat operasi keamanan enterprise yang menawarkan deteksi kerentanan otomatis, kontrol akses zero-trust, dan netralisasi ancaman secara langsung.', 
'["Real-time threat detection", "Automated patch auditor", "Zero-trust auth engine", "Encrypted audit logs"]', 
'["Deteksi ancaman real-time", "Auditor tambalan otomatis", "Mesin autentikasi zero-trust", "Log audit terenkripsi"]', 
'["React", "Go", "Docker", "Kubernetes", "ElasticSearch"]');
