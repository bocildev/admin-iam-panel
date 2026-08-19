<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Loader {

	protected $_ci_loaded_models = array();

	public function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}

	public function view($view, $vars = array(), $return = FALSE)
	{
		$path = VIEWPATH . $view . '.php';
		if ( ! file_exists($path)) {
			$path = VIEWPATH . $view;
		}

		extract($vars);

		if ($return) {
			ob_start();
			include $path;
			$buffer = ob_get_contents();
			ob_end_clean();
			return $buffer;
		}

		include $path;
	}

	public function model($model, $name = '', $db_conn = FALSE)
	{
		if (empty($model)) return;

		$path = APPPATH . 'models/' . $model . '.php';
		if (file_exists($path)) {
			require_once $path;

			// Determine class name: try exact, then ucfirst
			if (class_exists($model)) {
				$class = $model;
			} elseif (class_exists(ucfirst($model))) {
				$class = ucfirst($model);
			} else {
				// Build class name from filename (e.g. "User_model" -> "User_model")
				$class = $model;
			}

			// Preserve the exact property name as passed (e.g. "User_model" stays "User_model")
			// This ensures $this->User_model works in controllers
			$object_name = $name ? $name : $model;

			$CI =& get_instance();
			$CI->$object_name = new $class();
			$this->_ci_loaded_models[] = $object_name;
		}
	}

	public function helper($helpers = array())
	{
		foreach ((array)$helpers as $helper) {
			$path = APPPATH . 'helpers/' . $helper . '_helper.php';
			if (file_exists($path)) {
				require_once $path;
			}
		}
	}

	public function library($library, $params = NULL, $object_name = NULL)
	{
		// Native CI libraries compatibility layer
		if ($library === 'session') {
			$CI =& get_instance();
			if (!isset($CI->session)) {
				$CI->session = new CI_Session();
			}
		}
	}
}
