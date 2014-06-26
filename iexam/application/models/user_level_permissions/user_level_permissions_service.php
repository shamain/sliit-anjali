<?php

class User_level_permissions_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('user_level_permissions/user_level_permissions_model');
    }

    public function get_all_user_level_permissions() {

        $this->db->select('*');
        $this->db->from('user_level_permissions');
        $this->db->where('DelInd', '1');
        $this->db->order_by("userlevelid", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_user_level($user_levels_model) {
        return $this->db->insert('user_levels', $user_levels_model);
    }

    function delete_user_level($user_level_id) {
        $data = array('DelInd' => '0');
        $this->db->where('userlevelid', $user_level_id);
        return $this->db->update('user_levels', $data);
    }

    function get_user_level_by_id($user_level_id) {
        $query = $this->db->get_where('user_levels', array('userlevelid' => $user_level_id));
        return $query->row();
    }

  
    function update_user_level($user_levels_model) {

        $data = array(
            'userlevelname' => $user_levels_model->getUserlevelname()
        );

        $this->db->where('userlevelid', $user_levels_model->getUserlevelid());

        return $this->db->update('user_levels', $data);
    }

}
