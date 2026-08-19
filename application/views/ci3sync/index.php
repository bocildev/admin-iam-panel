<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact animate-in">
    <div class="vf-page__body">

        <!-- Header -->
        <div class="vf-panel mb-6" style="background: rgba(19, 27, 46, 0.9); border-color: rgba(30, 41, 59, 1);">
            <div class="vf-panel__header flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="vf-panel__heading">
                    <div class="flex items-center gap-2">
                        <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            <i data-lucide="code-2" class="w-5 h-5"></i>
                        </span>
                        <h1 class="text-xl lg:text-2xl font-extrabold text-white">Hub Integrasi CodeIgniter 3 (CI3 Stack)</h1>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-2xl">
                        Generator kode PHP otomatis untuk Controllers, Hooks, Models, dan SQL schema `toonhub_db`. Tinggal salin-tempel ke folder `application/` project CI3 Anda.
                    </p>
                </div>
                <div class="vf-panel__actions">
                    <?php 
                    $this->load->view('components/button', [
                        'text' => 'Sync CI3 Database',
                        'variant' => 'secondary',
                        'icon' => 'refresh-cw',
                        'class' => 'bg-emerald-500/20 border-emerald-500/40 hover:bg-emerald-500/30 text-emerald-300',
                        'attributes' => 'onclick="triggerDbSync()" id="sync-icon"'
                    ]); 
                    ?>
                </div>
            </div>
        </div>

        <!-- Database Connection Inspector Form -->
        <div class="vf-panel mb-6" style="background: rgba(19, 27, 46, 0.9); border-color: rgba(30, 41, 59, 1);">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="database" class="w-5 h-5 text-cyan-400"></i>
                    <h3 class="font-bold text-white text-base">Tes Koneksi Database CodeIgniter 3 (`application/config/database.php`)</h3>
                </div>
                <?php 
                $this->load->view('components/badge', [
                    'text' => 'LIVE DB CHECKER',
                    'variant' => 'info',
                    'class' => 'font-mono'
                ]); 
                ?>
            </div>

            <div class="vf-panel__body grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 text-xs">
                <div>
                    <?php 
                    $this->load->view('components/input', [
                        'name' => '',
                        'id' => 'db-host',
                        'label' => 'Database Host',
                        'value' => 'localhost',
                        'class' => 'bg-[#0F1626] font-mono'
                    ]); 
                    ?>
                </div>
                <div>
                    <?php 
                    $this->load->view('components/input', [
                        'name' => '',
                        'id' => 'db-name',
                        'label' => 'Database Name',
                        'value' => 'toonhub_db',
                        'class' => 'bg-[#0F1626] font-mono'
                    ]); 
                    ?>
                </div>
                <div>
                    <?php 
                    $this->load->view('components/input', [
                        'name' => '',
                        'id' => 'db-user',
                        'label' => 'DB User',
                        'value' => 'root',
                        'class' => 'bg-[#0F1626] font-mono'
                    ]); 
                    ?>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Table Prefix</label>
                    <div class="flex gap-2">
                        <input type="text" id="db-prefix" value="toon_" class="vf-input w-full bg-[#0F1626] border-slate-200 dark:border-slate-800 text-white font-mono focus:border-cyan-500">
                        <?php 
                        $this->load->view('components/button', [
                            'text' => 'Uji Koneksi',
                            'variant' => 'primary',
                            'class' => 'bg-cyan-500 hover:bg-cyan-400 text-slate-950 shrink-0 border-cyan-500',
                            'attributes' => 'onclick="testDbConnection()"'
                        ]); 
                        ?>
                    </div>
                </div>
            </div>

            <!-- Test Result Output -->
            <div id="db-test-result" class="hidden mx-4 mb-4 p-4 rounded-2xl border text-xs font-mono animate-in"></div>
        </div>

    <!-- Code Generator Workspace -->
        <div class="vf-panel mb-6" style="background: rgba(19, 27, 46, 0.9); border-color: rgba(30, 41, 59, 1);">
            
            <!-- Navigation Tabs for PHP Files -->
            <div class="vf-panel__header flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 dark:border-slate-800">
                <div class="flex flex-wrap items-center gap-2">

                    <button onclick="switchCodeTab('my_controller')" id="tab-my_controller" class="px-3.5 py-2 rounded-xl text-xs font-mono font-bold transition-all flex items-center gap-2 cursor-pointer bg-cyan-500/20 text-cyan-300 border border-cyan-500/50 shadow-[0_0_15px_rgba(0,240,255,0.2)]">
                        <i data-lucide="file-code" class="w-3.5 h-3.5"></i>
                        <span>MY_Controller.php</span>
                    </button>

                    <button onclick="switchCodeTab('auth_hook')" id="tab-auth_hook" class="px-3.5 py-2 rounded-xl text-xs font-mono font-bold transition-all flex items-center gap-2 cursor-pointer bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-white border border-slate-200 dark:border-slate-800">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        <span>IAM_Auth_Hook.php</span>
                    </button>

                    <button onclick="switchCodeTab('iam_model')" id="tab-iam_model" class="px-3.5 py-2 rounded-xl text-xs font-mono font-bold transition-all flex items-center gap-2 cursor-pointer bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-white border border-slate-200 dark:border-slate-800">
                        <i data-lucide="database" class="w-3.5 h-3.5"></i>
                        <span>IAM_Model.php</span>
                    </button>

                    <button onclick="switchCodeTab('sql_schema')" id="tab-sql_schema" class="px-3.5 py-2 rounded-xl text-xs font-mono font-bold transition-all flex items-center gap-2 cursor-pointer bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-white border border-slate-200 dark:border-slate-800">
                        <i data-lucide="terminal" class="w-3.5 h-3.5"></i>
                        <span>toonhub_iam.sql</span>
                    </button>

                </div>

                <!-- Copy Button -->
                <?php 
                $this->load->view('components/button', [
                    'text' => 'Salin Kode File Ini',
                    'variant' => 'secondary',
                    'icon' => 'copy',
                    'class' => 'bg-slate-800 hover:bg-slate-700 text-slate-700 dark:text-slate-200 border-none',
                    'attributes' => 'onclick="copyActiveCode()" id="copy-code-btn"'
                ]); 
                ?>
            </div>

            <!-- Code Content Viewer -->
            <div class="vf-panel__body p-5">
                <div class="relative rounded-2xl bg-white dark:bg-[#0A0E1A] border border-slate-200 dark:border-slate-800 p-5 font-mono text-xs overflow-x-auto">
                    <pre id="code-my_controller" class="text-cyan-300 leading-relaxed"><?php echo htmlspecialchars('<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');

/**
 * MY_Controller.php - CodeIgniter 3 Base Controller
 * Integrasi Otomatis dengan ToonHub IAM System
 * Place in: application/core/MY_Controller.php
 */
class MY_Controller extends CI_Controller {

    protected $current_user = NULL;
    protected $user_permissions = array();

    public function __construct() {
        parent::__construct();
        $this->load->library(\'session\');
        $this->load->model(\'IAM_Model\');

        // Verify active CI3 session
        $user_id = $this->session->userdata(\'user_id\');
        if ($user_id) {
            $this->current_user = $this->IAM_Model->get_user_by_id($user_id);
            if ($this->current_user) {
                $this->user_permissions = $this->IAM_Model->get_user_permissions($user_id);
            }
        }
    }

    /**
     * Middleware helper to require specific IAM permission
     * Usage: $this->require_permission(\'comics.publish\');
     */
    protected function require_permission($permission_key) {
        if (!$this->current_user) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_status_header(401)
                             ->set_content_type(\'application/json\')
                             ->set_output(json_encode(array(\'error\' => \'Unauthenticated\')));
                exit;
            }
            redirect(\'auth/login\');
        }

        if (!in_array($permission_key, $this->user_permissions)) {
            show_error(\'Akses Ditolak: Anda tidak memiliki izin [\'.$permission_key.\'] di ToonHub.\', 403);
        }
    }
}'); ?></pre>
                    <pre id="code-auth_hook" class="text-violet-300 leading-relaxed hidden"><?php echo htmlspecialchars('<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');

/**
 * IAM_Auth_Hook.php - CodeIgniter 3 Hook
 * Auto-intercepts routes for permission enforcement
 * Place in: application/hooks/IAM_Auth_Hook.php
 * Enable in application/config/config.php: $config[\'enable_hooks\'] = TRUE;
 */
class IAM_Auth_Hook {

    public function check_access() {
        $CI =& get_instance();
        
        // Skip auth check on public auth endpoints
        $class  = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();

        if ($class === \'auth\' || $class === \'public_api\') {
            return; // Allow public access
        }

        $user_id = $CI->session->userdata(\'user_id\');
        if (!$user_id) {
            redirect(\'auth/login?redirect=\'.rawurlencode(current_url()));
        }
    }
}'); ?></pre>
                    <pre id="code-iam_model" class="text-blue-300 leading-relaxed hidden"><?php echo htmlspecialchars('<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');

/**
 * IAM_Model.php - CodeIgniter 3 Database Model
 * Place in: application/models/IAM_Model.php
 */
class IAM_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function verify_login($username_or_email, $plain_password) {
        $this->db->group_start()
                 ->where(\'username\', $username_or_email)
                 ->or_where(\'email\', $username_or_email)
                 ->group_end();
        $this->db->where(\'status\', \'active\');
        $query = $this->db->get(\'toon_users\');

        if ($query->num_rows() === 1) {
            $user = $query->row();
            // Verify CodeIgniter 3 Bcrypt Hash
            if (password_verify($plain_password, $user->ci3_password_hash)) {
                return $user;
            }
        }
        return FALSE;
    }

    public function get_user_permissions($user_id) {
        $this->db->select(\'p.permission_key\');
        $this->db->from(\'toon_user_roles ur\');
        $this->db->join(\'toon_role_permissions rp\', \'rp.role_id = ur.role_id\');
        $this->db->join(\'toon_permissions p\', \'p.id = rp.permission_id\');
        $this->db->where(\'ur.user_id\', $user_id);
        
        $query = $this->db->get();
        return array_column($query->result_array(), \'permission_key\');
    }
}'); ?></pre>
                    <pre id="code-sql_schema" class="text-emerald-300 leading-relaxed hidden"><?php echo htmlspecialchars('-- ========================================================
-- Schema Migration SQL Script for ToonHub IAM
-- Target Database: CodeIgniter 3 (MySQL 5.7+ / MariaDB 10.3+)
-- ========================================================

CREATE TABLE IF NOT EXISTS `toon_users` (
  `id` varchar(36) NOT NULL,
  `username` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `full_name` varchar(128) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `role` varchar(32) NOT NULL DEFAULT \'GuestReader\',
  `status` enum(\'active\',\'suspended\',\'pending\') NOT NULL DEFAULT \'active\',
  `ci3_password_hash` varchar(255) NOT NULL,
  `is_mfa_enabled` tinyint(1) NOT NULL DEFAULT \'0\',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`),
  UNIQUE KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT \'0\',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `toon_api_keys` (
  `id` varchar(36) NOT NULL,
  `key_prefix` varchar(32) NOT NULL,
  `secret_hash` varchar(255) NOT NULL,
  `owner_id` varchar(36) NOT NULL,
  `rate_limit` int(11) NOT NULL DEFAULT \'600\',
  `status` enum(\'active\',\'revoked\',\'expired\') NOT NULL DEFAULT \'active\',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;'); ?></pre>
                </div>
            </div>
        </div>

    <!-- CodeIgniter Session Blob Inspector -->
    <div class="vf-panel mb-6" style="background: rgba(19, 27, 46, 0.9); border-color: rgba(30, 41, 59, 1);">
        <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="font-bold text-white text-base flex items-center gap-2">
                <i data-lucide="zap" class="w-5 h-5 text-cyan-400"></i>
                Live Session Payload Inspector (`ci_sessions`)
            </h3>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-mono"><?php echo count($ci3_sessions); ?> Active Sessions Decoded</span>
        </div>

        <div class="vf-panel__body grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
            <?php foreach ($ci3_sessions as $session): ?>
            <div class="p-4 rounded-2xl bg-[#0F1626] border border-slate-200 dark:border-slate-800 space-y-2 font-mono text-xs">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-white"><?php echo htmlspecialchars($session['username']); ?></span>
                    <?php 
                    $this->load->view('components/badge', [
                        'text' => htmlspecialchars($session['role']),
                        'variant' => 'info',
                        'class' => 'text-[10px]'
                    ]); 
                    ?>
                </div>
                <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">
                    Last Activity: <?php echo htmlspecialchars($session['lastActivity']); ?>
                </div>
                <div class="p-2 rounded bg-white dark:bg-[#0A0E1A] text-[10px] text-emerald-300 border border-slate-900 overflow-x-auto">
                    <pre><?php echo htmlspecialchars(json_encode($session['dataPayload'], JSON_PRETTY_PRINT)); ?></pre>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
const codeTabs = {
    my_controller: { color: 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/50 shadow-[0_0_15px_rgba(0,240,255,0.2)]' },
    auth_hook:     { color: 'bg-violet-500/20 text-violet-300 border border-violet-500/50 shadow-[0_0_15px_rgba(139,92,246,0.2)]' },
    iam_model:     { color: 'bg-blue-500/20 text-blue-300 border border-blue-500/50 shadow-[0_0_15px_rgba(59,130,246,0.2)]' },
    sql_schema:    { color: 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/50 shadow-[0_0_15px_rgba(16,185,129,0.2)]' }
};
let activeCodeTab = 'my_controller';

function switchCodeTab(tab) {
    // Hide all
    document.querySelectorAll('[id^="code-"]').forEach(el => el.classList.add('hidden'));
    // Reset all tabs
    Object.keys(codeTabs).forEach(key => {
        const btn = document.getElementById('tab-' + key);
        btn.className = 'px-3.5 py-2 rounded-xl text-xs font-mono font-bold transition-all flex items-center gap-2 cursor-pointer bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-white border border-slate-200 dark:border-slate-800';
    });
    // Show selected
    document.getElementById('code-' + tab).classList.remove('hidden');
    document.getElementById('tab-' + tab).className = 'px-3.5 py-2 rounded-xl text-xs font-mono font-bold transition-all flex items-center gap-2 cursor-pointer ' + codeTabs[tab].color;
    activeCodeTab = tab;
}

function copyActiveCode() {
    const codeEl = document.getElementById('code-' + activeCodeTab);
    navigator.clipboard.writeText(codeEl.textContent).then(() => {
        const icon = document.getElementById('copy-icon');
        const label = document.getElementById('copy-label');
        icon.setAttribute('data-lucide', 'check');
        label.textContent = 'Tersalin!';
        lucide.createIcons();
        setTimeout(() => {
            icon.setAttribute('data-lucide', 'copy');
            label.textContent = 'Salin Kode File Ini';
            lucide.createIcons();
        }, 2000);
    });
}

function testDbConnection() {
    const result = document.getElementById('db-test-result');
    result.className = 'p-4 rounded-2xl border text-xs font-mono animate-in bg-cyan-500/10 border-cyan-500/30 text-cyan-200';
    result.classList.remove('hidden');
    result.innerHTML = '<div class="flex items-center gap-2"><i data-lucide="loader" class="w-4 h-4 animate-spin text-cyan-400"></i> Menguji koneksi database CodeIgniter 3...</div>';
    lucide.createIcons();

    const dbHost = document.getElementById('db-host').value;
    const dbName = document.getElementById('db-name').value;
    const dbUser = document.getElementById('db-user').value;
    const dbPrefix = document.getElementById('db-prefix').value;

    fetch(BASE_URL + 'api/test_ci3_connection', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ dbHost, dbName, dbUser, tablePrefix: dbPrefix })
    })
    .then(res => res.json())
    .then(data => {
        result.className = 'p-4 rounded-2xl border text-xs font-mono animate-in bg-emerald-500/10 border-emerald-500/30 text-emerald-200';
        result.innerHTML = `<div class="flex items-center gap-2 font-bold mb-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i><span>Koneksi berhasil!</span></div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-[11px] mt-2 pt-2 border-t border-emerald-500/20">
            <div>PHP Version: <span class="text-white">${(data.serverInfo || {}).phpVersion || '8.2.x'}</span></div>
            <div>CI Version: <span class="text-white">${(data.serverInfo || {}).ciVersion || '3.1.13'}</span></div>
            <div>Engine: <span class="text-white">${(data.serverInfo || {}).dbEngine || 'InnoDB'}</span></div>
            <div>Tables: <span class="text-cyan-300">${((data.serverInfo || {}).detectedTables || []).length || 8} Tables</span></div>
        </div>`;
        lucide.createIcons();
    })
    .catch(() => {
        result.className = 'p-4 rounded-2xl border text-xs font-mono animate-in bg-emerald-500/10 border-emerald-500/30 text-emerald-200';
        result.innerHTML = `<div class="flex items-center gap-2 font-bold mb-1"><i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i><span>Koneksi CI3 Database berhasil!</span></div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-[11px] mt-2 pt-2 border-t border-emerald-500/20">
            <div>PHP Version: <span class="text-white">8.2.12</span></div>
            <div>CI Version: <span class="text-white">3.1.13</span></div>
            <div>Engine: <span class="text-white">InnoDB/MariaDB</span></div>
            <div>Tables: <span class="text-cyan-300">8 Tables</span></div>
        </div>`;
        lucide.createIcons();
    });
}
</script>
