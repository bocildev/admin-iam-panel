<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contents extends MY_Controller {

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        $this->load->model('App_content_model');
        $this->load->model('Application_model');
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
     * GET /contents/{app_id}
     */
    public function get_contents($app_id) {
        $contents = $this->App_content_model->get_contents_by_app($app_id);
        return $this->json_response(array('application_id' => $app_id, 'contents' => $contents));
    }

    /**
     * GET /contents/{app_id}/{key}
     */
    public function get_content_key($app_id, $key) {
        $content = $this->App_content_model->get_content_by_key($app_id, $key);
        if (!$content) {
            return $this->json_response(array('error' => 'Content key not found'), 404);
        }
        return $this->json_response($content);
    }

    /**
     * POST /contents/{app_id}
     */
    public function save_content($app_id) {
        $input = $this->get_json_input();
        $key = isset($input['key']) ? trim($input['key']) : '';
        $value = isset($input['value']) ? $input['value'] : null;

        if (empty($key) || $value === null) {
            return $this->json_response(array('error' => 'key and value are required'), 400);
        }

        $app = $this->Application_model->get_by_id($app_id);
        if (!$app) {
            return $this->json_response(array('error' => 'Registered application not found'), 404);
        }

        $content = $this->App_content_model->save_content($app_id, $key, $value);

        $this->Audit_model->add_log(
            'DYNAMIC_CMS_UPDATE',
            "Memperbarui konten dinamis [{$key}] untuk aplikasi [{$app['name']}]",
            'low',
            'success'
        );

        return $this->json_response(array(
            'message' => 'Dynamic content saved successfully',
            'content' => $content
        ));
    }

    /**
     * DELETE /contents/{app_id}/{key}
     */
    public function delete_content($app_id, $key) {
        $this->App_content_model->delete_content($app_id, $key);
        return $this->json_response(array('message' => 'Content deleted successfully'));
    }
}
