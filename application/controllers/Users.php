<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->model('Audit_model');
    }

    public function index() {
        $users = $this->User_model->get_all_users();
        $roles = $this->Role_model->get_all_roles();
        $stats = get_mock_stats();

        $data = array(
            'active_tab' => 'users',
            'title'      => 'Identity & User Account Management',
            'users'      => $users,
            'roles'      => $roles,
            'stats'      => $stats
        );

        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar',  $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('users/index', $data);
        echo '</main>';
        $this->load->view('layout/footer', $data);
    }
}

