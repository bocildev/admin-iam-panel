<?php
/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * YOU CAN SET THIS TO ONE OF THE FOLLOWING:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting number below
 */
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 */
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1);
}

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 */
$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 */
$application_folder = 'application';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 */
$view_folder = '';

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER / ROUTER SETUP FOR CI3 COMPATIBILITY
 * --------------------------------------------------------------------
 */

if (defined('STDIN'))
{
	chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE)
{
	$system_path = $_temp.DIRECTORY_SEPARATOR;
}
else
{
	$system_path = str_replace('\\', '/', $system_path).'/';
}

if ( ! is_dir($system_path))
{
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
	exit(3);
}

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_path);
define('FCPATH', dirname(__FILE__).'/');
define('SYSDIR', basename(BASEPATH));

if (is_dir($application_folder))
{
	if (($_temp = realpath($application_folder)) !== FALSE)
	{
		$application_folder = $_temp;
	}

	define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
}
else
{
	if ( ! is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
		exit(3);
	}

	define('APPPATH', BASEPATH.$application_folder.DIRECTORY_SEPARATOR);
}

if ( ! is_dir($view_folder))
{
	if ( ! empty($view_folder) && is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.$view_folder;
	}
	elseif (is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.'views';
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
		exit(3);
	}
}

define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 */
require_once BASEPATH.'core/CodeIgniter.php';
