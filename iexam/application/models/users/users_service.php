<?php

class Users_service extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('users/users_model');
    }

    public function get_all_users() {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('DelInd', '1');
        $this->db->where('Activated', 1);
        $this->db->order_by("UserID", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function add_new_users($users_model) {
        return $this->db->insert('users', $users_model);
    }

    function delete_users($user_id) {
        $data = array('DelInd' => '0');
        $this->db->where('UserID', $user_id);
        return $this->db->update('users', $data);
    }

    function get_user_by_id($user_id) {
        $query = $this->db->get_where('users', array('UserID' => $user_id));
        return $query->row();
    }

  
    function update_user($users_model) {

        $data = array(
            'Username' => $users_model->getUsername(),
            'Password' => $users_model->getPassword(),
            'UserLevel' => $users_model->getUserLevel(),
            'DesignationID' => $users_model->getDesignationID(),
            'FirstName' => $users_model->getFirstName(),
            'MiddleName' => $users_model->getMiddleName(),
            'LastName' => $users_model->getLastName(),
            'Email' => $users_model->getEmail(),
            'RegistrationNumber' => $users_model->getRegistrationNumber(),
            'NICNumber' => $users_model->getNICNumber(),
            'Gender' => $users_model->getGender(),
            'MaritalStatusID' => $users_model->getMaritalStatusID(),
            'DateOfBirth' => $users_model->getDateOfBirth(),
            'RegisteredOn' => $users_model->getRegisteredOn(),
            'RegistrationValidTill' => $users_model->getRegistrationValidTill(),
            'PhotoPath' => $users_model->getPhotoPath(),
        );

        $this->db->where('UserID', $users_model->getUserID());

        return $this->db->update('users', $data);
    }

    function authenticate_user_with_password($users_model) {

        $data = array('Username' => $users_model->getUsername(), 'Password' => $users_model->getPassword(), 'del_ind' => '1');

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($data);
        $query = $this->db->get();
        return $query->row();
    }
    
    
}
