<?php

class User_levels extends CI_Model{
    
    var $UserLevelID;
    var $UserLevelName;
    
    function __construct() {
        parent::_construct();
        
    }

    
    public function getUserLevelID() {
        return $this->UserLevelID;
    }

    public function getUserLevelName() {
        return $this->UserLevelName;
    }

    public function setUserLevelID($UserLevelID) {
        $this->UserLevelID = $UserLevelID;
    }

    public function setUserLevelName($UserLevelName) {
        $this->UserLevelName = $UserLevelName;
    }


}

