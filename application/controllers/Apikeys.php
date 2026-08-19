<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apikeys extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Apikey_model');
        $this->load->model('User_model');
        $this->load->model('Audit_model');
    }

    public function index() {
        $api_keys = $this->Apikey_model->get_all_keys();
        $users    = $this->User_model->get_all_users();
        $stats    = get_mock_stats();

        $data = array(
            'active_tab' => 'api_keys',
            'title'      => 'REST API Token & Key Management',
            'api_keys'   => $api_keys,
            'users'      => $users,
            'stats'      => $stats
        );

        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar',  $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('apikeys/index', $data);
        echo '</main>';
        $this->load->view('layout/footer', $data);
    }
}

