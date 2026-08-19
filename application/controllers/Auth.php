<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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
        $this->load->library('session');
        require_once APPPATH . 'libraries/Auth_service.php';
        $this->auth_service = new Auth_service();
    }

    private function check_brute_force($ip) {
        $max_attempts = 5;
        $lockout_time = 300; // 5 minutes
        
        $cache_file = APPPATH . 'cache/brute_force_' . md5($ip) . '.json';
        if (file_exists($cache_file)) {
            $data = json_decode(file_get_contents($cache_file), true);
            if ($data['attempts'] >= $max_attempts) {
                if (time() - $data['last_attempt'] < $lockout_time) {
                    return false; // Locked out
                } else {
                    // Lockout expired
                    unlink($cache_file);
                    return true;
                }
            }
        }
        return true;
    }

    private function record_failed_login($ip) {
        $cache_file = APPPATH . 'cache/brute_force_' . md5($ip) . '.json';
        if (file_exists($cache_file)) {
            $data = json_decode(file_get_contents($cache_file), true);
            $data['attempts']++;
            $data['last_attempt'] = time();
        } else {
            $data = array(
                'attempts' => 1,
                'last_attempt' => time()
            );
        }
        file_put_contents($cache_file, json_encode($data));
    }

    private function clear_failed_login($ip) {
        $cache_file = APPPATH . 'cache/brute_force_' . md5($ip) . '.json';
        if (file_exists($cache_file)) {
            unlink($cache_file);
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
     * POST /auth/login
     */
    public function login() {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($this->auth_service->is_logged_in()) {
                $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/iam-admin-panel/";
                header('Location: ' . $base_url . 'dashboard');
                exit;
            }
            return $this->load->view('auth/login');
        }

        $input = $this->get_json_input();
        $identifier = isset($input['identifier']) ? trim($input['identifier']) : (isset($input['email']) ? trim($input['email']) : '');
        $password = isset($input['password']) ? trim($input['password']) : '';

        if (empty($identifier) || empty($password)) {
            return $this->json_response(array('error' => 'Username/Email and password are required'), 400);
        }

        $ip = $this->input->ip_address();
        if (!$this->check_brute_force($ip)) {
            $this->Audit_model->add_log('BRUTE_FORCE', "Terlalu banyak percobaan login gagal dari IP: {$ip}", 'high', 'blocked');
            return $this->json_response(array('error' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam 5 menit.'), 429);
        }

        $result = $this->auth_service->authenticate($identifier, $password);

        if (!$result['success']) {
            $this->record_failed_login($ip);
            $this->Audit_model->add_log(
                'FAILED_LOGIN',
                "Percobaan login gagal untuk username/email: {$identifier} dari IP: {$ip}",
                'high',
                'blocked'
            );
            return $this->json_response(array('error' => $result['message'], 'debug_res' => $result), 401);
        }

        $this->clear_failed_login($ip);

        $this->Audit_model->add_log(
            'ADMIN_LOGIN',
            "Admin {$result['admin']['username']} ({$result['admin']['email']}) berhasil login.",
            'low',
            'success'
        );

        return $this->json_response(array(
            'message' => 'Login successful',
            'admin' => $result['admin']
        ));
    }

    /**
     * POST /auth/logout
     */
    public function logout() {
        $admin = $this->auth_service->get_current_admin();
        if ($admin) {
            $this->Audit_model->add_log(
                'ADMIN_LOGOUT',
                "Admin {$admin['username']} logged out.",
                'low',
                'success'
            );
        }

        $this->auth_service->logout();
        return $this->json_response(array('message' => 'Logout successful'));
    }

    /**
     * GET /auth/me
     */
    public function me() {
        $admin = $this->auth_service->get_current_admin();
        if (!$admin) {
            return $this->json_response(array('authenticated' => false), 401);
        }
        return $this->json_response(array(
            'authenticated' => true,
            'admin' => $admin
        ));
    }

    /**
     * GET /auth/admins
     */
    public function list_admins() {
        $admins = $this->Admin_model->get_all_admins();
        return $this->json_response(array('admins' => $admins));
    }

    /**
     * POST /auth/admins (Super Admin only)
     */
    public function create_admin() {
        $input = $this->get_json_input();
        if (empty($input['username']) || empty($input['email']) || empty($input['password']) || empty($input['fullName'])) {
            return $this->json_response(array('error' => 'Username, Email, Full Name, and Password are required'), 400);
        }

        // Check if email or username already exists
        if ($this->Admin_model->get_by_email($input['email'])) {
            return $this->json_response(array('error' => 'Email address already registered'), 409);
        }

        $new_admin = $this->Admin_model->create_admin($input);

        $this->Audit_model->add_log(
            'ADMIN_CREATED',
            "Membuat akun admin baru: @{$new_admin['username']} ({$new_admin['email']})",
            'medium',
            'success'
        );

        return $this->json_response(array(
            'message' => 'Admin account created successfully',
            'admin' => $new_admin
        ), 201);
    }

    /**
     * POST /auth/admins/update
     */
    public function update_admin() {
        $input = $this->get_json_input();
        $id = isset($input['id']) ? trim($input['id']) : '';

        if (empty($id) || empty($input['username']) || empty($input['email']) || empty($input['fullName'])) {
            return $this->json_response(array('error' => 'ID, Username, Email, and Full Name are required'), 400);
        }

        $update_data = array(
            'username' => trim($input['username']),
            'full_name' => trim($input['fullName']),
            'email' => trim($input['email']),
            'role' => isset($input['role']) ? $input['role'] : 'admin',
            'status' => isset($input['status']) ? $input['status'] : 'active',
            'password' => isset($input['password']) ? trim($input['password']) : ''
        );

        $this->Admin_model->update_admin($id, $update_data);

        $this->Audit_model->add_log(
            'ADMIN_UPDATED',
            "Memperbarui data akun admin: @{$update_data['username']} ({$id})",
            'medium',
            'success'
        );

        return $this->json_response(array('message' => 'Admin account updated successfully'));
    }
}
