<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apikey_model extends CI_Model {

    public function get_all_keys() {
        if ($this->db->is_connected()) {
            $res = $this->db->query("SELECT * FROM api_keys ORDER BY created_at DESC");
            $rows = ($res && !is_bool($res)) ? $res->result_array() : array();
            if (!empty($rows)) {
                $keys = array();
                foreach ($rows as $r) {
                    $keys[] = array(
                        'id' => $r['id'],
                        'name' => $r['name'],
                        'prefix' => $r['prefix'],
                        'secretMasked' => $r['secret_masked'],
                        'ownerId' => 'ADM-0001',
                        'ownerName' => $r['owner_email'],
                        'scopes' => json_decode($r['scopes'], true) ?: array(),
                        'rateLimit' => (int)$r['rate_limit'],
                        'status' => $r['status'],
                    );
                }
                return $keys;
            }
        }
        return array();
    }

    public function add_key($data) {
        $id = 'KEY-' . sprintf('%02d', rand(4, 99));
        $rand_str = bin2hex(random_bytes(8));
        $prefix = 'th_live_' . substr($rand_str, 0, 4) . '...';
        $secret_masked = 'th_live_' . substr($rand_str, 0, 4) . '********************' . substr($rand_str, -4);

        $new_key = array(
            'id' => $id,
            'name' => $data['name'],
            'prefix' => $prefix,
            'secretMasked' => $secret_masked,
            'ownerEmail' => $data['ownerEmail'],
            'scopes' => isset($data['scopes']) ? $data['scopes'] : array('comics.read'),
            'rateLimit' => isset($data['rateLimit']) ? (int)$data['rateLimit'] : 120,
            'status' => 'active',
            'createdAt' => date('Y-m-d H:i:s')
        );

        if ($this->db->is_connected()) {
            $sql = "INSERT INTO api_keys (id, name, prefix, secret_masked, owner_email, scopes, rate_limit, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, array(
                $new_key['id'],
                $new_key['name'],
                $new_key['prefix'],
                $new_key['secretMasked'],
                $new_key['ownerEmail'],
                json_encode($new_key['scopes']),
                $new_key['rateLimit'],
                'active',
                $new_key['createdAt']
            ));
        }

        return $new_key;
    }

    public function revoke_key($key_id) {
        if ($this->db->is_connected()) {
            $this->db->query("UPDATE api_keys SET status = 'revoked' WHERE id = ?", array($key_id));
        }
        return true;
    }
}
