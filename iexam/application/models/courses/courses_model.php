<?php

class Courses_model extends CI_Model{
    
    var $CourseID;
    var $SubjectID;
    var $Course;
    var $CourseCode;
    
    function __construct() {
        parent::_construct();
    }
    
    public function getCourseID() {
        return $this->CourseID;
    }

    public function getSubjectID() {
        return $this->SubjectID;
    }

    public function getCourse() {
        return $this->Course;
    }

    public function getCourseCode() {
        return $this->CourseCode;
    }

    public function setCourseID($CourseID) {
        $this->CourseID = $CourseID;
    }

    public function setSubjectID($SubjectID) {
        $this->SubjectID = $SubjectID;
    }

    public function setCourse($Course) {
        $this->Course = $Course;
    }

    public function setCourseCode($CourseCode) {
        $this->CourseCode = $CourseCode;
    }


}