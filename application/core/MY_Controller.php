<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->library('session');
        $this->load->helper('url');
        require_once APPPATH . 'libraries/Auth_service.php';
        $this->auth_service = new Auth_service();

        $this->_check_auth();
    }

    protected function _check_auth() {
        if (!$this->auth_service->is_logged_in()) {
            $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
            if ($is_ajax) {
                $this->output->set_content_type('application/json')
                             ->set_status_header(401)
                             ->set_output(json_encode(['error' => 'Unauthorized. Please login.']))
                             ->_display();
                exit;
            } else {
                header('Location: ' . base_url('auth/login'));
                exit;
            }
        }
    }
}
