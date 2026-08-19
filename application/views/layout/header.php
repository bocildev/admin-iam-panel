<!DOCTYPE html>
<html lang="id" class="dark" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'PortAdmin IAM Admin Panel - CodeIgniter 3'; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- VyrnForge UI CSS -->
    <link rel="stylesheet" href="https://unpkg.com/@vyrnforge/ui-core/styles/index.css">
    <link rel="stylesheet" href="https://unpkg.com/@vyrnforge/ui-elements/styles/index.css">
    <link rel="stylesheet" href="https://unpkg.com/@vyrnforge/ui-components/styles/index.css">
    <link rel="stylesheet" href="https://unpkg.com/@vyrnforge/ui-data-grid/styles/index.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        dark: {
                            900: '#0A0E1A',
                            800: '#131B2E',
                            700: '#1E293B',
                            600: '#253147',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0A0E1A;
            color: #F1F5F9;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0A0E1A; }
        ::-webkit-scrollbar-thumb { background: #253147; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #06B6D4; }
        /* Animate-in utility */
        .animate-in { animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="h-screen flex flex-col font-sans antialiased bg-[#0A0E1A] text-slate-100 overflow-hidden">
