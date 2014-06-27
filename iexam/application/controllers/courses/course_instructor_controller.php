<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_instructor_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('USER_LOGGED_IN')) {
            redirect(site_url() . '/login/login_controller');
        } else {
            $this->load->model('course_instructor/course_instructor_model');
            $this->load->model('course_instructor/course_instructor_service');

            $this->load->model('semisters/semisters_model');
            $this->load->model('semisters/semisters_service');

            $this->load->model('courses/courses_model');
            $this->load->model('courses/courses_service');

            $this->load->model('users/users_model');
            $this->load->model('users/users_service');
        }
    }

    function manage_instructors() {

        $course_instructor_service = new Course_instructor_service();
        $semester_service = new Semisters_service;
        $course_service = new Courses_service();

        $data['heading'] = "Manage Instructors";
        $data['instructors'] = $course_instructor_service->get_all_course_instructor();
        $data['semesters'] = $semester_service->get_all_semisters();
        $data['courses'] = $course_service->get_all_courses();
//        $partials = array('content' => 'designations/manage_designations_view');
//        $this->template->load('template/main_template', $partials, $data);
        $this->load->view('courses/manage_course_instructor_view', $data);
    }

    function add_new_instructor() {

        $course_instructor_model = new Course_instructor_model();
        $course_instructor_service = new Course_instructor_service();


        $course_instructor_model->setCourseID($this->input->post('course_id', TRUE));
        $course_instructor_model->setSemesterID($this->input->post('semester_id', TRUE));
        $course_instructor_model->setYear($this->input->post('year', TRUE));
        $course_instructor_model->setInstructorID($this->input->post('instructor_id', TRUE));


        echo $course_instructor_service->add_new_course_instructor($course_instructor_model);
    }

    function delete_course_instructor() {

        $courses_instructor_service = new Course_instructor_service();

        echo $courses_instructor_service->delete_course_instructor(trim($this->input->post('instructor_id', TRUE)));
    }

    function edit_course_instructor_view($id) {

        $courses_instructor_service = new Course_instructor_service();

        $data['heading'] = "Edit Course Instructor";
        $data['instructor'] = $courses_instructor_service->get_course_instructor_by_id($id);


        $partials = array('content' => 'courses/edit_course_instructor_view');
        $this->template->load('template/main_template', $partials, $data);
    }

    function edit_course_instructor() {
        $course_instructor_model = new Course_instructor_model();
        $course_instructor_service = new Course_instructor_service();


        $course_instructor_model->setCourseID($this->input->post('course_id', TRUE));
        $course_instructor_model->setSemesterID($this->input->post('semester_id', TRUE));
        $course_instructor_model->setYear($this->input->post('year', TRUE));
        $course_instructor_model->setInstructorID($this->input->post('instructor_id', TRUE));

        $course_instructor_model->setCourseInstructorID($this->input->post('course_ins_id', TRUE));

        echo $course_instructor_service->update_course_instructor($course_instructor_model);
    }

}
