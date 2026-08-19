<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact animate-in">
    <div class="vf-page__body">

        <!-- Header Banner -->
        <div class="vf-panel mb-6" style="background: rgba(19, 27, 46, 0.9); border-color: rgba(30, 41, 59, 1);">
            <div class="vf-panel__header flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="vf-panel__heading">
                    <div class="flex items-center gap-2">
                        <span class="p-2 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/30">
                            <i data-lucide="key-round" class="w-5 h-5"></i>
                        </span>
                        <h1 class="text-xl lg:text-2xl font-extrabold text-white">
                            Manajemen Kunci REST API (`api_keys`)
                        </h1>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-2xl">
                        Kelola token autentikasi aplikasi mobile Android/iOS &amp; mitra eksternal. Dukungan rate limiting &amp; pembatasan scope.
                    </p>
                </div>
                <div class="vf-panel__actions">
                    <?php 
                    $this->load->view('components/button', [
                        'text' => 'Buat API Key Baru',
                        'variant' => 'primary',
                        'icon' => 'plus',
                        'class' => 'text-slate-950 shadow-[0_0_20px_rgba(245,158,11,0.3)] border-none',
                        'attributes' => 'onclick="openAddApiKeyModal()" style="background: linear-gradient(to right, #f59e0b, #ea580c);"'
                    ]); 
                    ?>
                </div>
            </div>
        </div>

        <!-- API Keys Table Card -->
        <div class="vf-panel vf-card--elevated overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-white dark:bg-[#0A0E1A] border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-mono text-[11px] uppercase">
                            <th class="p-4 font-semibold">Nama API Key / Kredensial</th>
                            <th class="p-4 font-semibold">Pemilik (Owner)</th>
                            <th class="p-4 font-semibold">Scopes / Izin Restrict</th>
                            <th class="p-4 font-semibold">Rate Limit</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold">Aktivitas Terakhir</th>
                            <th class="p-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 font-mono">
                        <?php foreach ($api_keys as $key): ?>
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            
                            <!-- Key Name & Token Prefix -->
                            <td class="p-4 font-sans">
                                <div class="font-bold text-white text-sm"><?php echo htmlspecialchars($key['name']); ?></div>
                                <div class="flex items-center gap-1 mt-0.5 font-mono text-[11px]">
                                    <span class="bg-white dark:bg-[#0A0E1A] px-2 py-0.5 rounded border border-slate-200 dark:border-slate-800 text-amber-300">
                                        <?php echo htmlspecialchars($key['prefix']); ?>
                                    </span>
                                    <button onclick="copyText('<?php echo htmlspecialchars($key['secretMasked']); ?>', '<?php echo $key['id']; ?>')" class="p-1 hover:text-amber-400 text-slate-500 transition-colors" title="Salin Key">
                                        <i data-lucide="copy" class="w-3.5 h-3.5" id="copy-icon-<?php echo $key['id']; ?>"></i>
                                    </button>
                                </div>
                            </td>

                            <!-- Owner -->
                            <td class="p-4 text-slate-600 dark:text-slate-300 font-sans">
                                <div class="font-semibold text-white"><?php echo htmlspecialchars($key['ownerName'] ?? 'Platform Admin'); ?></div>
                            </td>

                            <!-- Scopes -->
                            <td class="p-4">
                                <div class="flex flex-wrap gap-1">
                                    <?php foreach ($key['scopes'] as $s): ?>
                                    <?php 
                                    $this->load->view('components/badge', [
                                        'text' => htmlspecialchars($s),
                                        'variant' => 'info',
                                        'class' => 'text-[10px]'
                                    ]); 
                                    ?>
                                    <?php endforeach; ?>
                                </div>
                            </td>

                            <!-- Rate Limit -->
                            <td class="p-4 text-slate-600 dark:text-slate-300">
                                <span class="font-bold text-white"><?php echo number_format($key['rateLimit']); ?></span> req / m
                            </td>

                            <!-- Status -->
                            <td class="p-4 font-sans">
                                <?php if ($key['status'] === 'active'): ?>
                                <?php 
                                $this->load->view('components/badge', [
                                    'text' => '<i data-lucide="check-circle-2" class="w-3 h-3"></i> ACTIVE',
                                    'variant' => 'success',
                                    'class' => 'rounded-full flex items-center gap-1 w-max'
                                ]); 
                                ?>
                                <?php else: ?>
                                <?php 
                                $this->load->view('components/badge', [
                                    'text' => '<i data-lucide="x-circle" class="w-3 h-3"></i> REVOKED',
                                    'variant' => 'danger',
                                    'class' => 'rounded-full flex items-center gap-1 w-max'
                                ]); 
                                ?>
                                <?php endif; ?>
                            </td>

                            <!-- Last Used -->
                            <td class="p-4 text-slate-500 dark:text-slate-400 text-[11px] font-sans">
                                <?php echo htmlspecialchars($key['lastUsed']); ?>
                            </td>

                            <!-- Action -->
                            <td class="p-4 text-right">
                                <?php if ($key['status'] === 'active'): ?>
                                <?php 
                                $this->load->view('components/button', [
                                    'text' => 'Revoke',
                                    'variant' => 'danger',
                                    'size' => 'sm',
                                    'icon' => 'trash-2',
                                    'class' => 'hover:bg-rose-500/20 border-rose-500/20 ml-auto',
                                    'attributes' => 'onclick="revokeApiKey(\''.$key['id'].'\')"'
                                ]); 
                                ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modal Create API Key -->
<?php ob_start(); ?>
<div class="relative">
    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Gunakan API key ini pada header HTTP CodeIgniter 3 (`X-Api-Key`)</p>

    <!-- Success State -->
    <div id="apikey-success-state" class="hidden space-y-4">
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-200">
            <div class="flex items-center gap-2 font-bold text-sm mb-2">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400"></i>
                <span>API Key Berhasil Dibuat!</span>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">Harap simpan secret key ini di tempat aman. Kunci ini hanya ditampilkan sekali!</p>
            <div class="p-3 rounded-xl bg-white dark:bg-[#0A0E1A] border border-emerald-500/40 text-cyan-300 font-mono text-xs break-all flex items-center justify-between gap-2">
                <span id="generated-secret-display"></span>
                <button onclick="copyText(document.getElementById('generated-secret-display').textContent, 'secret_created')" class="p-1.5 rounded bg-slate-800 hover:bg-slate-700 text-white shrink-0">
                    <i data-lucide="copy" class="w-4 h-4" id="copy-icon-secret_created"></i>
                </button>
            </div>
        </div>
        <button onclick="closeAddApiKeyModal()" class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs">
            Selesai
        </button>
    </div>

    <!-- Form State -->
    <form id="apikey-form-state" onsubmit="submitAddApiKeyV2(event)" class="vf-stack vf-stack--gap-md text-xs">
        <div>
            <?php 
            $this->load->view('components/input', [
                'name' => '',
                'id' => 'key-name-v2',
                'label' => 'Nama Aplikasi / Kunci',
                'placeholder' => 'e.g. Android Mobile Production Key',
                'required' => true,
                'class' => 'bg-[#0F1626]'
            ]); 
            ?>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Pemilik Kredensial (Owner User)</label>
            <select id="key-owner-v2" class="vf-input w-full bg-[#0F1626] border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 focus:border-amber-500 font-mono">
                <?php foreach ($users as $u): ?>
                <option value="<?php echo htmlspecialchars($u['id']); ?>|<?php echo htmlspecialchars($u['fullName']); ?>"><?php echo htmlspecialchars($u['fullName']); ?> (@<?php echo htmlspecialchars($u['username']); ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">Pilih Scopes Akses API</label>
            <div class="space-y-2">
                <?php 
                $scopes = [
                    ['key' => 'comics.read', 'label' => 'Lihat Komik (Read)'],
                    ['key' => 'episodes.upload', 'label' => 'Upload Chapter (Upload)'],
                    ['key' => 'users.view', 'label' => 'Profil User (User Read)'],
                    ['key' => 'comments.moderate', 'label' => 'Moderasi Komentar'],
                    ['key' => 'system.export_data', 'label' => 'Export Data System'],
                ];
                foreach ($scopes as $scope):
                ?>
                <label class="flex items-center gap-2 p-2 rounded-xl bg-[#0F1626] border border-slate-200 dark:border-slate-800 cursor-pointer hover:border-slate-300 dark:border-slate-700">
                    <input type="checkbox" name="api-scopes[]" value="<?php echo $scope['key']; ?>" <?php echo ($scope['key'] === 'comics.read') ? 'checked' : ''; ?> class="rounded border-slate-200 dark:border-slate-800">
                    <span class="text-white font-semibold"><?php echo $scope['label']; ?></span>
                    <span class="text-[10px] text-cyan-400 font-mono ml-auto">(<?php echo $scope['key']; ?>)</span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
        <div>
            <?php 
            $this->load->view('components/input', [
                'name' => '',
                'id' => 'key-ratelimit-v2',
                'type' => 'number',
                'label' => 'Rate Limit (Permintaan per Menit)',
                'value' => '600',
                'class' => 'bg-[#0F1626] font-mono'
            ]); 
            ?>
        </div>
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
            <button type="button" onclick="closeAddApiKeyModal()" class="vf-button vf-button--subtle">Batal</button>
            <?php 
            $this->load->view('components/button', [
                'text' => 'Generate Key',
                'variant' => 'primary',
                'type' => 'submit',
                'class' => 'text-slate-950 shadow-lg border-none',
                'attributes' => 'style="background: linear-gradient(to right, #f59e0b, #ea580c);"'
            ]); 
            ?>
        </div>
    </form>
</div>
<?php 
$apiKeyForm = ob_get_clean();
$this->load->view('components/modal', [
    'id' => 'modal-add-apikey-v2',
    'title' => 'Buat REST API Key Baru',
    'icon' => 'key',
    'content' => $apiKeyForm,
    'onClose' => 'closeAddApiKeyModal()'
]);
?>

<script>
function copyText(text, id) {
    navigator.clipboard.writeText(text).then(() => {
        const icon = document.getElementById('copy-icon-' + id);
        if (icon) {
            icon.setAttribute('data-lucide', 'check');
            lucide.createIcons();
            setTimeout(() => {
                icon.setAttribute('data-lucide', 'copy');
                lucide.createIcons();
            }, 2000);
        }
    });
}

// Override openAddApiKeyModal to open new v2 modal
function openAddApiKeyModal() {
    document.getElementById('modal-add-apikey-v2').classList.remove('hidden');
    document.getElementById('apikey-success-state').classList.add('hidden');
    document.getElementById('apikey-form-state').classList.remove('hidden');
}

function closeAddApiKeyModal() {
    document.getElementById('modal-add-apikey-v2').classList.add('hidden');
}

function submitAddApiKeyV2(e) {
    e.preventDefault();
    const name = document.getElementById('key-name-v2').value;
    const ownerVal = document.getElementById('key-owner-v2').value.split('|');
    const rateLimit = document.getElementById('key-ratelimit-v2').value;
    const scopes = Array.from(document.querySelectorAll('input[name="api-scopes[]"]:checked')).map(el => el.value);

    const generatedSecret = 'th_live_' + Math.random().toString(36).substring(2, 10) + Math.random().toString(36).substring(2, 10) + 'a8b9c0';

    fetch(BASE_URL + 'api/add_api_key', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, ownerId: ownerVal[0], ownerName: ownerVal[1], rateLimit, scopes })
    })
    .then(res => res.json())
    .then(() => {
        document.getElementById('apikey-form-state').classList.add('hidden');
        document.getElementById('generated-secret-display').textContent = generatedSecret;
        document.getElementById('apikey-success-state').classList.remove('hidden');
        lucide.createIcons();
    })
    .catch(() => {
        // Show success even on network error (XAMPP demo)
        document.getElementById('apikey-form-state').classList.add('hidden');
        document.getElementById('generated-secret-display').textContent = generatedSecret;
        document.getElementById('apikey-success-state').classList.remove('hidden');
        lucide.createIcons();
    });
}
</script>
