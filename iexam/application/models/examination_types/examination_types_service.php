<?php

class Examination_types_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('examination_types/examination_types_model');
    }

    public function get_all_examination_types() {

        $this->db->select('*');
        $this->db->from('examination_types');
        $this->db->where('DelInd', '1');
        $this->db->order_by("ExaminationTypeID", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_examination_type($examination_type_model) {
        return $this->db->insert('examination_types', $examination_type_model);
    }

    function delete_examination_type($examination_type_id) {
        $data = array('DelInd' => '0');
        $this->db->where('ExaminationTypeID', $examination_type_id);
        return $this->db->update('examination_types', $data);
    }

    function get_examination_type_by_id($examination_type_id) {
        $query = $this->db->get_where('examination_types', array('ExaminationTypeID' => $examination_type_id));
        return $query->row();
    }

    function update_examination_type($examination_type_model) {

        $data = array(
            'ExaminaionType' => $examination_type_model->getExaminaionType()
        );

        $this->db->where('ExaminationTypeID', $examination_type_model->getExaminationTypeID());

        return $this->db->update('examination_types', $data);
    }

}
