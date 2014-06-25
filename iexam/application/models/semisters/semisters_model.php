<?php

class Semisters extends CI_Model {

    var $SemesterID;
    var $Semester;
    var $DelInd;

    function __construct() {
        parent::_construct();
    }

    public function getSemesterID() {
        return $this->SemesterID;
    }

    public function getSemester() {
        return $this->Semester;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setSemesterID($SemesterID) {
        $this->SemesterID = $SemesterID;
    }

    public function setSemester($Semester) {
        $this->Semester = $Semester;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }

}
