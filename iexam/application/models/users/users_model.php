<?php

class Users_model extends CI_Model{
    
    var $UserID;
    var $Username;
    var $Password;
    var $UserLevel;
    var $Activated;
    var $DesignationID;
    var $FirstName;
    var $MiddleName;
    var $LastName;
    var $Email;
    var $RegistrationNumber;
    var $NICNumber;
    var $Gender;
    var $MaritalStatusID;
    var $DateOfBirth;
    var $RegisteredOn;
    var $RegistrationValidTill;
    var $PhotoPath;
    
    function __construct() {
        parent::_consruct();
    }
    
    public function getUserID() {
        return $this->UserID;
    }

    public function getUsername() {
        return $this->Username;
    }

    public function getPassword() {
        return $this->Password;
    }

    public function getUserLevel() {
        return $this->UserLevel;
    }

    public function getActivated() {
        return $this->Activated;
    }

    public function getDesignationID() {
        return $this->DesignationID;
    }

    public function getFirstName() {
        return $this->FirstName;
    }

    public function getMiddleName() {
        return $this->MiddleName;
    }

    public function getLastName() {
        return $this->LastName;
    }

    public function getEmail() {
        return $this->Email;
    }

    public function getRegistrationNumber() {
        return $this->RegistrationNumber;
    }

    public function getNICNumber() {
        return $this->NICNumber;
    }

    public function getGender() {
        return $this->Gender;
    }

    public function getMaritalStatusID() {
        return $this->MaritalStatusID;
    }

    public function getDateOfBirth() {
        return $this->DateOfBirth;
    }

    public function getRegisteredOn() {
        return $this->RegisteredOn;
    }

    public function getRegistrationValidTill() {
        return $this->RegistrationValidTill;
    }

    public function getPhotoPath() {
        return $this->PhotoPath;
    }

    public function setUserID($UserID) {
        $this->UserID = $UserID;
    }

    public function setUsername($Username) {
        $this->Username = $Username;
    }

    public function setPassword($Password) {
        $this->Password = $Password;
    }

    public function setUserLevel($UserLevel) {
        $this->UserLevel = $UserLevel;
    }

    public function setActivated($Activated) {
        $this->Activated = $Activated;
    }

    public function setDesignationID($DesignationID) {
        $this->DesignationID = $DesignationID;
    }

    public function setFirstName($FirstName) {
        $this->FirstName = $FirstName;
    }

    public function setMiddleName($MiddleName) {
        $this->MiddleName = $MiddleName;
    }

    public function setLastName($LastName) {
        $this->LastName = $LastName;
    }

    public function setEmail($Email) {
        $this->Email = $Email;
    }

    public function setRegistrationNumber($RegistrationNumber) {
        $this->RegistrationNumber = $RegistrationNumber;
    }

    public function setNICNumber($NICNumber) {
        $this->NICNumber = $NICNumber;
    }

    public function setGender($Gender) {
        $this->Gender = $Gender;
    }

    public function setMaritalStatusID($MaritalStatusID) {
        $this->MaritalStatusID = $MaritalStatusID;
    }

    public function setDateOfBirth($DateOfBirth) {
        $this->DateOfBirth = $DateOfBirth;
    }

    public function setRegisteredOn($RegisteredOn) {
        $this->RegisteredOn = $RegisteredOn;
    }

    public function setRegistrationValidTill($RegistrationValidTill) {
        $this->RegistrationValidTill = $RegistrationValidTill;
    }

    public function setPhotoPath($PhotoPath) {
        $this->PhotoPath = $PhotoPath;
    }


}
