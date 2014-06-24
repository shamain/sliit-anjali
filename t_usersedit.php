<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "t_usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$t_users_edit = NULL; // Initialize page object first

class ct_users_edit extends ct_users {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_users';

	// Page object name
	var $PageObjName = 't_users_edit';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_users)
		if (!isset($GLOBALS["t_users"]) || get_class($GLOBALS["t_users"]) == "ct_users") {
			$GLOBALS["t_users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_users"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_users', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("t_userslist.php");
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->_UserID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["_UserID"] <> "") {
			$this->_UserID->setQueryStringValue($_GET["_UserID"]);
			$this->RecKey["_UserID"] = $this->_UserID->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("t_userslist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->_UserID->CurrentValue) == strval($this->Recordset->fields('UserID'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("t_userslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_usersview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
		$this->PhotoPath->Upload->Index = $objForm->Index;
		if ($this->PhotoPath->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->PhotoPath->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->PhotoPath->CurrentValue = $this->PhotoPath->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->_UserID->FldIsDetailKey)
			$this->_UserID->setFormValue($objForm->GetValue("x__UserID"));
		if (!$this->Username->FldIsDetailKey) {
			$this->Username->setFormValue($objForm->GetValue("x_Username"));
		}
		if (!$this->Password->FldIsDetailKey) {
			$this->Password->setFormValue($objForm->GetValue("x_Password"));
		}
		if (!$this->UserLevel->FldIsDetailKey) {
			$this->UserLevel->setFormValue($objForm->GetValue("x_UserLevel"));
		}
		if (!$this->Activated->FldIsDetailKey) {
			$this->Activated->setFormValue($objForm->GetValue("x_Activated"));
		}
		if (!$this->DesignationID->FldIsDetailKey) {
			$this->DesignationID->setFormValue($objForm->GetValue("x_DesignationID"));
		}
		if (!$this->FirstName->FldIsDetailKey) {
			$this->FirstName->setFormValue($objForm->GetValue("x_FirstName"));
		}
		if (!$this->MiddleName->FldIsDetailKey) {
			$this->MiddleName->setFormValue($objForm->GetValue("x_MiddleName"));
		}
		if (!$this->LastName->FldIsDetailKey) {
			$this->LastName->setFormValue($objForm->GetValue("x_LastName"));
		}
		if (!$this->_Email->FldIsDetailKey) {
			$this->_Email->setFormValue($objForm->GetValue("x__Email"));
		}
		if (!$this->RegistrationNumber->FldIsDetailKey) {
			$this->RegistrationNumber->setFormValue($objForm->GetValue("x_RegistrationNumber"));
		}
		if (!$this->NICNumber->FldIsDetailKey) {
			$this->NICNumber->setFormValue($objForm->GetValue("x_NICNumber"));
		}
		if (!$this->Gender->FldIsDetailKey) {
			$this->Gender->setFormValue($objForm->GetValue("x_Gender"));
		}
		if (!$this->MaritalStatusID->FldIsDetailKey) {
			$this->MaritalStatusID->setFormValue($objForm->GetValue("x_MaritalStatusID"));
		}
		if (!$this->DateOfBirth->FldIsDetailKey) {
			$this->DateOfBirth->setFormValue($objForm->GetValue("x_DateOfBirth"));
			$this->DateOfBirth->CurrentValue = ew_UnFormatDateTime($this->DateOfBirth->CurrentValue, 5);
		}
		if (!$this->RegisteredOn->FldIsDetailKey) {
			$this->RegisteredOn->setFormValue($objForm->GetValue("x_RegisteredOn"));
			$this->RegisteredOn->CurrentValue = ew_UnFormatDateTime($this->RegisteredOn->CurrentValue, 5);
		}
		if (!$this->RegistrationValidTill->FldIsDetailKey) {
			$this->RegistrationValidTill->setFormValue($objForm->GetValue("x_RegistrationValidTill"));
			$this->RegistrationValidTill->CurrentValue = ew_UnFormatDateTime($this->RegistrationValidTill->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->_UserID->CurrentValue = $this->_UserID->FormValue;
		$this->Username->CurrentValue = $this->Username->FormValue;
		$this->Password->CurrentValue = $this->Password->FormValue;
		$this->UserLevel->CurrentValue = $this->UserLevel->FormValue;
		$this->Activated->CurrentValue = $this->Activated->FormValue;
		$this->DesignationID->CurrentValue = $this->DesignationID->FormValue;
		$this->FirstName->CurrentValue = $this->FirstName->FormValue;
		$this->MiddleName->CurrentValue = $this->MiddleName->FormValue;
		$this->LastName->CurrentValue = $this->LastName->FormValue;
		$this->_Email->CurrentValue = $this->_Email->FormValue;
		$this->RegistrationNumber->CurrentValue = $this->RegistrationNumber->FormValue;
		$this->NICNumber->CurrentValue = $this->NICNumber->FormValue;
		$this->Gender->CurrentValue = $this->Gender->FormValue;
		$this->MaritalStatusID->CurrentValue = $this->MaritalStatusID->FormValue;
		$this->DateOfBirth->CurrentValue = $this->DateOfBirth->FormValue;
		$this->DateOfBirth->CurrentValue = ew_UnFormatDateTime($this->DateOfBirth->CurrentValue, 5);
		$this->RegisteredOn->CurrentValue = $this->RegisteredOn->FormValue;
		$this->RegisteredOn->CurrentValue = ew_UnFormatDateTime($this->RegisteredOn->CurrentValue, 5);
		$this->RegistrationValidTill->CurrentValue = $this->RegistrationValidTill->FormValue;
		$this->RegistrationValidTill->CurrentValue = ew_UnFormatDateTime($this->RegistrationValidTill->CurrentValue, 5);
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->_UserID->setDbValue($rs->fields('UserID'));
		$this->Username->setDbValue($rs->fields('Username'));
		$this->Password->setDbValue($rs->fields('Password'));
		$this->UserLevel->setDbValue($rs->fields('UserLevel'));
		$this->Activated->setDbValue($rs->fields('Activated'));
		$this->DesignationID->setDbValue($rs->fields('DesignationID'));
		if (array_key_exists('EV__DesignationID', $rs->fields)) {
			$this->DesignationID->VirtualValue = $rs->fields('EV__DesignationID'); // Set up virtual field value
		} else {
			$this->DesignationID->VirtualValue = ""; // Clear value
		}
		$this->FirstName->setDbValue($rs->fields('FirstName'));
		$this->MiddleName->setDbValue($rs->fields('MiddleName'));
		$this->LastName->setDbValue($rs->fields('LastName'));
		$this->_Email->setDbValue($rs->fields('Email'));
		$this->RegistrationNumber->setDbValue($rs->fields('RegistrationNumber'));
		$this->NICNumber->setDbValue($rs->fields('NICNumber'));
		$this->Gender->setDbValue($rs->fields('Gender'));
		$this->MaritalStatusID->setDbValue($rs->fields('MaritalStatusID'));
		if (array_key_exists('EV__MaritalStatusID', $rs->fields)) {
			$this->MaritalStatusID->VirtualValue = $rs->fields('EV__MaritalStatusID'); // Set up virtual field value
		} else {
			$this->MaritalStatusID->VirtualValue = ""; // Clear value
		}
		$this->DateOfBirth->setDbValue($rs->fields('DateOfBirth'));
		$this->RegisteredOn->setDbValue($rs->fields('RegisteredOn'));
		$this->RegistrationValidTill->setDbValue($rs->fields('RegistrationValidTill'));
		$this->PhotoPath->Upload->DbValue = $rs->fields('PhotoPath');
		$this->PhotoPath->CurrentValue = $this->PhotoPath->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->_UserID->DbValue = $row['UserID'];
		$this->Username->DbValue = $row['Username'];
		$this->Password->DbValue = $row['Password'];
		$this->UserLevel->DbValue = $row['UserLevel'];
		$this->Activated->DbValue = $row['Activated'];
		$this->DesignationID->DbValue = $row['DesignationID'];
		$this->FirstName->DbValue = $row['FirstName'];
		$this->MiddleName->DbValue = $row['MiddleName'];
		$this->LastName->DbValue = $row['LastName'];
		$this->_Email->DbValue = $row['Email'];
		$this->RegistrationNumber->DbValue = $row['RegistrationNumber'];
		$this->NICNumber->DbValue = $row['NICNumber'];
		$this->Gender->DbValue = $row['Gender'];
		$this->MaritalStatusID->DbValue = $row['MaritalStatusID'];
		$this->DateOfBirth->DbValue = $row['DateOfBirth'];
		$this->RegisteredOn->DbValue = $row['RegisteredOn'];
		$this->RegistrationValidTill->DbValue = $row['RegistrationValidTill'];
		$this->PhotoPath->Upload->DbValue = $row['PhotoPath'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// UserID
		// Username
		// Password
		// UserLevel
		// Activated
		// DesignationID
		// FirstName
		// MiddleName
		// LastName
		// Email
		// RegistrationNumber
		// NICNumber
		// Gender
		// MaritalStatusID
		// DateOfBirth
		// RegisteredOn
		// RegistrationValidTill
		// PhotoPath

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// UserID
			$this->_UserID->ViewValue = $this->_UserID->CurrentValue;
			$this->_UserID->ViewCustomAttributes = "";

			// Username
			$this->Username->ViewValue = $this->Username->CurrentValue;
			$this->Username->ViewCustomAttributes = "";

			// Password
			$this->Password->ViewValue = "********";
			$this->Password->ViewCustomAttributes = "";

			// UserLevel
			if ($Security->CanAdmin()) { // System admin
			if (strval($this->UserLevel->CurrentValue) <> "") {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->UserLevel->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->UserLevel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->UserLevel->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->UserLevel->ViewValue = $this->UserLevel->CurrentValue;
				}
			} else {
				$this->UserLevel->ViewValue = NULL;
			}
			} else {
				$this->UserLevel->ViewValue = "********";
			}
			$this->UserLevel->ViewCustomAttributes = "";

			// Activated
			$this->Activated->ViewValue = $this->Activated->CurrentValue;
			$this->Activated->ViewCustomAttributes = "";

			// DesignationID
			if ($this->DesignationID->VirtualValue <> "") {
				$this->DesignationID->ViewValue = $this->DesignationID->VirtualValue;
			} else {
			if (strval($this->DesignationID->CurrentValue) <> "") {
				$sFilterWrk = "`DesignationID`" . ew_SearchString("=", $this->DesignationID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `DesignationID`, `Designation` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_designations`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->DesignationID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Designation`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->DesignationID->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->DesignationID->ViewValue = $this->DesignationID->CurrentValue;
				}
			} else {
				$this->DesignationID->ViewValue = NULL;
			}
			}
			$this->DesignationID->ViewCustomAttributes = "";

			// FirstName
			$this->FirstName->ViewValue = $this->FirstName->CurrentValue;
			$this->FirstName->ViewCustomAttributes = "";

			// MiddleName
			$this->MiddleName->ViewValue = $this->MiddleName->CurrentValue;
			$this->MiddleName->ViewCustomAttributes = "";

			// LastName
			$this->LastName->ViewValue = $this->LastName->CurrentValue;
			$this->LastName->ViewCustomAttributes = "";

			// Email
			$this->_Email->ViewValue = $this->_Email->CurrentValue;
			$this->_Email->ViewCustomAttributes = "";

			// RegistrationNumber
			$this->RegistrationNumber->ViewValue = $this->RegistrationNumber->CurrentValue;
			$this->RegistrationNumber->ViewCustomAttributes = "";

			// NICNumber
			$this->NICNumber->ViewValue = $this->NICNumber->CurrentValue;
			$this->NICNumber->ViewCustomAttributes = "";

			// Gender
			if (strval($this->Gender->CurrentValue) <> "") {
				switch ($this->Gender->CurrentValue) {
					case $this->Gender->FldTagValue(1):
						$this->Gender->ViewValue = $this->Gender->FldTagCaption(1) <> "" ? $this->Gender->FldTagCaption(1) : $this->Gender->CurrentValue;
						break;
					case $this->Gender->FldTagValue(2):
						$this->Gender->ViewValue = $this->Gender->FldTagCaption(2) <> "" ? $this->Gender->FldTagCaption(2) : $this->Gender->CurrentValue;
						break;
					default:
						$this->Gender->ViewValue = $this->Gender->CurrentValue;
				}
			} else {
				$this->Gender->ViewValue = NULL;
			}
			$this->Gender->ViewCustomAttributes = "";

			// MaritalStatusID
			if ($this->MaritalStatusID->VirtualValue <> "") {
				$this->MaritalStatusID->ViewValue = $this->MaritalStatusID->VirtualValue;
			} else {
			if (strval($this->MaritalStatusID->CurrentValue) <> "") {
				$sFilterWrk = "`MaritalStatusID`" . ew_SearchString("=", $this->MaritalStatusID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `MaritalStatusID`, `MaritalStatus` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_marital_statuses`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->MaritalStatusID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `MaritalStatus`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->MaritalStatusID->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->MaritalStatusID->ViewValue = $this->MaritalStatusID->CurrentValue;
				}
			} else {
				$this->MaritalStatusID->ViewValue = NULL;
			}
			}
			$this->MaritalStatusID->ViewCustomAttributes = "";

			// DateOfBirth
			$this->DateOfBirth->ViewValue = $this->DateOfBirth->CurrentValue;
			$this->DateOfBirth->ViewValue = ew_FormatDateTime($this->DateOfBirth->ViewValue, 5);
			$this->DateOfBirth->ViewCustomAttributes = "";

			// RegisteredOn
			$this->RegisteredOn->ViewValue = $this->RegisteredOn->CurrentValue;
			$this->RegisteredOn->ViewValue = ew_FormatDateTime($this->RegisteredOn->ViewValue, 5);
			$this->RegisteredOn->ViewCustomAttributes = "";

			// RegistrationValidTill
			$this->RegistrationValidTill->ViewValue = $this->RegistrationValidTill->CurrentValue;
			$this->RegistrationValidTill->ViewValue = ew_FormatDateTime($this->RegistrationValidTill->ViewValue, 5);
			$this->RegistrationValidTill->ViewCustomAttributes = "";

			// PhotoPath
			$this->PhotoPath->UploadPath = '\photos';
			if (!ew_Empty($this->PhotoPath->Upload->DbValue)) {
				$this->PhotoPath->ImageAlt = $this->PhotoPath->FldAlt();
				$this->PhotoPath->ViewValue = ew_UploadPathEx(FALSE, $this->PhotoPath->UploadPath) . $this->PhotoPath->Upload->DbValue;
			} else {
				$this->PhotoPath->ViewValue = "";
			}
			$this->PhotoPath->ViewCustomAttributes = "";

			// UserID
			$this->_UserID->LinkCustomAttributes = "";
			$this->_UserID->HrefValue = "";
			$this->_UserID->TooltipValue = "";

			// Username
			$this->Username->LinkCustomAttributes = "";
			$this->Username->HrefValue = "";
			$this->Username->TooltipValue = "";

			// Password
			$this->Password->LinkCustomAttributes = "";
			$this->Password->HrefValue = "";
			$this->Password->TooltipValue = "";

			// UserLevel
			$this->UserLevel->LinkCustomAttributes = "";
			$this->UserLevel->HrefValue = "";
			$this->UserLevel->TooltipValue = "";

			// Activated
			$this->Activated->LinkCustomAttributes = "";
			$this->Activated->HrefValue = "";
			$this->Activated->TooltipValue = "";

			// DesignationID
			$this->DesignationID->LinkCustomAttributes = "";
			$this->DesignationID->HrefValue = "";
			$this->DesignationID->TooltipValue = "";

			// FirstName
			$this->FirstName->LinkCustomAttributes = "";
			$this->FirstName->HrefValue = "";
			$this->FirstName->TooltipValue = "";

			// MiddleName
			$this->MiddleName->LinkCustomAttributes = "";
			$this->MiddleName->HrefValue = "";
			$this->MiddleName->TooltipValue = "";

			// LastName
			$this->LastName->LinkCustomAttributes = "";
			$this->LastName->HrefValue = "";
			$this->LastName->TooltipValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";
			$this->_Email->TooltipValue = "";

			// RegistrationNumber
			$this->RegistrationNumber->LinkCustomAttributes = "";
			$this->RegistrationNumber->HrefValue = "";
			$this->RegistrationNumber->TooltipValue = "";

			// NICNumber
			$this->NICNumber->LinkCustomAttributes = "";
			$this->NICNumber->HrefValue = "";
			$this->NICNumber->TooltipValue = "";

			// Gender
			$this->Gender->LinkCustomAttributes = "";
			$this->Gender->HrefValue = "";
			$this->Gender->TooltipValue = "";

			// MaritalStatusID
			$this->MaritalStatusID->LinkCustomAttributes = "";
			$this->MaritalStatusID->HrefValue = "";
			$this->MaritalStatusID->TooltipValue = "";

			// DateOfBirth
			$this->DateOfBirth->LinkCustomAttributes = "";
			$this->DateOfBirth->HrefValue = "";
			$this->DateOfBirth->TooltipValue = "";

			// RegisteredOn
			$this->RegisteredOn->LinkCustomAttributes = "";
			$this->RegisteredOn->HrefValue = "";
			$this->RegisteredOn->TooltipValue = "";

			// RegistrationValidTill
			$this->RegistrationValidTill->LinkCustomAttributes = "";
			$this->RegistrationValidTill->HrefValue = "";
			$this->RegistrationValidTill->TooltipValue = "";

			// PhotoPath
			$this->PhotoPath->LinkCustomAttributes = "";
			$this->PhotoPath->HrefValue = "";
			$this->PhotoPath->HrefValue2 = $this->PhotoPath->UploadPath . $this->PhotoPath->Upload->DbValue;
			$this->PhotoPath->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// UserID
			$this->_UserID->EditCustomAttributes = "";
			$this->_UserID->EditValue = $this->_UserID->CurrentValue;
			$this->_UserID->ViewCustomAttributes = "";

			// Username
			$this->Username->EditCustomAttributes = "";
			$this->Username->EditValue = ew_HtmlEncode($this->Username->CurrentValue);
			$this->Username->PlaceHolder = ew_RemoveHtml($this->Username->FldCaption());

			// Password
			$this->Password->EditCustomAttributes = "";
			$this->Password->EditValue = ew_HtmlEncode($this->Password->CurrentValue);

			// UserLevel
			$this->UserLevel->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->UserLevel->EditValue = "********";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->UserLevel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->UserLevel->EditValue = $arwrk;
			}

			// Activated
			$this->Activated->EditCustomAttributes = "";
			$this->Activated->EditValue = ew_HtmlEncode($this->Activated->CurrentValue);
			$this->Activated->PlaceHolder = ew_RemoveHtml($this->Activated->FldCaption());

			// DesignationID
			$this->DesignationID->EditCustomAttributes = "";
			if (trim(strval($this->DesignationID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`DesignationID`" . ew_SearchString("=", $this->DesignationID->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `DesignationID`, `Designation` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_designations`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->DesignationID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Designation`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->DesignationID->EditValue = $arwrk;

			// FirstName
			$this->FirstName->EditCustomAttributes = "";
			$this->FirstName->EditValue = ew_HtmlEncode($this->FirstName->CurrentValue);
			$this->FirstName->PlaceHolder = ew_RemoveHtml($this->FirstName->FldCaption());

			// MiddleName
			$this->MiddleName->EditCustomAttributes = "";
			$this->MiddleName->EditValue = ew_HtmlEncode($this->MiddleName->CurrentValue);
			$this->MiddleName->PlaceHolder = ew_RemoveHtml($this->MiddleName->FldCaption());

			// LastName
			$this->LastName->EditCustomAttributes = "";
			$this->LastName->EditValue = ew_HtmlEncode($this->LastName->CurrentValue);
			$this->LastName->PlaceHolder = ew_RemoveHtml($this->LastName->FldCaption());

			// Email
			$this->_Email->EditCustomAttributes = "";
			$this->_Email->EditValue = ew_HtmlEncode($this->_Email->CurrentValue);
			$this->_Email->PlaceHolder = ew_RemoveHtml($this->_Email->FldCaption());

			// RegistrationNumber
			$this->RegistrationNumber->EditCustomAttributes = "";
			$this->RegistrationNumber->EditValue = ew_HtmlEncode($this->RegistrationNumber->CurrentValue);
			$this->RegistrationNumber->PlaceHolder = ew_RemoveHtml($this->RegistrationNumber->FldCaption());

			// NICNumber
			$this->NICNumber->EditCustomAttributes = "";
			$this->NICNumber->EditValue = ew_HtmlEncode($this->NICNumber->CurrentValue);
			$this->NICNumber->PlaceHolder = ew_RemoveHtml($this->NICNumber->FldCaption());

			// Gender
			$this->Gender->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Gender->FldTagValue(1), $this->Gender->FldTagCaption(1) <> "" ? $this->Gender->FldTagCaption(1) : $this->Gender->FldTagValue(1));
			$arwrk[] = array($this->Gender->FldTagValue(2), $this->Gender->FldTagCaption(2) <> "" ? $this->Gender->FldTagCaption(2) : $this->Gender->FldTagValue(2));
			$this->Gender->EditValue = $arwrk;

			// MaritalStatusID
			$this->MaritalStatusID->EditCustomAttributes = "";
			if (trim(strval($this->MaritalStatusID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`MaritalStatusID`" . ew_SearchString("=", $this->MaritalStatusID->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `MaritalStatusID`, `MaritalStatus` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_marital_statuses`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->MaritalStatusID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `MaritalStatus`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->MaritalStatusID->EditValue = $arwrk;

			// DateOfBirth
			$this->DateOfBirth->EditCustomAttributes = "";
			$this->DateOfBirth->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DateOfBirth->CurrentValue, 5));
			$this->DateOfBirth->PlaceHolder = ew_RemoveHtml($this->DateOfBirth->FldCaption());

			// RegisteredOn
			$this->RegisteredOn->EditCustomAttributes = "";
			$this->RegisteredOn->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->RegisteredOn->CurrentValue, 5));
			$this->RegisteredOn->PlaceHolder = ew_RemoveHtml($this->RegisteredOn->FldCaption());

			// RegistrationValidTill
			$this->RegistrationValidTill->EditCustomAttributes = "";
			$this->RegistrationValidTill->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->RegistrationValidTill->CurrentValue, 5));
			$this->RegistrationValidTill->PlaceHolder = ew_RemoveHtml($this->RegistrationValidTill->FldCaption());

			// PhotoPath
			$this->PhotoPath->EditCustomAttributes = "";
			$this->PhotoPath->UploadPath = '\photos';
			if (!ew_Empty($this->PhotoPath->Upload->DbValue)) {
				$this->PhotoPath->ImageAlt = $this->PhotoPath->FldAlt();
				$this->PhotoPath->EditValue = ew_UploadPathEx(FALSE, $this->PhotoPath->UploadPath) . $this->PhotoPath->Upload->DbValue;
			} else {
				$this->PhotoPath->EditValue = "";
			}
			if (!ew_Empty($this->PhotoPath->CurrentValue))
				$this->PhotoPath->Upload->FileName = $this->PhotoPath->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->PhotoPath);

			// Edit refer script
			// UserID

			$this->_UserID->HrefValue = "";

			// Username
			$this->Username->HrefValue = "";

			// Password
			$this->Password->HrefValue = "";

			// UserLevel
			$this->UserLevel->HrefValue = "";

			// Activated
			$this->Activated->HrefValue = "";

			// DesignationID
			$this->DesignationID->HrefValue = "";

			// FirstName
			$this->FirstName->HrefValue = "";

			// MiddleName
			$this->MiddleName->HrefValue = "";

			// LastName
			$this->LastName->HrefValue = "";

			// Email
			$this->_Email->HrefValue = "";

			// RegistrationNumber
			$this->RegistrationNumber->HrefValue = "";

			// NICNumber
			$this->NICNumber->HrefValue = "";

			// Gender
			$this->Gender->HrefValue = "";

			// MaritalStatusID
			$this->MaritalStatusID->HrefValue = "";

			// DateOfBirth
			$this->DateOfBirth->HrefValue = "";

			// RegisteredOn
			$this->RegisteredOn->HrefValue = "";

			// RegistrationValidTill
			$this->RegistrationValidTill->HrefValue = "";

			// PhotoPath
			$this->PhotoPath->HrefValue = "";
			$this->PhotoPath->HrefValue2 = $this->PhotoPath->UploadPath . $this->PhotoPath->Upload->DbValue;
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->Activated->FormValue)) {
			ew_AddMessage($gsFormError, $this->Activated->FldErrMsg());
		}
		if (!$this->DesignationID->FldIsDetailKey && !is_null($this->DesignationID->FormValue) && $this->DesignationID->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->DesignationID->FldCaption());
		}
		if ($this->Gender->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Gender->FldCaption());
		}
		if (!$this->DateOfBirth->FldIsDetailKey && !is_null($this->DateOfBirth->FormValue) && $this->DateOfBirth->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->DateOfBirth->FldCaption());
		}
		if (!ew_CheckDate($this->DateOfBirth->FormValue)) {
			ew_AddMessage($gsFormError, $this->DateOfBirth->FldErrMsg());
		}
		if (!ew_CheckDate($this->RegisteredOn->FormValue)) {
			ew_AddMessage($gsFormError, $this->RegisteredOn->FldErrMsg());
		}
		if (!ew_CheckDate($this->RegistrationValidTill->FormValue)) {
			ew_AddMessage($gsFormError, $this->RegistrationValidTill->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		if ($this->RegistrationNumber->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`RegistrationNumber` = '" . ew_AdjustSql($this->RegistrationNumber->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->RegistrationNumber->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->RegistrationNumber->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$this->PhotoPath->OldUploadPath = '\photos';
			$this->PhotoPath->UploadPath = $this->PhotoPath->OldUploadPath;
			$rsnew = array();

			// Username
			$this->Username->SetDbValueDef($rsnew, $this->Username->CurrentValue, NULL, $this->Username->ReadOnly);

			// Password
			$this->Password->SetDbValueDef($rsnew, $this->Password->CurrentValue, NULL, $this->Password->ReadOnly || (EW_ENCRYPTED_PASSWORD && $rs->fields('Password') == $this->Password->CurrentValue));

			// UserLevel
			if ($Security->CanAdmin()) { // System admin
			$this->UserLevel->SetDbValueDef($rsnew, $this->UserLevel->CurrentValue, NULL, $this->UserLevel->ReadOnly);
			}

			// Activated
			$this->Activated->SetDbValueDef($rsnew, $this->Activated->CurrentValue, NULL, $this->Activated->ReadOnly);

			// DesignationID
			$this->DesignationID->SetDbValueDef($rsnew, $this->DesignationID->CurrentValue, NULL, $this->DesignationID->ReadOnly);

			// FirstName
			$this->FirstName->SetDbValueDef($rsnew, $this->FirstName->CurrentValue, NULL, $this->FirstName->ReadOnly);

			// MiddleName
			$this->MiddleName->SetDbValueDef($rsnew, $this->MiddleName->CurrentValue, NULL, $this->MiddleName->ReadOnly);

			// LastName
			$this->LastName->SetDbValueDef($rsnew, $this->LastName->CurrentValue, NULL, $this->LastName->ReadOnly);

			// Email
			$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, NULL, $this->_Email->ReadOnly);

			// RegistrationNumber
			$this->RegistrationNumber->SetDbValueDef($rsnew, $this->RegistrationNumber->CurrentValue, NULL, $this->RegistrationNumber->ReadOnly);

			// NICNumber
			$this->NICNumber->SetDbValueDef($rsnew, $this->NICNumber->CurrentValue, NULL, $this->NICNumber->ReadOnly);

			// Gender
			$this->Gender->SetDbValueDef($rsnew, $this->Gender->CurrentValue, NULL, $this->Gender->ReadOnly);

			// MaritalStatusID
			$this->MaritalStatusID->SetDbValueDef($rsnew, $this->MaritalStatusID->CurrentValue, NULL, $this->MaritalStatusID->ReadOnly);

			// DateOfBirth
			$this->DateOfBirth->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DateOfBirth->CurrentValue, 5), NULL, $this->DateOfBirth->ReadOnly);

			// RegisteredOn
			$this->RegisteredOn->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->RegisteredOn->CurrentValue, 5), NULL, $this->RegisteredOn->ReadOnly);

			// RegistrationValidTill
			$this->RegistrationValidTill->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->RegistrationValidTill->CurrentValue, 5), NULL, $this->RegistrationValidTill->ReadOnly);

			// PhotoPath
			if (!($this->PhotoPath->ReadOnly) && !$this->PhotoPath->Upload->KeepFile) {
				$this->PhotoPath->Upload->DbValue = $rs->fields('PhotoPath'); // Get original value
				if ($this->PhotoPath->Upload->FileName == "") {
					$rsnew['PhotoPath'] = NULL;
				} else {
					$rsnew['PhotoPath'] = $this->PhotoPath->Upload->FileName;
				}
			}
			if (!$this->PhotoPath->Upload->KeepFile) {
				$this->PhotoPath->UploadPath = '\photos';
				if (!ew_Empty($this->PhotoPath->Upload->Value)) {
					$rsnew['PhotoPath'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->PhotoPath->UploadPath), $rsnew['PhotoPath']); // Get new file name
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if (!$this->PhotoPath->Upload->KeepFile) {
						if (!ew_Empty($this->PhotoPath->Upload->Value)) {
							$this->PhotoPath->Upload->SaveToFile($this->PhotoPath->UploadPath, $rsnew['PhotoPath'], TRUE);
						}
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// PhotoPath
		ew_CleanUploadTempPath($this->PhotoPath, $this->PhotoPath->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "t_userslist.php", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, ew_CurrentUrl());
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_users_edit)) $t_users_edit = new ct_users_edit();

// Page init
$t_users_edit->Page_Init();

// Page main
$t_users_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_users_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var t_users_edit = new ew_Page("t_users_edit");
t_users_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = t_users_edit.PageID; // For backward compatibility

// Form object
var ft_usersedit = new ew_Form("ft_usersedit");

// Validate form
ft_usersedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_Activated");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_users->Activated->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_DesignationID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_users->DesignationID->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Gender");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_users->Gender->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_DateOfBirth");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_users->DateOfBirth->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_DateOfBirth");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_users->DateOfBirth->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_RegisteredOn");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_users->RegisteredOn->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_RegistrationValidTill");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_users->RegistrationValidTill->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_usersedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_usersedit.ValidateRequired = true;
<?php } else { ?>
ft_usersedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_usersedit.Lists["x_UserLevel"] = {"LinkField":"x_userlevelid","Ajax":null,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_usersedit.Lists["x_DesignationID"] = {"LinkField":"x_DesignationID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Designation","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_usersedit.Lists["x_MaritalStatusID"] = {"LinkField":"x_MaritalStatusID","Ajax":true,"AutoFill":false,"DisplayFields":["x_MaritalStatus","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $t_users_edit->ShowPageHeader(); ?>
<?php
$t_users_edit->ShowMessage();
?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_users_edit->Pager)) $t_users_edit->Pager = new cPrevNextPager($t_users_edit->StartRec, $t_users_edit->DisplayRecs, $t_users_edit->TotalRecs) ?>
<?php if ($t_users_edit->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_users_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_edit->PageUrl() ?>start=<?php echo $t_users_edit->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_users_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_edit->PageUrl() ?>start=<?php echo $t_users_edit->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_users_edit->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_users_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_edit->PageUrl() ?>start=<?php echo $t_users_edit->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_users_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_edit->PageUrl() ?>start=<?php echo $t_users_edit->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_users_edit->Pager->PageCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
<?php } ?>
</td>
</tr></table>
</form>
<form name="ft_usersedit" id="ft_usersedit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="t_users">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_t_usersedit" class="table table-bordered table-striped">
<?php if ($t_users->_UserID->Visible) { // UserID ?>
	<tr id="r__UserID">
		<td><span id="elh_t_users__UserID"><?php echo $t_users->_UserID->FldCaption() ?></span></td>
		<td<?php echo $t_users->_UserID->CellAttributes() ?>>
<span id="el_t_users__UserID" class="control-group">
<span<?php echo $t_users->_UserID->ViewAttributes() ?>>
<?php echo $t_users->_UserID->EditValue ?></span>
</span>
<input type="hidden" data-field="x__UserID" name="x__UserID" id="x__UserID" value="<?php echo ew_HtmlEncode($t_users->_UserID->CurrentValue) ?>">
<?php echo $t_users->_UserID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->Username->Visible) { // Username ?>
	<tr id="r_Username">
		<td><span id="elh_t_users_Username"><?php echo $t_users->Username->FldCaption() ?></span></td>
		<td<?php echo $t_users->Username->CellAttributes() ?>>
<span id="el_t_users_Username" class="control-group">
<input type="text" data-field="x_Username" name="x_Username" id="x_Username" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($t_users->Username->PlaceHolder) ?>" value="<?php echo $t_users->Username->EditValue ?>"<?php echo $t_users->Username->EditAttributes() ?>>
</span>
<?php echo $t_users->Username->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->Password->Visible) { // Password ?>
	<tr id="r_Password">
		<td><span id="elh_t_users_Password"><?php echo $t_users->Password->FldCaption() ?></span></td>
		<td<?php echo $t_users->Password->CellAttributes() ?>>
<span id="el_t_users_Password" class="control-group">
<input type="password" data-field="x_Password" name="x_Password" id="x_Password" value="<?php echo $t_users->Password->EditValue ?>" size="30" maxlength="50"<?php echo $t_users->Password->EditAttributes() ?>>
</span>
<?php echo $t_users->Password->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->UserLevel->Visible) { // UserLevel ?>
	<tr id="r_UserLevel">
		<td><span id="elh_t_users_UserLevel"><?php echo $t_users->UserLevel->FldCaption() ?></span></td>
		<td<?php echo $t_users->UserLevel->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_t_users_UserLevel" class="control-group">
<?php echo $t_users->UserLevel->EditValue ?>
</span>
<?php } else { ?>
<span id="el_t_users_UserLevel" class="control-group">
<select data-field="x_UserLevel" id="x_UserLevel" name="x_UserLevel"<?php echo $t_users->UserLevel->EditAttributes() ?>>
<?php
if (is_array($t_users->UserLevel->EditValue)) {
	$arwrk = $t_users->UserLevel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_users->UserLevel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
ft_usersedit.Lists["x_UserLevel"].Options = <?php echo (is_array($t_users->UserLevel->EditValue)) ? ew_ArrayToJson($t_users->UserLevel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php echo $t_users->UserLevel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->Activated->Visible) { // Activated ?>
	<tr id="r_Activated">
		<td><span id="elh_t_users_Activated"><?php echo $t_users->Activated->FldCaption() ?></span></td>
		<td<?php echo $t_users->Activated->CellAttributes() ?>>
<span id="el_t_users_Activated" class="control-group">
<input type="text" data-field="x_Activated" name="x_Activated" id="x_Activated" size="30" placeholder="<?php echo ew_HtmlEncode($t_users->Activated->PlaceHolder) ?>" value="<?php echo $t_users->Activated->EditValue ?>"<?php echo $t_users->Activated->EditAttributes() ?>>
</span>
<?php echo $t_users->Activated->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->DesignationID->Visible) { // DesignationID ?>
	<tr id="r_DesignationID">
		<td><span id="elh_t_users_DesignationID"><?php echo $t_users->DesignationID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_users->DesignationID->CellAttributes() ?>>
<span id="el_t_users_DesignationID" class="control-group">
<select data-field="x_DesignationID" id="x_DesignationID" name="x_DesignationID"<?php echo $t_users->DesignationID->EditAttributes() ?>>
<?php
if (is_array($t_users->DesignationID->EditValue)) {
	$arwrk = $t_users->DesignationID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_users->DesignationID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "t_designations")) { ?>
&nbsp;<a id="aol_x_DesignationID" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_DesignationID',url:'t_designationsaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_users->DesignationID->FldCaption() ?></a>
<?php } ?>
<?php
$sSqlWrk = "SELECT `DesignationID`, `Designation` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_designations`";
$sWhereWrk = "";

// Call Lookup selecting
$t_users->Lookup_Selecting($t_users->DesignationID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Designation`";
?>
<input type="hidden" name="s_x_DesignationID" id="s_x_DesignationID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`DesignationID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $t_users->DesignationID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->FirstName->Visible) { // FirstName ?>
	<tr id="r_FirstName">
		<td><span id="elh_t_users_FirstName"><?php echo $t_users->FirstName->FldCaption() ?></span></td>
		<td<?php echo $t_users->FirstName->CellAttributes() ?>>
<span id="el_t_users_FirstName" class="control-group">
<input type="text" data-field="x_FirstName" name="x_FirstName" id="x_FirstName" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_users->FirstName->PlaceHolder) ?>" value="<?php echo $t_users->FirstName->EditValue ?>"<?php echo $t_users->FirstName->EditAttributes() ?>>
</span>
<?php echo $t_users->FirstName->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->MiddleName->Visible) { // MiddleName ?>
	<tr id="r_MiddleName">
		<td><span id="elh_t_users_MiddleName"><?php echo $t_users->MiddleName->FldCaption() ?></span></td>
		<td<?php echo $t_users->MiddleName->CellAttributes() ?>>
<span id="el_t_users_MiddleName" class="control-group">
<input type="text" data-field="x_MiddleName" name="x_MiddleName" id="x_MiddleName" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_users->MiddleName->PlaceHolder) ?>" value="<?php echo $t_users->MiddleName->EditValue ?>"<?php echo $t_users->MiddleName->EditAttributes() ?>>
</span>
<?php echo $t_users->MiddleName->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->LastName->Visible) { // LastName ?>
	<tr id="r_LastName">
		<td><span id="elh_t_users_LastName"><?php echo $t_users->LastName->FldCaption() ?></span></td>
		<td<?php echo $t_users->LastName->CellAttributes() ?>>
<span id="el_t_users_LastName" class="control-group">
<input type="text" data-field="x_LastName" name="x_LastName" id="x_LastName" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_users->LastName->PlaceHolder) ?>" value="<?php echo $t_users->LastName->EditValue ?>"<?php echo $t_users->LastName->EditAttributes() ?>>
</span>
<?php echo $t_users->LastName->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->_Email->Visible) { // Email ?>
	<tr id="r__Email">
		<td><span id="elh_t_users__Email"><?php echo $t_users->_Email->FldCaption() ?></span></td>
		<td<?php echo $t_users->_Email->CellAttributes() ?>>
<span id="el_t_users__Email" class="control-group">
<input type="text" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="75" placeholder="<?php echo ew_HtmlEncode($t_users->_Email->PlaceHolder) ?>" value="<?php echo $t_users->_Email->EditValue ?>"<?php echo $t_users->_Email->EditAttributes() ?>>
</span>
<?php echo $t_users->_Email->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->RegistrationNumber->Visible) { // RegistrationNumber ?>
	<tr id="r_RegistrationNumber">
		<td><span id="elh_t_users_RegistrationNumber"><?php echo $t_users->RegistrationNumber->FldCaption() ?></span></td>
		<td<?php echo $t_users->RegistrationNumber->CellAttributes() ?>>
<span id="el_t_users_RegistrationNumber" class="control-group">
<input type="text" data-field="x_RegistrationNumber" name="x_RegistrationNumber" id="x_RegistrationNumber" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_users->RegistrationNumber->PlaceHolder) ?>" value="<?php echo $t_users->RegistrationNumber->EditValue ?>"<?php echo $t_users->RegistrationNumber->EditAttributes() ?>>
</span>
<?php echo $t_users->RegistrationNumber->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->NICNumber->Visible) { // NICNumber ?>
	<tr id="r_NICNumber">
		<td><span id="elh_t_users_NICNumber"><?php echo $t_users->NICNumber->FldCaption() ?></span></td>
		<td<?php echo $t_users->NICNumber->CellAttributes() ?>>
<span id="el_t_users_NICNumber" class="control-group">
<input type="text" data-field="x_NICNumber" name="x_NICNumber" id="x_NICNumber" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($t_users->NICNumber->PlaceHolder) ?>" value="<?php echo $t_users->NICNumber->EditValue ?>"<?php echo $t_users->NICNumber->EditAttributes() ?>>
</span>
<?php echo $t_users->NICNumber->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->Gender->Visible) { // Gender ?>
	<tr id="r_Gender">
		<td><span id="elh_t_users_Gender"><?php echo $t_users->Gender->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_users->Gender->CellAttributes() ?>>
<span id="el_t_users_Gender" class="control-group">
<div id="tp_x_Gender" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Gender" id="x_Gender" value="{value}"<?php echo $t_users->Gender->EditAttributes() ?>></div>
<div id="dsl_x_Gender" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $t_users->Gender->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_users->Gender->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_Gender" name="x_Gender" id="x_Gender_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $t_users->Gender->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $t_users->Gender->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->MaritalStatusID->Visible) { // MaritalStatusID ?>
	<tr id="r_MaritalStatusID">
		<td><span id="elh_t_users_MaritalStatusID"><?php echo $t_users->MaritalStatusID->FldCaption() ?></span></td>
		<td<?php echo $t_users->MaritalStatusID->CellAttributes() ?>>
<span id="el_t_users_MaritalStatusID" class="control-group">
<select data-field="x_MaritalStatusID" id="x_MaritalStatusID" name="x_MaritalStatusID"<?php echo $t_users->MaritalStatusID->EditAttributes() ?>>
<?php
if (is_array($t_users->MaritalStatusID->EditValue)) {
	$arwrk = $t_users->MaritalStatusID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_users->MaritalStatusID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "t_marital_statuses")) { ?>
&nbsp;<a id="aol_x_MaritalStatusID" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_MaritalStatusID',url:'t_marital_statusesaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_users->MaritalStatusID->FldCaption() ?></a>
<?php } ?>
<?php
$sSqlWrk = "SELECT `MaritalStatusID`, `MaritalStatus` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_marital_statuses`";
$sWhereWrk = "";

// Call Lookup selecting
$t_users->Lookup_Selecting($t_users->MaritalStatusID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `MaritalStatus`";
?>
<input type="hidden" name="s_x_MaritalStatusID" id="s_x_MaritalStatusID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`MaritalStatusID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $t_users->MaritalStatusID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->DateOfBirth->Visible) { // DateOfBirth ?>
	<tr id="r_DateOfBirth">
		<td><span id="elh_t_users_DateOfBirth"><?php echo $t_users->DateOfBirth->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_users->DateOfBirth->CellAttributes() ?>>
<span id="el_t_users_DateOfBirth" class="control-group">
<input type="text" data-field="x_DateOfBirth" name="x_DateOfBirth" id="x_DateOfBirth" placeholder="<?php echo ew_HtmlEncode($t_users->DateOfBirth->PlaceHolder) ?>" value="<?php echo $t_users->DateOfBirth->EditValue ?>"<?php echo $t_users->DateOfBirth->EditAttributes() ?>>
<?php if (!$t_users->DateOfBirth->ReadOnly && !$t_users->DateOfBirth->Disabled && @$t_users->DateOfBirth->EditAttrs["readonly"] == "" && @$t_users->DateOfBirth->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_DateOfBirth" name="cal_x_DateOfBirth" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ft_usersedit", "x_DateOfBirth", "%Y/%m/%d");
</script>
<?php } ?>
</span>
<?php echo $t_users->DateOfBirth->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->RegisteredOn->Visible) { // RegisteredOn ?>
	<tr id="r_RegisteredOn">
		<td><span id="elh_t_users_RegisteredOn"><?php echo $t_users->RegisteredOn->FldCaption() ?></span></td>
		<td<?php echo $t_users->RegisteredOn->CellAttributes() ?>>
<span id="el_t_users_RegisteredOn" class="control-group">
<input type="text" data-field="x_RegisteredOn" name="x_RegisteredOn" id="x_RegisteredOn" placeholder="<?php echo ew_HtmlEncode($t_users->RegisteredOn->PlaceHolder) ?>" value="<?php echo $t_users->RegisteredOn->EditValue ?>"<?php echo $t_users->RegisteredOn->EditAttributes() ?>>
<?php if (!$t_users->RegisteredOn->ReadOnly && !$t_users->RegisteredOn->Disabled && @$t_users->RegisteredOn->EditAttrs["readonly"] == "" && @$t_users->RegisteredOn->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_RegisteredOn" name="cal_x_RegisteredOn" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ft_usersedit", "x_RegisteredOn", "%Y/%m/%d");
</script>
<?php } ?>
</span>
<?php echo $t_users->RegisteredOn->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->RegistrationValidTill->Visible) { // RegistrationValidTill ?>
	<tr id="r_RegistrationValidTill">
		<td><span id="elh_t_users_RegistrationValidTill"><?php echo $t_users->RegistrationValidTill->FldCaption() ?></span></td>
		<td<?php echo $t_users->RegistrationValidTill->CellAttributes() ?>>
<span id="el_t_users_RegistrationValidTill" class="control-group">
<input type="text" data-field="x_RegistrationValidTill" name="x_RegistrationValidTill" id="x_RegistrationValidTill" placeholder="<?php echo ew_HtmlEncode($t_users->RegistrationValidTill->PlaceHolder) ?>" value="<?php echo $t_users->RegistrationValidTill->EditValue ?>"<?php echo $t_users->RegistrationValidTill->EditAttributes() ?>>
<?php if (!$t_users->RegistrationValidTill->ReadOnly && !$t_users->RegistrationValidTill->Disabled && @$t_users->RegistrationValidTill->EditAttrs["readonly"] == "" && @$t_users->RegistrationValidTill->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_RegistrationValidTill" name="cal_x_RegistrationValidTill" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ft_usersedit", "x_RegistrationValidTill", "%Y/%m/%d");
</script>
<?php } ?>
</span>
<?php echo $t_users->RegistrationValidTill->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_users->PhotoPath->Visible) { // PhotoPath ?>
	<tr id="r_PhotoPath">
		<td><span id="elh_t_users_PhotoPath"><?php echo $t_users->PhotoPath->FldCaption() ?></span></td>
		<td<?php echo $t_users->PhotoPath->CellAttributes() ?>>
<span id="el_t_users_PhotoPath" class="control-group">
<span id="fd_x_PhotoPath">
<span class="btn btn-small fileinput-button"<?php if ($t_users->PhotoPath->ReadOnly || $t_users->PhotoPath->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_PhotoPath" name="x_PhotoPath" id="x_PhotoPath">
</span>
<input type="hidden" name="fn_x_PhotoPath" id= "fn_x_PhotoPath" value="<?php echo $t_users->PhotoPath->Upload->FileName ?>">
<?php if (@$_POST["fa_x_PhotoPath"] == "0") { ?>
<input type="hidden" name="fa_x_PhotoPath" id= "fa_x_PhotoPath" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_PhotoPath" id= "fa_x_PhotoPath" value="1">
<?php } ?>
<input type="hidden" name="fs_x_PhotoPath" id= "fs_x_PhotoPath" value="100">
</span>
<table id="ft_x_PhotoPath" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $t_users->PhotoPath->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<table class="ewPager">
<tr><td>
<?php if (!isset($t_users_edit->Pager)) $t_users_edit->Pager = new cPrevNextPager($t_users_edit->StartRec, $t_users_edit->DisplayRecs, $t_users_edit->TotalRecs) ?>
<?php if ($t_users_edit->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_users_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_edit->PageUrl() ?>start=<?php echo $t_users_edit->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_users_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_edit->PageUrl() ?>start=<?php echo $t_users_edit->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_users_edit->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_users_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_edit->PageUrl() ?>start=<?php echo $t_users_edit->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_users_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_edit->PageUrl() ?>start=<?php echo $t_users_edit->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_users_edit->Pager->PageCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
<?php } ?>
</td>
</tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
ft_usersedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$t_users_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_users_edit->Page_Terminate();
?>
