<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditlogs extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Audit_model');
    }

    public function index() {
        $logs  = $this->Audit_model->get_all_logs();
        $stats = get_mock_stats();

        $data = array(
            'active_tab' => 'audit_logs',
            'title'      => 'Security Audit Logs & Telemetry',
            'logs'       => $logs,
            'stats'      => $stats
        );

        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar',  $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('auditlogs/index', $data);
        echo '</main>';
        $this->load->view('layout/footer', $data);
    }
}

