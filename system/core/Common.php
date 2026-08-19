<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('is_php'))
{
	function is_php($version)
	{
		static $_is_php;
		$version = (string) $version;
		if ( ! isset($_is_php[$version]))
		{
			$_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
		}
		return $_is_php[$version];
	}
}

if ( ! function_exists('show_error'))
{
	function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
	{
		set_status_header($status_code);
		echo "<h1>{$heading}</h1><p>{$message}</p>";
		exit;
	}
}

if ( ! function_exists('show_404'))
{
	function show_404($page = '', $log_error = TRUE)
	{
		set_status_header(404);
		echo "<h1>404 Page Not Found</h1><p>The page you requested was not found.</p>";
		exit;
	}
}

if ( ! function_exists('set_status_header'))
{
	function set_status_header($code = 200, $text = '')
	{
		if (is_cli()) return;
		header("HTTP/1.1 {$code} {$text}", TRUE, $code);
	}
}

if ( ! function_exists('is_cli'))
{
	function is_cli()
	{
		return (PHP_SAPI === 'cli' OR defined('STDIN'));
	}
}

if ( ! function_exists('&get_instance'))
{
	function &get_instance()
	{
		return CI_Controller::get_instance();
	}
}
