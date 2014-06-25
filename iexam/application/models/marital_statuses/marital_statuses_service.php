<?php

class Marital_statuses_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('marital_statuses/marital_statuses_model');
    }

    public function get_all_marital_statuses() {

        $this->db->select('*');
        $this->db->from('marital_statuses');
        $this->db->where('DelInd', '1');
        $this->db->order_by("MaritalStatusID", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_marital_status($marital_status_model) {
        return $this->db->insert('marital_statuses', $marital_status_model);
    }

    function delete_marital_status($marital_status_id) {
        $data = array('DelInd' => '0');
        $this->db->where('MaritalStatusID', $marital_status_id);
        return $this->db->update('marital_statuses', $data);
    }

    function get_marital_status_by_id($marital_status_id) {
        $query = $this->db->get_where('marital_statuses', array('MaritalStatusID' => $marital_status_id));
        return $query->row();
    }

    function update_marital_status($marital_status_model) {

        $data = array(
            'MaritalStatus' => $marital_status_model->getMaritalStatus()
        );

        $this->db->where('MaritalStatusID', $marital_status_model->getMaritalStatusID());

        return $this->db->update('marital_statuses', $data);
    }

}
