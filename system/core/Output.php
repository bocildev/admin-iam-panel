<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Output {

	public function set_content_type($mime_type, $charset = NULL)
	{
		if ($mime_type === 'application/json') {
			header('Content-Type: application/json; charset=' . ($charset ?: 'utf-8'));
		} else {
			header('Content-Type: ' . $mime_type);
		}
		return $this;
	}

	public function set_output($output)
	{
		echo $output;
		return $this;
	}

	public function set_status_header($code = 200, $text = '')
	{
		set_status_header($code, $text);
		return $this;
	}
}
