<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_service {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Admin_model');
        $this->CI->load->library('session');
    }

    /**
     * Authenticate Admin credentials
     */
    public function authenticate($identifier, $password) {
        $admin = $this->CI->Admin_model->get_by_email_or_username($identifier);

        if (!$admin) {
            return array('success' => false, 'message' => 'Invalid email/username or password');
        }

        if ($admin['status'] !== 'active') {
            return array('success' => false, 'message' => 'Account is suspended or inactive');
        }

        $verify = password_verify($password, $admin['password_hash']);
        if (!$verify) {
            return array('success' => false, 'message' => "Debug: verify failed for email: {$email}, input pass: [{$password}], stored hash: [{$admin['password_hash']}]");
        }

        // Update login metadata
        $this->CI->Admin_model->update_last_login($admin['id']);

        // Establish Session
        $session_data = array(
            'admin_id' => $admin['id'],
            'username' => $admin['username'],
            'email' => $admin['email'],
            'role' => $admin['role'],
            'logged_in' => TRUE
        );
        $this->CI->session->set_userdata($session_data);

        unset($admin['password_hash']);

        return array(
            'success' => true,
            'message' => 'Authentication successful',
            'admin' => $admin
        );
    }

    /**
     * Get current logged in admin payload
     */
    public function get_current_admin() {
        if (!$this->is_logged_in()) {
            return null;
        }

        $admin_id = $this->CI->session->userdata('admin_id');
        $admin = $this->CI->Admin_model->get_by_id($admin_id);

        if ($admin) {
            unset($admin['password_hash']);
        }
        return $admin;
    }

    /**
     * Check if session is active
     */
    public function is_logged_in() {
        return (bool)$this->CI->session->userdata('logged_in');
    }

    /**
     * Check if logged in admin has SuperAdmin role
     */
    public function is_super_admin() {
        return $this->is_logged_in() && $this->CI->session->userdata('role') === 'super_admin';
    }

    /**
     * Invalidate session / Logout
     */
    public function logout() {
        $this->CI->session->sess_destroy();
        return true;
    }
}
