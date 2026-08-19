<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact">
    <div class="vf-page__body">

        <!-- Header -->
        <div class="vf-panel vf-card--elevated mb-6">
            <div class="vf-panel__header">
                <div class="vf-panel__heading">
                    <h1 class="vf-heading vf-heading--md flex items-center gap-2">
                        <i data-lucide="layers" class="w-6 h-6 text-cyan-400"></i> App &amp; Tenant Registry
                    </h1>
                    <p class="vf-panel__description mt-1">Registrasi aplikasi SaaS multi-tenant, penyediaan database terisolasi otomatis, dan pengujian koneksi.</p>
                </div>
                <div class="vf-panel__actions">
                    <?php 
                    $this->load->view('components/button', [
                        'text' => 'Register New Application',
                        'variant' => 'primary',
                        'icon' => 'plus-circle',
                        'attributes' => 'onclick="openRegisterAppModal()"'
                    ]); 
                    ?>
                </div>
            </div>
        </div>

        <!-- Applications Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if (empty($applications)): ?>
            <div class="col-span-full vf-panel vf-card--elevated text-center py-12 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center mx-auto text-cyan-400">
                    <i data-lucide="layers" class="w-8 h-8"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200">Belum Ada Aplikasi Terdaftar</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1 max-w-md mx-auto">Klik tombol di atas untuk mendaftarkan aplikasi multi-tenant baru Anda.</p>
                </div>
            </div>
            <?php else: ?>
            <?php foreach ($applications as $app): ?>
            <div class="vf-panel vf-card--elevated flex flex-col justify-between relative overflow-hidden group">
                <div class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <?php 
                            $this->load->view('components/badge', [
                                'text' => htmlspecialchars($app['category']),
                                'variant' => 'info',
                                'class' => 'uppercase text-[10px] tracking-wider'
                            ]); 
                            ?>
                            <h3 class="text-lg font-extrabold text-white mt-1.5"><?php echo htmlspecialchars($app['name']); ?></h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">slug: <?php echo htmlspecialchars($app['slug']); ?></p>
                        </div>
                        <?php 
                        $this->load->view('components/badge', [
                            'text' => strtoupper($app['status']),
                            'variant' => 'success',
                            'class' => 'rounded-full'
                        ]); 
                        ?>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300"><?php echo htmlspecialchars($app['description']); ?></p>

                    <!-- Database Provisioning Metadata Card -->
                    <div class="p-4 rounded-xl bg-white dark:bg-[#0A0E1A] border border-slate-200 dark:border-slate-800 space-y-2.5">
                        <div class="flex items-center justify-between text-xs font-mono">
                            <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                                <i data-lucide="database" class="w-4 h-4 text-cyan-400"></i> Isolated DB Name:
                            </span>
                            <span class="text-cyan-300 font-bold"><?php echo htmlspecialchars($app['db_name'] ?? 'db_' . str_replace('-', '_', $app['slug'])); ?></span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] font-mono text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800/60 pt-2">
                            <span>Provisioning Status: <strong class="text-emerald-400"><?php echo strtoupper($app['db_status'] ?? 'PROVISIONED'); ?></strong></span>
                            <span>Host: <?php echo htmlspecialchars($app['db_host'] ?? '127.0.0.1'); ?>:<?php echo htmlspecialchars($app['db_port'] ?? '3306'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <?php 
                        $this->load->view('components/button', [
                            'text' => 'Test DB',
                            'variant' => 'subtle',
                            'size' => 'sm',
                            'icon' => 'activity',
                            'class' => 'text-cyan-400',
                            'attributes' => 'onclick="testAppDatabase(\''.$app['id'].'\')"'
                        ]); 
                        ?>
                        <?php 
                        $this->load->view('components/button', [
                            'text' => 'Manage DB',
                            'variant' => 'subtle',
                            'size' => 'sm',
                            'icon' => 'database',
                            'class' => 'text-emerald-400 hover:bg-emerald-500/10 border-emerald-500/20',
                            'attributes' => 'onclick="window.location.href=\''.base_url('applications/database/'.$app['id']).'\'"'
                        ]); 
                        ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php 
                        $this->load->view('components/button', [
                            'text' => '',
                            'variant' => 'subtle',
                            'size' => 'sm',
                            'icon' => 'edit',
                            'class' => 'text-cyan-400 hover:bg-cyan-500/20 border-cyan-500/20',
                            'attributes' => 'onclick=\'openEditAppModal('.json_encode($app).')\' title="Edit Application"'
                        ]); 
                        ?>
                        <?php 
                        $this->load->view('components/button', [
                            'text' => '',
                            'variant' => 'danger',
                            'size' => 'sm',
                            'icon' => 'trash-2',
                            'class' => 'hover:bg-rose-500/20 border-rose-500/20',
                            'attributes' => 'onclick="deleteApp(\''.$app['id'].'\', \''.htmlspecialchars($app['name'], ENT_QUOTES).'\')" title="Delete Application"'
                        ]); 
                        ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Register App -->
<?php ob_start(); ?>
<form id="registerAppForm" onsubmit="registerNewApp(event)" class="vf-stack vf-stack--gap-md">
    <?php 
    $this->load->view('components/input', [
        'name' => 'name',
        'label' => 'Application Name *',
        'placeholder' => 'e.g. E-Commerce Multi-Tenant',
        'required' => true
    ]); 
    ?>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Category</label>
        <select name="category" class="vf-input w-full  focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            <option value="saas_tenant">SaaS Multi-Tenant</option>
            <option value="3d_portfolio">3D Portfolio</option>
            <option value="external_app">External Managed App</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Description</label>
        <textarea name="description" rows="3" placeholder="Deskripsi aplikasi atau spesifikasi tenant..." class="vf-input w-full  focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"></textarea>
    </div>

    <div class="space-y-4 pt-3 border-t border-slate-200 dark:border-slate-800">
        <h4 class="text-sm font-bold text-white flex items-center gap-2">
            <i data-lucide="database" class="w-4 h-4 text-cyan-400"></i> Manual Database Configuration
        </h4>
        
        <div class="grid grid-cols-2 gap-4">
            <?php 
            $this->load->view('components/input', [
                'name' => 'db_name',
                'label' => 'Database Name *',
                'placeholder' => 'e.g. saas_tenant_db',
                'required' => true
            ]); 
            ?>
            <div class="grid grid-cols-2 gap-2">
                <?php 
                $this->load->view('components/input', [
                    'name' => 'db_host',
                    'label' => 'Hostname',
                    'placeholder' => '127.0.0.1',
                    'value' => '127.0.0.1'
                ]); 
                ?>
                <?php 
                $this->load->view('components/input', [
                    'name' => 'db_port',
                    'label' => 'Port',
                    'placeholder' => '3306',
                    'value' => '3306'
                ]); 
                ?>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <?php 
            $this->load->view('components/input', [
                'name' => 'db_user',
                'label' => 'DB User *',
                'placeholder' => 'e.g. root',
                'required' => true
            ]); 
            ?>
            <?php 
            $this->load->view('components/input', [
                'name' => 'db_password',
                'type' => 'password',
                'label' => 'DB Password *',
                'placeholder' => 'Database password'
            ]); 
            ?>
        </div>
    </div>

    <div class="flex justify-end gap-2 pt-3 border-t border-slate-200 dark:border-slate-800 mt-4">
        <button type="button" onclick="closeRegisterAppModal()" class="vf-button vf-button--subtle px-5 py-2">Batal</button>
        <button type="submit" id="submitRegisterBtn" class="vf-button vf-button--primary px-5 py-2">
            <span class="vf-button__label flex items-center gap-1.5"><i data-lucide="check" class="w-4 h-4"></i> Register Application</span>
        </button>
    </div>
</form>
<?php 
$registerForm = ob_get_clean();
$this->load->view('components/modal', [
    'id' => 'registerAppModal',
    'title' => 'Register New Application',
    'icon' => 'layers',
    'hide_actions' => true,
    'content' => $registerForm,
    'onClose' => 'closeRegisterAppModal()'
]);
?>

<!-- Modal Edit App -->
<?php ob_start(); ?>
<form id="editAppForm" onsubmit="submitEditApp(event)" class="vf-stack vf-stack--gap-md">
    <input type="hidden" name="id" id="edit_app_id">

    <?php 
    $this->load->view('components/input', [
        'name' => 'name',
        'id' => 'edit_app_name',
        'label' => 'Application Name *',
        'required' => true
    ]); 
    ?>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Category</label>
            <select name="category" id="edit_app_category" class="vf-input w-full  focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                <option value="saas_tenant">SaaS Multi-Tenant</option>
                <option value="3d_portfolio">3D Portfolio</option>
                <option value="external_app">External Managed App</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Status</label>
            <select name="status" id="edit_app_status" class="vf-input w-full  focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                <option value="active">Active</option>
                <option value="maintenance">Maintenance</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Description</label>
        <textarea name="description" id="edit_app_description" rows="3" class="vf-input w-full  focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"></textarea>
    </div>

    <div class="space-y-4 pt-3 border-t border-slate-200 dark:border-slate-800">
        <h4 class="text-sm font-bold text-white flex items-center gap-2">
            <i data-lucide="database" class="w-4 h-4 text-cyan-400"></i> Database Configuration
        </h4>
        
        <div class="grid grid-cols-2 gap-4">
            <?php 
            $this->load->view('components/input', [
                'name' => 'db_name',
                'id' => 'edit_db_name',
                'label' => 'Database Name *',
                'required' => true
            ]); 
            ?>
            <div class="grid grid-cols-2 gap-2">
                <?php 
                $this->load->view('components/input', [
                    'name' => 'db_host',
                    'id' => 'edit_db_host',
                    'label' => 'Hostname',
                    'placeholder' => '127.0.0.1'
                ]); 
                ?>
                <?php 
                $this->load->view('components/input', [
                    'name' => 'db_port',
                    'id' => 'edit_db_port',
                    'label' => 'Port',
                    'placeholder' => '3306'
                ]); 
                ?>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <?php 
            $this->load->view('components/input', [
                'name' => 'db_user',
                'id' => 'edit_db_user',
                'label' => 'DB User *',
                'required' => true
            ]); 
            ?>
            <?php 
            $this->load->view('components/input', [
                'name' => 'db_password',
                'type' => 'password',
                'label' => 'DB Password',
                'placeholder' => 'Biarkan kosong jika tidak diubah'
            ]); 
            ?>
        </div>
    </div>

    <div class="flex justify-end gap-2 pt-3 border-t border-slate-200 dark:border-slate-800 mt-4">
        <button type="button" onclick="closeEditAppModal()" class="vf-button vf-button--subtle px-5 py-2">Batal</button>
        <button type="submit" id="submitEditAppBtn" class="vf-button vf-button--primary px-5 py-2">
            <span class="vf-button__label flex items-center gap-1.5"><i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan</span>
        </button>
    </div>
</form>
<?php 
$editForm = ob_get_clean();
$this->load->view('components/modal', [
    'id' => 'editAppModal',
    'title' => 'Edit Application Registry',
    'icon' => 'edit',
    'hide_actions' => true,
    'content' => $editForm,
    'onClose' => 'closeEditAppModal()'
]);
?>

<script>
function openRegisterAppModal() {
    document.getElementById('registerAppModal').classList.remove('hidden');
    document.getElementById('registerAppModal').classList.add('flex');
}

function closeRegisterAppModal() {
    document.getElementById('registerAppModal').classList.add('hidden');
    document.getElementById('registerAppModal').classList.remove('flex');
}

function registerNewApp(e) {
    e.preventDefault();
    const form = document.getElementById('registerAppForm');
    const formData = new FormData(form);
    const btn = document.getElementById('submitRegisterBtn');
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Provisioning...';

    fetch('<?php echo base_url("applications/create"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.application) {
            Swal.fire({
                title: 'Aplikasi & Database Berhasil Diprovisi!',
                html: `Aplikasi <strong>${data.application.name}</strong> telah terdaftar.<br><br>
                       <strong>Provisioned DB:</strong> <code>${data.provisioning_details ? data.provisioning_details.db_name : 'db_' + data.application.slug}</code><br>
                       <strong>DB User:</strong> <code>${data.provisioning_details ? data.provisioning_details.db_user : 'usr_app'}</code><br>
                       <strong>Raw Password:</strong> <code>${data.provisioning_details ? data.provisioning_details.raw_password : 'N/A'}</code>`,
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
        btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i> Register &amp; Provision DB';
    });
}

function testAppDatabase(appId) {
    Swal.fire({
        title: 'Menguji Koneksi DB...',
        text: 'Melakukan dekripsi AES-256 dan pengujian PDO...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); },
        background: '#0D1322',
        color: '#F1F5F9'
    });

    const formData = new FormData();
    formData.append('application_id', appId);

    fetch('<?php echo base_url("applications/test-db"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Koneksi Berhasil!',
                text: 'Koneksi ke database terisolasi berjalan sempurna.',
                icon: 'success',
                background: '#0D1322',
                color: '#F1F5F9',
                confirmButtonColor: '#10b981'
            });
        } else {
            Swal.fire({
                title: 'Koneksi Gagal',
                text: data.error || 'Gagal terhubung ke database.',
                icon: 'error',
                background: '#0D1322',
                color: '#F1F5F9'
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({ title: 'Error', text: 'Terjadi kesalahan sistem.', icon: 'error', background: '#0D1322', color: '#F1F5F9' });
    });
}
</script>


<script>
function openEditAppModal(app) {
    document.getElementById('edit_app_id').value = app.id;
    document.getElementById('edit_app_name').value = app.name;
    document.getElementById('edit_app_category').value = app.category;
    document.getElementById('edit_app_status').value = app.status;
    document.getElementById('edit_app_description').value = app.description || '';

    if(document.getElementById('edit_db_name')) document.getElementById('edit_db_name').value = app.db_name || '';
    if(document.getElementById('edit_db_host')) document.getElementById('edit_db_host').value = app.db_host || '';
    if(document.getElementById('edit_db_port')) document.getElementById('edit_db_port').value = app.db_port || '';
    if(document.getElementById('edit_db_user')) document.getElementById('edit_db_user').value = app.db_user || '';

    document.getElementById('editAppModal').classList.remove('hidden');
    document.getElementById('editAppModal').classList.add('flex');
}

function closeEditAppModal() {
    document.getElementById('editAppModal').classList.add('hidden');
    document.getElementById('editAppModal').classList.remove('flex');
}

function submitEditApp(e) {
    e.preventDefault();
    const form = document.getElementById('editAppForm');
    const formData = new FormData(form);
    const btn = document.getElementById('submitEditAppBtn');
    btn.disabled = true;

    fetch('<?php echo base_url("applications/update"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data aplikasi berhasil diperbarui.',
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

function deleteApp(appId, appName) {
    Swal.fire({
        title: 'Hapus Aplikasi Terdaftar?',
        html: `Apakah Anda yakin ingin menghapus aplikasi <strong>${appName}</strong>?<br><small class="text-rose-400">Tindakan ini tidak dapat dibatalkan.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        background: '#0D1322',
        color: '#F1F5F9',
        confirmButtonColor: '#f43f5e'
    }).then(result => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', appId);

            fetch('<?php echo base_url("applications/delete"); ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.message) {
                    Swal.fire({
                        title: 'Terhapus!',
                        text: 'Aplikasi terdaftar berhasil dihapus.',
                        icon: 'success',
                        background: '#0D1322',
                        color: '#F1F5F9',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({ title: 'Gagal', text: data.error || 'Terjadi kesalahan', icon: 'error', background: '#0D1322', color: '#F1F5F9' });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({ title: 'Error', text: 'Terjadi kesalahan sistem.', icon: 'error', background: '#0D1322', color: '#F1F5F9' });
            });
        }
    });
}
</script>
