<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact">
    <div class="vf-page__body">

        <!-- Header Panel -->
        <div class="vf-panel vf-card--elevated">
            <div class="vf-panel__header">
                <div class="vf-panel__heading">
                    <h1 class="vf-heading vf-heading--md">Manajemen Pengguna & Identitas</h1>
                    <p class="vf-panel__description mt-1">Kelola hak akses pengguna, sakelar MFA, status penangguhan (suspend), dan audit hash password CI3.</p>
                </div>
                <div class="vf-panel__actions">
                    <?php 
                    $this->load->view('components/button', [
                        'text' => 'Tambah Pengguna Baru',
                        'variant' => 'primary',
                        'icon' => 'user-plus',
                        'attributes' => 'onclick="openAddUserModal()"'
                    ]); 
                    ?>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="rounded-2xl bg-[#131B2E] border border-[#1E293B] p-4 mb-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="relative w-full md:w-96">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3 z-10"></i>
                    <input type="text" id="user-search-input" onkeyup="filterUserTable()" placeholder="Cari nama, username, atau email..." class="vf-input w-full pl-10 pr-4 py-2 bg-[#0A0E1A] border border-[#1E293B] rounded-xl focus:border-cyan-500 focus:outline-none text-slate-200 text-xs">
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <select id="app-filter" onchange="filterUserTable()" class="vf-select px-3 py-2 bg-[#0A0E1A] border border-[#1E293B] rounded-xl focus:border-cyan-500 focus:outline-none text-slate-200 text-xs">
                        <option value="">Semua Aplikasi</option>
                        <option value="all">Global / IAM Core</option>
                        <?php if (!empty($applications)): ?>
                            <?php foreach ($applications as $app): ?>
                            <option value="<?php echo htmlspecialchars($app['slug']); ?>"><?php echo htmlspecialchars($app['name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <select id="role-filter" onchange="filterUserTable()" class="vf-select px-3 py-2 bg-[#0A0E1A] border border-[#1E293B] rounded-xl focus:border-cyan-500 focus:outline-none text-slate-200 text-xs">
                        <option value="">Semua Peran (Roles)</option>
                        <?php foreach ($roles as $r): ?>
                        <option value="<?php echo htmlspecialchars($r['name']); ?>"><?php echo htmlspecialchars($r['displayName']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="status-filter" onchange="filterUserTable()" class="vf-select px-3 py-2 bg-[#0A0E1A] border border-[#1E293B] rounded-xl focus:border-cyan-500 focus:outline-none text-slate-200 text-xs">
                        <option value="">Semua Status</option>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                        <option value="mfa_required">MFA Required</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="rounded-2xl bg-[#131B2E] border border-[#1E293B] overflow-hidden shadow-lg">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm" id="users-table">
                    <thead class="bg-[#0A0E1A]/80 text-slate-400 border-b border-[#1E293B] font-mono text-[11px] uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5 font-semibold">Pengguna</th>
                            <th class="px-5 py-3.5 font-semibold">Peran (Role)</th>
                            <th class="px-5 py-3.5 font-semibold">Aplikasi</th>
                            <th class="px-5 py-3.5 font-semibold">Status</th>
                            <th class="px-5 py-3.5 font-semibold">2FA / MFA</th>
                            <th class="px-5 py-3.5 font-semibold">Bcrypt Hash (CI3)</th>
                            <th class="px-5 py-3.5 font-semibold">Login Terakhir</th>
                            <th class="px-5 py-3.5 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B] text-slate-300">
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-[#1E293B]/40 transition-colors user-row" 
                            data-username="<?php echo strtolower($user['username']); ?>" 
                            data-name="<?php echo strtolower($user['fullName']); ?>" 
                            data-email="<?php echo strtolower($user['email']); ?>" 
                            data-role="<?php echo $user['role']; ?>" 
                            data-status="<?php echo $user['status']; ?>"
                            data-app="<?php echo strtolower($user['appSlug'] ?? 'all'); ?>">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover ring-2 ring-[#1E293B]">
                                    <div>
                                        <p class="font-bold text-white"><?php echo htmlspecialchars($user['fullName']); ?></p>
                                        <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-400 font-mono">
                                            <span class="text-cyan-400">@<?php echo htmlspecialchars($user['username']); ?></span>
                                            <span>•</span>
                                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-[#0A0E1A] text-slate-300 border border-[#1E293B]">
                                    <?php echo htmlspecialchars($user['role']); ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <?php 
                                $u_app_slug = $user['appSlug'] ?? 'all';
                                $u_app_name = $user['appName'] ?? 'IAM Core';
                                
                                if ($u_app_slug === 'mochi-finance') {
                                    $badge_style = 'bg-pink-500/10 text-pink-300 border-pink-500/30';
                                    $dot_style = 'bg-pink-400';
                                } elseif ($u_app_slug === 'portofolio-motion') {
                                    $badge_style = 'bg-sky-500/10 text-sky-300 border-sky-500/30';
                                    $dot_style = 'bg-sky-400';
                                } elseif ($u_app_slug === 'all') {
                                    $badge_style = 'bg-cyan-500/10 text-cyan-300 border-cyan-500/30';
                                    $dot_style = 'bg-cyan-400';
                                } else {
                                    $badge_style = 'bg-indigo-500/10 text-indigo-300 border-indigo-500/30';
                                    $dot_style = 'bg-indigo-400';
                                }
                                ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold border <?php echo $badge_style; ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo $dot_style; ?>"></span>
                                    <?php echo htmlspecialchars($u_app_name); ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <?php 
                                $status_badge_class = 'bg-slate-500/10 text-slate-300 border-slate-500/30';
                                if ($user['status'] === 'active') $status_badge_class = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30';
                                elseif ($user['status'] === 'suspended') $status_badge_class = 'bg-rose-500/10 text-rose-400 border-rose-500/30';
                                elseif ($user['status'] === 'mfa_required') $status_badge_class = 'bg-amber-500/10 text-amber-400 border-amber-500/30';
                                ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold border <?php echo $status_badge_class; ?> uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo ($user['status'] === 'active') ? 'bg-emerald-400' : (($user['status'] === 'suspended') ? 'bg-rose-400' : 'bg-amber-400'); ?>"></span>
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <?php 
                                $this->load->view('components/button', [
                                    'text' => $user['isMfaEnabled'] ? 'Aktif' : 'Nonaktif',
                                    'variant' => $user['isMfaEnabled'] ? 'primary' : 'subtle',
                                    'size' => 'sm',
                                    'icon' => $user['isMfaEnabled'] ? 'shield-check' : 'shield-off',
                                    'attributes' => 'onclick="toggleMfa(\''.$user['id'].'\')"'
                                ]); 
                                ?>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs text-slate-400 max-w-[150px] truncate" title="<?php echo htmlspecialchars($user['ci3PasswordHash']); ?>">
                                <span class="bg-[#0A0E1A] px-2 py-0.5 rounded border border-[#1E293B]">
                                    <?php echo substr($user['ci3PasswordHash'], 0, 15); ?>...
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="text-xs text-slate-300 font-mono"><?php echo htmlspecialchars($user['lastLogin']); ?></p>
                                <p class="text-[11px] text-slate-500 font-mono mt-0.5"><?php echo htmlspecialchars($user['ipAddress']); ?></p>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <?php 
                                    $this->load->view('components/button', [
                                        'text' => '',
                                        'variant' => 'subtle',
                                        'size' => 'sm',
                                        'icon' => 'edit',
                                        'class' => 'text-cyan-400 hover:bg-cyan-500/20 border-cyan-500/20',
                                        'attributes' => 'title="Edit Account" onclick=\'openEditUserModal('.json_encode($user).')\''
                                    ]); 
                                    ?>
                                    
                                    <?php if ($user['status'] === 'active'): ?>
                                        <?php 
                                        $this->load->view('components/button', [
                                            'text' => '',
                                            'variant' => 'subtle',
                                            'size' => 'sm',
                                            'icon' => 'user-x',
                                            'class' => 'text-rose-400 hover:bg-rose-500/20 border-rose-500/20',
                                            'attributes' => 'title="Suspend User" onclick="updateUserStatus(\''.$user['id'].'\', \'suspended\')"'
                                        ]); 
                                        ?>
                                    <?php else: ?>
                                        <?php 
                                        $this->load->view('components/button', [
                                            'text' => '',
                                            'variant' => 'subtle',
                                            'size' => 'sm',
                                            'icon' => 'user-check',
                                            'class' => 'text-emerald-400 hover:bg-emerald-500/20 border-emerald-500/20',
                                            'attributes' => 'title="Activate User" onclick="updateUserStatus(\''.$user['id'].'\', \'active\')"'
                                        ]); 
                                        ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php 
// Prepare modal body for Edit User
ob_start();
?>
    <input type="hidden" name="id" id="edit_user_id">

    <?php 
    $this->load->view('components/input', [
        'name' => 'fullName',
        'id' => 'edit_full_name',
        'label' => 'Full Name',
        'required' => true
    ]); 
    ?>

    <div class="grid grid-cols-2 gap-4 mt-4">
        <?php 
        $this->load->view('components/input', [
            'name' => 'username',
            'id' => 'edit_username',
            'label' => 'Username',
            'required' => true
        ]); 
        
        $this->load->view('components/input', [
            'name' => 'email',
            'id' => 'edit_email',
            'label' => 'Email',
            'type' => 'email',
            'required' => true
        ]); 
        ?>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-4">
        <div class="vf-field">
            <label class="vf-label vf-label--sm mb-1 text-slate-300">Role</label>
            <select name="role" id="edit_role" class="vf-select w-full px-3.5 py-2 border border-[#1E293B] bg-[#0A0E1A] rounded-xl focus:border-cyan-500 focus:outline-none text-slate-200 text-xs">
                <option value="super_admin">Super Administrator</option>
                <option value="admin">Administrator</option>
            </select>
        </div>
        <div class="vf-field">
            <label class="vf-label vf-label--sm mb-1 text-slate-300">Status</label>
            <select name="status" id="edit_status" class="vf-select w-full px-3.5 py-2 border border-[#1E293B] bg-[#0A0E1A] rounded-xl focus:border-cyan-500 focus:outline-none text-slate-200 text-xs">
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    <div class="mt-4">
        <label class="vf-label vf-label--sm mb-1 text-slate-300">Aplikasi (Target Application)</label>
        <select name="application_id" id="edit_application_id" class="vf-select w-full px-3.5 py-2 border border-[#1E293B] bg-[#0A0E1A] rounded-xl focus:border-cyan-500 focus:outline-none text-slate-200 text-xs">
            <option value="">Global / IAM Core</option>
            <?php if (!empty($applications)): foreach ($applications as $app): ?>
            <option value="<?php echo htmlspecialchars($app['id']); ?>"><?php echo htmlspecialchars($app['name']); ?> (<?php echo htmlspecialchars($app['slug']); ?>)</option>
            <?php endforeach; endif; ?>
        </select>
    </div>

    <div class="mt-4">
        <?php 
        $this->load->view('components/input', [
            'name' => 'password',
            'label' => 'Password Baru (Opsional)',
            'type' => 'password',
            'placeholder' => 'Biarkan kosong jika tidak ingin mengubah password...'
        ]); 
        ?>
    </div>
<?php 
$modal_body = ob_get_clean();

$this->load->view('components/modal', [
    'id' => 'editUserModal',
    'title' => 'Edit Admin Account',
    'icon' => 'edit',
    'form_id' => 'editUserForm',
    'onsubmit' => 'submitEditUser(event)',
    'body' => $modal_body,
    'submit_text' => 'Simpan Perubahan',
    'submit_btn_id' => 'submitEditUserBtn'
]);
?>

<script>
function openEditUserModal(user) {
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_full_name').value = user.fullName;
    document.getElementById('edit_username').value = user.username;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_status').value = user.status;
    document.getElementById('edit_application_id').value = user.appId || '';

    document.getElementById('editUserModal').classList.remove('hidden');
    document.getElementById('editUserModal').classList.add('flex');
}

function submitEditUser(e) {
    e.preventDefault();
    const form = document.getElementById('editUserForm');
    const formData = new FormData(form);
    const btn = document.getElementById('submitEditUserBtn');
    btn.disabled = true;

    fetch('<?php echo base_url("auth/admins/update"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data akun admin berhasil diperbarui.',
                icon: 'success',
                background: '#131B2E',
                color: '#F1F5F9',
                confirmButtonColor: '#06b6d4'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({ title: 'Gagal', text: data.error || 'Terjadi kesalahan', icon: 'error', background: '#131B2E', color: '#F1F5F9' });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({ title: 'Error', text: 'Gagal terhubung ke server.', icon: 'error', background: '#131B2E', color: '#F1F5F9' });
    })
    .finally(() => {
        btn.disabled = false;
    });
}
</script>
