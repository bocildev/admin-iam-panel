<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database_manager extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('App_database_model');
        $this->load->model('Audit_model');
        require_once APPPATH . 'libraries/Database_provisioner.php';
        $this->db_provisioner = new Database_provisioner();
    }

    private function json_response($data, $status = 200) {
        $this->output
             ->set_content_type('application/json')
             ->set_status_header($status)
             ->set_output(json_encode($data));
    }

    private function get_json_input() {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function index($app_id) {
        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) {
            header('Location: ' . base_url('applications'));
            exit;
        }

        $data['active_tab'] = 'applications';
        $data['title'] = 'Database Manager - ' . htmlspecialchars($db['db_name']);
        $data['app_id'] = $app_id;
        $data['db_config'] = $db;
        
        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar',  $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('database_manager/index', $data);
        echo '</main>';
        $this->load->view('layout/footer');
    }

    public function api_tables($app_id) {
        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        $res = $this->db_provisioner->get_tables(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted']
        );
        return $this->json_response($res);
    }

    public function api_table_schema($app_id, $table_name) {
        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        $res = $this->db_provisioner->get_table_schema_and_relations(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted'], $table_name
        );
        return $this->json_response($res);
    }

    public function api_table_data($app_id, $table_name) {
        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        // using $_GET to be safe since CI3 input might be stripped
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
        $res = $this->db_provisioner->get_table_data(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted'], $table_name, $limit
        );
        return $this->json_response($res);
    }

    public function api_insert($app_id, $table_name) {
        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        $input = $this->get_json_input();
        if (!$input) return $this->json_response(['error' => 'No data provided'], 400);

        $res = $this->db_provisioner->insert_record(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted'], $table_name, $input
        );
        return $this->json_response($res);
    }

    public function api_update($app_id, $table_name) {
        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        $input = $this->get_json_input();
        if (!$input || empty($input['__pk_col']) || !isset($input['__pk_val'])) {
            return $this->json_response(['error' => 'Primary key required for update'], 400);
        }

        $pk_col = $input['__pk_col'];
        $pk_val = $input['__pk_val'];
        unset($input['__pk_col'], $input['__pk_val']);

        $res = $this->db_provisioner->update_record(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted'], $table_name, $pk_col, $pk_val, $input
        );
        return $this->json_response($res);
    }

    public function api_delete($app_id, $table_name) {
        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        $input = $this->get_json_input();
        if (!$input || empty($input['__pk_col']) || !isset($input['__pk_val'])) {
            return $this->json_response(['error' => 'Primary key required for delete'], 400);
        }

        $res = $this->db_provisioner->delete_record(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted'], $table_name, $input['__pk_col'], $input['__pk_val']
        );
        return $this->json_response($res);
    }

    public function api_all_relations($app_id) {
        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        $res = $this->db_provisioner->get_all_relations(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted']
        );
        return $this->json_response($res);
    }

    public function api_table_data_fk($app_id, $table_name) {
        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        $fk_col = isset($_GET['fk_col']) ? $_GET['fk_col'] : '';
        $fk_val = isset($_GET['fk_val']) ? $_GET['fk_val'] : '';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

        if (!$fk_col || !$fk_val) {
            return $this->json_response(['error' => 'FK column and value are required'], 400);
        }

        $res = $this->db_provisioner->get_table_data_by_fk(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted'], $table_name, $fk_col, $fk_val, $limit
        );
        return $this->json_response($res);
    }

    public function api_cascading_impact($app_id, $table_name) {
        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        $pk_val = isset($_GET['pk_val']) ? $_GET['pk_val'] : '';
        if (!$pk_val) return $this->json_response(['error' => 'PK value is required'], 400);

        $res = $this->db_provisioner->get_cascading_impact(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted'], $table_name, $pk_val
        );
        return $this->json_response($res);
    }

    public function api_delete_cascading($app_id, $table_name) {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json_response(['error' => 'Invalid method'], 405);
        }

        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['__pk_col']) || !isset($input['__pk_val'])) {
            return $this->json_response(['error' => 'Missing PK column or value'], 400);
        }

        $res = $this->db_provisioner->cascading_delete(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted'], $table_name, $input['__pk_col'], $input['__pk_val']
        );
        return $this->json_response($res);
    }

    public function api_soft_delete($app_id, $table_name) {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json_response(['error' => 'Invalid method'], 405);
        }

        $db = $this->App_database_model->get_by_application_id($app_id);
        if (!$db) return $this->json_response(['error' => 'Database not found'], 404);

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['__pk_col']) || !isset($input['__pk_val'])) {
            return $this->json_response(['error' => 'Missing PK column or value'], 400);
        }

        $res = $this->db_provisioner->soft_delete_record(
            $db['db_host'], $db['db_port'], $db['db_name'], $db['db_user'], $db['db_password_encrypted'], $table_name, $input['__pk_col'], $input['__pk_val']
        );
        return $this->json_response($res);
    }
}
