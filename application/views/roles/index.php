<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact">
    <div class="vf-page__body">

        <!-- Header Panel -->
        <div class="vf-panel vf-card--elevated">
            <div class="vf-panel__header">
                <div class="vf-panel__heading">
                    <h1 class="vf-heading vf-heading--md">Matriks Hak Akses Peran (RBAC)</h1>
                    <p class="vf-panel__description mt-1">Konfigurasi izin granular per kategori (Webtoons, Users, REST API, Systems) untuk seluruh peran pengguna platform.</p>
                </div>
                <div class="vf-panel__actions">
                    <?php 
                    $this->load->view('components/button', [
                        'text' => 'Definisikan Role Baru',
                        'variant' => 'primary',
                        'icon' => 'plus-circle',
                        'attributes' => 'onclick="openAddRoleModal()"'
                    ]); 
                    ?>
                </div>
            </div>
        </div>

        <!-- Role Cards Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php foreach ($roles as $role): ?>
            <div class="vf-panel vf-card--elevated flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <?php 
                        $this->load->view('components/badge', [
                            'text' => htmlspecialchars($role['name']),
                            'variant' => 'info'
                        ]); 
                        ?>
                        <span class="text-xs text-slate-500 font-mono"><?php echo $role['userCount']; ?> User</span>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm"><?php echo htmlspecialchars($role['displayName']); ?></h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1 line-clamp-2"><?php echo htmlspecialchars($role['description']); ?></p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-slate-500"><?php echo count($role['permissions']); ?> Permission Granted</span>
                    <?php if ($role['isSystemRole']): ?>
                        <?php 
                        $this->load->view('components/badge', [
                            'text' => 'System Role',
                            'variant' => 'warning'
                        ]); 
                        ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- RBAC Permission Matrix Table -->
        <div class="vf-panel vf-card--padding-none overflow-hidden border border-slate-200 dark:border-slate-800">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="grid" class="w-5 h-5 text-cyan-400"></i>
                    <span>Matriks Izin Interaktif</span>
                </h2>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100/80 dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-3 font-semibold min-w-[220px]">Kategori & Izin</th>
                            <?php foreach ($roles as $r): ?>
                            <th class="px-3 py-3 text-center font-semibold min-w-[110px]"><?php echo htmlspecialchars($r['name']); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-600 dark:text-slate-300">
                        <?php foreach ($permission_groups as $group): ?>
                            <tr class="bg-slate-100/60 dark:bg-slate-900/60">
                                <td colspan="<?php echo count($roles) + 1; ?>" class="px-6 py-2 text-xs font-bold text-cyan-400 uppercase font-mono tracking-wider">
                                    <?php echo htmlspecialchars($group['displayName']); ?>
                                </td>
                            </tr>
                            <?php foreach ($group['permissions'] as $perm): ?>
                            <tr class="hover:bg-slate-100/40 dark:bg-slate-900/40 transition">
                                <td class="px-6 py-3">
                                    <p class="font-bold text-slate-700 dark:text-slate-200"><?php echo htmlspecialchars($perm['label']); ?></p>
                                    <p class="text-[11px] text-slate-500 font-mono mt-0.5"><?php echo htmlspecialchars($perm['key']); ?> — <?php echo htmlspecialchars($perm['description']); ?></p>
                                </td>
                                <?php foreach ($roles as $r): ?>
                                <?php $has_perm = in_array($perm['key'], $r['permissions']); ?>
                                <td class="px-3 py-3 text-center">
                                    <?php 
                                    $this->load->view('components/button', [
                                        'text' => '',
                                        'variant' => $has_perm ? 'primary' : 'subtle',
                                        'size' => 'sm',
                                        'icon' => $has_perm ? 'check' : 'x',
                                        'class' => $has_perm ? 'bg-cyan-500/20 text-cyan-400 border-cyan-500/40 hover:bg-cyan-500/30' : 'text-slate-600',
                                        'attributes' => 'onclick="togglePermission(\''.$r['id'].'\', \''.$perm['key'].'\')"'
                                    ]); 
                                    ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
