<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Controller {

	private static $instance;

	/**
	 * Storage bucket for all dynamically loaded components (models, libraries, etc.)
	 * Using a plain array avoids __get/__set infinite recursion.
	 */
	private $_ci_dynamic_props = array();

	public function __construct()
	{
		self::$instance =& $this;

		// Store core services into the dynamic bucket so __get can find them
		$this->_ci_dynamic_props['load']    = new CI_Loader();
		$this->_ci_dynamic_props['input']   = new CI_Input();
		$this->_ci_dynamic_props['output']  = new CI_Output();
		$this->_ci_dynamic_props['session'] = new CI_Session();
		$this->_ci_dynamic_props['db']      = new CI_DB();
	}

	public static function &get_instance()
	{
		return self::$instance;
	}

	/**
	 * Magic getter — transparently returns any dynamically attached property
	 * (models, libraries, session, db, load, input, output …).
	 */
	public function __get($key)
	{
		if (array_key_exists($key, $this->_ci_dynamic_props)) {
			return $this->_ci_dynamic_props[$key];
		}
		// Return null so callers get a clear "null, not found" rather than a
		// fatal "undefined property" error.
		$null = NULL;
		return $null;
	}

	/**
	 * Magic setter — all property assignments (load->model(), etc.) go into
	 * the same bucket so __get can find them later.
	 */
	public function __set($key, $value)
	{
		$this->_ci_dynamic_props[$key] = $value;
	}
}
