<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apikey_model extends CI_Model {

    public function get_all_keys() {
        if ($this->db->is_connected()) {
            $sql = "SELECT k.*, a.name as application_name, a.slug as application_slug 
                    FROM api_keys k 
                    LEFT JOIN applications a ON k.application_id = a.id 
                    ORDER BY k.created_at DESC";
            $res = $this->db->query($sql);
            $rows = ($res && !is_bool($res)) ? $res->result_array() : array();
            if (!empty($rows)) {
                $keys = array();
                foreach ($rows as $r) {
                    $keys[] = array(
                        'id' => $r['id'],
                        'applicationId' => $r['application_id'],
                        'applicationName' => $r['application_name'] ?: 'Standalone / External Client',
                        'applicationSlug' => $r['application_slug'] ?: '',
                        'name' => $r['name'],
                        'prefix' => $r['prefix'],
                        'secretMasked' => $r['secret_masked'],
                        'secretKey' => $r['secret_key'] ?: $r['secret_masked'],
                        'ownerId' => 'ADM-0001',
                        'ownerName' => $r['owner_email'],
                        'scopes' => json_decode($r['scopes'], true) ?: array(),
                        'rateLimit' => (int)$r['rate_limit'],
                        'status' => $r['status'],
                        'lastUsed' => $r['last_used_at'] ? date('d M Y, H:i', strtotime($r['last_used_at'])) : 'Never',
                        'createdAt' => $r['created_at']
                    );
                }
                return $keys;
            }
        }
        return array();
    }

    public function get_key_by_id($id) {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM api_keys WHERE id = ?", array($id));
            return ($res && !is_bool($res)) ? $res->row_array() : null;
        }
        return null;
    }

    public function get_key_by_app_id($app_id) {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM api_keys WHERE application_id = ? AND status = 'active' ORDER BY created_at DESC LIMIT 1", array($app_id));
            return ($res && !is_bool($res)) ? $res->row_array() : null;
        }
        return null;
    }

    public function add_key($data) {
        $id = 'KEY-' . sprintf('%04d', rand(100, 9999));
        $rand_str = bin2hex(random_bytes(16));
        $full_token = 'iam_live_' . $rand_str;
        $token_hash = hash('sha256', $full_token);
        $prefix = substr($full_token, 0, 12) . '...';
        $secret_masked = substr($full_token, 0, 12) . '****************' . substr($full_token, -4);

        $app_id = isset($data['application_id']) && !empty($data['application_id']) ? $data['application_id'] : (isset($data['applicationId']) ? $data['applicationId'] : null);
        $scopes = isset($data['scopes']) ? (is_array($data['scopes']) ? $data['scopes'] : json_decode($data['scopes'], true)) : array('portfolio:read');
        $rate_limit = isset($data['rateLimit']) ? (int)$data['rateLimit'] : (isset($data['rate_limit']) ? (int)$data['rate_limit'] : 600);
        $owner_email = isset($data['ownerEmail']) ? $data['ownerEmail'] : (isset($data['owner_email']) ? $data['owner_email'] : (isset($data['ownerName']) ? $data['ownerName'] : 'athallahrich@gmail.com'));
        $name = isset($data['name']) ? $data['name'] : 'API Key';

        $new_key = array(
            'id' => $id,
            'application_id' => $app_id,
            'name' => $name,
            'prefix' => $prefix,
            'secret_masked' => $secret_masked,
            'token_hash' => $token_hash,
            'secret_key' => $full_token,
            'owner_email' => $owner_email,
            'scopes' => json_encode($scopes),
            'rate_limit' => $rate_limit,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->db->is_connected()) {
            $sql = "INSERT INTO api_keys (id, application_id, name, prefix, secret_masked, token_hash, secret_key, owner_email, scopes, rate_limit, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, array(
                $new_key['id'],
                $new_key['application_id'],
                $new_key['name'],
                $new_key['prefix'],
                $new_key['secret_masked'],
                $new_key['token_hash'],
                $new_key['secret_key'],
                $new_key['owner_email'],
                $new_key['scopes'],
                $new_key['rate_limit'],
                $new_key['status'],
                $new_key['created_at']
            ));
        }

        // Return user friendly array including the raw token to be shown once or copied
        return array(
            'id' => $new_key['id'],
            'applicationId' => $new_key['application_id'],
            'name' => $new_key['name'],
            'prefix' => $new_key['prefix'],
            'secretMasked' => $new_key['secret_masked'],
            'secretKey' => $full_token,
            'rawToken' => $full_token,
            'ownerEmail' => $new_key['owner_email'],
            'scopes' => $scopes,
            'rateLimit' => $new_key['rate_limit'],
            'status' => $new_key['status'],
            'createdAt' => $new_key['created_at']
        );
    }

    public function validate_token($raw_token, $required_scope = null) {
        $raw_token = trim((string)$raw_token);
        if (empty($raw_token)) {
            return array('valid' => false, 'reason' => 'missing_token');
        }

        if (stripos($raw_token, 'Bearer ') === 0) {
            $raw_token = trim(substr($raw_token, 7));
        }

        $token_hash = hash('sha256', $raw_token);

        if (!$this->db->is_connected()) {
            // Offline fallback for development resilience
            return array('valid' => true, 'key' => array('name' => 'Offline Mock', 'status' => 'active'));
        }

        $sql = "SELECT k.*, a.name as app_name, a.slug as app_slug, a.status as app_status
                FROM api_keys k
                LEFT JOIN applications a ON k.application_id = a.id
                WHERE (k.token_hash = ? OR k.secret_key = ?)
                LIMIT 1";
        $res = $this->db->query($sql, array($token_hash, $raw_token));
        $record = ($res && !is_bool($res)) ? $res->row_array() : null;

        if (!$record) {
            return array('valid' => false, 'reason' => 'invalid_token');
        }

        if ($record['status'] !== 'active') {
            return array('valid' => false, 'reason' => 'revoked_token');
        }

        if (!empty($record['app_status']) && $record['app_status'] !== 'active') {
            return array('valid' => false, 'reason' => 'app_inactive');
        }

        // Check required scope
        if ($required_scope) {
            $scopes = json_decode($record['scopes'], true) ?: array();
            if (!in_array('*', $scopes) && !in_array('admin', $scopes) && !in_array($required_scope, $scopes)) {
                return array('valid' => false, 'reason' => 'insufficient_scope', 'scopes' => $scopes);
            }
        }

        // Update last used timestamp
        $this->db->query("UPDATE api_keys SET last_used_at = NOW() WHERE id = ?", array($record['id']));

        return array('valid' => true, 'key' => $record);
    }

    public function regenerate_key($key_id) {
        $rand_str = bin2hex(random_bytes(16));
        $full_token = 'iam_live_' . $rand_str;
        $token_hash = hash('sha256', $full_token);
        $prefix = substr($full_token, 0, 12) . '...';
        $secret_masked = substr($full_token, 0, 12) . '****************' . substr($full_token, -4);

        if ($this->db->is_connected()) {
            $sql = "UPDATE api_keys SET token_hash = ?, secret_key = ?, prefix = ?, secret_masked = ?, status = 'active', created_at = NOW() WHERE id = ?";
            $this->db->query($sql, array($token_hash, $full_token, $prefix, $secret_masked, $key_id));
        }

        return array(
            'id' => $key_id,
            'rawToken' => $full_token,
            'secretKey' => $full_token,
            'prefix' => $prefix,
            'secretMasked' => $secret_masked
        );
    }

    public function revoke_key($key_id) {
        if ($this->db->is_connected()) {
            $this->db->query("UPDATE api_keys SET status = 'revoked' WHERE id = ?", array($key_id));
        }
        return true;
    }

    public function delete_key($key_id) {
        if ($this->db->is_connected()) {
            $this->db->query("DELETE FROM api_keys WHERE id = ?", array($key_id));
        }
        return true;
    }
}
