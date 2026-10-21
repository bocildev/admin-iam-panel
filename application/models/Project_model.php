<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends CI_Model {

    public function get_all_projects() {
        if ($this->db->is_connected()) {
            $table_res = $this->db->query("SHOW TABLES LIKE 'portofolio'");
            if ($table_res && $table_res->num_rows() > 0) {
                $res = $this->db->query("SELECT * FROM portofolio ORDER BY id ASC");
                $rows = $res ? $res->result_array() : array();
                $projects = array();
                foreach ($rows as $r) {
                    $projects[] = array(
                        'id' => (int)$r['id'],
                        'name' => $r['name'],
                        'src' => $r['src'],
                        'bg' => $r['bg'],
                        'lightBg' => $r['lightBg'],
                        'nebula1' => $r['nebula1'],
                        'nebula2' => $r['nebula2'],
                        'aura' => $r['aura'],
                        'description' => $r['description'],
                        'description_id' => $r['description_id'],
                        'features' => json_decode($r['features'], true) ?: array(),
                        'features_id' => json_decode($r['features_id'], true) ?: array(),
                        'techStack' => json_decode($r['techStack'], true) ?: array()
                    );
                }
                return $projects;
            }
        }
        return array();
    }

    public function get_project_by_id($id) {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM portofolio WHERE id = ?", array($id));
            $r = $res->row_array();
            if ($r) {
                return array(
                    'id' => (int)$r['id'],
                    'name' => $r['name'],
                    'src' => $r['src'],
                    'bg' => $r['bg'],
                    'lightBg' => $r['lightBg'],
                    'nebula1' => $r['nebula1'],
                    'nebula2' => $r['nebula2'],
                    'aura' => $r['aura'],
                    'description' => $r['description'],
                    'description_id' => $r['description_id'],
                    'features' => json_decode($r['features'], true) ?: array(),
                    'features_id' => json_decode($r['features_id'], true) ?: array(),
                    'techStack' => json_decode($r['techStack'], true) ?: array()
                );
            }
        }
        return null;
    }

    public function add_project($data) {
        if ($this->db->is_connected()) {
            $sql = "INSERT INTO portofolio (name, src, bg, lightBg, nebula1, nebula2, aura, description, description_id, features, features_id, techStack) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $features = isset($data['features']) ? (is_array($data['features']) ? json_encode($data['features']) : $data['features']) : '[]';
            $features_id = isset($data['features_id']) ? (is_array($data['features_id']) ? json_encode($data['features_id']) : $data['features_id']) : '[]';
            $techStack = isset($data['techStack']) ? (is_array($data['techStack']) ? json_encode($data['techStack']) : $data['techStack']) : '[]';

            $res = $this->db->query($sql, array(
                isset($data['name']) ? $data['name'] : '',
                isset($data['src']) ? $data['src'] : '',
                isset($data['bg']) ? $data['bg'] : '#040614',
                isset($data['lightBg']) ? $data['lightBg'] : '#0f172a',
                isset($data['nebula1']) ? $data['nebula1'] : '#6366f1',
                isset($data['nebula2']) ? $data['nebula2'] : '#06b6d4',
                isset($data['aura']) ? $data['aura'] : '#818cf8',
                isset($data['description']) ? $data['description'] : '',
                isset($data['description_id']) ? $data['description_id'] : '',
                $features,
                $features_id,
                $techStack
            ));

            if (!$res) {
                return false;
            }

            if (method_exists($this->db, 'insert_id')) {
                return $this->db->insert_id();
            }

            $last = $this->db->query("SELECT LAST_INSERT_ID() as id");
            $row = $last ? $last->row_array() : null;
            return $row ? (int)$row['id'] : true;
        }
        return false;
    }

    public function update_project($id, $data) {
        if ($this->db->is_connected()) {
            $sql = "UPDATE portofolio SET name = ?, src = ?, bg = ?, lightBg = ?, nebula1 = ?, nebula2 = ?, aura = ?, description = ?, description_id = ?, features = ?, features_id = ?, techStack = ? WHERE id = ?";
            
            $features = isset($data['features']) ? (is_array($data['features']) ? json_encode($data['features']) : $data['features']) : '[]';
            $features_id = isset($data['features_id']) ? (is_array($data['features_id']) ? json_encode($data['features_id']) : $data['features_id']) : '[]';
            $techStack = isset($data['techStack']) ? (is_array($data['techStack']) ? json_encode($data['techStack']) : $data['techStack']) : '[]';

            return $this->db->query($sql, array(
                isset($data['name']) ? $data['name'] : '',
                isset($data['src']) ? $data['src'] : '',
                isset($data['bg']) ? $data['bg'] : '#040614',
                isset($data['lightBg']) ? $data['lightBg'] : '#0f172a',
                isset($data['nebula1']) ? $data['nebula1'] : '#6366f1',
                isset($data['nebula2']) ? $data['nebula2'] : '#06b6d4',
                isset($data['aura']) ? $data['aura'] : '#818cf8',
                isset($data['description']) ? $data['description'] : '',
                isset($data['description_id']) ? $data['description_id'] : '',
                $features,
                $features_id,
                $techStack,
                $id
            ));
        }
        return false;
    }

    public function delete_project($id) {
        if ($this->db->is_connected()) {
            $this->db->query("DELETE FROM portofolio WHERE id = ?", array($id));
            return true;
        }
        return false;
    }
}
