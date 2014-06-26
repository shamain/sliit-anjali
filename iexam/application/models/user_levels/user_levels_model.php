<?php

class User_levels_model extends CI_Model{
    
    var $userlevelid;
    var $userlevelname;
    var $DelInd;
    
    function __construct() {
        parent::__construct();
    }

    
    public function getUserlevelid() {
        return $this->userlevelid;
    }

    public function getUserlevelname() {
        return $this->userlevelname;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setUserlevelid($userlevelid) {
        $this->userlevelid = $userlevelid;
    }

    public function setUserlevelname($userlevelname) {
        $this->userlevelname = $userlevelname;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }



}

