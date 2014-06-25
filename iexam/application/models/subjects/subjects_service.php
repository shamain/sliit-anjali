<?php

class Subjects_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('subjects/subjects_model');
    }

    public function get_all_subjects() {

        $this->db->select('*');
        $this->db->from('subjects');
        $this->db->where('DelInd', '1');
        $this->db->order_by("SubjectID", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_subject($subject_model) {
        return $this->db->insert('subjects', $subject_model);
    }

    function delete_subject($subject_id) {
        $data = array('DelInd' => '0');
        $this->db->where('SubjectID', $subject_id);
        return $this->db->update('subjects', $data);
    }

    function get_subject_by_id($subject_id) {
        $query = $this->db->get_where('subjects', array('SubjectID' => $subject_id));
        return $query->row();
    }

  
    function update_subject($subject_model) {

        $data = array(
            'Subject' => $subject_model->getSubject()
        );

        $this->db->where('SubjectID', $subject_model->getSubjectID());

        return $this->db->update('subjects', $data);
    }

}
