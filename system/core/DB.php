<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_DB {

	private $pdo = null;
	private $db_connected = false;
	private $connection_error = null;
	private $last_error = null;
	private $db_config = array();

	public function __construct()
	{
		$db_file = APPPATH . 'config/database.php';
		if (file_exists($db_file)) {
			include($db_file);
			$active_group = isset($active_group) ? $active_group : 'default';
			if (isset($db[$active_group])) {
				$this->db_config = $db[$active_group];
			}
		}

		$hostname = isset($this->db_config['hostname']) ? $this->db_config['hostname'] : 'localhost';
		$username = isset($this->db_config['username']) ? $this->db_config['username'] : 'root';
		$password = isset($this->db_config['password']) ? $this->db_config['password'] : '';
		$database = isset($this->db_config['database']) ? $this->db_config['database'] : 'saas_iam_db';

		try {
			$dsn = "mysql:host={$hostname};dbname={$database};charset=utf8mb4";
			$this->pdo = new PDO($dsn, $username, $password, array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			));
			$this->db_connected = true;
			$this->connection_error = null;
		} catch (Exception $e) {
			$this->db_connected = false;
			$this->connection_error = array(
				'message'  => $e->getMessage(),
				'code'     => $e->getCode(),
				'host'     => $hostname,
				'database' => $database,
				'user'     => $username,
				'file'     => $e->getFile(),
				'line'     => $e->getLine()
			);
		}
	}

	public function is_connected()
	{
		return $this->db_connected;
	}

	public function get_connection_error()
	{
		return $this->connection_error;
	}

	public function get_last_error()
	{
		return $this->last_error;
	}

	public function error()
	{
		if ($this->last_error) {
			return array(
				'code'    => isset($this->last_error['code']) ? $this->last_error['code'] : '',
				'message' => isset($this->last_error['message']) ? $this->last_error['message'] : ''
			);
		}
		if ($this->connection_error) {
			return array(
				'code'    => isset($this->connection_error['code']) ? $this->connection_error['code'] : '',
				'message' => isset($this->connection_error['message']) ? $this->connection_error['message'] : ''
			);
		}
		return array('code' => 0, 'message' => '');
	}

	public function get_config_info()
	{
		return array(
			'hostname'  => isset($this->db_config['hostname']) ? $this->db_config['hostname'] : 'localhost',
			'database'  => isset($this->db_config['database']) ? $this->db_config['database'] : 'saas_iam_db',
			'username'  => isset($this->db_config['username']) ? $this->db_config['username'] : 'root',
			'connected' => $this->db_connected
		);
	}

	public function table_exists($table_name)
	{
		if (!$this->db_connected || !$this->pdo) return false;
		try {
			$stmt = $this->pdo->prepare("SHOW TABLES LIKE ?");
			$stmt->execute(array($table_name));
			return ($stmt->rowCount() > 0 || count($stmt->fetchAll()) > 0);
		} catch (Exception $e) {
			return false;
		}
	}

	public function query($sql, $binds = array())
	{
		if (!$this->db_connected || !$this->pdo) {
			$this->last_error = array(
				'message' => $this->connection_error ? $this->connection_error['message'] : 'Database is not connected',
				'code'    => $this->connection_error ? $this->connection_error['code'] : 'NO_CONNECTION',
				'sql'     => $sql
			);
			return new CI_DB_Result(array());
		}

		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute((array)$binds);
			$this->last_error = null;

			if (preg_match('/^\s*(SELECT|SHOW|EXPLAIN)\s+/i', $sql)) {
				$data = $stmt->fetchAll();
				return new CI_DB_Result($data);
			}

			return true;
		} catch (Exception $e) {
			$this->last_error = array(
				'message' => $e->getMessage(),
				'code'    => $e->getCode(),
				'sql'     => $sql,
				'binds'   => $binds,
				'file'    => $e->getFile(),
				'line'    => $e->getLine()
			);
			return false;
		}
	}

	public function insert_id()
	{
		return ($this->db_connected && $this->pdo) ? (int)$this->pdo->lastInsertId() : 0;
	}
}

class CI_DB_Result {
	private $data = array();

	public function __construct($data)
	{
		$this->data = is_array($data) ? $data : array();
	}

	public function result_array()
	{
		return $this->data;
	}

	public function row_array()
	{
		return isset($this->data[0]) ? $this->data[0] : null;
	}

	public function num_rows()
	{
		return count($this->data);
	}
}
