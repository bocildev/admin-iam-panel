<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$active = isset($active_tab) ? $active_tab : 'dashboard';

$CI =& get_instance();
$CI->load->model('Admin_model');
$CI->load->model('Application_model');
$CI->load->model('Apikey_model');
$CI->load->model('Ci3sync_model');

$sidebar_admins = $CI->Admin_model->get_all_admins();
$sidebar_apps   = $CI->Application_model->get_all_applications();
$sidebar_keys   = $CI->Apikey_model->get_all_keys();

$adminCount  = count($sidebar_admins);
$appCount    = count($sidebar_apps);
$apiKeyCount = count(array_filter($sidebar_keys, function($k) { return isset($k['status']) && $k['status'] === 'active'; }));
?>
<aside class="w-full lg:w-72 bg-white/80 dark:bg-[#0A0E1A]/80 border-r border-slate-200 dark:border-[#1E293B] flex flex-col justify-between p-4 shrink-0 sticky top-0 overflow-y-auto">
    
    <!-- Navigation Section -->
    <div class="space-y-6">
        
        <!-- Multi-Tenant SaaS Platform Section -->
        <div>
            <div class="px-3 pt-2 pb-2 flex items-center justify-between">
                <span class="text-[11px] font-mono font-bold tracking-wider uppercase text-slate-400 flex items-center gap-1.5">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-cyan-400"></i>
                    IAM &amp; SaaS Control
                </span>
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
            </div>

            <nav class="space-y-1.5">
                <!-- Overview Dashboard -->
                <a href="<?php echo base_url('dashboard'); ?>" class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all group relative <?php echo ($active === 'dashboard') ? 'bg-gradient-to-r from-cyan-500/15 via-cyan-500/10 to-blue-500/15 border border-cyan-500/40 text-white shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:bg-[#131B2E]/90 border border-transparent hover:border-slate-800'; ?>">
                    <?php if ($active === 'dashboard'): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 rounded-r-full bg-cyan-400 shadow-[0_0_10px_#06B6D4]"></div>
                    <?php endif; ?>
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="p-1.5 rounded-lg transition-colors <?php echo ($active === 'dashboard') ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' : 'bg-slate-100 dark:bg-[#131B2E] text-slate-400 group-hover:text-cyan-400 group-hover:bg-slate-800'; ?>">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 text-cyan-400"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-xs text-slate-800 dark:text-slate-100 group-hover:text-slate-900 dark:hover:text-white">Overview Dashboard</div>
                            <div class="text-[10px] text-slate-400 font-sans">Telemetry &amp; Summary</div>
                        </div>
                    </div>
                </a>

                <!-- Dedicated 3D Portfolio Manager -->
                <a href="<?php echo base_url('projects'); ?>" class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all group relative <?php echo ($active === 'portofolio' || $active === 'projects') ? 'bg-gradient-to-r from-emerald-500/15 via-emerald-500/10 to-cyan-500/15 border border-emerald-500/40 text-white shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:bg-[#131B2E]/90 border border-transparent hover:border-slate-800'; ?>">
                    <?php if ($active === 'portofolio' || $active === 'projects'): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 rounded-r-full bg-emerald-400 shadow-[0_0_10px_#10B981]"></div>
                    <?php endif; ?>
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="p-1.5 rounded-lg transition-colors <?php echo ($active === 'portofolio' || $active === 'projects') ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-100 dark:bg-[#131B2E] text-slate-400 group-hover:text-emerald-400 group-hover:bg-slate-800'; ?>">
                            <i data-lucide="box" class="w-4 h-4 text-emerald-400"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-xs text-slate-800 dark:text-slate-100 group-hover:text-slate-900 dark:hover:text-white">Proyek Portofolio 3D</div>
                            <div class="text-[10px] text-slate-400 font-sans">E:\Portofolio\3d-portofolio</div>
                        </div>
                    </div>
                </a>

                <!-- Multi-Tenant App & Tenant Registry -->
                <a href="<?php echo base_url('applications'); ?>" class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all group relative <?php echo ($active === 'applications' || $active === 'apps') ? 'bg-gradient-to-r from-cyan-500/15 via-cyan-500/10 to-blue-500/15 border border-cyan-500/40 text-white shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:bg-[#131B2E]/90 border border-transparent hover:border-slate-800'; ?>">
                    <?php if ($active === 'applications' || $active === 'apps'): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 rounded-r-full bg-cyan-400 shadow-[0_0_10px_#06B6D4]"></div>
                    <?php endif; ?>
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="p-1.5 rounded-lg transition-colors <?php echo ($active === 'applications' || $active === 'apps') ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' : 'bg-slate-100 dark:bg-[#131B2E] text-slate-400 group-hover:text-cyan-400 group-hover:bg-slate-800'; ?>">
                            <i data-lucide="layers" class="w-4 h-4 text-cyan-400"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-xs text-slate-800 dark:text-slate-100 group-hover:text-slate-900 dark:hover:text-white">App &amp; Tenant Registry</div>
                            <div class="text-[10px] text-slate-400 font-sans">Dynamic DB Provisioning</div>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 text-[10px] font-mono font-bold rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/30"><?php echo $appCount; ?></span>
                </a>

                <!-- Admin Accounts (IAM) -->
                <a href="<?php echo base_url('users'); ?>" class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all group relative <?php echo ($active === 'users' || $active === 'admins') ? 'bg-gradient-to-r from-blue-500/15 via-blue-500/10 to-indigo-500/15 border border-blue-500/40 text-white shadow-[0_0_15px_rgba(59,130,246,0.15)]' : 'text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:bg-[#131B2E]/90 border border-transparent hover:border-slate-800'; ?>">
                    <?php if ($active === 'users' || $active === 'admins'): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 rounded-r-full bg-blue-400 shadow-[0_0_10px_#3B82F6]"></div>
                    <?php endif; ?>
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="p-1.5 rounded-lg transition-colors <?php echo ($active === 'users' || $active === 'admins') ? 'bg-blue-500/20 text-blue-300 border border-blue-500/40' : 'bg-slate-100 dark:bg-[#131B2E] text-slate-400 group-hover:text-blue-400 group-hover:bg-slate-800'; ?>">
                            <i data-lucide="users" class="w-4 h-4 text-blue-400"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-xs text-slate-800 dark:text-slate-100 group-hover:text-slate-900 dark:hover:text-white">Admin Accounts</div>
                            <div class="text-[10px] text-slate-400 font-sans">SaaS IAM Foundation</div>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 text-[10px] font-mono font-bold rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30"><?php echo $adminCount; ?></span>
                </a>

                <!-- Security Audit Logs -->
                <a href="<?php echo base_url('audit-logs'); ?>" class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all group relative <?php echo ($active === 'audit-logs') ? 'bg-gradient-to-r from-amber-500/15 via-amber-500/10 to-orange-500/15 border border-amber-500/40 text-white shadow-[0_0_15px_rgba(245,158,11,0.15)]' : 'text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:bg-[#131B2E]/90 border border-transparent hover:border-slate-800'; ?>">
                    <?php if ($active === 'audit-logs'): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 rounded-r-full bg-amber-400 shadow-[0_0_10px_#F59E0B]"></div>
                    <?php endif; ?>
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="p-1.5 rounded-lg transition-colors <?php echo ($active === 'audit-logs') ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'bg-slate-100 dark:bg-[#131B2E] text-slate-400 group-hover:text-amber-400 group-hover:bg-slate-800'; ?>">
                            <i data-lucide="file-text" class="w-4 h-4 text-amber-400"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-xs text-slate-800 dark:text-slate-100 group-hover:text-slate-900 dark:hover:text-white">Security Audit Logs</div>
                            <div class="text-[10px] text-slate-400 font-sans">Telemetry &amp; Audit</div>
                        </div>
                    </div>
                </a>

                <!-- API Keys & Security -->
                <a href="<?php echo base_url('api-keys'); ?>" class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all group relative <?php echo ($active === 'api-keys') ? 'bg-gradient-to-r from-purple-500/15 via-purple-500/10 to-indigo-500/15 border border-purple-500/40 text-white shadow-[0_0_15px_rgba(168,85,247,0.15)]' : 'text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:bg-[#131B2E]/90 border border-transparent hover:border-slate-800'; ?>">
                    <?php if ($active === 'api-keys'): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 rounded-r-full bg-purple-400 shadow-[0_0_10px_#A855F7]"></div>
                    <?php endif; ?>
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="p-1.5 rounded-lg transition-colors <?php echo ($active === 'api-keys') ? 'bg-purple-500/20 text-purple-300 border border-purple-500/40' : 'bg-slate-100 dark:bg-[#131B2E] text-slate-400 group-hover:text-purple-400 group-hover:bg-slate-800'; ?>">
                            <i data-lucide="key" class="w-4 h-4 text-purple-400"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-xs text-slate-800 dark:text-slate-100 group-hover:text-slate-900 dark:hover:text-white">API Keys Management</div>
                            <div class="text-[10px] text-slate-400 font-sans">Access Control Scopes</div>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 text-[10px] font-mono font-bold rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30"><?php echo $apiKeyCount; ?></span>
                </a>
            </nav>
        </div>

    </div>

    <!-- System Status Footbar -->
    <div class="mt-8 p-3.5 rounded-2xl bg-slate-100 dark:bg-[#131B2E]/80 border border-[#1E293B] relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-cyan-500/10 blur-xl pointer-events-none"></div>
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-mono text-cyan-400 font-bold uppercase tracking-wider flex items-center gap-1">
                <i data-lucide="server" class="w-3.5 h-3.5 text-cyan-400"></i> Multi-Tenant SaaS
            </span>
            <span class="px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[9px] font-mono font-bold">ONLINE</span>
        </div>
        <p class="text-[11px] text-slate-300 font-mono font-semibold">DB: saas_iam_db</p>
        <div class="mt-2 text-[10px] text-slate-400 space-y-1 font-mono">
            <div class="flex justify-between">
                <span>Core Target:</span>
                <span class="text-slate-200">IAM Auth &amp; 3D App</span>
            </div>
            <div class="flex justify-between">
                <span>Status:</span>
                <span class="text-emerald-400 font-bold">Active Management</span>
            </div>
        </div>
    </div>

</aside>
