<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_applications() {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT a.*, d.db_name, d.status as db_status, d.db_host, d.db_port, d.db_user FROM applications a LEFT JOIN app_databases d ON a.id = d.application_id ORDER BY a.created_at DESC");
            return $res ? $res->result_array() : array();
        }
        return array();
    }

    public function get_by_id($id) {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM applications WHERE id = ?", array($id));
            return $res ? $res->row_array() : null;
        }
        return null;
    }

    public function create_application($data) {
        $id = 'APP-' . sprintf('%04d', rand(1, 9999));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));

        $app = array(
            'id' => $id,
            'name' => $data['name'],
            'slug' => $slug,
            'description' => isset($data['description']) ? $data['description'] : '',
            'category' => isset($data['category']) ? $data['category'] : '3d_portfolio',
            'status' => isset($data['status']) ? $data['status'] : 'active',
            'meta_data' => isset($data['metaData']) ? json_encode($data['metaData']) : null,
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->db->is_connected()) {
            $sql = "INSERT INTO applications (id, name, slug, description, category, status, meta_data, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, array(
                $app['id'], $app['name'], $app['slug'], $app['description'], $app['category'], $app['status'], $app['meta_data'], $app['created_at']
            ));
        }

        return $app;
    }

    public function update_application($id, $data) {
        if ($this->db->is_connected()) {
            $sql = "UPDATE applications SET name = ?, description = ?, category = ?, status = ? WHERE id = ?";
            return $this->db->query($sql, array(
                $data['name'], $data['description'], $data['category'], $data['status'], $id
            ));
        }
        return false;
    }

    public function delete_application($id) {
        if ($this->db->is_connected()) {
            return $this->db->query("DELETE FROM applications WHERE id = ?", array($id));
        }
        return false;
    }
}
