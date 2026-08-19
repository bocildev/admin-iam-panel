<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Input {

	public function post($index = NULL, $xss_clean = NULL)
	{
		if ($index === NULL) {
			return $_POST;
		}

		if (isset($_POST[$index])) {
			return $_POST[$index];
		}

		// Also check raw php://input JSON body if request is application/json
		$rawInput = file_get_contents('php://input');
		if (!empty($rawInput)) {
			$json = json_decode($rawInput, true);
			if (is_array($json) && isset($json[$index])) {
				return $json[$index];
			}
		}

		return NULL;
	}

	public function get($index = NULL, $xss_clean = NULL)
	{
		if ($index === NULL) {
			return $_GET;
		}
		return isset($_GET[$index]) ? $_GET[$index] : NULL;
	}

	public function get_post($index = NULL, $xss_clean = NULL)
	{
		$val = $this->post($index, $xss_clean);
		return ($val !== NULL) ? $val : $this->get($index, $xss_clean);
	}

	public function raw_json()
	{
		$raw = file_get_contents('php://input');
		return json_decode($raw, true) ?: [];
	}

	public function user_agent()
	{
		return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'CodeIgniter 3 Client';
	}

	public function ip_address()
	{
		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
	}
}
