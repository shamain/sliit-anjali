<?php

class Course_Students_model extends CI_Model {

    var $CourseStudentID;
    var $CourseID;
    var $StudentID;
    var $DelInd;

    function __construct() {
        parent::__construct();
    }

    public function getCourseStudentID() {
        return $this->CourseStudentID;
    }

    public function getCourseID() {
        return $this->CourseID;
    }

    public function getStudentID() {
        return $this->StudentID;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setCourseStudentID($CourseStudentID) {
        $this->CourseStudentID = $CourseStudentID;
    }

    public function setCourseID($CourseID) {
        $this->CourseID = $CourseID;
    }

    public function setStudentID($StudentID) {
        $this->StudentID = $StudentID;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }

}
