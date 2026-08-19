<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_DB {

	private $pdo = null;
	private $db_connected = false;

	public function __construct()
	{
		$db_config = array();
		$db_file = APPPATH . 'config/database.php';
		if (file_exists($db_file)) {
			include($db_file);
			$active_group = isset($active_group) ? $active_group : 'default';
			if (isset($db[$active_group])) {
				$db_config = $db[$active_group];
			}
		}

		$hostname = isset($db_config['hostname']) ? $db_config['hostname'] : 'localhost';
		$username = isset($db_config['username']) ? $db_config['username'] : 'root';
		$password = isset($db_config['password']) ? $db_config['password'] : '';
		$database = isset($db_config['database']) ? $db_config['database'] : 'toonhub_iam';

		try {
			$dsn = "mysql:host={$hostname};dbname={$database};charset=utf8mb4";
			$this->pdo = new PDO($dsn, $username, $password, array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			));
			$this->db_connected = true;
		} catch (Exception $e) {
			$this->db_connected = false;
		}
	}

	public function is_connected()
	{
		return $this->db_connected;
	}

	public function query($sql, $binds = array())
	{
		if (!$this->db_connected || !$this->pdo) {
			return new CI_DB_Result(array());
		}

		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute((array)$binds);

			if (preg_match('/^\s*(SELECT|SHOW|EXPLAIN)\s+/i', $sql)) {
				$data = $stmt->fetchAll();
				return new CI_DB_Result($data);
			}

			return true;
		} catch (Exception $e) {
			return false;
		}
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
