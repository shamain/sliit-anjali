<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Courses_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('USER_LOGGED_IN')) {
            redirect(site_url() . '/login/login_controller');
        } else {
            $this->load->model('courses/courses_model');
            $this->load->model('courses/courses_service');

            $this->load->model('subjects/subjects_model');
            $this->load->model('subjects/subjects_service');
        }
    }

    function manage_courses() {

        $courses_service = new Courses_service();
        $subject_service = new Subjects_service();

        $data['heading'] = "Manage Courses";
        $data['courses'] = $courses_service->get_all_courses();
        $data['subjects'] = $subject_service->get_all_subjects();

//        $partials = array('content' => 'designations/manage_designations_view');
//        $this->template->load('template/main_template', $partials, $data);
        $this->load->view('courses/manage_courses_view', $data);
    }

    function add_new_course() {


        $courses_model = new Courses_model();
        $courses_service = new Courses_service();

        $courses_model->setCourse($this->input->post('course_name', TRUE));
        $courses_model->setCourseCode($this->input->post('course_code', TRUE));
        $courses_model->setSubjectID($this->input->post('subject_id', TRUE));
        $courses_model->setDelInd('1');

        echo $courses_service->add_new_course($courses_model);
    }

    function delete_course() {

        $courses_service = new Courses_service();

        echo $courses_service->delete_course(trim($this->input->post('course_id', TRUE)));
    }

    function edit_course_view($id) {

        $courses_service = new Courses_service();

        $data['heading'] = "Edit Course";
        $data['course'] = $courses_service->get_course_by_id($id);


        $partials = array('content' => 'semisters/edit_semisters_view');
        $this->template->load('template/main_template', $partials, $data);
    }

    function edit_course() {
        $courses_model = new Courses_model();
        $courses_service = new Courses_service();

        $courses_model->setCourse($this->input->post('course_name', TRUE));
        $courses_model->setCourseCode($this->input->post('course_code', TRUE));
        $courses_model->setSubjectID($this->input->post('subject_id', TRUE));

        $courses_model->setCourseID($this->input->post('course_id', TRUE));

        echo $courses_service->update_course($courses_model);
    }

}
