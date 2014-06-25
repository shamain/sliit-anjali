<?php

class Marital_statuses extends CI_Model {

    var $MaritalStatusID;
    var $MaritalStatus;
    var $DelInd;

    function __construct() {
        parent::_construct();
    }

    public function getMaritalStatusID() {
        return $this->MaritalStatusID;
    }

    public function getMaritalStatus() {
        return $this->MaritalStatus;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setMaritalStatusID($MaritalStatusID) {
        $this->MaritalStatusID = $MaritalStatusID;
    }

    public function setMaritalStatus($MaritalStatus) {
        $this->MaritalStatus = $MaritalStatus;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }

}
