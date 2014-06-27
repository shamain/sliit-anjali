<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('USER_LOGGED_IN')) {
            redirect(site_url() . '/login/login_controller');
        } else {
        $this->load->model('users/users_model');
        $this->load->model('users/users_service');

        $this->load->model('designations/designations_model');
        $this->load->model('designations/designations_service');

        $this->load->model('marital_statuses/marital_statuses_model');
        $this->load->model('marital_statuses/marital_statuses_service');

        $this->load->model('user_levels/user_levels_model');
        $this->load->model('user_levels/user_levels_service');
        }
    }

    function manage_users() {

        $users_service = new Users_service();
        $designations_service = new Designations_service();
        $marital_statuses_service = new Marital_statuses_service();
        $user_level_service = new User_levels_service();



        $data['heading'] = "Manage Users";
        $data['users'] = $users_service->get_all_users();
        $data['designations'] = $designations_service->get_all_designations();
        $data['marital_statuses'] = $marital_statuses_service->get_all_marital_statuses();
        $data['user_levels'] = $user_level_service->get_all_user_levels();

//        $partials = array('content' => 'subjects/manage_subjects_view');
//        $this->template->load('template/main_template', $partials, $data);
        $this->load->view('users/manage_users_view', $data);
    }

    function add_new_user() {


        $users_model = new Users_model();
        $users_service = new Users_service();

        $users_model->setUsername($this->input->post('user_name', TRUE));
        $users_model->setPassword(md5($this->input->post('password', TRUE)));
        $users_model->setUserLevel($this->input->post('user_level', TRUE));
        $users_model->setActivated($this->input->post('active_status', TRUE));
        $users_model->setDesignationID($this->input->post('designation_id', TRUE));
        $users_model->setFirstName($this->input->post('first_name', TRUE));
        $users_model->setMiddleName($this->input->post('middle_name', TRUE));
        $users_model->setLastName($this->input->post('last_name', TRUE));
        $users_model->setMaritalStatusID($this->input->post('marital_status_id', TRUE));
        $users_model->setEmail($this->input->post('email', TRUE));
        $users_model->setRegistrationNumber($this->input->post('reg_number', TRUE));
        $users_model->setGender($this->input->post('gender', TRUE));
        $users_model->setNICNumber($this->input->post('nic', TRUE));
        $users_model->setDateOfBirth($this->input->post('dob', TRUE));
        $users_model->setRegisteredOn(date('Y-m-d H:i:s'));
        $users_model->setRegistrationValidTill($this->input->post('reg_valid_til', TRUE));
        $users_model->setPhotoPath($this->input->post('pro_image', TRUE));
        $users_model->setDelInd('1');

        echo $users_service->add_new_users($users_model);
    }

    function delete_user() {

        $users_service = new Users_service();

        echo $users_service->delete_users(trim($this->input->post('id', TRUE)));
    }

    function edit_user_view($id) {

        $users_service = new Users_service();


        $data['heading'] = "Edit User";
        $data['user'] = $users_service->get_user_by_id($id);


        $partials = array('content' => 'users/edit_users_view');
        $this->template->load('template/main_template', $partials, $data);
    }

    function edit_user() {

        $users_model = new Users_model();
        $users_service = new Users_service();

        $users_model->setUsername($this->input->post('user_name', TRUE));
        $users_model->setPassword(md5($this->input->post('password', TRUE)));
        $users_model->setUserLevel($this->input->post('user_level', TRUE));
        $users_model->setDesignationID($this->input->post('designation_id', TRUE));
        $users_model->setFirstName($this->input->post('first_name', TRUE));
        $users_model->setMiddleName($this->input->post('middle_name', TRUE));
        $users_model->setLastName($this->input->post('last_name', TRUE));
        $users_model->setMaritalStatusID($this->input->post('marital_status_id', TRUE));
        $users_model->setEmail($this->input->post('email', TRUE));
        $users_model->setRegistrationNumber($this->input->post('reg_number', TRUE));
        $users_model->setGender($this->input->post('gender', TRUE));
        $users_model->setNICNumber($this->input->post('nic', TRUE));
        $users_model->setDateOfBirth($this->input->post('dob', TRUE));
        $users_model->setRegistrationValidTill($this->input->post('reg_valid_til', TRUE));
        $users_model->setPhotoPath($this->input->post('pro_image', TRUE));

        $users_model->setUserID($this->input->post('user_id', TRUE));

        echo $users_service->update_user($users_model);
    }

}
