<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<header class="sticky top-0 z-40 bg-[#0A0E1A]/90 backdrop-blur-xl border-b border-[#1E293B] px-4 lg:px-8 py-3 transition-all">
    <div class="flex items-center justify-between gap-4">
        
        <!-- Brand Badge -->
        <div class="flex items-center gap-3">
            <a href="<?php echo base_url('projects'); ?>" class="flex items-center gap-2.5 group">
                <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500/20 via-emerald-500/10 to-cyan-500/20 border border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.25)] group-hover:border-emerald-400 group-hover:shadow-[0_0_20px_rgba(16,185,129,0.4)] transition-all">
                    <i data-lucide="box" class="w-5 h-5 text-emerald-400"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-lg tracking-wider text-white font-mono">PORT<span class="text-emerald-400">ADMIN</span></span>
                        <span class="px-1.5 py-0.5 text-[10px] font-mono font-semibold rounded bg-emerald-500/10 text-emerald-300 border border-emerald-500/30">3D PORTFOLIO MANAGER</span>
                    </div>
                    <p class="text-[11px] text-slate-400 flex items-center gap-1 font-mono">
                        <i data-lucide="folder-git" class="w-3 h-3 text-emerald-400"></i> target: E:\Portofolio\3d-portofolio
                    </p>
                </div>
            </a>
        </div>

        <!-- Quick Search with Command Palette trigger -->
        <div class="hidden md:flex items-center flex-1 max-w-md mx-4">
            <button onclick="openCommandPalette()" class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl bg-[#131B2E]/90 border border-[#1E293B] hover:border-slate-700 text-slate-400 text-xs transition-colors group cursor-pointer text-left">
                <span class="flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                    <span>Cari modul atau aksi cepat...</span>
                </span>
                <kbd class="px-2 py-0.5 rounded bg-[#0A0E1A] border border-[#1E293B] text-[10px] font-mono text-slate-400 group-hover:text-cyan-400">Ctrl+K</kbd>
            </button>
        </div>

        <!-- Status Indicators -->
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#131B2E] border border-[#1E293B] text-xs font-mono shadow-sm">
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
            <div class="hidden lg:flex items-center gap-2 pl-2 border-l border-[#1E293B] relative group cursor-pointer">
                <div class="w-8 h-8 rounded-full bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-emerald-300 font-mono text-xs font-bold">
                    PA
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-xs font-semibold text-slate-200">Portfolio Admin</span>
                    <span class="text-[10px] font-mono text-emerald-400">Owner</span>
                </div>
                
                <!-- Hover Dropdown Menu -->
                <div class="absolute top-full right-0 mt-3 w-48 bg-[#131B2E] border border-[#1E293B] rounded-xl shadow-[0_15px_30px_-5px_rgba(0,0,0,0.8)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="p-1.5">
                        <button onclick="handleLogout()" class="w-full text-left px-3 py-2 text-xs text-rose-400 font-semibold hover:bg-rose-500/10 rounded-lg flex items-center gap-2.5 transition-colors">
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
