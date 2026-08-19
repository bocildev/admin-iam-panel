<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ci3sync extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Ci3sync_model');
        $this->load->model('Audit_model');
    }

    public function index() {
        $ci3Sessions = $this->Ci3sync_model->get_active_sessions();
        $stats       = get_mock_stats();

        $data = array(
            'active_tab'   => 'ci3_hub',
            'title'        => 'CodeIgniter 3 Core Integration & DB Sync',
            'ci3_sessions' => $ci3Sessions,
            'stats'        => $stats
        );

        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar',  $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('ci3sync/index', $data);
        echo '</main>';
        $this->load->view('layout/footer', $data);
    }
}

