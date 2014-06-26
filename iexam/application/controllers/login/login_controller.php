<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        // if ($this->session->userdata('LCS_EMPLOYEE_LOGGED_IN')) {
        //echo 1;die();
        // redirect(base_url() . 'index.php/IMS/dashboard_controller/');
        // } else {
        $this->load->model('users/users_model');
        $this->load->model('users/users_service');

        //}
    }

    function index() {
        if ($this->session->userdata('USER_LOGGED_IN')) {
            $this->template->load('template/main_template');
        } else {

//            $this->template->load('template/login');
            $this->template->load('template/main_template');
        }
    }

    //Login details checking function 
    function authenticate_user() {

        $users_model = new Users_model();
        $users_service = new Users_service();

        $user_name = $this->input->post('login_username', TRUE);
        $user_password = $this->input->post('login_password', TRUE);


        $users_model->setUsername($user_name);
        $users_model->setPassword(md5($user_password));

        $logged_user_details = $users_service->authenticate_user_with_password($users_model);



        if (count($logged_user_details) == 0) {

            echo 0;
        } else {

            //Setting sessions		
            $this->session->set_userdata('USER_FIRST_NAME', $logged_user_details->FirstName);
            $this->session->set_userdata('USER_MIDDLE_NAME', $logged_user_details->MiddleName);
            $this->session->set_userdata('USER_LAST_NAME', $logged_user_details->LastName);
            $this->session->set_userdata('USER_REG_NUM', $logged_user_details->RegistrationNumber);
            $this->session->set_userdata('USER_PROPIC', $logged_user_details->PhotoPath);

            $this->session->set_userdata('USER_LOGGED_IN', 'TRUE');

            echo 1;
        }
    }

    function logout() {

        $this->session->sess_destroy();
        redirect(site_url() . '/login/login_controller');
    }

}
