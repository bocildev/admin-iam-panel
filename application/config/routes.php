<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'projects';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Custom IAM Admin Routes
$route['dashboard'] = 'dashboard/index';
$route['users'] = 'users/index';
$route['roles'] = 'roles/index';
$route['api-keys'] = 'apikeys/index';
$route['audit-logs'] = 'auditlogs/index';
$route['ci3-sync'] = 'ci3sync/index';
$route['ai-advisor'] = 'aiadvisor/index';
$route['projects'] = 'projects/index';

// Portofolio API Routes
$route['api/projects'] = 'api/projects';
$route['api/projects/add'] = 'api/add_project';
$route['api/projects/update'] = 'api/update_project';
$route['api/projects/delete'] = 'api/delete_project';
$route['api/projects/upload-image'] = 'api/upload_image';
$route['api/portfolio/settings'] = 'api/get_settings';
$route['api/portfolio/settings/update'] = 'api/update_settings';
$route['api/portfolio/layout'] = 'api/get_layout';
$route['api/portfolio/layout/update'] = 'api/update_layout';

// Dedicated Motion Portfolio Routes (Moch. Adam Athallahrich)
$route['motion-portfolio'] = 'motion_portfolio/index';
$route['motion-portfolio/save-profile'] = 'motion_portfolio/save_profile';
$route['motion-portfolio/save-project'] = 'motion_portfolio/save_project';
$route['motion-portfolio/delete-project/(:num)'] = 'motion_portfolio/delete_project/$1';
$route['motion-portfolio/save-skills'] = 'motion_portfolio/save_skills';
$route['motion-portfolio/save-tools'] = 'motion_portfolio/save_tools';
$route['motion-portfolio/upload-media'] = 'motion_portfolio/upload_media';

// Motion Portfolio REST API Routes
$route['api/motion/portfolio'] = 'api/motion_portfolio';
$route['api/motion/projects'] = 'api/motion_projects';
$route['api/motion/project/save'] = 'api/motion_save_project';
$route['api/motion/config/save'] = 'api/motion_save_config';

// Auth & Admin IAM Routes
$route['auth/login'] = 'auth/login';
$route['auth/logout'] = 'auth/logout';
$route['auth/me'] = 'auth/me';
$route['auth/admins'] = 'auth/list_admins';
$route['auth/admins/create'] = 'auth/create_admin';
$route['auth/admins/update'] = 'auth/update_admin';

// Applications & Dynamic Database Provisioning Routes
$route['applications'] = 'applications/index';
$route['applications/create'] = 'applications/create';
$route['applications/update'] = 'applications/update';
$route['applications/delete'] = 'applications/delete';
$route['applications/test-db'] = 'applications/test_db';

// Database Manager Route
$route['applications/database/(:any)'] = 'database_manager/index/$1';

// Dynamic CMS Routes (Section 3.4)
$route['contents/save/(:any)'] = 'contents/save_content/$1';
$route['contents/(:any)/(:any)'] = 'contents/get_content_key/$1/$2';
$route['contents/(:any)'] = 'contents/get_contents/$1';

// Application Access IAM Routes (Section 3.5)
$route['access/app/(:any)'] = 'access/get_app_users/$1';
$route['access/grant'] = 'access/grant';
$route['access/revoke'] = 'access/revoke';



