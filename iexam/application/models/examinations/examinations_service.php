<?php

class Examinations_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('examinations/examinations_model');
    }

    public function get_all_examinations() {

        $this->db->select('examinations.*,courses.Course,users.FirstName as Instructor,semisters.Semester,examination_types.ExaminationType');
        $this->db->from('examinations');
        $this->db->join('courses', 'courses.CourseID = examinations.CourseID');
        $this->db->join('users', 'users.UserID = examinations.InsttructorID');
        $this->db->join('semisters', 'semisters.SemesterID = examinations.SeminsterID');
        $this->db->join('examination_types', 'semisters.ExaminationTypeID = examinations.ExaminationTypeID');
        $this->db->where('examinations.DelInd', '1');
        $this->db->where('users.DelInd', '1');
        $this->db->where('courses.DelInd', '1');
        $this->db->where('semisters.DelInd', '1');
        $this->db->where('examination_types.DelInd', '1');
        $this->db->order_by("examinations.ExaminationID", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_examination($examination_model) {
        return $this->db->insert('examinations', $examination_model);
    }

    function delete_examination($examination_id) {
        $data = array('DelInd' => '0');
        $this->db->where('ExaminationID', $examination_id);
        return $this->db->update('examinations', $data);
    }

    function get_examination_by_id($examination_id) {
        $query = $this->db->get_where('examinations', array('ExaminationID' => $examination_id));
        return $query->row();
    }

    function update_examination($examination_model) {

        $data = array(
            'Name' => $examination_model->getName(),
            'ExaminationTypeID' => $examination_model->getExaminationTypeID(),
            'Year' => $examination_model->getYear(),
            'SemesterID' => $examination_model->getSemesterID(),
            'CourseID' => $examination_model->getCourseID(),
            'InstructorID' => $examination_model->getInstructorID(),
            'NumberOfMCQs' => $examination_model->getNumberOfMCQs(),
            'NumberOfShortAnswerQuestions' => $examination_model->getNumberOfShortAnswerQuestions(),
            'StartDate' => $examination_model->getStartDate(),
            'EndDate' => $examination_model->getEndDate(),
            'Active' => $examination_model->getActive()
        );

        $this->db->where('ExaminationID', $examination_model->getExaminationID());

        return $this->db->update('examinations', $data);
    }

}
