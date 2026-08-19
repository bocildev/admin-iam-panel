<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aiadvisor extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Audit_model');
    }

    public function index() {
        $stats = get_mock_stats();

        $data = array(
            'active_tab' => 'ai_advisor',
            'title' => 'AI Security & CI3 IAM Advisor',
            'stats' => $stats
        );

        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('aiadvisor/index', $data);
        echo '</main>';
        $this->load->view('layout/footer', $data);
    }
}
