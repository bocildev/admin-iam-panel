<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_user_access_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_users_by_app($application_id) {
        if ($this->db->is_connected()) {
            $sql = "SELECT m.id as access_id, m.app_role, m.created_at as granted_at, a.id as admin_id, a.username, a.email, a.full_name, a.avatar 
                    FROM app_user_access m 
                    JOIN admins a ON m.admin_id = a.id 
                    WHERE m.application_id = ? 
                    ORDER BY m.created_at DESC";
            $res = $this->db->query($sql, array($application_id));
            return $res ? $res->result_array() : array();
        }
        return array();
    }

    public function grant_access($admin_id, $application_id, $app_role = 'editor') {
        $id = 'ACC-' . sprintf('%04d', rand(1, 9999));
        $now = date('Y-m-d H:i:s');

        if ($this->db->is_connected()) {
            $sql = "INSERT INTO app_user_access (id, admin_id, application_id, app_role, created_at) 
                    VALUES (?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE app_role = VALUES(app_role)";
            $this->db->query($sql, array($id, $admin_id, $application_id, $app_role, $now));
        }

        return true;
    }

    public function revoke_access($admin_id, $application_id) {
        if ($this->db->is_connected()) {
            return $this->db->query("DELETE FROM app_user_access WHERE admin_id = ? AND application_id = ?", array($admin_id, $application_id));
        }
        return false;
    }
}
