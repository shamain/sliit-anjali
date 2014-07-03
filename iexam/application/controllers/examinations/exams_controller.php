<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Exams_controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('USER_LOGGED_IN')) {
            redirect(site_url() . '/login/login_controller');
        } else {
            $this->load->model('examinations/examinations_model');
            $this->load->model('examinations/examinations_service');

            $this->load->model('examination_types/examination_types_model');
            $this->load->model('examination_types/examination_types_service');
            
            $this->load->model('semisters/semisters_model');
            $this->load->model('semisters/semisters_service');
            
            $this->load->model('courses/courses_model');
            $this->load->model('courses/courses_service');
            
            $this->load->model('users/users_model');
            $this->load->model('users/users_service');
            
            $this->load->model('course_instructor/course_instructor_model');
            $this->load->model('course_instructor/course_instructor_service');
            
        }
    }

    function manage_examinations() {

        $examinations_service = new Examinations_service();
        $examination_types_service = new Examination_types_service();
        $semister_service = new Semisters_service();
        $courses_service = new Courses_service();
        $course_instructor_service = new Course_instructor_service();

        $data['heading'] = "Manage Examinations";
        $data['examinations'] = $examinations_service->get_all_examinations();
        $data['examination_types'] = $examination_types_service->get_all_examination_types();
        $data['semesters'] = $semister_service->get_all_semisters();
        $data['courses'] = $courses_service->get_all_courses();
        $data['instructors'] = $course_instructor_service->get_all_course_instructor();


//        $partials = array('content' => 'examination_types/manage_examination_types_view');
//        $this->template->load('template/main_template', $partials, $data);

        $this->load->view('examinations/manage_examination_view', $data);
    }

    function add_new_examination() {


        $examinations_model = new Examinations_model();
        $examinations_service = new Examinations_service();

        $examinations_model->setName($this->input->post('exam_name', TRUE));
        $examinations_model->setExaminationTypeID($this->input->post('exam_type_id', TRUE));
        $examinations_model->setYear($this->input->post('year', TRUE));
        $examinations_model->setSemesterID($this->input->post('semester_id', TRUE));
        $examinations_model->setCourseID($this->input->post('course_id', TRUE));
        $examinations_model->setInstructorID($this->input->post('instructor_id', TRUE));
        $examinations_model->setNumberOfMCQs($this->input->post('no_mcq', TRUE));
        $examinations_model->setNumberOfShortAnswerQuestions($this->input->post('no_short_ans', TRUE));
        $examinations_model->setStartDate($this->input->post('start_date', TRUE));
        $examinations_model->setEndDate($this->input->post('end_date', TRUE));
        $examinations_model->setActive($this->input->post('active', TRUE));
        $examinations_model->setDelInd('1');

        echo $examinations_service->add_new_examination($examinations_model);
    }

    function delete_examination() {

        $examinations_service = new Examinations_service();

        echo $examinations_service->delete_examination(trim($this->input->post('id', TRUE)));
    }

    function edit_examination_view($id) {

        $examinations_service = new Examinations_service();

        $data['heading'] = "Edit Examination";
        $data['examination'] = $examinations_service->get_examination_by_id($id);


        $this->load->view('examinations/edit_examination_view', $data);
    }

    function edit_examination() {
        $examinations_model = new Examinations_model();
        $examinations_service = new Examinations_service();

        $examinations_model->setName($this->input->post('exam_name', TRUE));
        $examinations_model->setExaminationTypeID($this->input->post('exam_type_id', TRUE));
        $examinations_model->setYear($this->input->post('year', TRUE));
        $examinations_model->setSemesterID($this->input->post('semester_id', TRUE));
        $examinations_model->setCourseID($this->input->post('course_id', TRUE));
        $examinations_model->setInstructorID($this->input->post('instructor_id', TRUE));
        $examinations_model->setNumberOfMCQs($this->input->post('no_mcq', TRUE));
        $examinations_model->setNumberOfShortAnswerQuestions($this->input->post('no_short_ans', TRUE));
        $examinations_model->setStartDate($this->input->post('start_date', TRUE));
        $examinations_model->setEndDate($this->input->post('end_date', TRUE));
        $examinations_model->setActive($this->input->post('active', TRUE));
        $examinations_model->setDelInd('1');

        $examinations_model->setExaminationID($this->input->post('exam_id', TRUE));

        echo $examinations_service->update_examination($examinations_model);
    }

}
