<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['base_url'] = 'http://localhost/iam-admin-panel/';
$config['index_page'] = '';
$config['uri_protocol']	= 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']	= 'english';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = TRUE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = FALSE;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['encryption_key'] = 'c38a9d1b0e7f45a230198f65e2194c7b';

// CI3 Session Config
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;
$config['sess_encrypt_cookie'] = TRUE;
$config['sess_use_database'] = TRUE;
$config['sess_table_name'] = 'ci_sessions';

$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= TRUE;
$config['global_xss_filtering'] = FALSE;
$config['csrf_protection'] = FALSE;
