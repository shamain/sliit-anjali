<?php

class Course_instructor_model extends CI_Model{
    
    var $CourseInstructor;
    var $CourseID;
    var $Year;
    var $SemesterID;
    var $InstructorID;
    
    function __construct() {
        parent::__construct();
    }
    
    public function getCcourseInstructor(){
        return $this->CourseInstructor;
    }
    
    public function setCourseInstructor($CourseInstructor){
        $this->CourseInstructor=$CourseInstructor;
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


}

