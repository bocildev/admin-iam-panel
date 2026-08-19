<!DOCTYPE html>
<html lang="id" class="dark" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PortAdmin IAM Panel</title>
    
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
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Background Decor -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md z-10 animate-in fade-in zoom-in duration-500">
        <!-- Logo -->
        <div class="flex flex-col items-center justify-center mb-8 gap-3">
            <div class="relative flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500/20 via-emerald-500/10 to-cyan-500/20 border border-emerald-500/40 shadow-[0_0_20px_rgba(16,185,129,0.25)]">
                <i data-lucide="box" class="w-7 h-7 text-emerald-400"></i>
            </div>
            <div class="text-center">
                <h1 class="font-extrabold text-2xl tracking-wider text-white font-mono">PORT<span class="text-emerald-400">ADMIN</span></h1>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-1">IAM &amp; SaaS Management Platform</p>
            </div>
        </div>

        <!-- Login Card -->
        <div class="bg-[#131B2E] border border-[#1E293B] rounded-2xl p-8 shadow-2xl relative">
            <h2 class="text-xl font-bold text-slate-100 mb-6">Sign In to Continue</h2>
            
            <form id="loginForm" class="vf-stack vf-stack--gap-lg">
                <?php 
                $this->load->view('components/input', [
                    'name' => 'identifier',
                    'id' => 'identifier',
                    'label' => 'Email or Username',
                    'icon' => 'user',
                    'placeholder' => 'admin@saas.local or admin',
                    'required' => true,
                    'input_class' => 'bg-[#0A0E1A] border-[#1E293B] focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-100'
                ]); 
                ?>

                <?php 
                $this->load->view('components/input', [
                    'name' => 'password',
                    'id' => 'password',
                    'type' => 'password',
                    'label' => 'Password',
                    'icon' => 'lock',
                    'placeholder' => '••••••••',
                    'required' => true,
                    'input_class' => 'bg-[#0A0E1A] border-[#1E293B] focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-100'
                ]); 
                ?>

                <div class="pt-2">
                    <button type="submit" id="loginBtn" class="vf-button w-full justify-center bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold border-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.3)] hover:shadow-[0_0_25px_rgba(16,185,129,0.4)]">
                        <span class="vf-button__label flex items-center gap-2">
                            <span>Sign In</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-6 text-xs text-slate-500">
            &copy; 2026 3D Portfolio IAM Admin Panel
        </div>
    </div>

    <script>
        lucide.createIcons();

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('loginBtn');
            const originalBtnHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="vf-button__label flex items-center gap-2"><i data-lucide="loader" class="w-4 h-4 animate-spin"></i> <span>Authenticating...</span></span>';
            lucide.createIcons();

            const identifier = document.getElementById('identifier').value;
            const password = document.getElementById('password').value;

            fetch('<?php echo base_url("auth/login"); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ identifier: identifier, password: password })
            })
            .then(res => res.json().then(data => ({status: res.status, body: data})))
            .then(res => {
                if(res.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil',
                        text: 'Mengarahkan ke Dashboard...',
                        background: '#131B2E',
                        color: '#fff',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '<?php echo base_url(); ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        text: res.body.error || 'Username atau password salah',
                        background: '#131B2E',
                        color: '#fff',
                        confirmButtonColor: '#10b981'
                    });
                    btn.disabled = false;
                    btn.innerHTML = originalBtnHtml;
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan sistem',
                    background: '#131B2E',
                    color: '#fff',
                    confirmButtonColor: '#10b981'
                });
                btn.disabled = false;
                btn.innerHTML = originalBtnHtml;
            });
        });
    </script>
</body>
</html>
