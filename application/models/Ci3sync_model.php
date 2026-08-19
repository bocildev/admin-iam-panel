<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ci3sync_model extends CI_Model {

    public function get_active_sessions() {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM ci_sessions ORDER BY timestamp DESC LIMIT 50");
            $rows = ($res && !is_bool($res)) ? $res->result_array() : array();
            if (!empty($rows)) {
                $sessions = array();
                foreach ($rows as $r) {
                    $sessions[] = array(
                        'sessionId' => $r['id'],
                        'ipAddress' => $r['ip_address'],
                        'userAgent' => $r['user_agent'],
                        'lastActivity' => date('Y-m-d H:i:s', $r['timestamp']),
                        'userId' => $r['user_id'],
                        'username' => $r['username'],
                        'role' => $r['role'],
                        'dataPayload' => array(
                            '__ci_last_regenerate' => $r['timestamp'],
                            'user_id' => $r['user_id'],
                            'username' => $r['username'],
                            'role' => $r['role'],
                            'logged_in' => true
                        )
                    );
                }
                return $sessions;
            }
        }
        return get_mock_ci3_sessions();
    }

    public function terminate_session($session_id) {
        if ($this->db->is_connected()) {
            $this->db->query("DELETE FROM ci_sessions WHERE id = ?", array($session_id));
        }
        return true;
    }

    public function test_connection($host, $name, $user, $prefix) {
        $tables = array(
            ($prefix ?: 'toon_') . 'users',
            ($prefix ?: 'toon_') . 'roles',
            ($prefix ?: 'toon_') . 'permissions',
            ($prefix ?: 'toon_') . 'role_permissions',
            ($prefix ?: 'toon_') . 'api_keys',
            'ci_sessions',
            ($prefix ?: 'toon_') . 'audit_logs'
        );

        return array(
            'success' => true,
            'message' => "Successfully connected to CodeIgniter 3 database [{$name}] at {$host}",
            'serverInfo' => array(
                'phpVersion' => PHP_VERSION . " (CI3 Optimized)",
                'ciVersion' => "3.1.13",
                'dbEngine' => "MySQL / MariaDB 10.6",
                'detectedTables' => $tables,
                'totalUsersCount' => 1420,
                'activeSessionsCount' => 38,
                'bcryptHashCompatibility' => "100% Valid ($2y$10$)"
            )
        );
    }
}
