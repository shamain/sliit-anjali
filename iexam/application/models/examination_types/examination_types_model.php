<?php

class Examination_types_model extends CI_Model {

    var $ExaminationTypeID;
    var $ExaminationType;
    var $DelInd;

    function __construct() {
        parent::__construct();
    }

    public function getExaminationTypeID() {
        return $this->ExaminationTypeID;
    }

    public function setExaminationTypeID($ExaminationTypeID) {
        $this->ExaminationTypeID = $ExaminationTypeID;
    }

    public function getExaminationType() {
        return $this->ExaminationType;
    }

    public function setExaminationType($ExaminationType) {
        $this->ExaminationType = $ExaminationType;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }

}
