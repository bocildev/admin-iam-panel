<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_content_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_contents_by_app($application_id) {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM app_contents WHERE application_id = ? ORDER BY created_at DESC", array($application_id));
            $rows = $res ? $res->result_array() : array();
            $contents = array();
            foreach ($rows as $r) {
                $contents[] = array(
                    'id' => $r['id'],
                    'applicationId' => $r['application_id'],
                    'contentKey' => $r['content_key'],
                    'contentValue' => json_decode($r['content_value'], true) ?: $r['content_value'],
                    'createdAt' => $r['created_at'],
                    'updatedAt' => $r['updated_at']
                );
            }
            return $contents;
        }
        return array();
    }

    public function get_content_by_key($application_id, $content_key) {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM app_contents WHERE application_id = ? AND content_key = ? LIMIT 1", array($application_id, $content_key));
            $r = $res ? $res->row_array() : null;
            if ($r) {
                return array(
                    'id' => $r['id'],
                    'applicationId' => $r['application_id'],
                    'contentKey' => $r['content_key'],
                    'contentValue' => json_decode($r['content_value'], true) ?: $r['content_value'],
                    'createdAt' => $r['created_at'],
                    'updatedAt' => $r['updated_at']
                );
            }
        }
        return null;
    }

    public function save_content($application_id, $content_key, $content_value) {
        $id = 'CNT-' . sprintf('%04d', rand(1, 9999));
        $encoded_value = is_array($content_value) ? json_encode($content_value, JSON_UNESCAPED_UNICODE) : $content_value;
        $now = date('Y-m-d H:i:s');

        if ($this->db->is_connected()) {
            $sql = "INSERT INTO app_contents (id, application_id, content_key, content_value, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE content_value = VALUES(content_value), updated_at = VALUES(updated_at)";
            $this->db->query($sql, array($id, $application_id, $content_key, $encoded_value, $now, $now));
        }

        return $this->get_content_by_key($application_id, $content_key);
    }

    public function delete_content($application_id, $content_key) {
        if ($this->db->is_connected()) {
            return $this->db->query("DELETE FROM app_contents WHERE application_id = ? AND content_key = ?", array($application_id, $content_key));
        }
        return false;
    }
}
