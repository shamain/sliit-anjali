<?php

class Course_students_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('course_students/course_students_model');
    }

    public function get_all_course_students() {

        $this->db->select('*');
        $this->db->from('course_students');
        $this->db->where('courses.DelInd', '1');
        $this->db->order_by("course_students.CourseStudentID", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_course_students($course_student_model) {
        return $this->db->insert('course_students', $course_student_model);
    }

    function delete_course_students($course_student_id) {
        $data = array('DelInd' => '0');
        $this->db->where('CourseStudentID', $course_student_id);
        return $this->db->update('course_students', $data);
    }

    function get_course_students_by_id($course_student_id) {
        $query = $this->db->get_where('course_students', array('CourseStudentID' => $course_student_id));
        return $query->row();
    }

    function update_course_student($course_student_model) {

        $data = array(
            'CourseID' => $course_student_model->getCourseID(),
            'StudentID' => $course_student_model->getStudentID()
        );

        $this->db->where('CourseStudentID', $course_student_model->getCourseStudentID());

        return $this->db->update('course_students', $data);
    }

}
