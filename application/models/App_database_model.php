<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_database_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_by_application_id($application_id) {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM app_databases WHERE application_id = ? LIMIT 1", array($application_id));
            return $res ? $res->row_array() : null;
        }
        return null;
    }

    public function save_db_metadata($data) {
        $id = 'DB-' . sprintf('%04d', rand(1, 9999));
        $record = array(
            'id' => $id,
            'application_id' => $data['application_id'],
            'db_host' => isset($data['db_host']) ? $data['db_host'] : '127.0.0.1',
            'db_port' => isset($data['db_port']) ? (int)$data['db_port'] : 3306,
            'db_name' => $data['db_name'],
            'db_user' => $data['db_user'],
            'db_password_encrypted' => $data['db_password_encrypted'],
            'db_driver' => isset($data['db_driver']) ? $data['db_driver'] : 'mysql',
            'status' => isset($data['status']) ? $data['status'] : 'provisioned',
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->db->is_connected()) {
            $sql = "INSERT INTO app_databases (id, application_id, db_host, db_port, db_name, db_user, db_password_encrypted, db_driver, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, array(
                $record['id'], $record['application_id'], $record['db_host'], $record['db_port'], $record['db_name'], $record['db_user'], $record['db_password_encrypted'], $record['db_driver'], $record['status'], $record['created_at']
            ));
        }

        return $record;
    }

    public function update_db_metadata($application_id, $data) {
        if ($this->db->is_connected()) {
            $updates = array();
            $params = array();

            if (isset($data['db_host'])) { $updates[] = "db_host = ?"; $params[] = $data['db_host']; }
            if (isset($data['db_port'])) { $updates[] = "db_port = ?"; $params[] = (int)$data['db_port']; }
            if (isset($data['db_name'])) { $updates[] = "db_name = ?"; $params[] = $data['db_name']; }
            if (isset($data['db_user'])) { $updates[] = "db_user = ?"; $params[] = $data['db_user']; }
            if (isset($data['db_password_encrypted']) && !empty($data['db_password_encrypted'])) { 
                $updates[] = "db_password_encrypted = ?"; 
                $params[] = $data['db_password_encrypted']; 
            }

            if (!empty($updates)) {
                $params[] = $application_id;
                $sql = "UPDATE app_databases SET " . implode(', ', $updates) . " WHERE application_id = ?";
                return $this->db->query($sql, $params);
            }
        }
        return false;
    }
}
