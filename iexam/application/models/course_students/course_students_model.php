<?php

class Course_Students_model extends CI_Model{
    
    var $CourseStudentID;
    var $CourseID;
    var $StudentID;
    
    function __construct() {
        parent::_conctruc();
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

    public function setCourseStudentID($CourseStudentID) {
        $this->CourseStudentID = $CourseStudentID;
    }

    public function setCourseID($CourseID) {
        $this->CourseID = $CourseID;
    }

    public function setStudentID($StudentID) {
        $this->StudentID = $StudentID;
    }


}


