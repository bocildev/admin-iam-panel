<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mochi_api extends CI_Controller {

    private $mochi_pdo;

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        $this->load->model('Admin_model');
        $this->load->model('Audit_model');
        $this->init_mochi_db();
    }

    private function init_mochi_db() {
        try {
            $this->mochi_pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=db_mochi_finance;charset=utf8mb4", 'root', '', array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ));
        } catch (Exception $e) {
            $this->mochi_pdo = null;
        }
    }

    private function json_response($data, $status = 200) {
        $this->output->set_content_type('application/json')
                     ->set_status_header($status)
                     ->set_output(json_encode($data));
    }

    private function get_json_input() {
        $stream = file_get_contents('php://input');
        $json = json_decode($stream, true);
        if (is_array($json)) {
            return $json;
        }
        return $this->input->post() ?? array();
    }

    /**
     * POST /mochi_api/login
     * Validates admin against IAM saas_iam_db and checks Mochi Finance authorization
     */
    public function login() {
        $input = $this->get_json_input();
        $identifier = isset($input['identifier']) ? trim($input['identifier']) : (isset($input['email']) ? trim($input['email']) : '');
        $password = isset($input['password']) ? trim($input['password']) : '';

        if (empty($identifier) || empty($password)) {
            return $this->json_response(array('error' => 'Username/Email dan Password wajib diisi'), 400);
        }

        $admin = $this->Admin_model->get_by_email_or_username($identifier);
        if (!$admin) {
            return $this->json_response(array('error' => 'Identitas atau password salah'), 401);
        }

        if ($admin['status'] !== 'active') {
            return $this->json_response(array('error' => 'Akun admin ini sedang dinonaktifkan atau ditangguhkan'), 403);
        }

        if (!password_verify($password, $admin['password_hash'])) {
            return $this->json_response(array('error' => 'Identitas atau password salah'), 401);
        }

        // Authorization check: Must be SuperAdmin OR assigned to mochi-finance
        $is_super_admin = ($admin['role'] === 'super_admin');
        $is_mochi_assigned = (!empty($admin['app_slug']) && $admin['app_slug'] === 'mochi-finance');

        if (!$is_super_admin && !$is_mochi_assigned) {
            return $this->json_response(array(
                'error' => 'Akses ditolak: Akun admin Anda terdaftar untuk aplikasi "' . ($admin['app_name'] ?? 'IAM Core') . '" dan tidak memiliki hak akses ke Mochi Finance.'
            ), 403);
        }

        $this->Admin_model->update_last_login($admin['id']);

        $token = 'MCH-TOK-' . bin2hex(random_bytes(16));

        $this->Audit_model->add_log(
            'MOCHI_ADMIN_LOGIN',
            "Admin @{$admin['username']} berhasil login ke Mochi Finance Admin App.",
            'low',
            'success'
        );

        return $this->json_response(array(
            'success' => true,
            'message' => 'Login Mochi Finance Admin berhasil',
            'token' => $token,
            'admin' => array(
                'id' => $admin['id'],
                'username' => $admin['username'],
                'email' => $admin['email'],
                'fullName' => $admin['full_name'],
                'avatar' => $admin['avatar'],
                'role' => $admin['role'],
                'appName' => $admin['app_name'] ?? 'All Applications (SuperAdmin)',
                'appSlug' => $admin['app_slug'] ?? 'all'
            )
        ));
    }

    /**
     * GET /mochi_api/stats
     */
    public function stats() {
        if (!$this->mochi_pdo) {
            return $this->json_response(array('error' => 'Database db_mochi_finance tidak terhubung'), 500);
        }

        $total_stmt = $this->mochi_pdo->query("SELECT COUNT(*) FROM mochi_users");
        $total_users = (int)$total_stmt->fetchColumn();

        $active_stmt = $this->mochi_pdo->query("SELECT COUNT(*) FROM mochi_users WHERE status = 'active'");
        $active_users = (int)$active_stmt->fetchColumn();

        $suspended_stmt = $this->mochi_pdo->query("SELECT COUNT(*) FROM mochi_users WHERE status = 'suspended'");
        $suspended_users = (int)$suspended_stmt->fetchColumn();

        $tx_stmt = $this->mochi_pdo->query("SELECT COALESCE(SUM(total_transactions), 0) FROM mochi_users");
        $total_transactions = (int)$tx_stmt->fetchColumn();

        $wallet_stmt = $this->mochi_pdo->query("SELECT COALESCE(SUM(total_wallets), 0) FROM mochi_users");
        $total_wallets = (int)$wallet_stmt->fetchColumn();

        return $this->json_response(array(
            'totalUsers' => $total_users,
            'activeUsers' => $active_users,
            'suspendedUsers' => $suspended_users,
            'totalTransactions' => $total_transactions,
            'totalWallets' => $total_wallets,
            'dbStatus' => 'connected',
            'database' => 'db_mochi_finance'
        ));
    }

    /**
     * GET /mochi_api/users
     */
    public function users() {
        if (!$this->mochi_pdo) {
            return $this->json_response(array('error' => 'Database db_mochi_finance tidak terhubung'), 500);
        }

        $stmt = $this->mochi_pdo->query("SELECT * FROM mochi_users ORDER BY created_at DESC");
        $rows = $stmt->fetchAll();

        return $this->json_response(array(
            'users' => $rows
        ));
    }

    /**
     * POST /mochi_api/create_user
     */
    public function create_user() {
        if (!$this->mochi_pdo) {
            return $this->json_response(array('error' => 'Database db_mochi_finance tidak terhubung'), 500);
        }

        $input = $this->get_json_input();
        if (empty($input['username']) || empty($input['email']) || empty($input['full_name'])) {
            return $this->json_response(array('error' => 'Username, Email, dan Nama Lengkap wajib diisi'), 400);
        }

        $id = 'MC-USR-' . sprintf('%03d', rand(10, 999));
        $avatar = !empty($input['avatar']) ? $input['avatar'] : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150';
        $currency = !empty($input['currency']) ? strtoupper($input['currency']) : 'IDR';

        $sql = "INSERT INTO mochi_users (id, username, email, full_name, avatar, currency, status, total_wallets, total_transactions, last_active) VALUES (?, ?, ?, ?, ?, ?, 'active', 1, 0, NOW())";
        $stmt = $this->mochi_pdo->prepare($sql);
        try {
            $stmt->execute(array($id, $input['username'], $input['email'], $input['full_name'], $avatar, $currency));
        } catch (Exception $e) {
            return $this->json_response(array('error' => 'Username atau Email sudah terdaftar'), 409);
        }

        $this->Audit_model->add_log(
            'MOCHI_USER_CREATE',
            "Pengguna baru Mochi Finance didaftarkan: @{$input['username']} ({$id})",
            'medium',
            'success'
        );

        return $this->json_response(array('success' => true, 'message' => 'Pengguna Mochi Finance berhasil ditambahkan', 'id' => $id));
    }

    /**
     * POST /mochi_api/update_status
     */
    public function update_status() {
        if (!$this->mochi_pdo) {
            return $this->json_response(array('error' => 'Database db_mochi_finance tidak terhubung'), 500);
        }

        $input = $this->get_json_input();
        $id = isset($input['id']) ? trim($input['id']) : '';
        $status = isset($input['status']) ? trim($input['status']) : '';

        if (empty($id) || !in_array($status, array('active', 'suspended'))) {
            return $this->json_response(array('error' => 'ID dan status (active/suspended) wajib valid'), 400);
        }

        $stmt = $this->mochi_pdo->prepare("UPDATE mochi_users SET status = ? WHERE id = ?");
        $stmt->execute(array($status, $id));

        $this->Audit_model->add_log(
            'MOCHI_USER_STATUS',
            "Status pengguna Mochi Finance {$id} diubah menjadi {$status}.",
            'medium',
            'success'
        );

        return $this->json_response(array('success' => true, 'message' => 'Status pengguna berhasil diperbarui'));
    }

    /**
     * POST /mochi_api/delete_user
     */
    public function delete_user() {
        if (!$this->mochi_pdo) {
            return $this->json_response(array('error' => 'Database db_mochi_finance tidak terhubung'), 500);
        }

        $input = $this->get_json_input();
        $id = isset($input['id']) ? trim($input['id']) : '';

        if (empty($id)) {
            return $this->json_response(array('error' => 'ID pengguna wajib diisi'), 400);
        }

        $stmt = $this->mochi_pdo->prepare("DELETE FROM mochi_users WHERE id = ?");
        $stmt->execute(array($id));

        $this->Audit_model->add_log(
            'MOCHI_USER_DELETE',
            "Pengguna Mochi Finance {$id} berhasil dihapus oleh admin.",
            'high',
            'success'
        );

        return $this->json_response(array('success' => true, 'message' => 'Pengguna berhasil dihapus'));
    }
}
