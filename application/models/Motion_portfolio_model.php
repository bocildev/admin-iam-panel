<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Motion_portfolio_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_projects($only_active = false) {
        $sql = "SELECT * FROM motion_projects";
        if ($only_active) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY display_order ASC, id ASC";

        $query = $this->db->query($sql);
        if (!$query) return array();

        $rows = $query->result_array();
        $results = array();

        foreach ($rows as $r) {
            $results[] = array(
                'id' => (int)$r['id'],
                'project_key' => $r['project_key'],
                'number' => $r['number'],
                'title' => $r['title'],
                'subtitle' => $r['subtitle'],
                'company' => $r['company'],
                'period' => $r['period'],
                'category' => $r['category'],
                'roles' => json_decode($r['roles'], true) ?: array('frontend'),
                'cover_image' => $r['cover_image'],
                'logo' => $r['logo'],
                'description' => $r['description'],
                'impact_highlights' => json_decode($r['impact_highlights'], true) ?: array(),
                'tech_stack' => json_decode($r['tech_stack'], true) ?: array(),
                'team' => json_decode($r['team'], true) ?: array(),
                'live_url' => $r['live_url'],
                'repo_url' => $r['repo_url'],
                'demo_credentials' => $r['demo_credentials'],
                'gallery' => json_decode($r['gallery'], true) ?: array(),
                'display_order' => (int)$r['display_order'],
                'is_active' => (int)$r['is_active']
            );
        }
        return $results;
    }

    public function get_project_by_id($id) {
        $query = $this->db->query("SELECT * FROM motion_projects WHERE id = ? LIMIT 1", array($id));
        if (!$query || $query->num_rows() === 0) return null;

        $r = $query->row_array();
        return array(
            'id' => (int)$r['id'],
            'project_key' => $r['project_key'],
            'number' => $r['number'],
            'title' => $r['title'],
            'subtitle' => $r['subtitle'],
            'company' => $r['company'],
            'period' => $r['period'],
            'category' => $r['category'],
            'roles' => json_decode($r['roles'], true) ?: array('frontend'),
            'cover_image' => $r['cover_image'],
            'logo' => $r['logo'],
            'description' => $r['description'],
            'impact_highlights' => json_decode($r['impact_highlights'], true) ?: array(),
            'tech_stack' => json_decode($r['tech_stack'], true) ?: array(),
            'team' => json_decode($r['team'], true) ?: array(),
            'live_url' => $r['live_url'],
            'repo_url' => $r['repo_url'],
            'demo_credentials' => $r['demo_credentials'],
            'gallery' => json_decode($r['gallery'], true) ?: array(),
            'display_order' => (int)$r['display_order'],
            'is_active' => (int)$r['is_active']
        );
    }

    public function save_project($data) {
        $id = isset($data['id']) && !empty($data['id']) ? (int)$data['id'] : null;

        $project_key = isset($data['project_key']) && !empty($data['project_key']) ? $data['project_key'] : url_title(strtolower($data['title']));
        $number = isset($data['number']) ? $data['number'] : '01';
        $title = isset($data['title']) ? $data['title'] : '';
        $subtitle = isset($data['subtitle']) ? $data['subtitle'] : '';
        $company = isset($data['company']) ? $data['company'] : '';
        $period = isset($data['period']) ? $data['period'] : '';
        $category = isset($data['category']) ? $data['category'] : 'enterprise';

        $roles = is_array($data['roles']) ? json_encode($data['roles'], JSON_UNESCAPED_UNICODE) : (isset($data['roles']) ? $data['roles'] : '[]');
        $cover_image = isset($data['cover_image']) ? $data['cover_image'] : '';
        $logo = isset($data['logo']) ? $data['logo'] : '';
        $description = isset($data['description']) ? $data['description'] : '';

        $impact_highlights = is_array($data['impact_highlights']) ? json_encode($data['impact_highlights'], JSON_UNESCAPED_UNICODE) : (isset($data['impact_highlights']) ? $data['impact_highlights'] : '[]');
        $tech_stack = is_array($data['tech_stack']) ? json_encode($data['tech_stack'], JSON_UNESCAPED_UNICODE) : (isset($data['tech_stack']) ? $data['tech_stack'] : '[]');
        $team = is_array($data['team']) ? json_encode($data['team'], JSON_UNESCAPED_UNICODE) : (isset($data['team']) ? $data['team'] : '[]');

        $live_url = isset($data['live_url']) ? $data['live_url'] : '';
        $repo_url = isset($data['repo_url']) ? $data['repo_url'] : '';
        $demo_credentials = isset($data['demo_credentials']) ? $data['demo_credentials'] : '';

        $gallery = is_array($data['gallery']) ? json_encode($data['gallery'], JSON_UNESCAPED_UNICODE) : (isset($data['gallery']) ? $data['gallery'] : '[]');
        $display_order = isset($data['display_order']) ? (int)$data['display_order'] : 0;
        $is_active = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        if ($id) {
            $sql = "UPDATE motion_projects SET 
                project_key = ?, number = ?, title = ?, subtitle = ?, company = ?, period = ?, category = ?,
                roles = ?, cover_image = ?, logo = ?, description = ?, impact_highlights = ?, tech_stack = ?,
                team = ?, live_url = ?, repo_url = ?, demo_credentials = ?, gallery = ?, display_order = ?, is_active = ?
                WHERE id = ?";
            $this->db->query($sql, array(
                $project_key, $number, $title, $subtitle, $company, $period, $category,
                $roles, $cover_image, $logo, $description, $impact_highlights, $tech_stack,
                $team, $live_url, $repo_url, $demo_credentials, $gallery, $display_order, $is_active,
                $id
            ));
            return $id;
        } else {
            $sql = "INSERT INTO motion_projects (
                project_key, number, title, subtitle, company, period, category,
                roles, cover_image, logo, description, impact_highlights, tech_stack,
                team, live_url, repo_url, demo_credentials, gallery, display_order, is_active
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, array(
                $project_key, $number, $title, $subtitle, $company, $period, $category,
                $roles, $cover_image, $logo, $description, $impact_highlights, $tech_stack,
                $team, $live_url, $repo_url, $demo_credentials, $gallery, $display_order, $is_active
            ));
            return $this->db->insert_id();
        }
    }

    public function delete_project($id) {
        return $this->db->query("DELETE FROM motion_projects WHERE id = ?", array((int)$id));
    }

    public function get_config($key) {
        $query = $this->db->query("SELECT config_value FROM motion_config WHERE config_key = ? LIMIT 1", array($key));
        if ($query && $query->num_rows() > 0) {
            $row = $query->row_array();
            return json_decode($row['config_value'], true);
        }
        return null;
    }

    public function save_config($key, $value) {
        $json = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
        $sql = "INSERT INTO motion_config (config_key, config_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE config_value = VALUES(config_value)";
        return $this->db->query($sql, array($key, $json));
    }

    public function get_all_portfolio_data() {
        $projects = $this->get_all_projects(true);
        $profile = $this->get_config('profile') ?: array();
        $skills = $this->get_config('skills') ?: array();
        $software_tools = $this->get_config('software_tools') ?: array();

        return array(
            'profile' => $profile,
            'projects' => $projects,
            'skills' => $skills,
            'softwareTools' => $software_tools
        );
    }
}
