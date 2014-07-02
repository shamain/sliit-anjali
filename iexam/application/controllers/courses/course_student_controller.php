<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_student_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('USER_LOGGED_IN')) {
            redirect(site_url() . '/login/login_controller');
        } else {
            $this->load->model('course_students/course_students_model');
            $this->load->model('course_students/course_students_service');

            $this->load->model('users/users_model');
            $this->load->model('users/users_service');

            $this->load->model('courses/courses_model');
            $this->load->model('courses/courses_service');
        }
    }

    function manage_course_students() {

        $course_student_service = new Course_students_service();
        $course_service = new Courses_service();
        $users_service = new Users_service();


        $data['heading'] = "Manage Course Students";
        $data['course_students'] = $course_student_service->get_all_course_students();
        $data['courses'] = $course_service->get_all_courses();
        $data['students'] = $users_service->get_all_users();

//        $partials = array('content' => 'designations/manage_designations_view');
//        $this->template->load('template/main_template', $partials, $data);
        $this->load->view('courses/manage_course_students_view', $data);
    }

    function add_new_course_student() {

        $course_student_service = new Course_students_service();
        $course_student_model=new Course_Students_model();

        $course_student_model->setCourseID($this->input->post('course_id', TRUE));
        $course_student_model->setStudentID($this->input->post('student', TRUE));
        $course_student_model->setDelInd('1');

        echo $course_student_service->add_new_course_students($course_student_model);
    }

    function delete_course_student() {

        $course_student_service = new Course_students_service();

        echo $course_student_service->delete_course_students(trim($this->input->post('id', TRUE)));
    }

    function edit_course_student_view($id) {

        $course_student_service = new Course_students_service();
        $course_service = new Courses_service();
        $users_service = new Users_service();

        $data['heading'] = "Edit Course Student";
        $data['course_student'] = $course_student_service->get_course_students_by_id($id);
        $data['courses'] = $course_service->get_all_courses();
        $data['students'] = $users_service->get_all_users();


//        $partials = array('content' => 'courses/edit_course_students_view');
//        $this->template->load('template/main_template', $partials, $data);
        
         $this->load->view('courses/edit_course_students_view', $data);
    }

    function edit_course_student() {
        $course_student_service = new Course_students_service();
        $course_student_model=new Course_Students_model();

        $course_student_model->setCourseID($this->input->post('course_id', TRUE));
        $course_student_model->setStudentID($this->input->post('student', TRUE));

        $course_student_model->setCourseStudentID($this->input->post('course_student_id', TRUE));

        echo $course_student_service->update_course_student($course_student_model);
    }

}
