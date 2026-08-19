<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Input.php';
require_once BASEPATH . 'core/Output.php';
require_once BASEPATH . 'core/Session.php';
require_once BASEPATH . 'core/DB.php';
require_once BASEPATH . 'core/Loader.php';
require_once BASEPATH . 'core/Controller.php';
require_once BASEPATH . 'core/Model.php';

$my_controller_file = APPPATH . 'core/MY_Controller.php';
if (file_exists($my_controller_file)) {
    require_once $my_controller_file;
}

// Helper autoload
require_once APPPATH . 'helpers/iam_helper.php';

// Parse URI segments
$uri_string = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');

// Strip script directory prefix if present
$script_name = $_SERVER['SCRIPT_NAME'];
$dir_name = dirname($script_name);
if ($dir_name !== '/' && $dir_name !== '\\') {
	$uri_string = str_replace($dir_name, '', $uri_string);
}
$uri_string = str_replace('/index.php', '', $uri_string);
$uri_string = strtok($uri_string, '?');
$uri_string = trim($uri_string, '/');

// Load routes config and match routes
$route = array();
$routes_file = APPPATH . 'config/routes.php';
if (file_exists($routes_file)) {
	include $routes_file;
}

if (isset($route[$uri_string])) {
	$uri_string = $route[$uri_string];
} else {
	foreach ($route as $key => $val) {
		$key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);
		if (preg_match('#^'.$key.'$#', $uri_string)) {
			$uri_string = preg_replace('#^'.$key.'$#', $val, $uri_string);
			break;
		}
	}
}

$segments = explode('/', $uri_string);

$controller_name = !empty($segments[0]) ? ucfirst($segments[0]) : 'Dashboard';
$method_name = isset($segments[1]) && !empty($segments[1]) ? $segments[1] : 'index';
$params = array_slice($segments, 2);

// Check if controller file exists
$controller_file = APPPATH . 'controllers/' . $controller_name . '.php';

if (!file_exists($controller_file)) {
	// Fallback to Dashboard controller
	$controller_name = 'Dashboard';
	$controller_file = APPPATH . 'controllers/Dashboard.php';
}

require_once $controller_file;

if (!class_exists($controller_name)) {
	show_error("Controller class {$controller_name} does not exist.", 500);
}

$CI = new $controller_name();

// Execute hook if defined
$hook_file = APPPATH . 'hooks/IAM_Hook.php';
if (file_exists($hook_file)) {
	require_once $hook_file;
	if (class_exists('IAM_Hook')) {
		$hook = new IAM_Hook();
		if (method_exists($hook, 'check_permission')) {
			$hook->check_permission();
		}
	}
}

if (!method_exists($CI, $method_name)) {
	$method_name = 'index';
}

call_user_func_array(array($CI, $method_name), $params);
