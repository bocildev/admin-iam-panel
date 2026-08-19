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
        <div class="vf-panel">
            <div class="vf-inline vf-inline--justify-between vf-inline--wrap">
                <div class="relative w-full md:w-96">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3 z-10"></i>
                    <input type="text" id="user-search-input" onkeyup="filterUserTable()" placeholder="Cari nama, username, atau email..." class="vf-input w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="vf-inline vf-inline--gap-sm">
                    <select id="role-filter" onchange="filterUserTable()" class="vf-select px-3 py-2 border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 rounded-md focus:border-blue-500 focus:outline-none text-sm">
                        <option value="">Semua Peran (Roles)</option>
                        <?php foreach ($roles as $r): ?>
                        <option value="<?php echo htmlspecialchars($r['name']); ?>"><?php echo htmlspecialchars($r['displayName']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="status-filter" onchange="filterUserTable()" class="vf-select px-3 py-2 border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 rounded-md focus:border-blue-500 focus:outline-none text-sm">
                        <option value="">Semua Status</option>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                        <option value="mfa_required">MFA Required</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="vf-panel vf-card--padding-none overflow-hidden border border-slate-200 dark:border-slate-800">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm" id="users-table">
                    <thead class="bg-slate-100/80 dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Pengguna</th>
                            <th class="px-4 py-3 font-semibold">Peran (Role)</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">2FA / MFA</th>
                            <th class="px-4 py-3 font-semibold">Bcrypt Hash (CI3)</th>
                            <th class="px-4 py-3 font-semibold">Login Terakhir</th>
                            <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-600 dark:text-slate-300">
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-slate-100/40 dark:bg-slate-900/40 transition user-row" data-username="<?php echo strtolower($user['username']); ?>" data-name="<?php echo strtolower($user['fullName']); ?>" data-email="<?php echo strtolower($user['email']); ?>" data-role="<?php echo $user['role']; ?>" data-status="<?php echo $user['status']; ?>">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-slate-100"><?php echo htmlspecialchars($user['fullName']); ?></p>
                                        <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                            <span class="font-mono text-blue-400">@<?php echo htmlspecialchars($user['username']); ?></span>
                                            <span>•</span>
                                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <?php 
                                $this->load->view('components/badge', [
                                    'text' => htmlspecialchars($user['role']),
                                    'variant' => 'neutral'
                                ]); 
                                ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php 
                                $status_variant = 'neutral';
                                if ($user['status'] === 'active') $status_variant = 'success';
                                elseif ($user['status'] === 'suspended') $status_variant = 'danger';
                                elseif ($user['status'] === 'mfa_required') $status_variant = 'warning';
                                
                                $this->load->view('components/badge', [
                                    'text' => ucfirst($user['status']),
                                    'variant' => $status_variant
                                ]); 
                                ?>
                            </td>
                            <td class="px-4 py-3">
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
                            <td class="px-4 py-3 font-mono text-xs text-slate-500 max-w-[150px] truncate" title="<?php echo htmlspecialchars($user['ci3PasswordHash']); ?>">
                                <?php echo substr($user['ci3PasswordHash'], 0, 15); ?>...
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-xs"><?php echo htmlspecialchars($user['lastLogin']); ?></p>
                                <p class="text-[11px] text-slate-500 font-mono mt-1"><?php echo htmlspecialchars($user['ipAddress']); ?></p>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="vf-inline vf-inline--justify-end vf-inline--gap-xs">
                                    <?php 
                                    $this->load->view('components/button', [
                                        'text' => '',
                                        'variant' => 'subtle',
                                        'size' => 'sm',
                                        'icon' => 'edit',
                                        'class' => 'text-blue-400',
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
                                            'class' => 'text-red-400',
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
                                            'class' => 'text-emerald-400',
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
            <label class="vf-label vf-label--sm mb-1">Role</label>
            <select name="role" id="edit_role" class="vf-select w-full px-3.5 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0A0E1A] rounded-md focus:border-blue-500 focus:outline-none">
                <option value="super_admin">Super Administrator</option>
                <option value="admin">Administrator</option>
            </select>
        </div>
        <div class="vf-field">
            <label class="vf-label vf-label--sm mb-1">Status</label>
            <select name="status" id="edit_status" class="vf-select w-full px-3.5 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0A0E1A] rounded-md focus:border-blue-500 focus:outline-none">
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
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
                background: '#0D1322',
                color: '#F1F5F9',
                confirmButtonColor: '#06b6d4'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({ title: 'Gagal', text: data.error || 'Terjadi kesalahan', icon: 'error', background: '#0D1322', color: '#F1F5F9' });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({ title: 'Error', text: 'Gagal terhubung ke server.', icon: 'error', background: '#0D1322', color: '#F1F5F9' });
    })
    .finally(() => {
        btn.disabled = false;
    });
}
</script>
