<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Session {

	public function __construct()
	{
		if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
			session_start();
		}
	}

	public function userdata($key = NULL)
	{
		if ($key === NULL) {
			return $_SESSION;
		}
		return isset($_SESSION[$key]) ? $_SESSION[$key] : NULL;
	}

	public function set_userdata($data, $value = NULL)
	{
		if (is_array($data)) {
			foreach ($data as $k => $v) {
				$_SESSION[$k] = $v;
			}
		} else {
			$_SESSION[$data] = $value;
		}
	}

	public function unset_userdata($key)
	{
		if (is_array($key)) {
			foreach ($key as $k) {
				unset($_SESSION[$k]);
			}
		} else {
			unset($_SESSION[$key]);
		}
	}

	public function sess_destroy()
	{
		session_destroy();
	}
}
