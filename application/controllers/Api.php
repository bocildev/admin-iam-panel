<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->model('Apikey_model');
        $this->load->model('Audit_model');
        $this->load->model('Ci3sync_model');
        $this->load->model('Project_model');
        $this->load->model('Config_model');
    }

    private function json_response($data, $status = 200) {
        $this->output->set_content_type('application/json')
                     ->set_status_header($status)
                     ->set_output(json_encode($data));
    }

    // GET /api/health
    public function health() {
        $this->json_response(array(
            'status' => 'ok',
            'app' => 'PortAdmin IAM Portal (CodeIgniter 3)',
            'timestamp' => date('c')
        ));
    }

    // POST /api/users/add
    public function add_user() {
        $input = $this->input->raw_json();
        if (empty($input['username']) || empty($input['email'])) {
            return $this->json_response(array('error' => 'Username & Email required'), 400);
        }

        $user = $this->User_model->add_user($input);
        $this->Audit_model->add_log(
            'USER_REGISTRATION',
            "Berhasil menambahkan akun baru: @{$user['username']} ({$user['fullName']}) dengan peran {$user['role']}.",
            'medium',
            'success'
        );

        $this->json_response(array('success' => true, 'user' => $user));
    }

    // POST /api/users/update_status
    public function update_user_status() {
        $input = $this->input->raw_json();
        $user_id = isset($input['userId']) ? $input['userId'] : null;
        $status = isset($input['status']) ? $input['status'] : null;

        if (!$user_id || !$status) {
            return $this->json_response(array('error' => 'Missing parameters'), 400);
        }

        $this->User_model->update_status($user_id, $status);
        $is_suspended = ($status === 'suspended');
        $this->Audit_model->add_log(
            $is_suspended ? 'USER_SUSPEND' : 'USER_ACTIVATE',
            "Mengubah status pengguna ID: {$user_id} menjadi " . strtoupper($status) . ".",
            $is_suspended ? 'high' : 'medium',
            'success'
        );

        $this->json_response(array('success' => true));
    }

    // POST /api/users/toggle_mfa
    public function toggle_user_mfa() {
        $input = $this->input->raw_json();
        $user_id = isset($input['userId']) ? $input['userId'] : null;
        if (!$user_id) {
            return $this->json_response(array('error' => 'Missing userId'), 400);
        }

        $this->User_model->toggle_mfa($user_id);
        $this->Audit_model->add_log(
            'MFA_CONFIG_TOGGLE',
            "Autentikasi dua faktor (MFA) untuk user {$user_id} telah diubah.",
            'medium',
            'success'
        );

        $this->json_response(array('success' => true));
    }

    // POST /api/roles/toggle_permission
    public function toggle_permission() {
        $input = $this->input->raw_json();
        $role_id = isset($input['roleId']) ? $input['roleId'] : null;
        $permission_key = isset($input['permissionKey']) ? $input['permissionKey'] : null;

        if (!$role_id || !$permission_key) {
            return $this->json_response(array('error' => 'Missing parameters'), 400);
        }

        $this->Role_model->toggle_permission($role_id, $permission_key);
        $this->Audit_model->add_log(
            'ROLE_PERMISSIONS_UPDATE',
            "Mengubah izin akses role [{$role_id}]: toggle izin [{$permission_key}].",
            'high',
            'warning'
        );

        $this->json_response(array('success' => true));
    }

    // POST /api/roles/add
    public function add_role() {
        $input = $this->input->raw_json();
        if (empty($input['name']) || empty($input['displayName'])) {
            return $this->json_response(array('error' => 'Role name & display name required'), 400);
        }

        $role = $this->Role_model->add_role($input);
        $this->Audit_model->add_log(
            'ROLE_CREATION',
            "Berhasil mendefinisikan kustom role baru: {$role['displayName']} ({$role['name']}).",
            'medium',
            'success'
        );

        $this->json_response(array('success' => true, 'role' => $role));
    }

    // POST /api/keys/add
    public function add_api_key() {
        $input = $this->input->raw_json();
        if (empty($input['name']) || empty($input['ownerId'])) {
            return $this->json_response(array('error' => 'Token name & owner required'), 400);
        }

        $key = $this->Apikey_model->add_key($input);
        $this->Audit_model->add_log(
            'API_KEY_GENERATION',
            "Berhasil men-generate REST API token baru \"{$key['name']}\" untuk Owner: {$key['ownerName']}.",
            'high',
            'success'
        );

        $this->json_response(array('success' => true, 'key' => $key));
    }

    // POST /api/keys/revoke
    public function revoke_api_key() {
        $input = $this->input->raw_json();
        $key_id = isset($input['keyId']) ? $input['keyId'] : null;

        if (!$key_id) {
            return $this->json_response(array('error' => 'Missing keyId'), 400);
        }

        $this->Apikey_model->revoke_key($key_id);
        $this->Audit_model->add_log(
            'API_KEY_REVOCATION',
            "Mencabut kredensial REST API Key {$key_id} secara permanen.",
            'high',
            'warning'
        );

        $this->json_response(array('success' => true));
    }

    // POST /api/sessions/terminate
    public function terminate_session() {
        $input = $this->input->raw_json();
        $session_id = isset($input['sessionId']) ? $input['sessionId'] : null;

        if (!$session_id) {
            return $this->json_response(array('error' => 'Missing sessionId'), 400);
        }

        $this->Ci3sync_model->terminate_session($session_id);
        $this->Audit_model->add_log(
            'SESSION_TERMINATION',
            "Menghapus payload cookie ci_sessions untuk ID: " . substr($session_id, 0, 12) . "...",
            'medium',
            'success'
        );

        $this->json_response(array('success' => true));
    }

    // POST /api/ci3/test_connection
    public function test_ci3_connection() {
        $input = $this->input->raw_json();
        $host = isset($input['dbHost']) ? $input['dbHost'] : 'localhost';
        $name = isset($input['dbName']) ? $input['dbName'] : 'toonhub_iam';
        $user = isset($input['dbUser']) ? $input['dbUser'] : 'root';
        $prefix = isset($input['tablePrefix']) ? $input['tablePrefix'] : 'toon_';

        $res = $this->Ci3sync_model->test_connection($host, $name, $user, $prefix);
        $this->json_response($res);
    }

    // POST /api/ai/security_advice
    public function security_advice() {
        $input = $this->input->raw_json();
        $prompt = isset($input['prompt']) ? $input['prompt'] : '';
        $context = isset($input['context']) ? $input['context'] : array();

        if (empty($prompt)) {
            return $this->json_response(array('error' => 'Prompt is required'), 400);
        }

        $apiKey = getenv('GEMINI_API_KEY');
        if (!empty($apiKey) && $apiKey !== 'MY_GEMINI_API_KEY') {
            $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey);
            $systemInstruction = "You are an expert Security Architect and CodeIgniter 3 Specialist assisting with the Identity and Access Management (IAM) platform for 'ToonHub' (a webtoon/comic reading platform built on CodeIgniter 3). Provide precise, actionable security guidelines, IAM role policy structure, and CodeIgniter 3 PHP code snippets or SQL queries. Format responses cleanly with markdown headers, code blocks, and clear bullet points. Write in Indonesian or English matching the user's query prompt.";
            
            $payload = array(
                'contents' => array(
                    array(
                        'parts' => array(
                            array('text' => $systemInstruction . "\n\nContext: " . json_encode($context) . "\nUser Query: " . $prompt)
                        )
                    )
                )
            );

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $resData = json_decode($response, true);
                if (isset($resData['candidates'][0]['content']['parts'][0]['text'])) {
                    return $this->json_response(array('advice' => $resData['candidates'][0]['content']['parts'][0]['text']));
                }
            }
        }

        // Fallback intelligent response if cURL / API Key is unconfigured
        $targetRole = isset($context['targetRole']) ? $context['targetRole'] : 'Content Manager';
        $advice = "### Security Analysis & Recommendation for ToonHub (CodeIgniter 3)\n" .
        "- **Role Strategy**: Define granular RBAC policies for `" . $targetRole . "`.\n" .
        "- **CI3 Session Security**: Ensure \$config['sess_encrypt_cookie'] = TRUE; and use HTTP-only secure cookies in CodeIgniter 3 config.php.\n" .
        "- **Bcrypt Hash Audit**: Upgrade legacy MD5/SHA1 user hashes in toon_users table to standard PHP password_hash(\$pass, PASSWORD_BCRYPT).\n" .
        "- **Middleware Hook**: Place IAM_Hook.php in application/hooks/ to intercept post_controller_constructor events.\n\n" .
        "```php\n" .
        "<?php\n" .
        "defined('BASEPATH') OR exit('No direct script access allowed');\n\n" .
        "class IAM_Hook {\n" .
        "    public function check_permission() {\n" .
        "        \$CI =& get_instance();\n" .
        "        \$CI->load->library('session');\n" .
        "        \$user_id = \$CI->session->userdata('user_id');\n" .
        "        \$role = \$CI->session->userdata('role');\n" .
        "        if (!\$user_id && \$CI->router->class !== 'auth') {\n" .
        "            redirect('auth/login');\n" .
        "        }\n" .
        "    }\n" .
        "}\n" .
        "```";

        $this->json_response(array(
            'advice' => $advice,
            'suggestedPolicy' => array(
                'role' => $targetRole,
                'permissions' => array('comics.create', 'comics.edit', 'episodes.upload', 'comments.moderate'),
                'ipWhitelistRequired' => false
            )
        ));
    }

    // GET /api/projects
    public function projects() {
        $projects = $this->Project_model->get_all_projects();
        $this->json_response($projects);
    }

    // POST /api/projects/add
    public function add_project() {
        $input = $this->input->raw_json();
        if (empty($input['name'])) {
            return $this->json_response(array('error' => 'Project name is required'), 400);
        }

        $id = $this->Project_model->add_project($input);
        
        $this->Audit_model->add_log(
            'PROJECT_CREATION',
            "Berhasil menambahkan proyek portofolio baru: {$input['name']}.",
            'medium',
            'success'
        );

        $this->json_response(array('success' => true, 'id' => $id));
    }

    // POST /api/projects/update
    public function update_project() {
        $input = $this->input->raw_json();
        $id = isset($input['id']) ? $input['id'] : null;
        if (!$id || empty($input['name'])) {
            return $this->json_response(array('error' => 'Missing project ID or name'), 400);
        }

        $this->Project_model->update_project($id, $input);

        $this->Audit_model->add_log(
            'PROJECT_UPDATE',
            "Memperbarui proyek portofolio ID: {$id} ({$input['name']}).",
            'medium',
            'success'
        );

        $this->json_response(array('success' => true));
    }

    // POST /api/projects/delete
    public function delete_project() {
        $input = $this->input->raw_json();
        $id = isset($input['id']) ? $input['id'] : null;
        if (!$id) {
            return $this->json_response(array('error' => 'Missing project ID'), 400);
        }

        $project = $this->Project_model->get_project_by_id($id);
        $projectName = $project ? $project['name'] : 'Unknown';

        $this->Project_model->delete_project($id);

        $this->Audit_model->add_log(
            'PROJECT_DELETION',
            "Menghapus proyek portofolio ID: {$id} ({$projectName}).",
            'high',
            'warning'
        );

        $this->json_response(array('success' => true));
    }

    // GET /api/portfolio/settings
    public function get_settings() {
        $settings = $this->Config_model->get_all_settings();
        $this->json_response($settings);
    }

    // POST /api/portfolio/settings/update
    public function update_settings() {
        $input = $this->input->raw_json();
        if (empty($input)) {
            return $this->json_response(array('error' => 'No data provided'), 400);
        }

        $this->Config_model->update_settings($input);

        $this->Audit_model->add_log(
            'PORTFOLIO_SETTINGS_UPDATE',
            "Memperbarui konfigurasi global tata letak & animasi 3D Portfolio.",
            'high',
            'success'
        );

        $this->json_response(array('success' => true));
    }

    // GET /api/portfolio/layout
    public function get_layout() {
        $layout = $this->Config_model->get_section_layout();
        if ($layout === null) {
            // Return default layout if not set yet
            $layout = $this->_default_layout();
        }
        $this->json_response($layout);
    }

    // POST /api/portfolio/layout/update
    public function update_layout() {
        $input = $this->input->raw_json();
        if (empty($input)) {
            $raw = file_get_contents('php://input');
            $input = json_decode($raw, true);
        }
        
        if (empty($input)) {
            return $this->json_response(array('error' => 'No layout data provided'), 400);
        }

        $this->Config_model->update_section_layout($input);

        $this->Audit_model->add_log(
            'PORTFOLIO_LAYOUT_UPDATE',
            "Memperbarui konfigurasi komponen layout section 3D Portfolio.",
            'high',
            'success'
        );

        $this->json_response(array('success' => true));
    }

    private function _default_layout() {
        return array(
            'splash' => array(
                'enabled' => true,
                'components' => array(
                    'letters_animation' => array('enabled' => true, 'text' => 'STUDIO'),
                    'progress_bar'      => array('enabled' => true),
                    'loading_text'      => array('enabled' => true)
                )
            ),
            'hero' => array(
                'enabled' => true,
                'components' => array(
                    'ghost_text'     => array('enabled' => true),
                    'brand_label'    => array('enabled' => true, 'text' => 'SHOWCASE'),
                    'carousel'       => array('enabled' => true, 'card_count' => 4, 'aspect_ratio' => '16/9', 'height_dvh' => 48),
                    'nav_arrows'     => array('enabled' => true),
                    'nav_dots'       => array('enabled' => true),
                    'explore_button' => array('enabled' => true),
                    'featured_title' => array('enabled' => true, 'en' => 'FEATURED APPS', 'id' => 'APLIKASI UNGGULAN'),
                    'featured_desc'  => array('enabled' => true, 'en' => 'Discover a curated collection of innovative applications.', 'id' => 'Temukan koleksi aplikasi inovatif terkurasi.')
                )
            ),
            'discover' => array(
                'enabled' => true,
                'components' => array(
                    'section_header'      => array('enabled' => true),
                    'project_count_badge' => array('enabled' => true),
                    'grid'                => array('enabled' => true, 'columns' => 4, 'gap' => 8),
                    'card_tech_badges'    => array('enabled' => true, 'max_badges' => 3),
                    'card_description'    => array('enabled' => true),
                    'card_index_badge'    => array('enabled' => true)
                )
            ),
            'background' => array(
                'nebula_glow'    => array('enabled' => true, 'opacity' => 65),
                'star_particles' => array('enabled' => true, 'count' => 24),
                'vignette'       => array('enabled' => true)
            )
        );
    }
}
