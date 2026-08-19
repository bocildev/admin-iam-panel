<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_logs() {
        if (isset($this->db) && $this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 100");
            return $res->result_array();
        }
        return array();
    }

    public function add_log($action, $details, $riskLevel = 'low', $status = 'success', $actorName = 'System Admin', $actorEmail = 'admin@saas.local') {
        $id = 'LOG-' . rand(9000, 9999);
        $ip = $this->input->ip_address();
        $ua = $this->input->user_agent();
        $now = date('Y-m-d H:i:s');

        if (isset($this->db) && $this->db->is_connected()) {
            $sql = "INSERT INTO audit_logs (id, actor_name, actor_email, action, resource, ip_address, user_agent, risk_level, status, details, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, array(
                $id, $actorName, $actorEmail, $action, 'IAM Control Panel', $ip, $ua, $riskLevel, $status, $details, $now
            ));
        }

        return array(
            'id' => $id,
            'actorName' => $actorName,
            'actorEmail' => $actorEmail,
            'action' => $action,
            'details' => $details
        );
    }
}
