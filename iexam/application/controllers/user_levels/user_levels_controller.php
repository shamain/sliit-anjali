<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_levels_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('USER_LOGGED_IN')) {
            redirect(site_url() . '/login/login_controller');
        } else {
            $this->load->model('user_levels/user_levels_model');
            $this->load->model('user_levels/user_levels_service');
        }
    }

    function manage_user_levels() {

        $user_level_service = new User_levels_service();

        $data['heading'] = "Manage User Level";
        $data['user_levels'] = $user_level_service->get_all_user_levels();

//        $partials = array('content' => 'subjects/manage_subjects_view');
//        $this->template->load('template/main_template', $partials, $data);
        $this->load->view('user_levels/manage_user_levels_view', $data);
    }

    function add_new_user_level() {

        $user_level_model = new User_levels_model();
        $user_level_service = new User_levels_service();

        $user_level_model->setUserlevelname($this->input->post('user_level_name', TRUE));
        $user_level_model->setDelInd('1');

        echo $user_level_service->add_new_user_level($user_level_model);
    }

    function delete_user_level() {

        $user_level_service = new User_levels_service();

        echo $user_level_service->delete_user_level(trim($this->input->post('id', TRUE)));
    }

    function edit_user_level_view($id) {

        $user_level_service = new User_levels_service();


        $data['heading'] = "Edit User Level";
        $data['user_level'] = $user_level_service->get_user_level_by_id($id);


//        $partials = array('content' => 'user_levels/edit_user_levels_view');
//        $this->template->load('template/main_template', $partials, $data);
        
        $this->load->view('user_levels/edit_user_levels_view', $data);
    }

    function edit_user_level() {

        $user_level_model = new User_levels_model();
        $user_level_service = new User_levels_service();

        $user_level_model->setUserlevelname($this->input->post('user_level_name', TRUE));

        $user_level_model->setUserlevelid($this->input->post('user_level_id', TRUE));

        echo $user_level_service->update_user_level($user_level_model);
    }

}
