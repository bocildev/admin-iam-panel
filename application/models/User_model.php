<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function get_all_users() {
        if ($this->db->is_connected()) {
            $sql = "SELECT 
                        adm.*, 
                        app.id as app_id, 
                        app.name as app_name, 
                        app.slug as app_slug,
                        aua.app_role
                    FROM admins adm
                    LEFT JOIN app_user_access aua ON adm.id = aua.admin_id
                    LEFT JOIN applications app ON aua.application_id = app.id
                    ORDER BY adm.created_at DESC";
            $res = $this->db->query($sql);
            $rows = $res ? $res->result_array() : array();
            if (!empty($rows)) {
                $users = array();
                foreach ($rows as $r) {
                    $app_name = !empty($r['app_name']) ? $r['app_name'] : (($r['role'] === 'super_admin') ? 'All Applications' : 'IAM Core');
                    $app_slug = !empty($r['app_slug']) ? $r['app_slug'] : (($r['role'] === 'super_admin') ? 'all' : 'iam-core');
                    
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
                        'createdAt' => $r['created_at'],
                        'appId' => $r['app_id'],
                        'appName' => $app_name,
                        'appSlug' => $app_slug
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

            if (!empty($data['application_id'])) {
                $aua_id = 'AUA-' . rand(1100, 9999);
                $this->db->query("INSERT INTO app_user_access (id, admin_id, application_id, app_role) VALUES (?, ?, ?, ?)", array(
                    $aua_id,
                    $new_user['id'],
                    $data['application_id'],
                    'app_admin'
                ));
            }
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
