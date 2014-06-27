<?php

class Course_instructor_model extends CI_Model {

    var $CourseInstructorID;
    var $CourseID;
    var $Year;
    var $SemesterID;
    var $InstructorID;
    var $DelInd;

    function __construct() {
        parent::__construct();
    }

    public function getCourseInstructorID() {
        return $this->CourseInstructorID;
    }

    public function getCourseID() {
        return $this->CourseID;
    }

    public function getYear() {
        return $this->Year;
    }

    public function getSemesterID() {
        return $this->SemesterID;
    }

    public function getInstructorID() {
        return $this->InstructorID;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setCourseInstructorID($CourseInstructorID) {
        $this->CourseInstructorID = $CourseInstructorID;
    }

    public function setCourseID($CourseID) {
        $this->CourseID = $CourseID;
    }

    public function setYear($Year) {
        $this->Year = $Year;
    }

    public function setSemesterID($SemesterID) {
        $this->SemesterID = $SemesterID;
    }

    public function setInstructorID($InstructorID) {
        $this->InstructorID = $InstructorID;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }


   
}
