<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Application_model');
        $this->load->model('Apikey_model');
        $this->load->model('Audit_model');
        $this->load->model('Project_model');
    }

    public function index() {
        $admins       = $this->Admin_model->get_all_admins();
        $applications = $this->Application_model->get_all_applications();
        $apiKeys      = $this->Apikey_model->get_all_keys();
        $projects     = $this->Project_model->get_all_projects();
        $auditLogs    = $this->Audit_model->get_all_logs();

        $stats = array(
            'totalAdmins'       => count($admins),
            'totalApplications' => count($applications),
            'totalApiKeys'      => count($apiKeys),
            'totalProjects'     => count($projects)
        );

        $data = array(
            'active_tab'   => 'dashboard',
            'title'        => 'Platform Telemetry & System Overview',
            'admins'       => $admins,
            'applications' => $applications,
            'audit_logs'   => $auditLogs,
            'stats'        => $stats
        );

        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar',  $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('dashboard/index', $data);
        echo '</main>';
        $this->load->view('layout/footer', $data);
    }
}
