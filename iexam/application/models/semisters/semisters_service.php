<?php

class Semisters_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('semisters/semisters_model');
    }

    public function get_all_semisters() {

        $this->db->select('*');
        $this->db->from('semisters');
        $this->db->where('DelInd', '1');
        $this->db->order_by("SemesterID", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_semister($semister_model) {
        return $this->db->insert('semisters', $semister_model);
    }

    function delete_semister($semister_id) {
        $data = array('DelInd' => '0');
        $this->db->where('SemesterID', $semister_id);
        return $this->db->update('semisters', $data);
    }

    function get_semister_by_id($semister_id) {
        $query = $this->db->get_where('semisters', array('SemesterID' => $semister_id));
        return $query->row();
    }

  
    function update_semister($semister_model) {

        $data = array(
            'Semester' => $semister_model->getSemester()
        );

        $this->db->where('SemesterID', $semister_model->getSemesterID());

        return $this->db->update('semisters', $data);
    }

}
