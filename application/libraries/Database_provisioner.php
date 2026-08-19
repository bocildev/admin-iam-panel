<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database_provisioner {

    protected $CI;
    private $cipher = "aes-256-cbc";
    private $encryption_key;

    public function __construct() {
        $this->CI =& get_instance();
        $secret = (isset($this->CI->config) && method_exists($this->CI->config, 'item')) ? $this->CI->config->item('encryption_key') : 'SaaS_IAM_SecretKey_2026';
        $this->encryption_key = hash('sha256', $secret ?: 'SaaS_IAM_SecretKey_2026');
    }

    /**
     * Encrypt sensitive string (e.g. DB password)
     */
    public function encrypt($plain_text) {
        $iv_length = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $encrypted = openssl_encrypt($plain_text, $this->cipher, $this->encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    /**
     * Decrypt sensitive string
     */
    public function decrypt($encrypted_text) {
        $data = base64_decode($encrypted_text);
        if (strpos($data, '::') === false) return null;
        list($encrypted, $iv) = explode('::', $data, 2);
        return openssl_decrypt($encrypted, $this->cipher, $this->encryption_key, 0, $iv);
    }

    /**
     * Programmatically provision an isolated MySQL Database for an application
     */
    public function provision_database($app_name, $app_slug) {
        $clean_slug = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $app_slug));
        $db_name = 'db_' . substr($clean_slug, 0, 40);
        $db_user = 'usr_' . substr($clean_slug, 0, 20);
        $raw_password = 'P@ss_' . bin2hex(random_bytes(6));
        $host = '127.0.0.1';
        $port = 3306;

        try {
            $pdo = new PDO("mysql:host={$host};port={$port}", 'root', '', array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ));

            // Create Database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

            // Create User & Grant Privileges for localhost & wildcard %
            $pdo->exec("CREATE USER IF NOT EXISTS '{$db_user}'@'%' IDENTIFIED BY '{$raw_password}';");
            $pdo->exec("CREATE USER IF NOT EXISTS '{$db_user}'@'localhost' IDENTIFIED BY '{$raw_password}';");
            $pdo->exec("ALTER USER '{$db_user}'@'%' IDENTIFIED BY '{$raw_password}';");
            $pdo->exec("ALTER USER '{$db_user}'@'localhost' IDENTIFIED BY '{$raw_password}';");
            $pdo->exec("GRANT ALL PRIVILEGES ON `{$db_name}`.* TO '{$db_user}'@'%';");
            $pdo->exec("GRANT ALL PRIVILEGES ON `{$db_name}`.* TO '{$db_user}'@'localhost';");
            $pdo->exec("FLUSH PRIVILEGES;");

            return array(
                'success' => true,
                'db_host' => $host,
                'db_port' => $port,
                'db_name' => $db_name,
                'db_user' => $db_user,
                'raw_password' => $raw_password,
                'encrypted_password' => $this->encrypt($raw_password)
            );
        } catch (Exception $e) {
            return array(
                'success' => false,
                'error' => 'Database provisioning failed: ' . $e->getMessage()
            );
        }
    }

    /**
     * Test connection to a provisioned application database
     */
    public function test_connection($host, $port, $db_name, $db_user, $encrypted_password) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            return array('success' => true, 'message' => 'Connection successful');
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Fetch all tables in the database
     */
    public function get_tables($host, $port, $db_name, $db_user, $encrypted_password) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return array('success' => true, 'tables' => $tables);
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Fetch records from a specific table
     */
    public function get_table_data($host, $port, $db_name, $db_user, $encrypted_password, $table_name, $limit = 50) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            // Validate table name to prevent basic SQL injection
            $clean_table = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
            if (empty($clean_table)) {
                throw new Exception("Invalid table name.");
            }
            $limit = (int)$limit;
            
            $stmt = $pdo->query("SELECT * FROM `{$clean_table}` LIMIT {$limit}");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $columns = [];
            if (!empty($data)) {
                $columns = array_keys($data[0]);
            } else {
                $colStmt = $pdo->query("SHOW COLUMNS FROM `{$clean_table}`");
                $columns = $colStmt->fetchAll(PDO::FETCH_COLUMN);
            }
            
            return array('success' => true, 'data' => $data, 'columns' => $columns);
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Guess table relations based on column names (e.g. user_id -> users)
     */
    public function get_table_schema_and_relations($host, $port, $db_name, $db_user, $encrypted_password, $table_name) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            $clean_table = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
            if (empty($clean_table)) throw new Exception("Invalid table name.");
            
            // Get all tables to match against
            $stmt = $pdo->query("SHOW TABLES");
            $all_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Get columns for the requested table
            $stmt = $pdo->query("SHOW COLUMNS FROM `{$clean_table}`");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $relations = [];
            foreach ($columns as $col) {
                $colName = $col['Field'];
                // Detect relations heuristically: ends with _id
                if (preg_match('/^(.*)_id$/', $colName, $matches)) {
                    $base_name = $matches[1]; // e.g. 'location' or 'jastiper'
                    $matched_table = null;
                    
                    if ($colName === 'jastiper_id' && in_array('jastiper_users', $all_tables)) {
                        $matched_table = 'jastiper_users';
                    } else {
                        // Look for table matching base_name, or base_name + s, or ends with base_name + s
                        foreach ($all_tables as $t) {
                            $suffix1 = '_' . $base_name;
                            $suffix2 = '_' . $base_name . 's';
                            if (
                                $t === $base_name ||
                                $t === $base_name . 's' ||
                                substr($t, -strlen($suffix1)) === $suffix1 ||
                                substr($t, -strlen($suffix2)) === $suffix2
                            ) {
                                $matched_table = $t;
                                break;
                            }
                        }
                    }

                    if ($matched_table) {
                        $relations[$colName] = $matched_table;
                    }
                }
            }

            return array('success' => true, 'columns' => $columns, 'relations' => $relations);
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Scan all tables and get full relation mapping
     */
    public function get_all_relations($host, $port, $db_name, $db_user, $encrypted_password) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            $stmt = $pdo->query("SHOW TABLES");
            $all_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $child_to_parent = [];
            $parent_to_children = [];
            
            foreach ($all_tables as $table) {
                $parent_to_children[$table] = [];
            }
            
            foreach ($all_tables as $table) {
                $stmt = $pdo->query("SHOW COLUMNS FROM `{$table}`");
                $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $child_to_parent[$table] = [];
                
                foreach ($columns as $col) {
                    $colName = $col['Field'];
                    if (preg_match('/^(.*)_id$/', $colName, $matches)) {
                        $base_name = $matches[1];
                        $matched_table = null;
                        
                        if ($colName === 'jastiper_id' && in_array('jastiper_users', $all_tables)) {
                            $matched_table = 'jastiper_users';
                        } else {
                            foreach ($all_tables as $t) {
                                $suffix1 = '_' . $base_name;
                                $suffix2 = '_' . $base_name . 's';
                                if (
                                    $t === $base_name ||
                                    $t === $base_name . 's' ||
                                    substr($t, -strlen($suffix1)) === $suffix1 ||
                                    substr($t, -strlen($suffix2)) === $suffix2
                                ) {
                                    $matched_table = $t;
                                    break;
                                }
                            }
                        }
                        
                        if ($matched_table) {
                            $child_to_parent[$table][$colName] = $matched_table;
                            if (!in_array($table, $parent_to_children[$matched_table])) {
                                // Store as assoc array so we know which column points to it
                                $parent_to_children[$matched_table][] = ['table' => $table, 'fk_col' => $colName];
                            }
                        }
                    }
                }
            }
            
            return array('success' => true, 'tables' => $all_tables, 'child_to_parent' => $child_to_parent, 'parent_to_children' => $parent_to_children);
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Get table data filtered by a foreign key
     */
    public function get_table_data_by_fk($host, $port, $db_name, $db_user, $encrypted_password, $table_name, $fk_col, $fk_val, $limit = 50) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            $clean_table = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
            $clean_col = preg_replace('/[^a-zA-Z0-9_]/', '', $fk_col);
            
            $stmt = $pdo->prepare("SELECT * FROM `{$clean_table}` WHERE `{$clean_col}` = :fk_val LIMIT :limit");
            $stmt->bindValue(':fk_val', $fk_val);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return array('success' => true, 'data' => $data);
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    private function _get_primary_key($pdo, $table, &$pk_cache) {
        if (isset($pk_cache[$table])) return $pk_cache[$table];
        
        $stmt = $pdo->query("SHOW KEYS FROM `{$table}` WHERE Key_name = 'PRIMARY'");
        $key = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($key) {
            $pk_cache[$table] = $key['Column_name'];
            return $key['Column_name'];
        }
        
        $stmt = $pdo->query("SHOW COLUMNS FROM `{$table}`");
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pk = $cols[0]['Field'] ?? 'id';
        $pk_cache[$table] = $pk;
        return $pk;
    }

    private function _collect_cascading_impact($pdo, $relations, $table, $pk_val, &$impact, &$pk_cache) {
        if (!isset($impact[$table])) $impact[$table] = 0;
        $impact[$table]++;
        
        $children = $relations['parent_to_children'][$table] ?? [];
        foreach ($children as $child) {
            $child_table = $child['table'];
            $fk_col = $child['fk_col'];
            
            $child_pk = $this->_get_primary_key($pdo, $child_table, $pk_cache);
            $stmt = $pdo->prepare("SELECT `{$child_pk}` FROM `{$child_table}` WHERE `{$fk_col}` = ?");
            $stmt->execute([$pk_val]);
            $child_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($child_ids as $cid) {
                $this->_collect_cascading_impact($pdo, $relations, $child_table, $cid, $impact, $pk_cache);
            }
        }
    }

    private function _do_cascading_delete($pdo, $relations, $table, $pk_col, $pk_val, &$pk_cache) {
        $children = $relations['parent_to_children'][$table] ?? [];
        foreach ($children as $child) {
            $child_table = $child['table'];
            $fk_col = $child['fk_col'];
            
            $child_pk = $this->_get_primary_key($pdo, $child_table, $pk_cache);
            $stmt = $pdo->prepare("SELECT `{$child_pk}` FROM `{$child_table}` WHERE `{$fk_col}` = ?");
            $stmt->execute([$pk_val]);
            $child_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($child_ids as $cid) {
                $this->_do_cascading_delete($pdo, $relations, $child_table, $child_pk, $cid, $pk_cache);
            }
        }
        $stmt = $pdo->prepare("DELETE FROM `{$table}` WHERE `{$pk_col}` = ?");
        $stmt->execute([$pk_val]);
    }

    public function get_cascading_impact($host, $port, $db_name, $db_user, $encrypted_password, $table_name, $pk_value) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            $relations = $this->get_all_relations($host, $port, $db_name, $db_user, $encrypted_password);
            if (!$relations['success']) return $relations;

            $impact = [];
            $pk_cache = [];
            $this->_collect_cascading_impact($pdo, $relations, $table_name, $pk_value, $impact, $pk_cache);
            
            return array('success' => true, 'impact' => $impact);
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    public function cascading_delete($host, $port, $db_name, $db_user, $encrypted_password, $table_name, $pk_column, $pk_value) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            $relations = $this->get_all_relations($host, $port, $db_name, $db_user, $encrypted_password);
            if (!$relations['success']) return $relations;

            $pdo->beginTransaction();
            $pk_cache = [];
            $this->_do_cascading_delete($pdo, $relations, $table_name, $pk_column, $pk_value, $pk_cache);
            $pdo->commit();
            
            return array('success' => true);
        } catch (Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Soft Delete (Toggle active status)
     */
    public function soft_delete_record($host, $port, $db_name, $db_user, $encrypted_password, $table_name, $pk_column, $pk_value) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            $clean_table = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
            $clean_pk = preg_replace('/[^a-zA-Z0-9_]/', '', $pk_column);
            
            $stmt = $pdo->query("SHOW COLUMNS FROM `{$clean_table}`");
            $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $colNames = array_column($cols, 'Field');
            
            $targetCol = null;
            $toggleVal = null;
            
            $stmt = $pdo->prepare("SELECT * FROM `{$clean_table}` WHERE `{$clean_pk}` = ?");
            $stmt->execute([$pk_value]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$row) return array('success' => false, 'error' => 'Record not found');
            
            if (in_array('is_active', $colNames)) {
                $targetCol = 'is_active';
                $toggleVal = $row['is_active'] ? 0 : 1;
            } else if (in_array('status', $colNames)) {
                $targetCol = 'status';
                $val = strtolower($row['status']);
                if (in_array($val, ['active', 'aktif', '1', 'on'])) {
                    $toggleVal = 'inactive';
                } else {
                    $toggleVal = 'active';
                }
            } else if (in_array('deleted_at', $colNames)) {
                $targetCol = 'deleted_at';
                $toggleVal = $row['deleted_at'] ? null : date('Y-m-d H:i:s');
            } else {
                return array('success' => false, 'error' => 'Tabel ini tidak mendukung Soft Delete (tidak ada kolom is_active, status, atau deleted_at)');
            }
            
            $stmt = $pdo->prepare("UPDATE `{$clean_table}` SET `{$targetCol}` = ? WHERE `{$clean_pk}` = ?");
            $stmt->execute([$toggleVal, $pk_value]);
            
            return array('success' => true, 'new_status' => $toggleVal, 'column' => $targetCol);
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Delete record
     */
    public function delete_record($host, $port, $db_name, $db_user, $encrypted_password, $table_name, $pk_column, $pk_value) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            $clean_table = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
            $clean_pk = preg_replace('/[^a-zA-Z0-9_]/', '', $pk_column);
            
            $stmt = $pdo->prepare("DELETE FROM `{$clean_table}` WHERE `{$clean_pk}` = :id");
            $stmt->execute(['id' => $pk_value]);
            
            return array('success' => true);
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Insert record
     */
    public function insert_record($host, $port, $db_name, $db_user, $encrypted_password, $table_name, $data) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            $clean_table = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
            
            $columns = [];
            $placeholders = [];
            $values = [];
            foreach ($data as $key => $val) {
                $clean_key = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
                if (empty($clean_key)) continue;
                $columns[] = "`{$clean_key}`";
                $placeholders[] = ":{$clean_key}";
                $values[$clean_key] = $val;
            }

            if (empty($columns)) throw new Exception("No data to insert.");

            $colStr = implode(', ', $columns);
            $valStr = implode(', ', $placeholders);
            
            $stmt = $pdo->prepare("INSERT INTO `{$clean_table}` ({$colStr}) VALUES ({$valStr})");
            $stmt->execute($values);
            
            return array('success' => true, 'insert_id' => $pdo->lastInsertId());
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Update record
     */
    public function update_record($host, $port, $db_name, $db_user, $encrypted_password, $table_name, $pk_column, $pk_value, $data) {
        $password = $this->decrypt($encrypted_password);
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3
            ));
            
            $clean_table = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
            $clean_pk = preg_replace('/[^a-zA-Z0-9_]/', '', $pk_column);
            
            $set_clauses = [];
            $values = [];
            foreach ($data as $key => $val) {
                $clean_key = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
                if (empty($clean_key) || $clean_key === $clean_pk) continue; // skip PK
                $set_clauses[] = "`{$clean_key}` = :{$clean_key}";
                $values[$clean_key] = $val;
            }

            if (empty($set_clauses)) throw new Exception("No data to update.");

            $values['primary_key_val'] = $pk_value;
            $setStr = implode(', ', $set_clauses);
            
            $stmt = $pdo->prepare("UPDATE `{$clean_table}` SET {$setStr} WHERE `{$clean_pk}` = :primary_key_val");
            $stmt->execute($values);
            
            return array('success' => true);
        } catch (Exception $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    }
}
