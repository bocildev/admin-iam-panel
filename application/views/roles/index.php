<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact">
    <div class="vf-page__body">

        <!-- Header Panel -->
        <div class="vf-panel mb-6 bg-[#131B2E] border border-[#1E293B] rounded-2xl shadow-xl">
            <div class="vf-panel__header flex-col md:flex-row md:items-center justify-between gap-4 p-5">
                <div class="vf-panel__heading">
                    <div class="flex items-center gap-3">
                        <span class="p-2.5 rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/30">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </span>
                        <div>
                            <h1 class="text-xl lg:text-2xl font-extrabold text-white">Matriks Hak Akses Peran (RBAC)</h1>
                            <p class="text-xs text-slate-400 mt-1 max-w-2xl font-normal">
                                Konfigurasi izin granular per kategori (Webtoons, Users, REST API, Systems) untuk seluruh peran pengguna platform.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="vf-panel__actions">
                    <?php 
                    $this->load->view('components/button', [
                        'text' => 'Definisikan Role Baru',
                        'variant' => 'primary',
                        'icon' => 'plus-circle',
                        'class' => 'bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs shadow-lg shadow-emerald-500/10',
                        'attributes' => 'onclick="openAddRoleModal()"'
                    ]); 
                    ?>
                </div>
            </div>
        </div>

        <!-- Role Cards Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <?php foreach ($roles as $role): ?>
            <div class="vf-panel bg-[#131B2E] border border-[#1E293B] hover:border-slate-600/50 rounded-2xl p-5 shadow-lg flex flex-col justify-between transition-all">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <?php 
                        $this->load->view('components/badge', [
                            'text' => htmlspecialchars($role['name']),
                            'variant' => 'info'
                        ]); 
                        ?>
                        <span class="text-xs text-slate-400 font-mono"><?php echo $role['userCount']; ?> User</span>
                    </div>
                    <h3 class="font-bold text-slate-100 text-sm mt-1"><?php echo htmlspecialchars($role['displayName']); ?></h3>
                    <p class="text-slate-400 text-xs mt-1.5 line-clamp-2 leading-relaxed"><?php echo htmlspecialchars($role['description']); ?></p>
                </div>
                <div class="mt-4 pt-3 border-t border-[#1E293B] flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-mono text-[11px]"><?php echo count($role['permissions']); ?> Permission Granted</span>
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
        <div class="vf-panel overflow-hidden bg-[#131B2E] border border-[#1E293B] rounded-2xl shadow-xl">
            <div class="p-5 border-b border-[#1E293B] flex items-center justify-between">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <i data-lucide="grid" class="w-4 h-4 text-cyan-400"></i>
                    <span>Matriks Izin Interaktif</span>
                </h2>
                <span class="text-xs font-mono text-slate-400">Granular Matrix</span>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#0A0E1A]/80 text-slate-400 border-b border-[#1E293B] text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 min-w-[220px]">Kategori &amp; Izin</th>
                            <?php foreach ($roles as $r): ?>
                            <th class="px-3 py-3 text-center min-w-[110px] font-mono"><?php echo htmlspecialchars($r['name']); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B]/70 text-slate-300 text-xs">
                        <?php foreach ($permission_groups as $group): ?>
                            <tr class="bg-[#0A0E1A]/40">
                                <td colspan="<?php echo count($roles) + 1; ?>" class="px-6 py-2.5 text-xs font-bold text-cyan-400 uppercase font-mono tracking-wider">
                                    <?php echo htmlspecialchars($group['displayName']); ?>
                                </td>
                            </tr>
                            <?php foreach ($group['permissions'] as $perm): ?>
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-3.5">
                                    <p class="font-bold text-slate-200"><?php echo htmlspecialchars($perm['label']); ?></p>
                                    <p class="text-[11px] text-slate-400 font-mono mt-0.5"><?php echo htmlspecialchars($perm['key']); ?> — <?php echo htmlspecialchars($perm['description']); ?></p>
                                </td>
                                <?php foreach ($roles as $r): ?>
                                <?php $has_perm = in_array($perm['key'], $r['permissions']); ?>
                                <td class="px-3 py-3.5 text-center">
                                    <?php 
                                    $this->load->view('components/button', [
                                        'text' => '',
                                        'variant' => $has_perm ? 'primary' : 'subtle',
                                        'size' => 'sm',
                                        'icon' => $has_perm ? 'check' : 'x',
                                        'class' => $has_perm ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/40 hover:bg-cyan-500/30' : 'bg-[#0A0E1A] text-slate-500 border border-[#1E293B] hover:text-slate-300',
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
