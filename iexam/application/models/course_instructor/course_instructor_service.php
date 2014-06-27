<?php

class Course_instructor_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('course_instructor/course_instructor_model');
    }

    public function get_all_course_instructor() {

        $this->db->select('course_instructor.*,courses.Course,semisters.Semester,users.FirstName as Instructor');
        $this->db->from('course_instructor');
        $this->db->join('courses', 'courses.CourseID = course_instructor.CourseID');
        $this->db->join('semisters', 'semisters.SemesterID = course_instructor.SemisterID');
        $this->db->join('users', 'users.UserID = course_instructor.InstructorID');
        $this->db->where('course_instructor.DelInd', '1');
        $this->db->where('users.DelInd', '1');
        $this->db->where('courses.DelInd', '1');
        $this->db->where('semisters.DelInd', '1');
        $this->db->order_by("course_instructor.CourseInstructorID", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_course_instructor($course_instructor_model) {
        return $this->db->insert('course_instructor', $course_instructor_model);
    }

    function delete_course_instructor($course_instructor_id) {
        $data = array('DelInd' => '0');
        $this->db->where('CourseInstructorID', $course_instructor_id);
        return $this->db->update('course_instructor', $data);
    }

    function get_course_instructor_by_id($course_instructor_id) {
        $query = $this->db->get_where('course_instructor', array('CourseInstructorID' => $course_instructor_id));
        return $query->row();
    }

    function update_course_instructor($course_instructor_model) {

        $data = array(
            'CourseID' => $course_instructor_model->getCourseID(),
            'Year' => $course_instructor_model->getYear(),
            'SemisterID' => $course_instructor_model->getSemesterID(),
            'InstructorID' => $course_instructor_model->getInstructorID()
        );

        $this->db->where('CourseInstructorID', $course_instructor_model->getCourseInstructorID());

        return $this->db->update('course_instructor', $data);
    }

}
