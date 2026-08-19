<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<header class="sticky top-0 z-40 bg-white/90 dark:bg-[#0A0E1A]/90 backdrop-blur-xl border-b border-slate-200 dark:border-[#1E293B] px-4 lg:px-8 py-3 transition-all">
    <div class="flex items-center justify-between gap-4">
        
        <!-- Brand Badge -->
        <div class="flex items-center gap-3">
            <a href="<?php echo base_url('projects'); ?>" class="flex items-center gap-2.5 group">
                <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500/20 via-emerald-500/10 to-cyan-500/20 border border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.25)] group-hover:border-emerald-400 group-hover:shadow-[0_0_20px_rgba(16,185,129,0.4)] transition-all">
                    <i data-lucide="box" class="w-5 h-5 text-emerald-400"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-lg tracking-wider text-slate-800 dark:text-white font-mono">PORT<span class="text-emerald-400">ADMIN</span></span>
                        <span class="px-1.5 py-0.5 text-[10px] font-mono font-semibold rounded bg-emerald-500/10 text-emerald-300 border border-emerald-500/30">3D PORTFOLIO MANAGER</span>
                    </div>
                    <p class="text-[11px] text-slate-400 flex items-center gap-1 font-mono">
                        <i data-lucide="folder-git" class="w-3 h-3 text-emerald-400"></i> target: E:\Portofolio\3d-portofolio
                    </p>
                </div>
            </a>
        </div>

        <!-- Quick Search -->
        <div class="hidden md:flex items-center flex-1 max-w-md mx-4">
            <div class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl bg-slate-100/90 dark:bg-[#131B2E]/90 border border-slate-200 dark:border-[#1E293B] text-slate-500 dark:text-slate-400 text-xs">
                <span class="flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                    <span>Cari proyek atau pengaturan 3D...</span>
                </span>
            </div>
        </div>

        <!-- Status Indicators -->
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-[#131B2E] border border-slate-200 dark:border-[#1E293B] text-xs font-mono">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-emerald-400"></i>
                <div class="flex flex-col items-start text-left">
                    <span class="text-[10px] text-slate-400 uppercase font-sans font-medium">3D Environment</span>
                    <span class="text-emerald-400 text-[11px] font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>Active</span>
                    </span>
                </div>
            </div>



            <!-- Admin Profile Badge & Dropdown -->
            <div class="hidden lg:flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-800 relative group cursor-pointer">
                <div class="w-8 h-8 rounded-full bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-emerald-300 font-mono text-xs font-bold">
                    PA
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-xs font-semibold text-slate-800 dark:text-white">Portfolio Admin</span>
                    <span class="text-[10px] font-mono text-emerald-400">Owner</span>
                </div>
                
                <!-- Hover Dropdown Menu -->
                <div class="absolute top-full right-0 mt-4 w-48 bg-white dark:bg-[#0A0E1A] border border-slate-200 dark:border-[#1E293B] rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                    <div class="p-2">
                        <button onclick="handleLogout()" class="w-full text-left px-3 py-2.5 text-sm text-rose-400 font-medium hover:bg-rose-500/10 rounded-lg flex items-center gap-2.5 transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i> Sign Out
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function handleLogout() {
                fetch('<?php echo base_url("auth/logout"); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    window.location.href = '<?php echo base_url("auth/login"); ?>';
                })
                .catch(err => console.error('Logout error:', err));
            }


        </script>

    </div>
</header>

<!-- Layout wrapper: Sidebar + Main Content flex row -->
<div class="flex flex-1 overflow-hidden">
