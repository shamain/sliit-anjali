<?php

class Designations_model extends CI_Model {

    var $DesignationID;
    var $Designation;
    var $DelInd;

    function __construct() {
        parent::__construct();
    }

    public function getDesignationID() {
        return $this->DesignationID;
    }

    public function getDesignation() {
        return $this->Designation;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setDesignationID($DesignationID) {
        $this->DesignationID = $DesignationID;
    }

    public function setDesignation($Designation) {
        $this->Designation = $Designation;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }

}
