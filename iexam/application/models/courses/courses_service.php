<?php

class Courses_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('courses/courses_model');
    }

    public function get_all_courses() {

        $this->db->select('courses.*,subjects.Subject');
        $this->db->from('courses');
        $this->db->join('subjects', 'subjects.SubjectID = courses.SubjectID');
        $this->db->where('subjects.DelInd', '1');
        $this->db->where('courses.DelInd', '1');
        $this->db->order_by("courses.CourseID", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_course($course_model) {
        return $this->db->insert('courses', $course_model);
    }

    function delete_course($course_id) {
        $data = array('DelInd' => '0');
        $this->db->where('CourseID', $course_id);
        return $this->db->update('courses', $data);
    }

    function get_course_by_id($course_id) {
        $query = $this->db->get_where('courses', array('CourseID' => $course_id));
        return $query->row();
    }

    function update_course($course_model) {

        $data = array(
            'Course' => $course_model->getCourse(),
            'SubjectID' => $course_model->getSubjectID(),
            'CourseCode' => $course_model->getCourseCode()
        );

        $this->db->where('CourseID', $course_model->getCourseID());

        return $this->db->update('courses', $data);
    }

}
