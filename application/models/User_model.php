<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function get_all_users() {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM admins ORDER BY created_at DESC");
            $rows = $res ? $res->result_array() : array();
            if (!empty($rows)) {
                $users = array();
                foreach ($rows as $r) {
                    $users[] = array(
                        'id' => $r['id'],
                        'username' => $r['username'],
                        'email' => $r['email'],
                        'fullName' => $r['full_name'],
                        'avatar' => $r['avatar'],
                        'role' => $r['role'],
                        'status' => $r['status'],
                        'ci3PasswordHash' => $r['password_hash'],
                        'isMfaEnabled' => (bool)$r['is_mfa_enabled'],
                        'lastLogin' => $r['last_login'],
                        'ipAddress' => $r['ip_address'],
                        'createdAt' => $r['created_at']
                    );
                }
                return $users;
            }
        }
        return get_mock_users();
    }

    public function add_user($data) {
        $id = 'USR-' . rand(1100, 9999);
        $new_user = array(
            'id' => $id,
            'username' => $data['username'],
            'email' => $data['email'],
            'fullName' => $data['fullName'],
            'avatar' => isset($data['avatar']) ? $data['avatar'] : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
            'role' => $data['role'],
            'status' => isset($data['status']) ? $data['status'] : 'active',
            'ci3PasswordHash' => password_hash('ToonHub2026!', PASSWORD_BCRYPT),
            'isMfaEnabled' => isset($data['isMfaEnabled']) ? (bool)$data['isMfaEnabled'] : false,
            'lastLogin' => 'Never',
            'ipAddress' => $this->input->ip_address(),
            'createdAt' => date('Y-m-d H:i:s')
        );

        if ($this->db->is_connected()) {
            $sql = "INSERT INTO admins (id, username, email, full_name, avatar, role, status, password_hash, is_mfa_enabled, last_login, ip_address, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, array(
                $new_user['id'],
                $new_user['username'],
                $new_user['email'],
                $new_user['fullName'],
                $new_user['avatar'],
                $new_user['role'],
                $new_user['status'],
                $new_user['ci3PasswordHash'],
                $new_user['isMfaEnabled'] ? 1 : 0,
                NULL,
                $new_user['ipAddress'],
                $new_user['createdAt']
            ));
        }

        return $new_user;
    }

    public function update_status($user_id, $status) {
        if ($this->db->is_connected()) {
            $this->db->query("UPDATE admins SET status = ? WHERE id = ?", array($status, $user_id));
        }
        return true;
    }

    public function update_role($user_id, $role) {
        if ($this->db->is_connected()) {
            $this->db->query("UPDATE admins SET role = ? WHERE id = ?", array($role, $user_id));
        }
        return true;
    }

    public function toggle_mfa($user_id) {
        if ($this->db->is_connected()) {
            $this->db->query("UPDATE admins SET is_mfa_enabled = NOT is_mfa_enabled WHERE id = ?", array($user_id));
        }
        return true;
    }
}
