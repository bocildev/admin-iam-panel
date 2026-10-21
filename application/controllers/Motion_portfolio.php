<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Motion_portfolio extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Motion_portfolio_model');
    }

    private function json_response($data, $status = 200) {
        $this->output->set_content_type('application/json')
                     ->set_status_header($status)
                     ->set_output(json_encode($data));
    }

    public function index() {
        $this->load->model('Apikey_model');
        $this->load->model('Application_model');

        $db_connected = $this->db->is_connected();
        $db_error = $this->db->get_connection_error();
        $table_errors = array();

        $projects       = array();
        $profile        = array();
        $skills         = array();
        $software_tools = array();
        $app            = null;
        $api_key        = null;

        if ($db_connected) {
            $required_tables = array('motion_projects', 'motion_config', 'applications', 'api_keys');
            foreach ($required_tables as $tbl) {
                if (!$this->db->table_exists($tbl)) {
                    $table_errors[] = $tbl;
                }
            }

            if (empty($table_errors)) {
                $projects       = $this->Motion_portfolio_model->get_all_projects();
                $profile        = $this->Motion_portfolio_model->get_config('profile') ?: array();
                $skills         = $this->Motion_portfolio_model->get_config('skills') ?: array();
                $software_tools = $this->Motion_portfolio_model->get_config('software_tools') ?: array();
                $app            = $this->Application_model->get_by_slug('motion-portfolio');
                $api_key        = $app ? $this->Apikey_model->get_key_by_app_id($app['id']) : null;
            }
        }

        $data = array(
            'active_tab'     => 'motion_portfolio',
            'title'          => 'Portofolio Motion Control Center',
            'projects'       => $projects,
            'profile'        => $profile,
            'skills'         => $skills,
            'software_tools' => $software_tools,
            'application'    => $app,
            'api_key'        => $api_key,
            'db_status'      => array(
                'connected'        => $db_connected,
                'connection_error' => $db_error,
                'missing_tables'   => $table_errors,
                'config'           => $this->db->get_config_info()
            )
        );

        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar',  $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('motion_portfolio/index', $data);
        echo '</main>';
        $this->load->view('layout/footer', $data);
    }

    public function save_profile() {
        if (!$this->db->is_connected()) {
            $err = $this->db->get_connection_error();
            return $this->json_response(array(
                'success'    => false,
                'error'      => 'Koneksi database gagal terhubung. Periksa konfigurasi MySQL.',
                'db_error'   => true,
                'db_details' => $err
            ), 503);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $this->input->post();
        }

        if (empty($input['name'])) {
            return $this->json_response(array('success' => false, 'error' => 'Nama lengkap wajib diisi'), 400);
        }

        $ok = $this->Motion_portfolio_model->save_config('profile', $input);
        if (!$ok) {
            $last_err = $this->db->get_last_error();
            return $this->json_response(array(
                'success'    => false,
                'error'      => 'Gagal menyimpan profil ke database: ' . (isset($last_err['message']) ? $last_err['message'] : 'Query execution error'),
                'db_error'   => true,
                'db_details' => $last_err
            ), 500);
        }
        return $this->json_response(array('success' => true, 'message' => 'Profil berhasil diperbarui'));
    }

    public function save_project() {
        if (!$this->db->is_connected()) {
            $err = $this->db->get_connection_error();
            return $this->json_response(array(
                'success'    => false,
                'error'      => 'Koneksi database gagal terhubung.',
                'db_error'   => true,
                'db_details' => $err
            ), 503);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $this->input->post();
        }

        if (empty($input['title'])) {
            return $this->json_response(array('success' => false, 'error' => 'Judul proyek wajib diisi'), 400);
        }

        $id = $this->Motion_portfolio_model->save_project($input);
        if (!$id) {
            $last_err = $this->db->get_last_error();
            return $this->json_response(array(
                'success'    => false,
                'error'      => 'Gagal menyimpan proyek ke database: ' . (isset($last_err['message']) ? $last_err['message'] : 'Query execution error'),
                'db_error'   => true,
                'db_details' => $last_err
            ), 500);
        }
        return $this->json_response(array('success' => true, 'id' => $id, 'message' => 'Proyek berhasil disimpan'));
    }

    public function delete_project($id) {
        if (!$this->db->is_connected()) {
            $err = $this->db->get_connection_error();
            return $this->json_response(array(
                'success'    => false,
                'error'      => 'Koneksi database gagal terhubung.',
                'db_error'   => true,
                'db_details' => $err
            ), 503);
        }

        if (!$id) {
            return $this->json_response(array('success' => false, 'error' => 'Project ID required'), 400);
        }
        $ok = $this->Motion_portfolio_model->delete_project($id);
        if (!$ok) {
            $last_err = $this->db->get_last_error();
            return $this->json_response(array(
                'success'    => false,
                'error'      => 'Gagal menghapus proyek: ' . (isset($last_err['message']) ? $last_err['message'] : 'Query execution error'),
                'db_error'   => true,
                'db_details' => $last_err
            ), 500);
        }
        return $this->json_response(array('success' => true, 'message' => 'Proyek berhasil dihapus'));
    }

    public function save_skills() {
        if (!$this->db->is_connected()) {
            $err = $this->db->get_connection_error();
            return $this->json_response(array(
                'success'    => false,
                'error'      => 'Koneksi database gagal terhubung.',
                'db_error'   => true,
                'db_details' => $err
            ), 503);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !is_array($input)) {
            return $this->json_response(array('success' => false, 'error' => 'Data skill tidak valid'), 400);
        }

        $ok = $this->Motion_portfolio_model->save_config('skills', $input);
        if (!$ok) {
            $last_err = $this->db->get_last_error();
            return $this->json_response(array(
                'success'    => false,
                'error'      => 'Gagal menyimpan skills ke database: ' . (isset($last_err['message']) ? $last_err['message'] : 'Query error'),
                'db_error'   => true,
                'db_details' => $last_err
            ), 500);
        }
        return $this->json_response(array('success' => true, 'message' => 'Daftar keahlian berhasil diperbarui'));
    }

    public function save_tools() {
        if (!$this->db->is_connected()) {
            $err = $this->db->get_connection_error();
            return $this->json_response(array(
                'success'    => false,
                'error'      => 'Koneksi database gagal terhubung.',
                'db_error'   => true,
                'db_details' => $err
            ), 503);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !is_array($input)) {
            return $this->json_response(array('success' => false, 'error' => 'Data tools tidak valid'), 400);
        }

        $ok = $this->Motion_portfolio_model->save_config('software_tools', $input);
        if (!$ok) {
            $last_err = $this->db->get_last_error();
            return $this->json_response(array(
                'success'    => false,
                'error'      => 'Gagal menyimpan tools ke database: ' . (isset($last_err['message']) ? $last_err['message'] : 'Query error'),
                'db_error'   => true,
                'db_details' => $last_err
            ), 500);
        }
        return $this->json_response(array('success' => true, 'message' => 'Daftar tools berhasil diperbarui'));
    }

    public function upload_media() {
        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            return $this->json_response(array('success' => false, 'error' => 'Tidak ada file yang diunggah'), 400);
        }

        $file = $_FILES['file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'webp', 'svg', 'gif');

        if (!in_array($ext, $allowed)) {
            return $this->json_response(array('success' => false, 'error' => 'Tipe file tidak didukung'), 400);
        }

        $upload_dir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'projects' . DIRECTORY_SEPARATOR;
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        $filename = 'motion_' . time() . '_' . substr(md5(uniqid(rand(), true)), 0, 6) . '.' . $ext;
        $target = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            $url = base_url('uploads/projects/' . $filename);
            return $this->json_response(array('success' => true, 'url' => $url, 'filename' => $filename));
        } else {
            return $this->json_response(array('success' => false, 'error' => 'Gagal menyimpan file di server'), 500);
        }
    }
}
