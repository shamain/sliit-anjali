<?php

class User_level_permissions_model extends CI_Model {

    var $userlevelid;
    var $tablename;
    var $permission;
    var $DelInd;

    function __construct() {
        parent::__construct();
    }

    public function getUserlevelid() {
        return $this->userlevelid;
    }

    public function getTablename() {
        return $this->tablename;
    }

    public function getPermission() {
        return $this->permission;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setUserlevelid($userlevelid) {
        $this->userlevelid = $userlevelid;
    }

    public function setTablename($tablename) {
        $this->tablename = $tablename;
    }

    public function setPermission($permission) {
        $this->permission = $permission;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }

}
