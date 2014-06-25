<?php

class Examination_types extends CI_Model {

    var $ExaminationTypeID;
    var $ExaminaionType;
    var $DelInd;

    function __construct() {
        parent::_construct();
    }

    public function getExaminationTypeID() {
        return $this->ExaminationTypeID;
    }

    public function getExaminaionType() {
        return $this->ExaminaionType;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setExaminationTypeID($ExaminationTypeID) {
        $this->ExaminationTypeID = $ExaminationTypeID;
    }

    public function setExaminaionType($ExaminaionType) {
        $this->ExaminaionType = $ExaminaionType;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }

}
