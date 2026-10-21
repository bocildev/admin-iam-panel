<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact animate-in">
    <div class="vf-page__body">

        <!-- Header Banner -->
        <div class="rounded-2xl bg-[#131B2E] border border-[#1E293B] p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="p-2.5 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/30">
                            <i data-lucide="key-round" class="w-5 h-5"></i>
                        </span>
                        <h1 class="text-xl lg:text-2xl font-extrabold text-white">
                            Manajemen Kunci &amp; Token REST API (`api_keys`)
                        </h1>
                    </div>
                    <p class="text-xs text-slate-400 mt-2 max-w-2xl">
                        Kelola token autentikasi aplikasi klien (Portofolio Nuxt 3, Mobile App, &amp; Layanan Eksternal). Sistem mendukung validasi token Bearer, rate limiting, pembatasan scope, dan pencabutan hak akses (*revocation*).
                    </p>
                </div>
                <div>
                    <button onclick="openAddApiKeyModal()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white font-bold text-xs transition shadow-lg shadow-amber-500/20 border border-amber-500/40 cursor-pointer flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Buat API Token Baru</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- API Keys Table Card -->
        <div class="rounded-2xl bg-[#131B2E] border border-[#1E293B] overflow-hidden shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#0A0E1A]/80 border-b border-[#1E293B] text-slate-400 font-mono text-[11px] uppercase">
                            <th class="p-4 font-semibold">Nama Token / Kredensial</th>
                            <th class="p-4 font-semibold">Aplikasi Terdaftar</th>
                            <th class="p-4 font-semibold">Scopes / Izin Restrict</th>
                            <th class="p-4 font-semibold">Rate Limit</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold">Aktivitas Terakhir</th>
                            <th class="p-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B] font-mono">
                        <?php if (empty($api_keys)): ?>
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 font-sans">
                                Belum ada REST API Key yang dibuat. Klik tombol "Buat API Token Baru" untuk membuat kredensial baru.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($api_keys as $key): ?>
                        <tr class="hover:bg-[#1E293B]/40 transition-colors">
                            
                            <!-- Key Name & Token Prefix -->
                            <td class="p-4 font-sans">
                                <div class="font-bold text-white text-sm"><?php echo htmlspecialchars($key['name']); ?></div>
                                <div class="flex items-center gap-1.5 mt-1 font-mono text-[11px]">
                                    <span class="bg-[#0A0E1A] px-2 py-0.5 rounded border border-[#1E293B] text-amber-300">
                                        <?php echo htmlspecialchars($key['prefix']); ?>
                                    </span>
                                    <button onclick="copyText('<?php echo htmlspecialchars($key['secretKey']); ?>', '<?php echo $key['id']; ?>')" class="p-1 hover:text-amber-400 text-slate-400 transition-colors" title="Salin Full API Token">
                                        <i data-lucide="copy" class="w-3.5 h-3.5" id="copy-icon-<?php echo $key['id']; ?>"></i>
                                    </button>
                                </div>
                            </td>

                            <!-- Registered Application -->
                            <td class="p-4 text-slate-300 font-sans">
                                <?php if (!empty($key['applicationName']) && $key['applicationName'] !== 'Standalone / External Client'): ?>
                                <div class="font-semibold text-cyan-300 flex items-center gap-1.5">
                                    <i data-lucide="app-window" class="w-3.5 h-3.5 text-cyan-400"></i>
                                    <span><?php echo htmlspecialchars($key['applicationName']); ?></span>
                                </div>
                                <span class="text-[10px] text-slate-500 font-mono">ID: <?php echo htmlspecialchars($key['applicationId']); ?></span>
                                <?php else: ?>
                                <span class="text-slate-400 font-mono text-[11px]">External / Standalone</span>
                                <?php endif; ?>
                            </td>

                            <!-- Scopes -->
                            <td class="p-4">
                                <div class="flex flex-wrap gap-1">
                                    <?php foreach ($key['scopes'] as $s): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono bg-cyan-500/10 text-cyan-300 border border-cyan-500/30">
                                        <?php echo htmlspecialchars($s); ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            </td>

                            <!-- Rate Limit -->
                            <td class="p-4 text-slate-300">
                                <span class="font-bold text-white"><?php echo number_format($key['rateLimit']); ?></span> req / m
                            </td>

                            <!-- Status -->
                            <td class="p-4 font-sans">
                                <?php if ($key['status'] === 'active'): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> ACTIVE
                                </span>
                                <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30 uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> REVOKED
                                </span>
                                <?php endif; ?>
                            </td>

                            <!-- Last Used -->
                            <td class="p-4 text-slate-400 text-[11px] font-mono">
                                <?php echo htmlspecialchars($key['lastUsed']); ?>
                            </td>

                            <!-- Action -->
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <?php if ($key['status'] === 'active'): ?>
                                    <button onclick="regenerateApiKey('<?php echo $key['id']; ?>')" class="px-2.5 py-1.5 rounded-lg bg-[#0A0E1A] border border-cyan-500/30 hover:border-cyan-400 text-cyan-400 hover:text-cyan-300 text-[11px] font-semibold transition flex items-center gap-1 cursor-pointer" title="Regenerate Token Baru">
                                        <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                        <span>Regen</span>
                                    </button>
                                    <button onclick="revokeApiKey('<?php echo $key['id']; ?>')" class="px-2.5 py-1.5 rounded-lg bg-[#0A0E1A] border border-rose-500/30 hover:border-rose-400 text-rose-400 hover:text-rose-300 text-[11px] font-semibold transition flex items-center gap-1 cursor-pointer" title="Revoke Token">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        <span>Revoke</span>
                                    </button>
                                    <?php else: ?>
                                    <span class="text-slate-500 text-[11px] font-mono">Dicabut</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modal Create API Key -->
<?php ob_start(); ?>
<div class="relative">
    <p class="text-xs text-slate-400 mb-4">Gunakan API token ini pada header HTTP klien: <code class="text-cyan-300">Authorization: Bearer &lt;token&gt;</code> atau <code class="text-cyan-300">X-API-Key: &lt;token&gt;</code></p>

    <!-- Success State -->
    <div id="apikey-success-state" class="hidden space-y-4">
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-200">
            <div class="flex items-center gap-2 font-bold text-sm mb-2">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400"></i>
                <span>API Token Berhasil Dibuat!</span>
            </div>
            <p class="text-xs text-slate-300 mb-3">Salin dan simpan secret key ini ke file <code class="text-amber-300">.env</code> aplikasi klien Anda (<code class="text-amber-300">IAM_API_TOKEN</code>):</p>
            <div class="p-3 rounded-xl bg-[#0A0E1A] border border-emerald-500/40 text-cyan-300 font-mono text-xs break-all flex items-center justify-between gap-2">
                <span id="generated-secret-display"></span>
                <button onclick="copyText(document.getElementById('generated-secret-display').textContent, 'secret_created')" class="p-1.5 rounded bg-[#1E293B] hover:bg-[#253147] text-white shrink-0 cursor-pointer">
                    <i data-lucide="copy" class="w-4 h-4" id="copy-icon-secret_created"></i>
                </button>
            </div>
        </div>
        <button onclick="window.location.reload()" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white font-bold text-xs shadow-md cursor-pointer">
            Selesai &amp; Muat Ulang
        </button>
    </div>

    <!-- Form State -->
    <form id="apikey-form-state" onsubmit="submitAddApiKeyV2(event)" class="vf-stack vf-stack--gap-md text-xs">
        <div>
            <?php 
            $this->load->view('components/input', [
                'name' => '',
                'id' => 'key-name-v2',
                'label' => 'Nama Token / Kredensial',
                'placeholder' => 'e.g. Motion Portfolio Nuxt Client Key',
                'required' => true,
                'class' => 'bg-[#0A0E1A]'
            ]); 
            ?>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1">Aplikasi Terdaftar (Registered Application)</label>
            <select id="key-application-id" class="vf-input w-full bg-[#0A0E1A] border-[#1E293B] text-slate-200 focus:border-cyan-500 font-mono">
                <option value="">-- Klien Eksternal / Standalone --</option>
                <?php if (!empty($applications)): ?>
                    <?php foreach ($applications as $app): ?>
                    <option value="<?php echo htmlspecialchars($app['id']); ?>" <?php echo ($app['slug'] === 'motion-portfolio') ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($app['name']); ?> (slug: <?php echo htmlspecialchars($app['slug']); ?>)
                    </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1">Pemilik Kredensial (Owner User)</label>
            <select id="key-owner-v2" class="vf-input w-full bg-[#0A0E1A] border-[#1E293B] text-slate-200 focus:border-cyan-500 font-mono">
                <?php foreach ($users as $u): ?>
                <option value="<?php echo htmlspecialchars($u['id']); ?>|<?php echo htmlspecialchars($u['email'] ?? $u['fullName']); ?>"><?php echo htmlspecialchars($u['fullName']); ?> (@<?php echo htmlspecialchars($u['username']); ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-2">Pilih Scopes Akses API</label>
            <div class="space-y-2">
                <?php 
                $scopes = [
                    ['key' => 'portfolio:read', 'label' => 'Baca Profil, Proyek, & Skills Portofolio (Read)', 'checked' => true],
                    ['key' => 'portfolio:write', 'label' => 'Simpan & Update Proyek/Config Portofolio (Write)', 'checked' => true],
                    ['key' => '*', 'label' => 'Akses Lengkap / Seluruh Endpoint (Super Admin Scope)', 'checked' => false],
                    ['key' => 'comics.read', 'label' => 'Lihat Komik ToonHub (Read)', 'checked' => false],
                    ['key' => 'users.view', 'label' => 'Profil Pengguna IAM (User Read)', 'checked' => false]
                ];
                foreach ($scopes as $scope):
                ?>
                <label class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#0A0E1A] border border-[#1E293B] cursor-pointer hover:border-slate-700 transition-colors">
                    <input type="checkbox" name="api-scopes[]" value="<?php echo $scope['key']; ?>" <?php echo $scope['checked'] ? 'checked' : ''; ?> class="rounded border-[#1E293B] text-cyan-500 focus:ring-0">
                    <span class="text-slate-200 font-semibold"><?php echo $scope['label']; ?></span>
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
                'class' => 'bg-[#0A0E1A] font-mono'
            ]); 
            ?>
        </div>
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#1E293B]">
            <button type="button" onclick="closeAddApiKeyModal()" class="vf-button vf-button--subtle">Batal</button>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white font-bold text-xs shadow-md border border-amber-500/40 cursor-pointer">
                Generate Token
            </button>
        </div>
    </form>
</div>
<?php 
$apiKeyForm = ob_get_clean();
$this->load->view('components/modal', [
    'id' => 'modal-add-apikey-v2',
    'title' => 'Buat REST API Token Baru',
    'icon' => 'key',
    'content' => $apiKeyForm,
    'onClose' => 'closeAddApiKeyModal()'
]);
?>

<script>
function copyText(text, id) {
    if (!text) return;
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
        alert('API Token berhasil disalin ke clipboard:\n' + text);
    });
}

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
    const applicationId = document.getElementById('key-application-id').value;
    const ownerVal = document.getElementById('key-owner-v2').value.split('|');
    const rateLimit = document.getElementById('key-ratelimit-v2').value;
    const scopes = Array.from(document.querySelectorAll('input[name="api-scopes[]"]:checked')).map(el => el.value);

    fetch(BASE_URL + 'api/keys/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            name, 
            applicationId, 
            ownerId: ownerVal[0], 
            ownerEmail: ownerVal[1], 
            rateLimit, 
            scopes 
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.key) {
            const raw = data.key.rawToken || data.key.secretKey;
            document.getElementById('apikey-form-state').classList.add('hidden');
            document.getElementById('generated-secret-display').textContent = raw;
            document.getElementById('apikey-success-state').classList.remove('hidden');
            lucide.createIcons();
        } else {
            alert('Gagal membuat API key: ' + (data.error || 'Terjadi kesalahan'));
        }
    })
    .catch(err => {
        alert('Terjadi kesalahan jaringan saat membuat API Key: ' + err.message);
    });
}

function regenerateApiKey(keyId) {
    if (!confirm('Apakah Anda yakin ingin memperbarui (regenerate) token ini? Token lama akan langsung tidak berlaku!')) {
        return;
    }
    fetch(BASE_URL + 'api/keys/regenerate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ keyId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.key) {
            alert('API Token berhasil di-regenerate!\nToken baru: ' + (data.key.rawToken || data.key.secretKey));
            window.location.reload();
        } else {
            alert('Gagal me-regenerate token: ' + (data.error || 'Kesalahan'));
        }
    })
    .catch(err => alert('Kesalahan: ' + err.message));
}

function revokeApiKey(keyId) {
    if (!confirm('Apakah Anda yakin ingin mencabut (revoke) API key ini? Klien yang menggunakan key ini tidak akan bisa mengakses API lagi.')) {
        return;
    }
    fetch(BASE_URL + 'api/keys/revoke', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ keyId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('API Key berhasil dicabut.');
            window.location.reload();
        } else {
            alert('Gagal mencabut API key: ' + (data.error || 'Kesalahan'));
        }
    })
    .catch(err => alert('Kesalahan: ' + err.message));
}
</script>
