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
                        brand: {
                            bg: '#0A0E1A',
                            panel: '#131B2E',
                            border: '#1E293B',
                        },
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
        .animate-in { animation: fadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.96) translateY(6px); } to { opacity: 1; transform: scale(1) translateY(0); } }

        /* VyrnForge Dark Overrides conforming to design-system.md */
        .vf-panel, .vf-card--elevated {
            background-color: #131B2E !important;
            border: 1px solid #1E293B !important;
            color: #F1F5F9;
            border-radius: 1rem;
        }
        .vf-input, .vf-select, input.vf-input, textarea.vf-input, select.vf-select {
            background-color: #0A0E1A !important;
            border: 1px solid #1E293B !important;
            color: #F1F5F9 !important;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }
        .vf-input:focus, .vf-select:focus, input.vf-input:focus, textarea.vf-input:focus, select.vf-select:focus {
            border-color: #06B6D4 !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(6, 182, 212, 0.25) !important;
        }
        .vf-button {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .vf-button--primary {
            background: linear-gradient(135deg, #10B981, #059669) !important;
            color: #ffffff !important;
            border: 1px solid rgba(16, 185, 129, 0.4) !important;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
        }
        .vf-button--primary:hover {
            background: linear-gradient(135deg, #34D399, #10B981) !important;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            transform: translateY(-1px);
        }
        .vf-button--subtle {
            background-color: #1E293B !important;
            color: #94A3B8 !important;
            border: 1px solid #334155 !important;
        }
        .vf-button--subtle:hover {
            background-color: #2D3748 !important;
            color: #F1F5F9 !important;
            border-color: #475569 !important;
        }
        .vf-button--danger {
            background: linear-gradient(135deg, #F43F5E, #E11D48) !important;
            color: #ffffff !important;
            border: 1px solid rgba(244, 63, 94, 0.4) !important;
            box-shadow: 0 4px 14px rgba(244, 63, 94, 0.25);
        }
        .vf-button--danger:hover {
            background: linear-gradient(135deg, #FB7185, #F43F5E) !important;
            box-shadow: 0 6px 20px rgba(244, 63, 94, 0.35);
        }

        /* ==========================================================================
           Centralized Modal Styling System
           ========================================================================== */
        .vf-modal-backdrop {
            position: fixed !important;
            top: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            left: 0 !important;
            z-index: 50 !important;
            background-color: rgba(2, 6, 23, 0.82) !important;
            backdrop-filter: blur(14px) !important;
            -webkit-backdrop-filter: blur(14px) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 1rem !important;
        }

        .vf-modal-backdrop.hidden,
        .vf-modal-backdrop[hidden] {
            display: none !important;
        }

        .vf-modal-backdrop > .vf-panel {
            margin: auto !important;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 1rem;
            background: #131B2E !important;
            border: 1px solid #1E293B !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75) !important;
        }

        .vf-modal-backdrop .vf-panel__header {
            border-bottom: 1px solid #1E293B;
            padding-bottom: 0.85rem;
            margin-bottom: 1rem;
        }

        .vf-modal-backdrop .vf-panel__title {
            color: #F1F5F9;
            font-weight: 700;
            font-size: 1.05rem;
        }
    </style>
</head>
<body class="h-screen flex flex-col font-sans antialiased bg-[#0A0E1A] text-slate-100 overflow-hidden">
