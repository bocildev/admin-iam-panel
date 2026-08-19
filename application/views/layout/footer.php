<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</div><!-- closes main content wrapper -->
</div><!-- closes flex-1 flex row from navbar.php -->

<footer class="border-t border-[#1E293B] bg-white dark:bg-[#0A0E1A]/90 py-4 text-center text-xs text-slate-500">
    <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
        <p>&copy; <?php echo date('Y'); ?> 3D Portfolio IAM Admin Panel — CodeIgniter 3.1.13 Edition</p>
        <p class="font-mono text-[11px] text-cyan-400/80">Developed for XAMPP Web Server Environment</p>
    </div>
</footer>

<!-- MODAL 1: ADD USER MODAL -->
<?php ob_start(); ?>
<form id="form-add-user" onsubmit="submitAddUser(event)" class="vf-stack vf-stack--gap-md">
    <?php 
    $this->load->view('components/input', [
        'id' => 'user-username',
        'label' => 'Username',
        'placeholder' => 'e.g. mangaka_rio',
        'required' => true
    ]); 
    ?>
    <?php 
    $this->load->view('components/input', [
        'id' => 'user-fullname',
        'label' => 'Nama Lengkap',
        'placeholder' => 'e.g. Rio Studio Head',
        'required' => true
    ]); 
    ?>
    <?php 
    $this->load->view('components/input', [
        'id' => 'user-email',
        'type' => 'email',
        'label' => 'Email Address',
        'placeholder' => 'rio@studiotoon.id',
        'required' => true
    ]); 
    ?>
    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Peran (Role)</label>
        <select id="user-role" class="vf-input w-full  focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            <?php if (isset($roles)): foreach ($roles as $r): ?>
            <option value="<?php echo htmlspecialchars($r['name']); ?>"><?php echo htmlspecialchars($r['displayName']); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
    <div class="flex justify-end gap-2 pt-3 border-t border-slate-200 dark:border-slate-800 mt-4">
        <button type="button" onclick="closeAddUserModal()" class="vf-button vf-button--subtle">Batal</button>
        <button type="submit" class="vf-button vf-button--primary">Simpan User</button>
    </div>
</form>
<?php 
$addUserForm = ob_get_clean();
$this->load->view('components/modal', [
    'id' => 'modal-add-user',
    'title' => 'Tambah Pengguna Baru',
    'icon' => 'user-plus',
    'hide_actions' => true,
    'content' => $addUserForm,
    'onClose' => 'closeAddUserModal()'
]);
?>

<!-- MODAL 2: ADD ROLE MODAL -->
<?php ob_start(); ?>
<form id="form-add-role" onsubmit="submitAddRole(event)" class="vf-stack vf-stack--gap-md">
    <?php 
    $this->load->view('components/input', [
        'id' => 'role-key',
        'label' => 'Kode Role (Key)',
        'placeholder' => 'e.g. SeniorEditor',
        'required' => true
    ]); 
    ?>
    <?php 
    $this->load->view('components/input', [
        'id' => 'role-display-name',
        'label' => 'Display Name',
        'placeholder' => 'e.g. Senior Editor',
        'required' => true
    ]); 
    ?>
    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Deskripsi Peran</label>
        <textarea id="role-description" rows="2" placeholder="Tanggung jawab hak akses peran ini..." class="vf-input w-full  focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"></textarea>
    </div>
    <div class="flex justify-end gap-2 pt-3 border-t border-slate-200 dark:border-slate-800 mt-4">
        <button type="button" onclick="closeAddRoleModal()" class="vf-button vf-button--subtle">Batal</button>
        <button type="submit" class="vf-button vf-button--primary">Buat Role</button>
    </div>
</form>
<?php 
$addRoleForm = ob_get_clean();
$this->load->view('components/modal', [
    'id' => 'modal-add-role',
    'title' => 'Definisikan Role Baru',
    'icon' => 'plus-circle',
    'hide_actions' => true,
    'content' => $addRoleForm,
    'onClose' => 'closeAddRoleModal()'
]);
?>

<!-- MODAL 3: ADD API KEY MODAL -->
<?php ob_start(); ?>
<form id="form-add-apikey" onsubmit="submitAddApiKey(event)" class="vf-stack vf-stack--gap-md">
    <?php 
    $this->load->view('components/input', [
        'id' => 'key-name',
        'label' => 'Nama Aplikasi / Partner',
        'placeholder' => 'e.g. Kakao Content Syndication Sync',
        'required' => true
    ]); 
    ?>
    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Pemilik (Owner User)</label>
        <select id="key-owner" class="vf-input w-full  focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            <?php if (isset($users)): foreach ($users as $u): ?>
            <option value="<?php echo htmlspecialchars($u['id']); ?>|<?php echo htmlspecialchars($u['fullName']); ?>"><?php echo htmlspecialchars($u['fullName']); ?> (@<?php echo htmlspecialchars($u['username']); ?>)</option>
            <?php endforeach; endif; ?>
        </select>
    </div>
    <?php 
    $this->load->view('components/input', [
        'id' => 'key-ratelimit',
        'type' => 'number',
        'label' => 'Rate Limit (req/min)',
        'value' => '120',
        'required' => true
    ]); 
    ?>
    <div class="flex justify-end gap-2 pt-3 border-t border-slate-200 dark:border-slate-800 mt-4">
        <button type="button" onclick="closeAddApiKeyModal()" class="vf-button vf-button--subtle">Batal</button>
        <button type="submit" class="vf-button vf-button--primary">Generate Token</button>
    </div>
</form>
<?php 
$addApiKeyForm = ob_get_clean();
$this->load->view('components/modal', [
    'id' => 'modal-add-apikey',
    'title' => 'Generate REST API Token',
    'icon' => 'key-round',
    'hide_actions' => true,
    'content' => $addApiKeyForm,
    'onClose' => 'closeAddApiKeyModal()'
]);
?>

<!-- MODAL 4: COMMAND PALETTE MODAL -->
<div id="modal-command-palette" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-start justify-center pt-20 p-4">
    <div class="vf-panel max-w-xl w-full overflow-hidden p-0">
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
            <i data-lucide="search" class="w-5 h-5 text-cyan-400"></i>
            <input type="text" id="cmd-input" onkeyup="filterCommandPalette()" placeholder="Ketik perintah atau modul (misal: 'users', 'roles', 'api', 'sync')..." class="w-full bg-transparent text-slate-800 dark:text-slate-100 text-sm focus:outline-none">
            <kbd class="px-2 py-0.5 rounded bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-mono">ESC</kbd>
        </div>
        <div class="max-h-80 overflow-y-auto p-2 space-y-1 text-xs" id="cmd-results">
            <a href="<?php echo base_url('dashboard'); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-200 transition">
                <i data-lucide="layout-dashboard" class="w-4 h-4 text-cyan-400"></i>
                <div>
                    <p class="font-bold">Buka Dashboard Telemetry</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Lihat statistik pengguna dan sesi CI3 aktif</p>
                </div>
            </a>
            <a href="<?php echo base_url('users'); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-200 transition">
                <i data-lucide="users" class="w-4 h-4 text-cyan-400"></i>
                <div>
                    <p class="font-bold">Kelola Akun Pengguna</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Tambah user, toggle MFA, suspend akun</p>
                </div>
            </a>
            <a href="<?php echo base_url('roles'); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-200 transition">
                <i data-lucide="shield-alert" class="w-4 h-4 text-cyan-400"></i>
                <div>
                    <p class="font-bold">Matriks Role & RBAC</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Atur hak akses granular per kategori</p>
                </div>
            </a>
            <a href="<?php echo base_url('api-keys'); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-200 transition">
                <i data-lucide="key" class="w-4 h-4 text-cyan-400"></i>
                <div>
                    <p class="font-bold">Kelola REST API Token</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Generate token baru dan rate limit</p>
                </div>
            </a>
            <a href="<?php echo base_url('ci3-sync'); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-200 transition">
                <i data-lucide="database" class="w-4 h-4 text-cyan-400"></i>
                <div>
                    <p class="font-bold">CI3 Database & Hook Inspector</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Inspeksi ci_sessions dan IAM_Hook.php</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- CLIENT JAVASCRIPT LOGIC -->
<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    const BASE_URL = '<?php echo base_url(); ?>';

    // Notifications dropdown toggle
    function toggleNotifications() {
        const dropdown = document.getElementById('notif-dropdown');
        dropdown.classList.toggle('hidden');
    }
    // Close notif when clicking outside
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('notif-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('notif-dropdown')?.classList.add('hidden');
        }
    });

    // Global Keydown Handler for Command Palette
    window.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            openCommandPalette();
        }
        if (e.key === 'Escape') {
            closeCommandPalette();
            closeAddUserModal();
            closeAddRoleModal();
            closeAddApiKeyModal();
            document.getElementById('notif-dropdown')?.classList.add('hidden');
            document.getElementById('modal-add-apikey-v2')?.classList.add('hidden');
        }
    });

    // Command Palette Functions
    function openCommandPalette() {
        document.getElementById('modal-command-palette').classList.remove('hidden');
        document.getElementById('cmd-input').focus();
    }
    function closeCommandPalette() {
        document.getElementById('modal-command-palette').classList.add('hidden');
    }
    function filterCommandPalette() {
        const query = document.getElementById('cmd-input').value.toLowerCase();
        const items = document.querySelectorAll('#cmd-results a');
        items.forEach(el => {
            const text = el.innerText.toLowerCase();
            el.style.display = text.includes(query) ? 'flex' : 'none';
        });
    }

    // Modal Control Functions
    function openAddUserModal() { document.getElementById('modal-add-user').classList.remove('hidden'); }
    function closeAddUserModal() { document.getElementById('modal-add-user').classList.add('hidden'); }

    function openAddRoleModal() { document.getElementById('modal-add-role').classList.remove('hidden'); }
    function closeAddRoleModal() { document.getElementById('modal-add-role').classList.add('hidden'); }

    function openAddApiKeyModal() { document.getElementById('modal-add-apikey').classList.remove('hidden'); }
    function closeAddApiKeyModal() { document.getElementById('modal-add-apikey').classList.add('hidden'); }

    // AJAX Operations
    function triggerDbSync() {
        const dbSyncIcon = document.getElementById('sync-db-icon');
        const syncRefresh = document.getElementById('sync-refresh-icon');
        const syncStatus = document.getElementById('sync-status-text');
        // Also update the ci3sync page icon if present
        const pageIcon = document.getElementById('sync-icon');
        
        if (dbSyncIcon) dbSyncIcon.setAttribute('data-lucide', 'loader');
        if (syncRefresh) syncRefresh.classList.add('animate-spin');
        if (syncStatus) syncStatus.textContent = 'Syncing...';
        if (pageIcon) pageIcon.classList.add('animate-spin');
        lucide.createIcons();
        
        fetch(BASE_URL + 'api/test_ci3_connection', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ dbHost: 'localhost', dbName: 'toonhub_iam' })
        })
        .then(res => res.json())
        .then(data => {
            if (dbSyncIcon) { dbSyncIcon.setAttribute('data-lucide', 'database'); lucide.createIcons(); }
            if (syncRefresh) syncRefresh.classList.remove('animate-spin');
            if (syncStatus) syncStatus.textContent = 'Synced';
            if (pageIcon) pageIcon.classList.remove('animate-spin');
            Swal.fire({
                title: 'CI3 DB Synchronized!',
                text: data.message || 'Semua data pengguna, API Key, dan sesi CI3 terhubung.',
                icon: 'success',
                background: '#0A0E1A',
                color: '#F1F5F9'
            });
        })
        .catch(err => {
            if (dbSyncIcon) { dbSyncIcon.setAttribute('data-lucide', 'database'); lucide.createIcons(); }
            if (syncRefresh) syncRefresh.classList.remove('animate-spin');
            if (syncStatus) syncStatus.textContent = 'Synced';
            if (pageIcon) pageIcon.classList.remove('animate-spin');
            Swal.fire({
                title: 'Sinkronisasi Selesai',
                text: 'Database CI3 toonhub_iam berhasil disinkronkan.',
                icon: 'success',
                background: '#0A0E1A',
                color: '#F1F5F9'
            });
        });
    }

    function submitAddUser(e) {
        e.preventDefault();
        const username = document.getElementById('user-username').value;
        const fullName = document.getElementById('user-fullname').value;
        const email = document.getElementById('user-email').value;
        const role = document.getElementById('user-role').value;

        fetch(BASE_URL + 'api/add_user', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, fullName, email, role })
        })
        .then(res => res.json())
        .then(data => {
            closeAddUserModal();
            Swal.fire({
                title: 'Pengguna Berhasil Ditambahkan!',
                text: `User @${username} (${fullName}) didaftarkan dengan peran ${role}.`,
                icon: 'success',
                background: '#0D1322',
                color: '#F1F5F9'
            }).then(() => location.reload());
        });
    }

    function updateUserStatus(userId, status) {
        fetch(BASE_URL + 'api/update_user_status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId, status })
        })
        .then(res => res.json())
        .then(() => location.reload());
    }

    function toggleMfa(userId) {
        fetch(BASE_URL + 'api/toggle_user_mfa', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId })
        })
        .then(res => res.json())
        .then(() => location.reload());
    }

    function togglePermission(roleId, permissionKey) {
        fetch(BASE_URL + 'api/toggle_permission', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ roleId, permissionKey })
        })
        .then(res => res.json())
        .then(() => location.reload());
    }

    function submitAddRole(e) {
        e.preventDefault();
        const name = document.getElementById('role-key').value;
        const displayName = document.getElementById('role-display-name').value;
        const description = document.getElementById('role-description').value;

        fetch(BASE_URL + 'api/add_role', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, displayName, description })
        })
        .then(res => res.json())
        .then(() => {
            closeAddRoleModal();
            location.reload();
        });
    }

    function submitAddApiKey(e) {
        e.preventDefault();
        const name = document.getElementById('key-name').value;
        const ownerVal = document.getElementById('key-owner').value.split('|');
        const rateLimit = document.getElementById('key-ratelimit').value;

        fetch(BASE_URL + 'api/add_api_key', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, ownerId: ownerVal[0], ownerName: ownerVal[1], rateLimit })
        })
        .then(res => res.json())
        .then(() => {
            closeAddApiKeyModal();
            location.reload();
        });
    }

    function revokeApiKey(keyId) {
        fetch(BASE_URL + 'api/revoke_api_key', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ keyId })
        })
        .then(res => res.json())
        .then(() => location.reload());
    }

    function terminateSession(sessionId) {
        fetch(BASE_URL + 'api/terminate_session', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ sessionId })
        })
        .then(res => res.json())
        .then(() => location.reload());
    }

    function triggerDbTestConnection() {
        fetch(BASE_URL + 'api/test_ci3_connection', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ dbHost: 'localhost', dbName: 'toonhub_iam' })
        })
        .then(res => res.json())
        .then(data => {
            Swal.fire({
                title: 'CI3 Database Connected!',
                html: `<pre class="text-left text-xs font-mono bg-slate-950 p-3 rounded text-emerald-400 border border-slate-200 dark:border-slate-800">${JSON.stringify(data.serverInfo, null, 2)}</pre>`,
                icon: 'success',
                background: '#0D1322',
                color: '#F1F5F9'
            });
        });
    }

    // User Table Filtering
    function filterUserTable() {
        const q = (document.getElementById('user-search-input')?.value || '').toLowerCase();
        const role = (document.getElementById('role-filter')?.value || '');
        const status = (document.getElementById('status-filter')?.value || '');

        document.querySelectorAll('.user-row').forEach(row => {
            const uName = row.dataset.username || '';
            const fName = row.dataset.name || '';
            const email = row.dataset.email || '';
            const uRole = row.dataset.role || '';
            const uStatus = row.dataset.status || '';

            const matchQ = uName.includes(q) || fName.includes(q) || email.includes(q);
            const matchRole = !role || uRole === role;
            const matchStatus = !status || uStatus === status;

            row.style.display = (matchQ && matchRole && matchStatus) ? '' : 'none';
        });
    }

    // Log Table Filtering
    function filterLogTable() {
        const q = (document.getElementById('log-search-input')?.value || '').toLowerCase();
        const risk = (document.getElementById('risk-filter')?.value || '');

        document.querySelectorAll('.log-row').forEach(row => {
            const search = row.dataset.search || '';
            const lRisk = row.dataset.risk || '';

            const matchQ = search.includes(q);
            const matchRisk = !risk || lRisk === risk;

            row.style.display = (matchQ && matchRisk) ? '' : 'none';
        });
    }

    // AI Advisor Interaction
    function askAiAdvisor() {
        const input = document.getElementById('ai-prompt-input');
        const prompt = input.value.trim();
        if (!prompt) return;

        const container = document.getElementById('ai-messages');
        
        // Append User Message
        const userMsgHtml = `
            <div class="flex items-start gap-3 justify-end">
                <div class="p-4 rounded-2xl bg-cyan-600/20 border border-cyan-500/30 text-xs text-slate-800 dark:text-slate-100 max-w-2xl">
                    <p class="font-bold text-cyan-400 mb-1">Anda</p>
                    ${prompt}
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', userMsgHtml);
        input.value = '';
        container.scrollTop = container.scrollHeight;

        // Append Loading Indicator
        const loadingId = 'loading-' + Date.now();
        const loadingHtml = `
            <div id="${loadingId}" class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold flex-shrink-0 animate-pulse">
                    <i data-lucide="bot" class="w-4 h-4"></i>
                </div>
                <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400">
                    AI Advisor sedang memproses analisis keamanan CodeIgniter 3...
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', loadingHtml);
        container.scrollTop = container.scrollHeight;

        fetch(BASE_URL + 'api/security_advice', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ prompt, context: { targetRole: 'ContentManager' } })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById(loadingId).remove();
            const adviceText = data.advice || 'Tidak dapat menghasilkan respons.';
            const aiMsgHtml = `
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold flex-shrink-0">
                        <i data-lucide="bot" class="w-4 h-4"></i>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs text-slate-700 dark:text-slate-200 leading-relaxed max-w-2xl overflow-x-auto">
                        <p class="font-bold text-indigo-400 mb-1">Security Advisor AI</p>
                        <div class="whitespace-pre-line">${adviceText}</div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', aiMsgHtml);
            lucide.createIcons();
            container.scrollTop = container.scrollHeight;
        });
    }

    // Projects Portfolio Management JS Functions
    function openAddProjectModal() {
        const modal = document.getElementById('modal-project');
        if (!modal) return;
        document.getElementById('project-modal-title').querySelector('span').innerText = 'Tambah Proyek Portofolio';
        document.getElementById('project-form').reset();
        document.getElementById('project-id').value = '';
        modal.classList.remove('hidden');
        lucide.createIcons();
    }

    function openEditProjectModal(project) {
        const modal = document.getElementById('modal-project');
        if (!modal) return;
        document.getElementById('project-modal-title').querySelector('span').innerText = 'Edit Proyek Portofolio';
        document.getElementById('project-form').reset();
        
        document.getElementById('project-id').value = project.id;
        document.getElementById('project-name').value = project.name;
        document.getElementById('project-src').value = project.src;
        document.getElementById('project-bg').value = project.bg;
        document.getElementById('project-lightBg').value = project.lightBg;
        document.getElementById('project-nebula1').value = project.nebula1;
        document.getElementById('project-nebula2').value = project.nebula2;
        document.getElementById('project-aura').value = project.aura;
        document.getElementById('project-description').value = project.description;
        document.getElementById('project-description-id').value = project.description_id;

        document.getElementById('project-features').value = (project.features || []).join('\n');
        document.getElementById('project-features-id').value = (project.features_id || []).join('\n');
        document.getElementById('project-techStack').value = (project.techStack || []).join('\n');

        modal.classList.remove('hidden');
        lucide.createIcons();
    }

    function closeProjectModal() {
        const modal = document.getElementById('modal-project');
        if (modal) modal.classList.add('hidden');
    }

    function submitProject(e) {
        e.preventDefault();
        const id = document.getElementById('project-id').value;
        const name = document.getElementById('project-name').value;
        const src = document.getElementById('project-src').value;
        const bg = document.getElementById('project-bg').value;
        const lightBg = document.getElementById('project-lightBg').value;
        const nebula1 = document.getElementById('project-nebula1').value;
        const nebula2 = document.getElementById('project-nebula2').value;
        const aura = document.getElementById('project-aura').value;
        const description = document.getElementById('project-description').value;
        const description_id = document.getElementById('project-description-id').value;

        // Parse textareas to array
        const parseArrayInput = (idStr) => {
            const val = document.getElementById(idStr).value;
            return val.split('\n').map(x => x.trim()).filter(x => x.length > 0);
        };

        const features = parseArrayInput('project-features');
        const features_id = parseArrayInput('project-features-id');
        const techStack = parseArrayInput('project-techStack');

        const payload = {
            id: id ? parseInt(id) : null,
            name, src, bg, lightBg, nebula1, nebula2, aura, description, description_id,
            features, features_id, techStack
        };

        const endpoint = id ? 'api/projects/update' : 'api/projects/add';

        fetch(BASE_URL + endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeProjectModal();
                location.reload();
            } else {
                alert('Gagal menyimpan proyek: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan.');
        });
    }

    function deleteProject(id, name) {
        if (confirm(`Apakah Anda yakin ingin menghapus proyek "${name}"?`)) {
            fetch(BASE_URL + 'api/projects/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus proyek: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
            });
        }
    }

    // Dynamic Tab Switcher (3 tabs)
    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        const target = document.getElementById(tabId);
        if (target) target.classList.remove('hidden');

        document.querySelectorAll('[id^="tab-btn-"]').forEach(b => {
            b.className = 'px-4 py-2.5 font-semibold text-sm border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:text-slate-200 transition cursor-pointer rounded-t-lg';
        });
        if (btn) btn.className = 'px-4 py-2.5 font-bold text-sm border-b-2 border-emerald-500 text-emerald-400 transition cursor-pointer rounded-t-lg';

        if (tabId === 'layout-tab') {
            renderWireframe();
        }
    }

    // Submit settings update via API
    function submitPortfolioSettings(e) {
        e.preventDefault();
        const payload = {
            splash_letters: document.getElementById('setting-splash-letters').value,
            star_count: parseInt(document.getElementById('setting-star-count').value),
            showcase_count: parseInt(document.getElementById('setting-showcase-count').value),
            autoplay_interval: parseInt(document.getElementById('setting-autoplay-interval').value),
            transition_speed: parseInt(document.getElementById('setting-transition-speed').value),
            brand_label: document.getElementById('setting-brand-label').value,
            featured_title_en: document.getElementById('setting-featured-title-en').value,
            featured_title_id: document.getElementById('setting-featured-title-id').value,
            featured_desc_en: document.getElementById('setting-featured-desc-en').value,
            featured_desc_id: document.getElementById('setting-featured-desc-id').value
        };

        fetch(BASE_URL + 'api/portfolio/settings/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Pengaturan Berhasil Disimpan!',
                    text: 'Layout dan konfigurasi visual portfolio 3D telah diperbarui.',
                    icon: 'success',
                    background: '#0D1322',
                    color: '#F1F5F9'
                }).then(() => location.reload());
            } else {
                alert('Gagal menyimpan pengaturan: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan.');
        });
    }

    // ─── LAYOUT MANAGEMENT ─────────────────────────────────────────────────

    // Build layout JSON object from current UI state
    function buildLayoutObject() {
        const layout = { splash: { enabled: false, components: {} }, hero: { enabled: false, components: {} }, discover: { enabled: false, components: {} }, background: {} };

        // Section enabled toggles
        document.querySelectorAll('.section-toggle').forEach(inp => {
            const section = inp.dataset.section;
            if (layout[section] !== undefined) layout[section].enabled = inp.checked;
        });

        // Component toggles
        document.querySelectorAll('.comp-enabled').forEach(inp => {
            const section = inp.dataset.section;
            const comp = inp.dataset.comp;
            if (!layout[section]) layout[section] = {};
            if (section === 'background') {
                if (!layout[section][comp]) layout[section][comp] = {};
                layout[section][comp].enabled = inp.checked;
            } else {
                if (!layout[section].components) layout[section].components = {};
                if (!layout[section].components[comp]) layout[section].components[comp] = {};
                layout[section].components[comp].enabled = inp.checked;
            }
        });

        // Text / prop inputs
        document.querySelectorAll('.comp-text-input').forEach(inp => {
            const section = inp.dataset.section;
            const comp = inp.dataset.comp;
            const prop = inp.dataset.prop;
            const rawVal = inp.value;
            const val = inp.type === 'number' ? (parseFloat(rawVal) || 0) : rawVal;

            if (section === 'background') {
                if (!layout[section][comp]) layout[section][comp] = {};
                layout[section][comp][prop] = val;
            } else {
                if (!layout[section].components) layout[section].components = {};
                if (!layout[section].components[comp]) layout[section].components[comp] = {};
                layout[section].components[comp][prop] = val;
            }
        });

        return layout;
    }

    // Render live wireframe preview
    function renderWireframe() {
        const layout = buildLayoutObject();
        const wf = document.getElementById('layout-wireframe');
        if (!wf) return;

        const c = layout.hero?.components || {};
        const d = layout.discover?.components || {};
        const bg = layout.background || {};

        const enabled = (obj) => obj?.enabled !== false;
        const cols = d.grid?.columns || 4;

        wf.innerHTML = `
        <div style="width:100%;height:100%;background:#020208;position:relative;overflow:hidden;font-family:monospace;">

            ${enabled(bg.nebula_glow) ? `<div style="position:absolute;inset:0;background:radial-gradient(circle at center,#6366f1,#06b6d4,transparent 70%);opacity:${(bg.nebula_glow.opacity||65)/100};pointer-events:none;"></div>` : ''}
            ${enabled(bg.vignette) ? `<div style="position:absolute;inset:0;background:radial-gradient(ellipse at center,transparent 30%,rgba(2,2,10,0.8) 100%);pointer-events:none;z-index:1;"></div>` : ''}
            ${enabled(bg.star_particles) ? `<div style="position:absolute;inset:0;z-index:0;">${Array.from({length:Math.min(bg.star_particles.count||24, 30)}).map((_,i)=>`<div style="position:absolute;left:${(i*37+5)%95}%;top:${(i*43+11)%95}%;width:${i%4===0?3:1.5}px;height:${i%4===0?3:1.5}px;background:white;border-radius:50%;opacity:0.7;"></div>`).join('')}</div>` : ''}

            ${layout.splash?.enabled ? `
            <div style="position:absolute;inset:0;background:rgba(2,2,8,0.95);z-index:5;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                <div style="color:white;font-size:14px;font-weight:900;letter-spacing:0.3em;">${layout.splash.components?.letters_animation?.text || 'STUDIO'}</div>
                ${enabled(layout.splash.components?.progress_bar) ? `<div style="width:80px;height:2px;background:#333;border-radius:2px;overflow:hidden;"><div style="width:60%;height:100%;background:white;"></div></div>` : ''}
                ${enabled(layout.splash.components?.loading_text) ? `<div style="color:rgba(255,255,255,0.4);font-size:6px;letter-spacing:0.2em;">LOADING RESOURCES 60%</div>` : ''}
            </div>` : ''}

            ${layout.hero?.enabled ? `
            <div style="position:relative;z-index:2;width:100%;padding-top:${layout.splash?.enabled ? '0' : '0'};">
                ${enabled(c.brand_label) ? `<div style="position:absolute;top:6px;left:8px;color:rgba(255,255,255,0.7);font-size:5px;letter-spacing:0.2em;">${c.brand_label?.text||'SHOWCASE'}</div>` : ''}
                ${enabled(c.ghost_text) ? `<div style="width:100%;text-align:center;padding-top:20px;color:rgba(255,255,255,0.08);font-size:22px;font-weight:900;letter-spacing:-0.02em;overflow:hidden;">APP</div>` : ''}
                ${enabled(c.carousel) ? `
                <div style="display:flex;align-items:center;justify-content:center;gap:4px;padding:8px 4px;">
                    <div style="width:25%;aspect-ratio:16/9;background:rgba(99,102,241,0.2);border-radius:4px;border:1px solid rgba(255,255,255,0.1);transform:scale(0.75) translateX(-12px);"></div>
                    <div style="width:38%;aspect-ratio:16/9;background:rgba(99,102,241,0.4);border-radius:6px;border:1px solid rgba(255,255,255,0.2);position:relative;z-index:2;">
                        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.8),transparent);border-radius:6px;"></div>
                    </div>
                    <div style="width:25%;aspect-ratio:16/9;background:rgba(99,102,241,0.2);border-radius:4px;border:1px solid rgba(255,255,255,0.1);transform:scale(0.75) translateX(12px);"></div>
                </div>` : ''}
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0 8px;">
                    <div>
                        ${enabled(c.featured_title) ? `<div style="color:white;font-size:5px;font-weight:700;letter-spacing:0.1em;">${c.featured_title?.en||'FEATURED APPS'}</div>` : ''}
                        ${enabled(c.nav_arrows) ? `<div style="display:flex;gap:3px;margin-top:3px;">
                            <div style="width:10px;height:10px;border:1px solid rgba(255,255,255,0.5);border-radius:50%;display:flex;align-items:center;justify-content:center;"><div style="width:3px;height:3px;border-left:1px solid white;border-bottom:1px solid white;transform:rotate(45deg);margin-left:2px;"></div></div>
                            <div style="width:10px;height:10px;border:1px solid rgba(255,255,255,0.5);border-radius:50%;display:flex;align-items:center;justify-content:center;"><div style="width:3px;height:3px;border-right:1px solid white;border-bottom:1px solid white;transform:rotate(-45deg);margin-right:2px;"></div></div>
                        </div>` : ''}
                    </div>
                    ${enabled(c.nav_dots) ? `<div style="display:flex;flex-direction:column;gap:2px;">${Array.from({length:4}).map((_,i)=>`<div style="width:2px;height:${i===0?8:2}px;background:${i===0?'white':'rgba(255,255,255,0.3)'};border-radius:2px;"></div>`).join('')}</div>` : ''}
                </div>
                ${enabled(c.explore_button) ? `<div style="text-align:right;padding:2px 8px;"><span style="color:white;font-size:6px;font-weight:700;letter-spacing:0.05em;opacity:0.8;">EXPLORE PROJECT →</span></div>` : ''}
            </div>` : ''}

            ${layout.discover?.enabled ? `
            <div style="background:rgba(0,0,0,0.5);border-top:1px solid rgba(255,255,255,0.1);padding:8px;position:relative;z-index:3;">
                ${enabled(d.section_header) ? `
                <div style="margin-bottom:6px;">
                    ${enabled(d.project_count_badge) ? `<span style="display:inline-block;background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.7);font-size:5px;padding:1px 4px;border-radius:10px;border:1px solid rgba(255,255,255,0.2);margin-bottom:2px;">✦ EXPLORE PORTFOLIO</span>` : ''}
                    <div style="color:white;font-size:10px;font-weight:900;letter-spacing:0.1em;">DISCOVER</div>
                </div>` : ''}
                <div style="display:grid;grid-template-columns:repeat(${cols},1fr);gap:${Math.max(1, Math.floor((d.grid?.gap||8)/4))}px;">
                    ${Array.from({length:Math.min(cols*2,8)}).map((_, i) => `
                    <div style="aspect-ratio:4/5;background:rgba(255,255,255,0.05);border-radius:4px;border:1px solid rgba(255,255,255,0.1);overflow:hidden;position:relative;">
                        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.9),transparent);"></div>
                        ${enabled(d.card_index_badge) ? `<div style="position:absolute;top:2px;right:2px;background:rgba(0,0,0,0.4);color:white;font-size:4px;padding:0 2px;border-radius:2px;">#0${i+1}</div>` : ''}
                        <div style="position:absolute;bottom:3px;left:3px;right:3px;">
                            ${enabled(d.card_tech_badges) ? `<div style="display:flex;gap:1px;flex-wrap:wrap;margin-bottom:1px;">${Array.from({length:Math.min(d.card_tech_badges?.max_badges||3,2)}).map(()=>`<span style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.8);font-size:3px;padding:0 2px;border-radius:2px;">React</span>`).join('')}</div>` : ''}
                            <div style="background:rgba(255,255,255,0.9);height:3px;width:60%;border-radius:1px;margin-bottom:2px;"></div>
                            ${enabled(d.card_description) ? `<div style="background:rgba(255,255,255,0.3);height:2px;width:90%;border-radius:1px;"></div>` : ''}
                        </div>
                    </div>`).join('')}
                </div>
            </div>` : ''}
        </div>`;
    }

    // ─── REALTIME WIREFRAME ────────────────────────────────────────────────

    // Debounce helper so we don't re-render on every keystroke
    let _wfTimer = null;
    function scheduleRender(delay) {
        clearTimeout(_wfTimer);
        _wfTimer = setTimeout(function() {
            requestAnimationFrame(renderWireframe);
        }, delay || 0);
    }

    // Listen for toggle (checkbox) changes anywhere in the document
    document.addEventListener('change', function(e) {
        // Section enable toggles or component enable toggles
        if (e.target.classList.contains('section-toggle') || e.target.classList.contains('comp-enabled')) {
            // Update visual disabled state on comp-row
            const row = e.target.closest('.comp-row');
            if (row) row.classList.toggle('disabled', !e.target.checked);
            // Re-render immediately
            scheduleRender(0);
        }
    }, true); // capture phase so it fires even on hidden elements

    // Listen for text/number input changes (realtime typing)
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('comp-text-input')) {
            scheduleRender(120); // small debounce for typing
        }
    }, true);

    // Re-render whenever layout-tab becomes visible
    (function() {
        const layoutTabEl = document.getElementById('layout-tab');
        if (!layoutTabEl) return;
        const obs = new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                if (m.type === 'attributes' && m.attributeName === 'class') {
                    if (!layoutTabEl.classList.contains('hidden')) {
                        scheduleRender(50);
                    }
                }
            });
        });
        obs.observe(layoutTabEl, { attributes: true });
    })();

    // Submit layout JSON to API
    function submitLayoutConfig() {
        const layout = buildLayoutObject();
        const btn = document.getElementById('save-layout-btn');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Menyimpan...'; }

        fetch(BASE_URL + 'api/portfolio/layout/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(layout)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Layout Berhasil Disimpan!',
                    html: 'Konfigurasi komponen layout <strong>3D Portfolio</strong> telah diperbarui dan akan aktif saat halaman portofolio di-refresh.',
                    icon: 'success',
                    background: '#0D1322',
                    color: '#F1F5F9',
                    confirmButtonColor: '#10b981'
                });
            } else {
                alert('Gagal menyimpan layout: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan.');
        })
        .finally(() => {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> Simpan Konfigurasi Layout'; if(window.lucide) lucide.createIcons(); }
        });
    }

    // Reset layout to defaults
    function resetLayout() {
        Swal.fire({
            title: 'Reset ke Default?',
            text: 'Semua konfigurasi layout komponen akan dikembalikan ke pengaturan awal.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal',
            background: '#0D1322',
            color: '#F1F5F9',
            confirmButtonColor: '#f59e0b'
        }).then(result => {
            if (result.isConfirmed) {
                // Check all toggles ON
                document.querySelectorAll('.section-toggle, .comp-enabled').forEach(inp => inp.checked = true);
                document.querySelectorAll('.comp-row').forEach(row => row.classList.remove('disabled'));
                renderWireframe();
                Swal.fire({ title: 'Reset Selesai', text: 'Klik "Simpan" untuk menerapkan.', icon: 'info', background: '#0D1322', color: '#F1F5F9' });
            }
        });
    }

    // Init disabled states on load
    document.querySelectorAll('.comp-enabled').forEach(inp => {
        const row = inp.closest('.comp-row');
        if (row) row.classList.toggle('disabled', !inp.checked);
    });

    // ─── IFRAME LIVE PREVIEW ───────────────────────────────────────────────

    let _iframeScalePct = 50; // default 50% so full portfolio fits in panel

    function loadPreviewIframe() {
        const urlInput = document.getElementById('preview-iframe-url');
        const iframe   = document.getElementById('portfolio-preview-iframe');
        const loading  = document.getElementById('preview-loading');
        if (!urlInput || !iframe) return;

        const url = urlInput.value.trim();
        if (!url) return;

        // Show loading
        if (loading) {
            loading.innerHTML = `
                <div class="flex flex-col items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center animate-pulse">
                        <i data-lucide="loader" class="w-5 h-5 text-emerald-400"></i>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Memuat...</p>
                    <p class="text-[10px] text-slate-600 font-mono">${url}</p>
                </div>`;
            loading.style.display = 'flex';
            if (window.lucide) lucide.createIcons();
        }

        // Apply scale
        applyIframeScale();

        iframe.src = url;
    }

    function applyIframeScale() {
        const iframe = document.getElementById('portfolio-preview-iframe');
        const btn    = document.getElementById('iframe-scale-btn');
        if (!iframe) return;

        const container = iframe.parentElement;
        const w = container ? container.offsetWidth  : 384;
        const h = container ? container.offsetHeight : 600;
        const scale = _iframeScalePct / 100;

        iframe.style.width           = (w / scale) + 'px';
        iframe.style.height          = (h / scale) + 'px';
        iframe.style.transform       = `scale(${scale})`;
        iframe.style.transformOrigin = 'top left';

        if (btn) btn.textContent = _iframeScalePct + '%';
    }

    function toggleIframeScale() {
        // Cycle: 50 → 75 → 100 → 50
        _iframeScalePct = _iframeScalePct === 50 ? 75 : (_iframeScalePct === 75 ? 100 : 50);
        applyIframeScale();
    }

    function openPreviewExternal() {
        const url = document.getElementById('preview-iframe-url')?.value?.trim();
        if (url) window.open(url, '_blank');
    }

    function handleIframeLoad() {
        const iframe  = document.getElementById('portfolio-preview-iframe');
        const loading = document.getElementById('preview-loading');
        // Hide loading if iframe actually loaded something
        if (iframe && iframe.src && iframe.src !== 'about:blank') {
            if (loading) loading.style.display = 'none';
        }
    }

    function handleIframeError() {
        const loading = document.getElementById('preview-loading');
        if (loading) {
            loading.innerHTML = `
                <div class="flex flex-col items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center">
                        <i data-lucide="wifi-off" class="w-5 h-5 text-rose-400"></i>
                    </div>
                    <p class="text-xs text-rose-400 font-semibold">Gagal memuat</p>
                    <p class="text-[10px] text-slate-600 text-center max-w-[160px]">Periksa URL atau pastikan dev server berjalan</p>
                </div>`;
            loading.style.display = 'flex';
            if (window.lucide) lucide.createIcons();
        }
    }

    // Auto-load on tab switch to layout-tab if URL already set
    const _origSwitchTab = typeof switchTab === 'function' ? switchTab : null;
</script>
</body>
</html>

