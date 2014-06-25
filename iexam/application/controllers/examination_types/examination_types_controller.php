<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Examination_types_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

//        if (!$this->session->userdata('EMPLOYEE_LOGGED_IN')) {
//            redirect(site_url() . '/login/login_controller');
//        } else {
        $this->load->model('examination_types/examination_types_model');
        $this->load->model('examination_types/examination_types_service');
//        }
    }

    function manage_examination_types() {

        $examination_types_service = new Examination_types_service();

        $data['heading'] = "Manage Exam Types";
        $data['examination_types'] = $examination_types_service->get_all_examination_types();

        $partials = array('content' => 'examination_types/manage_examination_types_view');
        $this->template->load('template/main_template', $partials, $data);
    }

    function add_new_examination_type() {


        $examination_types_model = new Examination_types_model();
        $examination_types_service = new Examination_types_service();

        $examination_types_model->setExaminaionType($this->input->post('exam_type_name', TRUE));
        $examination_types_model->setDelInd('1');

        echo $examination_types_service->add_new_examination_type($examination_types_model);
    }

    function delete_examination_type() {

        $examination_types_service = new Examination_types_service();

        echo $examination_types_service->delete_examination_type(trim($this->input->post('id', TRUE)));
    }

    function edit_examination_type_view($id) {

        $examination_types_service = new Examination_types_service();

        $data['heading'] = "Edit Exam Type";
        $data['examination_type'] = $examination_types_service->get_examination_type_by_id($id);


        $partials = array('content' => 'semisters/edit_semisters_view');
        $this->template->load('template/main_template', $partials, $data);
    }

    function edit_examination_type() {
        $examination_types_model = new Examination_types_model();
        $examination_types_service = new Examination_types_service();

        $examination_types_model->setExaminaionType($this->input->post('exam_type_name', TRUE));

        $examination_types_model->setExaminationTypeID($this->input->post('exam_type_id', TRUE));

        echo $examination_types_service->update_examination_type($examination_types_model);
    }

}
