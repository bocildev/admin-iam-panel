<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks Config
| -------------------------------------------------------------------------
*/

$hook['post_controller_constructor'] = array(
	'class'    => 'IAM_Hook',
	'function' => 'check_permission',
	'filename' => 'IAM_Hook.php',
	'filepath' => 'hooks'
);
