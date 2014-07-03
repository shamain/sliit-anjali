<?php

class Examinations_model extends CI_Model {

    var $ExaminationID;
    var $Name;
    var $ExaminationTypeID;
    var $Year;
    var $SemesterID;
    var $CourseID;
    var $InstructorID;
    var $NumberOfMCQs;
    var $NumberOfShortAnswerQuestions;
    var $Duration;
    var $StartDate;
    var $EndDate;
    var $Active;
    var $DelInd;

    function __construct() {
        parent::__construct();
    }

    public function getExaminationID() {
        return $this->ExaminationID;
    }

    public function getName() {
        return $this->Name;
    }

    public function getExaminationTypeID() {
        return $this->ExaminationTypeID;
    }

    public function getYear() {
        return $this->Year;
    }

    public function getSemesterID() {
        return $this->SemesterID;
    }

    public function getCourseID() {
        return $this->CourseID;
    }

    public function getInstructorID() {
        return $this->InstructorID;
    }

    public function getNumberOfMCQs() {
        return $this->NumberOfMCQs;
    }

    public function getNumberOfShortAnswerQuestions() {
        return $this->NumberOfShortAnswerQuestions;
    }

    public function getDuration() {
        return $this->Duration;
    }

    public function getStartDate() {
        return $this->StartDate;
    }

    public function getEndDate() {
        return $this->EndDate;
    }

    public function getActive() {
        return $this->Active;
    }

    public function getDelInd() {
        return $this->DelInd;
    }

    public function setExaminationID($ExaminationID) {
        $this->ExaminationID = $ExaminationID;
    }

    public function setName($Name) {
        $this->Name = $Name;
    }

    public function setExaminationTypeID($ExaminationTypeID) {
        $this->ExaminationTypeID = $ExaminationTypeID;
    }

    public function setYear($Year) {
        $this->Year = $Year;
    }

    public function setSemesterID($SemesterID) {
        $this->SemesterID = $SemesterID;
    }

    public function setCourseID($CourseID) {
        $this->CourseID = $CourseID;
    }

    public function setInstructorID($InstructorID) {
        $this->InstructorID = $InstructorID;
    }

    public function setNumberOfMCQs($NumberOfMCQs) {
        $this->NumberOfMCQs = $NumberOfMCQs;
    }

    public function setNumberOfShortAnswerQuestions($NumberOfShortAnswerQuestions) {
        $this->NumberOfShortAnswerQuestions = $NumberOfShortAnswerQuestions;
    }

    public function setDuration($Duration) {
        $this->Duration = $Duration;
    }

    public function setStartDate($StartDate) {
        $this->StartDate = $StartDate;
    }

    public function setEndDate($EndDate) {
        $this->EndDate = $EndDate;
    }

    public function setActive($Active) {
        $this->Active = $Active;
    }

    public function setDelInd($DelInd) {
        $this->DelInd = $DelInd;
    }

}
