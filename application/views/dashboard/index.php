<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact">
    <div class="vf-page__body">

        <!-- Top Telemetry Header -->
        <div class="p-6 rounded-2xl bg-[#131B2E] border border-[#1E293B] mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-bold text-white tracking-wide">Platform Telemetry Overview</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-mono bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5 font-bold shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Live saas_iam_db
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">Pemantauan real-time otentikasi admin, aplikasi multi-tenant terdaftar, database terisolasi, dan audit log platform.</p>
            </div>
            <div>
                <a href="<?php echo base_url('applications'); ?>" class="vf-button vf-button--primary">
                    <span class="vf-button__label flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span>Register Application</span>
                    </span>
                </a>
            </div>
        </div>

        <!-- Real Database Stat Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            
            <!-- Total Admin Accounts -->
            <div class="p-5 rounded-2xl bg-[#131B2E] border border-[#1E293B] hover:border-blue-500/40 hover:shadow-[0_0_20px_rgba(59,130,246,0.15)] transition-all duration-200 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400">Total Admin Accounts</span>
                    <div class="p-2 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-mono"><?php echo number_format($stats['totalAdmins']); ?></p>
                <div class="flex items-center gap-1.5 mt-2 text-xs text-blue-400 font-mono">
                    <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    <span>Super Admin &amp; Admins</span>
                </div>
            </div>

            <!-- Registered Multi-Tenant Apps -->
            <div class="p-5 rounded-2xl bg-[#131B2E] border border-[#1E293B] hover:border-cyan-500/40 hover:shadow-[0_0_20px_rgba(6,182,212,0.15)] transition-all duration-200 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400">Registered SaaS Apps</span>
                    <div class="p-2 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-mono"><?php echo number_format($stats['totalApplications']); ?></p>
                <div class="flex items-center gap-1.5 mt-2 text-xs text-cyan-400 font-mono">
                    <i data-lucide="database" class="w-3.5 h-3.5"></i>
                    <span>Isolated DBs Active</span>
                </div>
            </div>

            <!-- 3D Portfolio Showcase Projects -->
            <div class="p-5 rounded-2xl bg-[#131B2E] border border-[#1E293B] hover:border-emerald-500/40 hover:shadow-[0_0_20px_rgba(16,185,129,0.15)] transition-all duration-200 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400">3D Portfolio Projects</span>
                    <div class="p-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="box" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-mono"><?php echo number_format($stats['totalProjects']); ?></p>
                <div class="flex items-center gap-1.5 mt-2 text-xs text-emerald-400 font-mono">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    <span>Live 3D App Synced</span>
                </div>
            </div>

            <!-- Active API Keys -->
            <div class="p-5 rounded-2xl bg-[#131B2E] border border-[#1E293B] hover:border-amber-500/40 hover:shadow-[0_0_20px_rgba(245,158,11,0.15)] transition-all duration-200 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400">Active API Keys</span>
                    <div class="p-2 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="key" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-mono"><?php echo number_format($stats['totalApiKeys']); ?></p>
                <div class="flex items-center gap-1.5 mt-2 text-xs text-amber-400 font-mono">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                    <span>Scoped Access Control</span>
                </div>
            </div>

        </div>

        <!-- Real System Data Grids -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Registered Applications List -->
            <div class="lg:col-span-2 rounded-2xl bg-[#131B2E] border border-[#1E293B] overflow-hidden shadow-lg">
                <div class="px-6 py-4 border-b border-[#1E293B] flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="layers" class="w-5 h-5 text-cyan-400"></i>
                        <h2 class="text-base font-bold text-white">Registered Tenant Applications</h2>
                    </div>
                    <a href="<?php echo base_url('applications'); ?>" class="text-xs font-semibold text-cyan-400 hover:text-cyan-300 transition-colors flex items-center gap-1">
                        <span>Kelola Aplikasi</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[#0A0E1A]/80 text-slate-400 border-b border-[#1E293B] font-mono text-[11px] uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Nama Aplikasi</th>
                                <th class="px-6 py-3 font-semibold">Kategori</th>
                                <th class="px-6 py-3 font-semibold">Database Terisolasi</th>
                                <th class="px-6 py-3 font-semibold text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1E293B] text-slate-300">
                            <?php foreach ($applications as $app): ?>
                            <tr class="hover:bg-[#1E293B]/40 transition-colors">
                                <td class="px-6 py-3.5">
                                    <p class="font-bold text-white"><?php echo htmlspecialchars($app['name']); ?></p>
                                    <p class="text-[10px] text-slate-400 font-mono mt-0.5">slug: <?php echo htmlspecialchars($app['slug']); ?></p>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-cyan-500/10 text-cyan-300 border border-cyan-500/30 uppercase">
                                        <?php echo htmlspecialchars($app['category']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="font-mono text-xs text-cyan-300 bg-cyan-500/10 px-2 py-0.5 rounded border border-cyan-500/20">
                                        <?php echo htmlspecialchars($app['db_name'] ?? 'db_' . str_replace('-', '_', $app['slug'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        <?php echo htmlspecialchars($app['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Real Security Audit Feed -->
            <div class="rounded-2xl bg-[#131B2E] border border-[#1E293B] p-5 flex flex-col justify-between shadow-lg">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#1E293B]">
                        <div class="flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-400"></i>
                            <h2 class="text-base font-bold text-white">Live Audit Log Feed</h2>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <?php if (empty($audit_logs)): ?>
                        <p class="text-xs text-slate-400 italic">Belum ada aktivitas audit log.</p>
                        <?php else: ?>
                        <?php foreach (array_slice($audit_logs, 0, 4) as $log): ?>
                        <div class="p-3 rounded-xl bg-[#0A0E1A] border border-[#1E293B] flex items-start gap-3 hover:border-slate-700 transition-colors">
                            <div class="p-2 rounded-lg bg-cyan-500/10 text-cyan-400 shrink-0 mt-0.5 border border-cyan-500/20">
                                <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-xs text-slate-200"><?php echo htmlspecialchars($log['action']); ?></p>
                                <p class="text-[11px] text-slate-400 mt-0.5 truncate"><?php echo htmlspecialchars($log['details']); ?></p>
                                <span class="text-[10px] font-mono text-slate-500 block mt-1"><?php echo htmlspecialchars($log['created_at']); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#1E293B] mt-4">
                    <a href="<?php echo base_url('audit-logs'); ?>" class="w-full block text-center py-2.5 rounded-xl bg-[#1E293B] hover:bg-[#253147] text-xs font-semibold text-slate-200 hover:text-white transition-colors border border-slate-700">
                        Lihat Semua Audit Log &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
