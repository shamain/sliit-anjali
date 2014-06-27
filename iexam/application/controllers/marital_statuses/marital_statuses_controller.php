<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Marital_statuses_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('USER_LOGGED_IN')) {
            redirect(site_url() . '/login/login_controller');
        } else {
            $this->load->model('marital_statuses/marital_statuses_model');
            $this->load->model('marital_statuses/marital_statuses_service');
        }
    }

    function manage_marital_statuses() {

        $marital_statuses_service = new Marital_statuses_service();

        $data['heading'] = "Manage Marital Statuses";
        $data['marital_statuses'] = $marital_statuses_service->get_all_marital_statuses();

//        $partials = array('content' => 'marital_statuses/manage_marital_statuses_view');
//        $this->template->load('template/main_template', $partials, $data);
        $this->load->view('marital_statuses/manage_marital_statuses_view', $data);
    }

    function add_new_marital_statuses() {


        $marital_statuses_service = new Marital_statuses_service();
        $marital_statuses_model = new Marital_statuses_model();

        $marital_statuses_model->setMaritalStatus($this->input->post('status_name', TRUE));
        $marital_statuses_model->setDelInd('1');

        echo $marital_statuses_service->add_new_marital_status($marital_statuses_model);
    }

    function delete_marital_status() {

        $marital_statuses_service = new Marital_statuses_service();

        echo $marital_statuses_service->delete_marital_status(trim($this->input->post('id', TRUE)));
    }

    function edit_marital_status_view($id) {

        $marital_statuses_service = new Marital_statuses_service();


        $data['heading'] = "Edit Marital Status";
        $data['marital_status'] = $marital_statuses_service->get_marital_status_by_id($id);


        $partials = array('content' => 'marital_statuses/edit_marital_statuses_view');
        $this->template->load('template/main_template', $partials, $data);
    }

    function edit_marital_status() {
        $marital_statuses_service = new Marital_statuses_service();
        $marital_statuses_model = new Marital_statuses_model();

        $marital_statuses_model->setMaritalStatus($this->input->post('status_name', TRUE));

        $marital_statuses_model->setMaritalStatusID($this->input->post('status_id', TRUE));

        echo $marital_statuses_service->update_marital_status($marital_statuses_model);
    }

}
