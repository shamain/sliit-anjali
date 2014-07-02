<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Designations_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('USER_LOGGED_IN')) {
            redirect(site_url() . '/login/login_controller');
        } else {
            $this->load->model('designations/designations_model');
            $this->load->model('designations/designations_service');
        }
    }

    function manage_designations() {

        $designations_service = new Designations_service();

        $data['heading'] = "Manage Designaitons";
        $data['designations'] = $designations_service->get_all_designations();

//        $partials = array('content' => 'designations/manage_designations_view');
//        $this->template->load('template/main_template', $partials, $data);
        $this->load->view('designations/manage_designations_view', $data);
    }

    function add_new_designation() {


        $designations_model = new Designations_model();
        $designations_service = new Designations_service();

        $designations_model->setDesignation($this->input->post('designation_name', TRUE));
        $designations_model->setDelInd('1');

        echo $designations_service->add_new_designation($designations_model);
    }

    function delete_designation() {

        $designations_service = new Designations_service();

        echo $designations_service->delete_designation(trim($this->input->post('id', TRUE)));
    }

    function edit_designation_view($id) {

        $designations_service = new Designations_service();

        $data['heading'] = "Edit Designation";
        $data['designation'] = $designations_service->get_designation_by_id($id);


//        $partials = array('content' => 'designations/edit_designations_view');
//        $this->template->load('template/main_template', $partials, $data);
        
        $this->load->view('designations/edit_designations_view', $data);
    }

    function edit_designation() {
        $designations_model = new Designations_model();
        $designations_service = new Designations_service();

        $designations_model->setDesignation($this->input->post('designation_name', TRUE));

        $designations_model->setDesignationID($this->input->post('designation_id', TRUE));

        echo $designations_service->update_designation($designations_model);
    }

}
