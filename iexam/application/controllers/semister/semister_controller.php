<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Semister_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('USER_LOGGED_IN')) {
            redirect(site_url() . '/login/login_controller');
        } else {
            $this->load->model('semisters/semisters_model');
            $this->load->model('semisters/semisters_service');
        }
    }

    function manage_semisters() {

        $semister_service = new Semisters_service();

        $data['heading'] = "Manage Semisters";
        $data['semesters'] = $semister_service->get_all_semisters();

//        $partials = array('content' => 'semisters/manage_semisters_view');
//        $this->template->load('template/main_template', $partials, $data);
        $this->load->view('semisters/manage_semisters_view', $data);
    }

    function add_new_semister() {


        $semister_model = new Semisters_model();
        $semister_service = new Semisters_service();

        $semister_model->setSemester($this->input->post('semister_name', TRUE));
        $semister_model->setDelInd('1');

        echo $semister_service->add_new_semister($semister_model);
    }

    function delete_semister() {

        $semister_service = new Semisters_service();

        echo $semister_service->delete_semister(trim($this->input->post('id', TRUE)));
    }

    function edit_semister_view($id) {

        $semister_service = new Semisters_service();


        $data['heading'] = "Edit Semister";
        $data['semister'] = $semister_service->get_semister_by_id($id);

        $partials = array('content' => 'semisters/edit_semisters_view');
        $this->template->load('template/main_template', $partials, $data);
    }

    function edit_semister() {


        $semister_model = new Semisters_model();
        $semister_service = new Semisters_service();

        $semister_model->setSemester($this->input->post('semister_name', TRUE));

        $semister_model->setSemesterID($this->input->post('semister_id', TRUE));

        echo $semister_service->update_semister($semister_model);
    }

}
