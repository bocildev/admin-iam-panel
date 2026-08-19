<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applications extends MY_Controller {

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        $this->load->model('Application_model');
        $this->load->model('App_database_model');
        $this->load->model('Audit_model');
        require_once APPPATH . 'libraries/Database_provisioner.php';
        $this->db_provisioner = new Database_provisioner();
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
     * GET /applications
     */
    public function index() {
        $apps = $this->Application_model->get_all_applications();

        // If requested as JSON API, return JSON
        $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        if ($is_ajax || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            return $this->json_response(array('applications' => $apps));
        }

        $data = array(
            'active_tab'   => 'applications',
            'title'        => 'App & Tenant Registry',
            'applications' => $apps
        );

        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar',  $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('applications/index', $data);
        echo '</main>';
        $this->load->view('layout/footer', $data);
    }

    /**
     * POST /applications/create
     * Registers an application and automatically provisions an isolated database
     */
    public function create() {
        $input = $this->get_json_input();
        if (empty($input['name'])) {
            return $this->json_response(array('error' => 'Application name is required'), 400);
        }

        // 1. Create Application Record
        $app = $this->Application_model->create_application($input);

        // 2. Save Manual Database Configuration
        $db_name = isset($input['db_name']) ? trim($input['db_name']) : '';
        $db_host = isset($input['db_host']) && !empty(trim($input['db_host'])) ? trim($input['db_host']) : '127.0.0.1';
        $db_port = isset($input['db_port']) && !empty(trim($input['db_port'])) ? (int)trim($input['db_port']) : 3306;
        $db_user = isset($input['db_user']) ? trim($input['db_user']) : '';
        $db_password = isset($input['db_password']) ? $input['db_password'] : '';

        if (empty($db_name) || empty($db_user)) {
            return $this->json_response(array('error' => 'Database name and user are required'), 400);
        }

        $encrypted_password = $this->db_provisioner->encrypt($db_password);

        $db_record = $this->App_database_model->save_db_metadata(array(
            'application_id' => $app['id'],
            'db_host' => $db_host,
            'db_port' => $db_port,
            'db_name' => $db_name,
            'db_user' => $db_user,
            'db_password_encrypted' => $encrypted_password,
            'status' => 'provisioned'
        ));

        $this->Audit_model->add_log(
            'APP_DATABASE_REGISTERED',
            "Berhasil mendaftarkan database [{$db_name}] untuk aplikasi [{$app['name']}]",
            'high',
            'success'
        );

        return $this->json_response(array(
            'message' => 'Application registered successfully',
            'application' => $app,
            'database' => $db_record
        ), 201);
    }

    /**
     * POST /applications/test-db
     */
    public function test_db() {
        $input = $this->get_json_input();
        $app_id = isset($input['application_id']) ? $input['application_id'] : '';
        $db = $this->App_database_model->get_by_application_id($app_id);

        if (!$db) {
            return $this->json_response(array('error' => 'Database configuration not found for application'), 404);
        }

        $res = $this->db_provisioner->test_connection(
            $db['db_host'],
            $db['db_port'],
            $db['db_name'],
            $db['db_user'],
            $db['db_password_encrypted']
        );

        return $this->json_response($res);
    }

    /**
     * POST /applications/update
     */
    public function update() {
        $input = $this->get_json_input();
        $id = isset($input['id']) ? trim($input['id']) : '';

        if (empty($id) || empty($input['name'])) {
            return $this->json_response(array('error' => 'Application ID and Name are required'), 400);
        }

        $data = array(
            'name' => trim($input['name']),
            'description' => isset($input['description']) ? trim($input['description']) : '',
            'category' => isset($input['category']) ? trim($input['category']) : 'general',
            'status' => isset($input['status']) ? trim($input['status']) : 'active'
        );

        $this->Application_model->update_application($id, $data);

        // Update DB Settings
        $db_data = array();
        if (isset($input['db_name'])) $db_data['db_name'] = trim($input['db_name']);
        if (isset($input['db_host'])) $db_data['db_host'] = trim($input['db_host']);
        if (isset($input['db_port'])) $db_data['db_port'] = trim($input['db_port']);
        if (isset($input['db_user'])) $db_data['db_user'] = trim($input['db_user']);
        
        if (!empty($input['db_password'])) {
            $db_data['db_password_encrypted'] = $this->db_provisioner->encrypt($input['db_password']);
        }
        
        if (!empty($db_data)) {
            $this->App_database_model->update_db_metadata($id, $db_data);
        }

        $this->Audit_model->add_log(
            'APP_UPDATED',
            "Memperbarui aplikasi [{$data['name']}] ({$id})",
            'medium',
            'success'
        );

        return $this->json_response(array('message' => 'Application updated successfully'));
    }

    /**
     * POST /applications/delete
     */
    public function delete() {
        $input = $this->get_json_input();
        $id = isset($input['id']) ? trim($input['id']) : '';

        if (empty($id)) {
            return $this->json_response(array('error' => 'Application ID is required'), 400);
        }

        $this->Application_model->delete_application($id);

        $this->Audit_model->add_log(
            'APP_DELETED',
            "Menghapus aplikasi terdaftar [{$id}]",
            'high',
            'warning'
        );

        return $this->json_response(array('message' => 'Application deleted successfully'));
    }

    /**
     * POST /applications/get-db-tables
     */
    public function get_db_tables() {
        $input = $this->get_json_input();
        $app_id = isset($input['application_id']) ? $input['application_id'] : '';
        $db = $this->App_database_model->get_by_application_id($app_id);

        if (!$db) {
            return $this->json_response(array('error' => 'Database configuration not found for application'), 404);
        }

        $res = $this->db_provisioner->get_tables(
            $db['db_host'],
            $db['db_port'],
            $db['db_name'],
            $db['db_user'],
            $db['db_password_encrypted']
        );

        return $this->json_response($res);
    }

    /**
     * POST /applications/get-table-data
     */
    public function get_table_data() {
        $input = $this->get_json_input();
        $app_id = isset($input['application_id']) ? $input['application_id'] : '';
        $table_name = isset($input['table_name']) ? $input['table_name'] : '';
        $limit = isset($input['limit']) ? $input['limit'] : 50;

        $db = $this->App_database_model->get_by_application_id($app_id);

        if (!$db || empty($table_name)) {
            return $this->json_response(array('error' => 'Invalid parameters or database configuration not found'), 400);
        }

        $res = $this->db_provisioner->get_table_data(
            $db['db_host'],
            $db['db_port'],
            $db['db_name'],
            $db['db_user'],
            $db['db_password_encrypted'],
            $table_name,
            $limit
        );

        return $this->json_response($res);
    }
}
