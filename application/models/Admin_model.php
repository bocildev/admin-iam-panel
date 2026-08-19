<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_by_email($email) {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM admins WHERE email = ?", array($email));
            return $res->row_array();
        }
        return null;
    }

    public function get_by_email_or_username($identifier) {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM admins WHERE email = ? OR username = ?", array($identifier, $identifier));
            return $res->row_array();
        }
        return null;
    }

    public function get_by_id($id) {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM admins WHERE id = ?", array($id));
            return $res ? $res->row_array() : null;
        }
        return null;
    }

    public function get_all_admins() {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT id, username, email, full_name, avatar, role, status, is_mfa_enabled, last_login, ip_address, created_at, updated_at FROM admins ORDER BY created_at DESC");
            return $res ? $res->result_array() : array();
        }
        return array();
    }

    public function create_admin($data) {
        $id = 'ADM-' . sprintf('%04d', rand(1, 9999));
        $admin = array(
            'id' => $id,
            'username' => $data['username'],
            'email' => $data['email'],
            'full_name' => $data['fullName'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'avatar' => isset($data['avatar']) ? $data['avatar'] : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
            'role' => isset($data['role']) && in_array($data['role'], array('super_admin', 'admin')) ? $data['role'] : 'admin',
            'status' => isset($data['status']) ? $data['status'] : 'active',
            'is_mfa_enabled' => isset($data['isMfaEnabled']) ? (int)$data['isMfaEnabled'] : 0,
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        );

        if (isset($this->db) && $this->db->is_connected()) {
            $sql = "INSERT INTO admins (id, username, email, full_name, password_hash, avatar, role, status, is_mfa_enabled, ip_address, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, array(
                $admin['id'], $admin['username'], $admin['email'], $admin['full_name'], $admin['password_hash'], $admin['avatar'], $admin['role'], $admin['status'], $admin['is_mfa_enabled'], $admin['ip_address'], $admin['created_at']
            ));
        }

        unset($admin['password_hash']);
        return $admin;
    }

    public function update_last_login($admin_id) {
        if (isset($this->db) && $this->db->is_connected()) {
            $now = date('Y-m-d H:i:s');
            $ip = $this->input->ip_address();
            return $this->db->query("UPDATE admins SET last_login = ?, ip_address = ? WHERE id = ?", array($now, $ip, $admin_id));
        }
        return false;
    }

    public function update_status($admin_id, $status) {
        if (isset($this->db) && $this->db->is_connected()) {
            return $this->db->query("UPDATE admins SET status = ? WHERE id = ?", array($status, $admin_id));
        }
        return false;
    }

    public function update_role($admin_id, $role) {
        if ($this->db->is_connected()) {
            return $this->db->query("UPDATE admins SET role = ? WHERE id = ?", array($role, $admin_id));
        }
        return false;
    }

    public function update_admin($id, $data) {
        if ($this->db->is_connected()) {
            $fields = array("username = ?", "full_name = ?", "email = ?", "role = ?", "status = ?");
            $params = array($data['username'], $data['full_name'], $data['email'], $data['role'], $data['status']);

            if (!empty($data['password'])) {
                $fields[] = "password_hash = ?";
                $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
            }

            $params[] = $id;
            $sql = "UPDATE admins SET " . implode(", ", $fields) . " WHERE id = ?";
            return $this->db->query($sql, $params);
        }
        return false;
    }
}
