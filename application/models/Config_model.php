<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_model extends CI_Model {

    private function table_exists() {
        $res = $this->db->query("SHOW TABLES LIKE 'portfolio_config'");
        return $res && $res->num_rows() > 0;
    }

    public function get_all_settings() {
        if (!$this->db->is_connected() || !$this->table_exists()) return array();
        $res = $this->db->query("SELECT * FROM portfolio_config");
        if (!$res || is_bool($res)) return array();
        $settings = array();
        foreach ($res->result_array() as $r) {
            $settings[$r['key']] = $r['value'];
        }
        return $settings;
    }

    public function get_section_layout() {
        if (!$this->db->is_connected() || !$this->table_exists()) return null;
        $res = $this->db->query("SELECT `value` FROM portfolio_config WHERE `key` = 'section_layout' LIMIT 1");
        if (!$res || is_bool($res) || $res->num_rows() === 0) return null;
        $row = $res->row_array();
        return json_decode($row['value'], true);
    }

    public function update_setting($key, $value) {
        if (!$this->db->is_connected()) return false;
        $sql = "INSERT INTO portfolio_config (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?";
        $this->db->query($sql, array($key, $value, $value));
        return true;
    }

    public function update_settings($data) {
        if (!$this->db->is_connected()) return false;
        foreach ($data as $key => $value) {
            $this->update_setting($key, $value);
        }
        return true;
    }

    public function update_section_layout($layout_array) {
        if (!$this->db->is_connected()) return false;
        $json = json_encode($layout_array, JSON_UNESCAPED_UNICODE);
        return $this->update_setting('section_layout', $json);
    }
}
