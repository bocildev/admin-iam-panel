<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact">
    <div class="vf-page__body">

        <!-- Top Telemetry Header -->
        <div class="vf-panel vf-card--elevated mb-6">
            <div class="vf-panel__header">
                <div class="vf-panel__heading">
                    <div class="flex items-center gap-2">
                        <h1 class="vf-heading vf-heading--md">Platform Telemetry Overview</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center gap-1.5 font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Live saas_iam_db
                        </span>
                    </div>
                    <p class="vf-panel__description mt-1">Pemantauan real-time otentikasi admin, aplikasi terdaftar, database terisolasi, dan audit log platform.</p>
                </div>
                <div class="vf-panel__actions">
                    <a href="<?php echo base_url('applications'); ?>" class="vf-button vf-button--primary">
                        <span class="vf-button__label flex items-center gap-2">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            <span>Register Application</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Real Database Stat Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            
            <!-- Total Admin Accounts -->
            <div class="vf-panel vf-card--elevated hover:border-slate-300 dark:border-slate-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Admin Accounts</span>
                    <div class="p-2 rounded-xl bg-blue-500/10 text-blue-400">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-mono"><?php echo number_format($stats['totalAdmins']); ?></p>
                <div class="flex items-center gap-2 mt-2 text-xs text-blue-400 font-mono">
                    <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    <span>Super Admin &amp; Admins</span>
                </div>
            </div>

            <!-- Registered Multi-Tenant Apps -->
            <div class="vf-panel vf-card--elevated hover:border-slate-300 dark:border-slate-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Registered SaaS Apps</span>
                    <div class="p-2 rounded-xl bg-cyan-500/10 text-cyan-400">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-mono"><?php echo number_format($stats['totalApplications']); ?></p>
                <div class="flex items-center gap-2 mt-2 text-xs text-cyan-400 font-mono">
                    <i data-lucide="database" class="w-3.5 h-3.5"></i>
                    <span>Isolated DBs Active</span>
                </div>
            </div>

            <!-- 3D Portfolio Showcase Projects -->
            <div class="vf-panel vf-card--elevated hover:border-slate-300 dark:border-slate-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">3D Portfolio Projects</span>
                    <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400">
                        <i data-lucide="box" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-mono"><?php echo number_format($stats['totalProjects']); ?></p>
                <div class="flex items-center gap-2 mt-2 text-xs text-emerald-400 font-mono">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    <span>Live 3D App Synced</span>
                </div>
            </div>

            <!-- Active API Keys -->
            <div class="vf-panel vf-card--elevated hover:border-slate-300 dark:border-slate-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Active API Keys</span>
                    <div class="p-2 rounded-xl bg-amber-500/10 text-amber-400">
                        <i data-lucide="key" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-mono"><?php echo number_format($stats['totalApiKeys']); ?></p>
                <div class="flex items-center gap-2 mt-2 text-xs text-amber-400 font-mono">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                    <span>Scoped Access Control</span>
                </div>
            </div>

        </div>

        <!-- Real System Data Grids -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Registered Applications List -->
            <div class="lg:col-span-2 vf-panel vf-card--padding-none border border-slate-200 dark:border-slate-800">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="layers" class="w-5 h-5 text-cyan-400"></i>
                        <h2 class="text-lg font-bold text-white">Registered Tenant Applications</h2>
                    </div>
                    <a href="<?php echo base_url('applications'); ?>" class="text-xs font-semibold text-cyan-400 hover:underline">Kelola Aplikasi &rarr;</a>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100/80 dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Nama Aplikasi</th>
                                <th class="px-6 py-3 font-semibold">Kategori</th>
                                <th class="px-6 py-3 font-semibold">Database Terisolasi</th>
                                <th class="px-6 py-3 font-semibold text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-slate-600 dark:text-slate-300">
                            <?php foreach ($applications as $app): ?>
                            <tr class="hover:bg-slate-100/40 dark:bg-slate-900/40 transition">
                                <td class="px-6 py-3">
                                    <p class="font-extrabold text-white"><?php echo htmlspecialchars($app['name']); ?></p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">slug: <?php echo htmlspecialchars($app['slug']); ?></p>
                                </td>
                                <td class="px-6 py-3">
                                    <?php 
                                    $this->load->view('components/badge', [
                                        'text' => htmlspecialchars($app['category']),
                                        'variant' => 'info',
                                        'class' => 'uppercase text-[10px]'
                                    ]); 
                                    ?>
                                </td>
                                <td class="px-6 py-3 font-mono text-cyan-300">
                                    <?php echo htmlspecialchars($app['db_name'] ?? 'db_' . str_replace('-', '_', $app['slug'])); ?>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <?php 
                                    $this->load->view('components/badge', [
                                        'text' => htmlspecialchars($app['status']),
                                        'variant' => 'success',
                                        'class' => 'uppercase text-[10px] rounded-full'
                                    ]); 
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Real Security Audit Feed -->
            <div class="vf-panel vf-card--elevated flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-400"></i>
                            <h2 class="text-lg font-bold text-white">Live Audit Log Feed</h2>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <?php if (empty($audit_logs)): ?>
                        <p class="text-xs text-slate-500 dark:text-slate-400 italic">Belum ada aktivitas audit log.</p>
                        <?php else: ?>
                        <?php foreach (array_slice($audit_logs, 0, 4) as $log): ?>
                        <div class="p-3 rounded-xl bg-white dark:bg-[#0A0E1A] border border-slate-200 dark:border-slate-800/80 flex items-start gap-3">
                            <div class="p-2 rounded-lg bg-cyan-500/10 text-cyan-400 shrink-0 mt-0.5">
                                <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                            </div>
                            <div>
                                <p class="font-bold text-xs text-slate-700 dark:text-slate-200"><?php echo htmlspecialchars($log['action']); ?></p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5"><?php echo htmlspecialchars($log['details']); ?></p>
                                <span class="text-[9px] font-mono text-slate-500 block mt-1"><?php echo htmlspecialchars($log['created_at']); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 mt-4">
                    <a href="<?php echo base_url('audit-logs'); ?>" class="w-full block text-center py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-600 dark:text-slate-300 transition border border-slate-300 dark:border-slate-700">
                        Lihat Semua Audit Log &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
