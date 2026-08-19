<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends MY_Controller {

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        $this->load->model('App_user_access_model');
        $this->load->model('Application_model');
        $this->load->model('Admin_model');
        $this->load->model('Audit_model');
    }

    private function json_response($data, $status = 200) {
        $this->output->set_content_type('application/json')
                     ->set_status_header($status)
                     ->set_output(json_encode($data));
    }

    private function get_json_input() {
        $stream = file_get_contents('php://input');
        $json = json_decode($stream, true);
        if (is_array($json)) {
            return $json;
        }
        return $this->input->post() ?? array();
    }

    /**
     * GET /access/app/{app_id}
     */
    public function get_app_users($app_id) {
        $users = $this->App_user_access_model->get_users_by_app($app_id);
        return $this->json_response(array('application_id' => $app_id, 'assigned_users' => $users));
    }

    /**
     * POST /access/grant
     */
    public function grant() {
        $input = $this->get_json_input();
        $admin_id = isset($input['admin_id']) ? trim($input['admin_id']) : '';
        $application_id = isset($input['application_id']) ? trim($input['application_id']) : '';
        $app_role = isset($input['app_role']) ? trim($input['app_role']) : 'editor';

        if (empty($admin_id) || empty($application_id)) {
            return $this->json_response(array('error' => 'admin_id and application_id are required'), 400);
        }

        $admin = $this->Admin_model->get_by_id($admin_id);
        $app = $this->Application_model->get_by_id($application_id);

        if (!$admin || !$app) {
            return $this->json_response(array('error' => 'Admin or Application record not found'), 404);
        }

        $this->App_user_access_model->grant_access($admin_id, $application_id, $app_role);

        $this->Audit_model->add_log(
            'IAM_APP_ACCESS_GRANTED',
            "Memberikan hak akses aplikasi [{$app['name']}] peran [{$app_role}] kepada admin @{$admin['username']}",
            'medium',
            'success'
        );

        return $this->json_response(array(
            'message' => 'Application access granted successfully',
            'access' => array(
                'admin_id' => $admin_id,
                'application_id' => $application_id,
                'app_role' => $app_role
            )
        ));
    }

    /**
     * POST /access/revoke
     */
    public function revoke() {
        $input = $this->get_json_input();
        $admin_id = isset($input['admin_id']) ? trim($input['admin_id']) : '';
        $application_id = isset($input['application_id']) ? trim($input['application_id']) : '';

        if (empty($admin_id) || empty($application_id)) {
            return $this->json_response(array('error' => 'admin_id and application_id are required'), 400);
        }

        $this->App_user_access_model->revoke_access($admin_id, $application_id);

        $this->Audit_model->add_log(
            'IAM_APP_ACCESS_REVOKED',
            "Mencabut hak akses aplikasi [{$application_id}] dari admin [{$admin_id}]",
            'medium',
            'success'
        );

        return $this->json_response(array('message' => 'Application access revoked successfully'));
    }
}
