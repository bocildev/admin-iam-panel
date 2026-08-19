<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Project_model');
        $this->load->model('Config_model');
        $this->load->model('Application_model');
    }

    public function index() {
        $projects       = $this->Project_model->get_all_projects();
        $applications   = $this->Application_model->get_all_applications();
        $settings       = $this->Config_model->get_all_settings();
        $section_layout = $this->Config_model->get_section_layout();

        if ($section_layout === null) {
            // Provide default so view always has a valid structure
            $section_layout = array(
                'splash'     => array('enabled' => true, 'components' => array(
                    'letters_animation' => array('enabled' => true, 'text' => 'STUDIO'),
                    'progress_bar'      => array('enabled' => true),
                    'loading_text'      => array('enabled' => true)
                )),
                'hero'       => array('enabled' => true, 'components' => array(
                    'ghost_text'     => array('enabled' => true),
                    'brand_label'    => array('enabled' => true, 'text' => 'SHOWCASE'),
                    'carousel'       => array('enabled' => true, 'card_count' => 4, 'aspect_ratio' => '16/9', 'height_dvh' => 48),
                    'nav_arrows'     => array('enabled' => true),
                    'nav_dots'       => array('enabled' => true),
                    'explore_button' => array('enabled' => true),
                    'featured_title' => array('enabled' => true, 'en' => 'FEATURED APPS', 'id' => 'APLIKASI UNGGULAN'),
                    'featured_desc'  => array('enabled' => true, 'en' => 'Discover a curated collection of innovative applications.', 'id' => 'Temukan koleksi aplikasi inovatif terkurasi.')
                )),
                'discover'   => array('enabled' => true, 'components' => array(
                    'section_header'      => array('enabled' => true),
                    'project_count_badge' => array('enabled' => true),
                    'grid'                => array('enabled' => true, 'columns' => 4, 'gap' => 8),
                    'card_tech_badges'    => array('enabled' => true, 'max_badges' => 3),
                    'card_description'    => array('enabled' => true),
                    'card_index_badge'    => array('enabled' => true)
                )),
                'background' => array(
                    'nebula_glow'    => array('enabled' => true, 'opacity' => 65),
                    'star_particles' => array('enabled' => true, 'count' => 24),
                    'vignette'       => array('enabled' => true)
                )
            );
        }

        $data = array(
            'active_tab'     => 'portofolio',
            'title'          => 'App Registry & Portofolio 3D',
            'projects'       => $projects,
            'applications'   => $applications,
            'settings'       => $settings,
            'section_layout' => $section_layout
        );

        $this->load->view('layout/header', $data);
        $this->load->view('layout/navbar',  $data);
        $this->load->view('layout/sidebar', $data);
        echo '<main class="flex-1 min-w-0 flex flex-col gap-6 p-6 lg:p-8 overflow-y-auto">';
        $this->load->view('projects/index', $data);
        echo '</main>';
        $this->load->view('layout/footer', $data);
    }
}
