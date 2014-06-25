<?php

class User_level_permissions extends CI_Model{
    
    var $UserLevelID;
    var $TableName;
    var $Permission;
    
    function __construct() {
        parent::_construct();
    }
    
    
    public function getUserLevelID() {
        return $this->UserLevelID;
    }

    public function getTableName() {
        return $this->TableName;
    }

    public function getPermission() {
        return $this->Permission;
    }

    public function setUserLevelID($UserLevelID) {
        $this->UserLevelID = $UserLevelID;
    }

    public function setTableName($TableName) {
        $this->TableName = $TableName;
    }

    public function setPermission($Permission) {
        $this->Permission = $Permission;
    }



}
