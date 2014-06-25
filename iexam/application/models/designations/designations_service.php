<?php

class Designations_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('designations/designations_model');
    }

    public function get_all_designations() {

        $this->db->select('*');
        $this->db->from('designations');
        $this->db->where('DelInd', '1');
        $this->db->order_by("DesignationID", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_designation($designation_model) {
        return $this->db->insert('designations', $designation_model);
    }

    function delete_designation($designation_id) {
        $data = array('DelInd' => '0');
        $this->db->where('DesignationID', $designation_id);
        return $this->db->update('designations', $data);
    }

    function get_designation_by_id($designation_id) {
        $query = $this->db->get_where('designations', array('DesignationID' => $designation_id));
        return $query->row();
    }

    function update_designation($designation_model) {

        $data = array(
            'Designation' => $designation_model->getDesignation()
        );

        $this->db->where('DesignationID', $designation_model->getDesignationID());

        return $this->db->update('designations', $data);
    }

}
