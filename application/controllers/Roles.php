<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Role_model');
        $this->load->model('Audit_model');
    }

    public function index() {
        $roles             = $this->Role_model->get_all_roles();
        $permission_groups = get_permission_groups();
        $stats             = get_mock_stats();

        $data = array(
            'active_tab'        => 'roles',
            'title'             => 'Role Access Control Matrix (RBAC)',
            'roles'             => $roles,
            'permission_groups' => $permission_groups,
            'stats'             => $stats
        );

        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar',  $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('roles/index', $data);
        echo '</main>';
        $this->load->view('layout/footer', $data);
    }
}

