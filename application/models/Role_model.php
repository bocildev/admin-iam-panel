<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model {

    public function get_all_roles() {
        return array(
            array(
                'id' => 'ROLE-01',
                'name' => 'super_admin',
                'displayName' => 'Super Administrator',
                'description' => 'Full root access to all security scopes, API keys, and database clusters.',
                'color' => 'red',
                'isSystemRole' => true,
                'userCount' => 1,
                'permissions' => array('system.all', 'db.manage', 'apps.manage')
            ),
            array(
                'id' => 'ROLE-02',
                'name' => 'admin',
                'displayName' => 'Platform Administrator',
                'description' => 'Standard admin access to manage portfolio apps and content.',
                'color' => 'cyan',
                'isSystemRole' => true,
                'userCount' => 1,
                'permissions' => array('apps.manage', 'content.manage')
            )
        );
    }
}
