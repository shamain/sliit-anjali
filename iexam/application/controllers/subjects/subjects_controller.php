<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subjects_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('USER_LOGGED_IN')) {
            redirect(site_url() . '/login/login_controller');
        } else {
            $this->load->model('subjects/subjects_model');
            $this->load->model('subjects/subjects_service');
        }
    }

    function manage_subjects() {

        $subjects_service = new Subjects_service();

        $data['heading'] = "Manage Subjects";
        $data['subjects'] = $subjects_service->get_all_subjects();

//        $partials = array('content' => 'subjects/manage_subjects_view');
//        $this->template->load('template/main_template', $partials, $data);
        $this->load->view('subjects/manage_subjects_view', $data);
    }

    function add_new_subject() {


//        $subjects_model = new Subjects_model();
        $subjects_service = new Subjects_service();

        $subjects_model->setSubject($this->input->post('subject_name', TRUE));
        $subjects_model->setDelInd('1');

        echo $subjects_service->add_new_subject($subjects_model);
    }

    function delete_subject() {

        $subjects_service = new Subjects_service();

        echo $subjects_service->delete_subject(trim($this->input->post('id', TRUE)));
    }

    function edit_subject_view($id) {

        $subjects_service = new Subjects_service();


        $data['heading'] = "Edit Subject";
        $data['subject'] = $subjects_service->get_subject_by_id($id);


//        $partials = array('content' => 'subjects/edit_subjects_view');
//        $this->template->load('template/main_template', $partials, $data);
        
        $this->load->view('subjects/edit_subjects_view', $data);
    }

    function edit_subject() {
        $subjects_model = new Subjects_model();
        $subjects_service = new Subjects_service();

        $subjects_model->setSubject($this->input->post('subject_name', TRUE));

        $subjects_model->setSubjectID($this->input->post('subject_id', TRUE));

        echo $subjects_service->update_subject($subjects_model);
    }

}
