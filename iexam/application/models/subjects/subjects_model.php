<?php

class Subjects extends CI_Model {

    var $SubjectID;
    var $Subject;
    var $DelInd;

    function __construct() {
        parent::_construct();
    }

    public function getSubjectID() {
        return $this->SubjectID;
    }

    public function getSubject() {
        return $this->Subject;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setSubjectID($SubjectID) {
        $this->SubjectID = $SubjectID;
    }

    public function setSubject($Subject) {
        $this->Subject = $Subject;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }

}
