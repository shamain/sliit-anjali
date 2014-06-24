<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "t_examinationsinfo.php" ?>
<?php include_once "t_usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$t_examinations_add = NULL; // Initialize page object first

class ct_examinations_add extends ct_examinations {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_examinations';

	// Page object name
	var $PageObjName = 't_examinations_add';

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

		// Table object (t_examinations)
		if (!isset($GLOBALS["t_examinations"]) || get_class($GLOBALS["t_examinations"]) == "ct_examinations") {
			$GLOBALS["t_examinations"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_examinations"];
		}

		// Table object (t_users)
		if (!isset($GLOBALS['t_users'])) $GLOBALS['t_users'] = new ct_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_examinations', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("t_examinationslist.php");
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["ExaminationID"] != "") {
				$this->ExaminationID->setQueryStringValue($_GET["ExaminationID"]);
				$this->setKey("ExaminationID", $this->ExaminationID->CurrentValue); // Set up key
			} else {
				$this->setKey("ExaminationID", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("t_examinationslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_examinationsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Name->CurrentValue = NULL;
		$this->Name->OldValue = $this->Name->CurrentValue;
		$this->ExaminationTypeID->CurrentValue = NULL;
		$this->ExaminationTypeID->OldValue = $this->ExaminationTypeID->CurrentValue;
		$this->Year->CurrentValue = NULL;
		$this->Year->OldValue = $this->Year->CurrentValue;
		$this->SeminsterID->CurrentValue = NULL;
		$this->SeminsterID->OldValue = $this->SeminsterID->CurrentValue;
		$this->CourseID->CurrentValue = NULL;
		$this->CourseID->OldValue = $this->CourseID->CurrentValue;
		$this->InsttructorID->CurrentValue = NULL;
		$this->InsttructorID->OldValue = $this->InsttructorID->CurrentValue;
		$this->NumberOfMCQs->CurrentValue = NULL;
		$this->NumberOfMCQs->OldValue = $this->NumberOfMCQs->CurrentValue;
		$this->NumberOfShortAnswerQuestions->CurrentValue = NULL;
		$this->NumberOfShortAnswerQuestions->OldValue = $this->NumberOfShortAnswerQuestions->CurrentValue;
		$this->Duration->CurrentValue = NULL;
		$this->Duration->OldValue = $this->Duration->CurrentValue;
		$this->Active->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Name->FldIsDetailKey) {
			$this->Name->setFormValue($objForm->GetValue("x_Name"));
		}
		if (!$this->ExaminationTypeID->FldIsDetailKey) {
			$this->ExaminationTypeID->setFormValue($objForm->GetValue("x_ExaminationTypeID"));
		}
		if (!$this->Year->FldIsDetailKey) {
			$this->Year->setFormValue($objForm->GetValue("x_Year"));
		}
		if (!$this->SeminsterID->FldIsDetailKey) {
			$this->SeminsterID->setFormValue($objForm->GetValue("x_SeminsterID"));
		}
		if (!$this->CourseID->FldIsDetailKey) {
			$this->CourseID->setFormValue($objForm->GetValue("x_CourseID"));
		}
		if (!$this->InsttructorID->FldIsDetailKey) {
			$this->InsttructorID->setFormValue($objForm->GetValue("x_InsttructorID"));
		}
		if (!$this->NumberOfMCQs->FldIsDetailKey) {
			$this->NumberOfMCQs->setFormValue($objForm->GetValue("x_NumberOfMCQs"));
		}
		if (!$this->NumberOfShortAnswerQuestions->FldIsDetailKey) {
			$this->NumberOfShortAnswerQuestions->setFormValue($objForm->GetValue("x_NumberOfShortAnswerQuestions"));
		}
		if (!$this->Duration->FldIsDetailKey) {
			$this->Duration->setFormValue($objForm->GetValue("x_Duration"));
		}
		if (!$this->Active->FldIsDetailKey) {
			$this->Active->setFormValue($objForm->GetValue("x_Active"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Name->CurrentValue = $this->Name->FormValue;
		$this->ExaminationTypeID->CurrentValue = $this->ExaminationTypeID->FormValue;
		$this->Year->CurrentValue = $this->Year->FormValue;
		$this->SeminsterID->CurrentValue = $this->SeminsterID->FormValue;
		$this->CourseID->CurrentValue = $this->CourseID->FormValue;
		$this->InsttructorID->CurrentValue = $this->InsttructorID->FormValue;
		$this->NumberOfMCQs->CurrentValue = $this->NumberOfMCQs->FormValue;
		$this->NumberOfShortAnswerQuestions->CurrentValue = $this->NumberOfShortAnswerQuestions->FormValue;
		$this->Duration->CurrentValue = $this->Duration->FormValue;
		$this->Active->CurrentValue = $this->Active->FormValue;
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
		$this->ExaminationID->setDbValue($rs->fields('ExaminationID'));
		$this->Name->setDbValue($rs->fields('Name'));
		$this->ExaminationTypeID->setDbValue($rs->fields('ExaminationTypeID'));
		if (array_key_exists('EV__ExaminationTypeID', $rs->fields)) {
			$this->ExaminationTypeID->VirtualValue = $rs->fields('EV__ExaminationTypeID'); // Set up virtual field value
		} else {
			$this->ExaminationTypeID->VirtualValue = ""; // Clear value
		}
		$this->Year->setDbValue($rs->fields('Year'));
		$this->SeminsterID->setDbValue($rs->fields('SeminsterID'));
		if (array_key_exists('EV__SeminsterID', $rs->fields)) {
			$this->SeminsterID->VirtualValue = $rs->fields('EV__SeminsterID'); // Set up virtual field value
		} else {
			$this->SeminsterID->VirtualValue = ""; // Clear value
		}
		$this->CourseID->setDbValue($rs->fields('CourseID'));
		if (array_key_exists('EV__CourseID', $rs->fields)) {
			$this->CourseID->VirtualValue = $rs->fields('EV__CourseID'); // Set up virtual field value
		} else {
			$this->CourseID->VirtualValue = ""; // Clear value
		}
		$this->InsttructorID->setDbValue($rs->fields('InsttructorID'));
		$this->NumberOfMCQs->setDbValue($rs->fields('NumberOfMCQs'));
		$this->NumberOfShortAnswerQuestions->setDbValue($rs->fields('NumberOfShortAnswerQuestions'));
		$this->Duration->setDbValue($rs->fields('Duration'));
		$this->Active->setDbValue($rs->fields('Active'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ExaminationID->DbValue = $row['ExaminationID'];
		$this->Name->DbValue = $row['Name'];
		$this->ExaminationTypeID->DbValue = $row['ExaminationTypeID'];
		$this->Year->DbValue = $row['Year'];
		$this->SeminsterID->DbValue = $row['SeminsterID'];
		$this->CourseID->DbValue = $row['CourseID'];
		$this->InsttructorID->DbValue = $row['InsttructorID'];
		$this->NumberOfMCQs->DbValue = $row['NumberOfMCQs'];
		$this->NumberOfShortAnswerQuestions->DbValue = $row['NumberOfShortAnswerQuestions'];
		$this->Duration->DbValue = $row['Duration'];
		$this->Active->DbValue = $row['Active'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("ExaminationID")) <> "")
			$this->ExaminationID->CurrentValue = $this->getKey("ExaminationID"); // ExaminationID
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// ExaminationID
		// Name
		// ExaminationTypeID
		// Year
		// SeminsterID
		// CourseID
		// InsttructorID
		// NumberOfMCQs
		// NumberOfShortAnswerQuestions
		// Duration
		// Active

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Name
			$this->Name->ViewValue = $this->Name->CurrentValue;
			$this->Name->ViewCustomAttributes = "";

			// ExaminationTypeID
			if ($this->ExaminationTypeID->VirtualValue <> "") {
				$this->ExaminationTypeID->ViewValue = $this->ExaminationTypeID->VirtualValue;
			} else {
			if (strval($this->ExaminationTypeID->CurrentValue) <> "") {
				$sFilterWrk = "`ExaminationTypeID`" . ew_SearchString("=", $this->ExaminationTypeID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `ExaminationTypeID`, `ExaminationType` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_examination_types`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->ExaminationTypeID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `ExaminationType`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->ExaminationTypeID->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->ExaminationTypeID->ViewValue = $this->ExaminationTypeID->CurrentValue;
				}
			} else {
				$this->ExaminationTypeID->ViewValue = NULL;
			}
			}
			$this->ExaminationTypeID->ViewCustomAttributes = "";

			// Year
			$this->Year->ViewValue = $this->Year->CurrentValue;
			$this->Year->ViewCustomAttributes = "";

			// SeminsterID
			if ($this->SeminsterID->VirtualValue <> "") {
				$this->SeminsterID->ViewValue = $this->SeminsterID->VirtualValue;
			} else {
			if (strval($this->SeminsterID->CurrentValue) <> "") {
				$sFilterWrk = "`SemisterID`" . ew_SearchString("=", $this->SeminsterID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `SemisterID`, `Semister` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_semisters`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->SeminsterID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->SeminsterID->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->SeminsterID->ViewValue = $this->SeminsterID->CurrentValue;
				}
			} else {
				$this->SeminsterID->ViewValue = NULL;
			}
			}
			$this->SeminsterID->ViewCustomAttributes = "";

			// CourseID
			if ($this->CourseID->VirtualValue <> "") {
				$this->CourseID->ViewValue = $this->CourseID->VirtualValue;
			} else {
			if (strval($this->CourseID->CurrentValue) <> "") {
				$sFilterWrk = "`CourseID`" . ew_SearchString("=", $this->CourseID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_courses`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->CourseID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `CourseCode`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->CourseID->ViewValue = $rswrk->fields('DispFld');
					$this->CourseID->ViewValue .= ew_ValueSeparator(1,$this->CourseID) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->CourseID->ViewValue = $this->CourseID->CurrentValue;
				}
			} else {
				$this->CourseID->ViewValue = NULL;
			}
			}
			$this->CourseID->ViewCustomAttributes = "";

			// InsttructorID
			if (strval($this->InsttructorID->CurrentValue) <> "") {
				$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->InsttructorID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `UserID`, `LastName` AS `DispFld`, `FirstName` AS `Disp2Fld`, `NICNumber` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_users`";
			$sWhereWrk = "";
			$lookuptblfilter = "`UserLevel` = '1'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->InsttructorID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `LastName`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->InsttructorID->ViewValue = $rswrk->fields('DispFld');
					$this->InsttructorID->ViewValue .= ew_ValueSeparator(1,$this->InsttructorID) . $rswrk->fields('Disp2Fld');
					$this->InsttructorID->ViewValue .= ew_ValueSeparator(2,$this->InsttructorID) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->InsttructorID->ViewValue = $this->InsttructorID->CurrentValue;
				}
			} else {
				$this->InsttructorID->ViewValue = NULL;
			}
			$this->InsttructorID->ViewCustomAttributes = "";

			// NumberOfMCQs
			$this->NumberOfMCQs->ViewValue = $this->NumberOfMCQs->CurrentValue;
			$this->NumberOfMCQs->ViewCustomAttributes = "";

			// NumberOfShortAnswerQuestions
			$this->NumberOfShortAnswerQuestions->ViewValue = $this->NumberOfShortAnswerQuestions->CurrentValue;
			$this->NumberOfShortAnswerQuestions->ViewCustomAttributes = "";

			// Duration
			$this->Duration->ViewValue = $this->Duration->CurrentValue;
			$this->Duration->ViewCustomAttributes = "";

			// Active
			if (strval($this->Active->CurrentValue) <> "") {
				switch ($this->Active->CurrentValue) {
					case $this->Active->FldTagValue(1):
						$this->Active->ViewValue = $this->Active->FldTagCaption(1) <> "" ? $this->Active->FldTagCaption(1) : $this->Active->CurrentValue;
						break;
					case $this->Active->FldTagValue(2):
						$this->Active->ViewValue = $this->Active->FldTagCaption(2) <> "" ? $this->Active->FldTagCaption(2) : $this->Active->CurrentValue;
						break;
					default:
						$this->Active->ViewValue = $this->Active->CurrentValue;
				}
			} else {
				$this->Active->ViewValue = NULL;
			}
			$this->Active->ViewCustomAttributes = "";

			// Name
			$this->Name->LinkCustomAttributes = "";
			$this->Name->HrefValue = "";
			$this->Name->TooltipValue = "";

			// ExaminationTypeID
			$this->ExaminationTypeID->LinkCustomAttributes = "";
			$this->ExaminationTypeID->HrefValue = "";
			$this->ExaminationTypeID->TooltipValue = "";

			// Year
			$this->Year->LinkCustomAttributes = "";
			$this->Year->HrefValue = "";
			$this->Year->TooltipValue = "";

			// SeminsterID
			$this->SeminsterID->LinkCustomAttributes = "";
			$this->SeminsterID->HrefValue = "";
			$this->SeminsterID->TooltipValue = "";

			// CourseID
			$this->CourseID->LinkCustomAttributes = "";
			$this->CourseID->HrefValue = "";
			$this->CourseID->TooltipValue = "";

			// InsttructorID
			$this->InsttructorID->LinkCustomAttributes = "";
			$this->InsttructorID->HrefValue = "";
			$this->InsttructorID->TooltipValue = "";

			// NumberOfMCQs
			$this->NumberOfMCQs->LinkCustomAttributes = "";
			$this->NumberOfMCQs->HrefValue = "";
			$this->NumberOfMCQs->TooltipValue = "";

			// NumberOfShortAnswerQuestions
			$this->NumberOfShortAnswerQuestions->LinkCustomAttributes = "";
			$this->NumberOfShortAnswerQuestions->HrefValue = "";
			$this->NumberOfShortAnswerQuestions->TooltipValue = "";

			// Duration
			$this->Duration->LinkCustomAttributes = "";
			$this->Duration->HrefValue = "";
			$this->Duration->TooltipValue = "";

			// Active
			$this->Active->LinkCustomAttributes = "";
			$this->Active->HrefValue = "";
			$this->Active->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Name
			$this->Name->EditCustomAttributes = "";
			$this->Name->EditValue = ew_HtmlEncode($this->Name->CurrentValue);
			$this->Name->PlaceHolder = ew_RemoveHtml($this->Name->FldCaption());

			// ExaminationTypeID
			$this->ExaminationTypeID->EditCustomAttributes = "";
			if (trim(strval($this->ExaminationTypeID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ExaminationTypeID`" . ew_SearchString("=", $this->ExaminationTypeID->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `ExaminationTypeID`, `ExaminationType` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_examination_types`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->ExaminationTypeID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `ExaminationType`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->ExaminationTypeID->EditValue = $arwrk;

			// Year
			$this->Year->EditCustomAttributes = "";
			$this->Year->EditValue = ew_HtmlEncode($this->Year->CurrentValue);
			$this->Year->PlaceHolder = ew_RemoveHtml($this->Year->FldCaption());

			// SeminsterID
			$this->SeminsterID->EditCustomAttributes = "";
			if (trim(strval($this->SeminsterID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`SemisterID`" . ew_SearchString("=", $this->SeminsterID->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `SemisterID`, `Semister` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_semisters`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->SeminsterID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->SeminsterID->EditValue = $arwrk;

			// CourseID
			$this->CourseID->EditCustomAttributes = "";
			if (trim(strval($this->CourseID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`CourseID`" . ew_SearchString("=", $this->CourseID->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_courses`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->CourseID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `CourseCode`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->CourseID->EditValue = $arwrk;

			// InsttructorID
			$this->InsttructorID->EditCustomAttributes = "";
			if (trim(strval($this->InsttructorID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->InsttructorID->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `UserID`, `LastName` AS `DispFld`, `FirstName` AS `Disp2Fld`, `NICNumber` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_users`";
			$sWhereWrk = "";
			$lookuptblfilter = "`UserLevel` = '1'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->InsttructorID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `LastName`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->InsttructorID->EditValue = $arwrk;

			// NumberOfMCQs
			$this->NumberOfMCQs->EditCustomAttributes = "";
			$this->NumberOfMCQs->EditValue = ew_HtmlEncode($this->NumberOfMCQs->CurrentValue);
			$this->NumberOfMCQs->PlaceHolder = ew_RemoveHtml($this->NumberOfMCQs->FldCaption());

			// NumberOfShortAnswerQuestions
			$this->NumberOfShortAnswerQuestions->EditCustomAttributes = "";
			$this->NumberOfShortAnswerQuestions->EditValue = ew_HtmlEncode($this->NumberOfShortAnswerQuestions->CurrentValue);
			$this->NumberOfShortAnswerQuestions->PlaceHolder = ew_RemoveHtml($this->NumberOfShortAnswerQuestions->FldCaption());

			// Duration
			$this->Duration->EditCustomAttributes = "";
			$this->Duration->EditValue = ew_HtmlEncode($this->Duration->CurrentValue);
			$this->Duration->PlaceHolder = ew_RemoveHtml($this->Duration->FldCaption());

			// Active
			$this->Active->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Active->FldTagValue(1), $this->Active->FldTagCaption(1) <> "" ? $this->Active->FldTagCaption(1) : $this->Active->FldTagValue(1));
			$arwrk[] = array($this->Active->FldTagValue(2), $this->Active->FldTagCaption(2) <> "" ? $this->Active->FldTagCaption(2) : $this->Active->FldTagValue(2));
			$this->Active->EditValue = $arwrk;

			// Edit refer script
			// Name

			$this->Name->HrefValue = "";

			// ExaminationTypeID
			$this->ExaminationTypeID->HrefValue = "";

			// Year
			$this->Year->HrefValue = "";

			// SeminsterID
			$this->SeminsterID->HrefValue = "";

			// CourseID
			$this->CourseID->HrefValue = "";

			// InsttructorID
			$this->InsttructorID->HrefValue = "";

			// NumberOfMCQs
			$this->NumberOfMCQs->HrefValue = "";

			// NumberOfShortAnswerQuestions
			$this->NumberOfShortAnswerQuestions->HrefValue = "";

			// Duration
			$this->Duration->HrefValue = "";

			// Active
			$this->Active->HrefValue = "";
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
		if (!$this->Name->FldIsDetailKey && !is_null($this->Name->FormValue) && $this->Name->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Name->FldCaption());
		}
		if (!$this->ExaminationTypeID->FldIsDetailKey && !is_null($this->ExaminationTypeID->FormValue) && $this->ExaminationTypeID->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ExaminationTypeID->FldCaption());
		}
		if (!$this->Year->FldIsDetailKey && !is_null($this->Year->FormValue) && $this->Year->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Year->FldCaption());
		}
		if (!ew_CheckInteger($this->Year->FormValue)) {
			ew_AddMessage($gsFormError, $this->Year->FldErrMsg());
		}
		if (!$this->SeminsterID->FldIsDetailKey && !is_null($this->SeminsterID->FormValue) && $this->SeminsterID->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->SeminsterID->FldCaption());
		}
		if (!$this->CourseID->FldIsDetailKey && !is_null($this->CourseID->FormValue) && $this->CourseID->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->CourseID->FldCaption());
		}
		if (!$this->InsttructorID->FldIsDetailKey && !is_null($this->InsttructorID->FormValue) && $this->InsttructorID->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->InsttructorID->FldCaption());
		}
		if (!$this->NumberOfMCQs->FldIsDetailKey && !is_null($this->NumberOfMCQs->FormValue) && $this->NumberOfMCQs->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->NumberOfMCQs->FldCaption());
		}
		if (!ew_CheckInteger($this->NumberOfMCQs->FormValue)) {
			ew_AddMessage($gsFormError, $this->NumberOfMCQs->FldErrMsg());
		}
		if (!$this->NumberOfShortAnswerQuestions->FldIsDetailKey && !is_null($this->NumberOfShortAnswerQuestions->FormValue) && $this->NumberOfShortAnswerQuestions->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->NumberOfShortAnswerQuestions->FldCaption());
		}
		if (!ew_CheckInteger($this->NumberOfShortAnswerQuestions->FormValue)) {
			ew_AddMessage($gsFormError, $this->NumberOfShortAnswerQuestions->FldErrMsg());
		}
		if (!$this->Duration->FldIsDetailKey && !is_null($this->Duration->FormValue) && $this->Duration->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Duration->FldCaption());
		}
		if (!ew_CheckInteger($this->Duration->FormValue)) {
			ew_AddMessage($gsFormError, $this->Duration->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		if ($this->Name->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(Name = '" . ew_AdjustSql($this->Name->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->Name->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->Name->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// Name
		$this->Name->SetDbValueDef($rsnew, $this->Name->CurrentValue, NULL, FALSE);

		// ExaminationTypeID
		$this->ExaminationTypeID->SetDbValueDef($rsnew, $this->ExaminationTypeID->CurrentValue, NULL, FALSE);

		// Year
		$this->Year->SetDbValueDef($rsnew, $this->Year->CurrentValue, NULL, FALSE);

		// SeminsterID
		$this->SeminsterID->SetDbValueDef($rsnew, $this->SeminsterID->CurrentValue, NULL, FALSE);

		// CourseID
		$this->CourseID->SetDbValueDef($rsnew, $this->CourseID->CurrentValue, NULL, FALSE);

		// InsttructorID
		$this->InsttructorID->SetDbValueDef($rsnew, $this->InsttructorID->CurrentValue, NULL, FALSE);

		// NumberOfMCQs
		$this->NumberOfMCQs->SetDbValueDef($rsnew, $this->NumberOfMCQs->CurrentValue, NULL, FALSE);

		// NumberOfShortAnswerQuestions
		$this->NumberOfShortAnswerQuestions->SetDbValueDef($rsnew, $this->NumberOfShortAnswerQuestions->CurrentValue, NULL, FALSE);

		// Duration
		$this->Duration->SetDbValueDef($rsnew, $this->Duration->CurrentValue, NULL, FALSE);

		// Active
		$this->Active->SetDbValueDef($rsnew, $this->Active->CurrentValue, NULL, strval($this->Active->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->ExaminationID->setDbValue($conn->Insert_ID());
			$rsnew['ExaminationID'] = $this->ExaminationID->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "t_examinationslist.php", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
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
if (!isset($t_examinations_add)) $t_examinations_add = new ct_examinations_add();

// Page init
$t_examinations_add->Page_Init();

// Page main
$t_examinations_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_examinations_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var t_examinations_add = new ew_Page("t_examinations_add");
t_examinations_add.PageID = "add"; // Page ID
var EW_PAGE_ID = t_examinations_add.PageID; // For backward compatibility

// Form object
var ft_examinationsadd = new ew_Form("ft_examinationsadd");

// Validate form
ft_examinationsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Name");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_examinations->Name->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ExaminationTypeID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_examinations->ExaminationTypeID->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Year");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_examinations->Year->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Year");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_examinations->Year->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_SeminsterID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_examinations->SeminsterID->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_CourseID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_examinations->CourseID->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_InsttructorID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_examinations->InsttructorID->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_NumberOfMCQs");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_examinations->NumberOfMCQs->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_NumberOfMCQs");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_examinations->NumberOfMCQs->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_NumberOfShortAnswerQuestions");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_examinations->NumberOfShortAnswerQuestions->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_NumberOfShortAnswerQuestions");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_examinations->NumberOfShortAnswerQuestions->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Duration");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_examinations->Duration->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Duration");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_examinations->Duration->FldErrMsg()) ?>");

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
ft_examinationsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_examinationsadd.ValidateRequired = true;
<?php } else { ?>
ft_examinationsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_examinationsadd.Lists["x_ExaminationTypeID"] = {"LinkField":"x_ExaminationTypeID","Ajax":true,"AutoFill":false,"DisplayFields":["x_ExaminationType","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationsadd.Lists["x_SeminsterID"] = {"LinkField":"x_SemisterID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Semister","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationsadd.Lists["x_CourseID"] = {"LinkField":"x_CourseID","Ajax":true,"AutoFill":false,"DisplayFields":["x_CourseCode","x_Course","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationsadd.Lists["x_InsttructorID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_LastName","x_FirstName","x_NICNumber",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $t_examinations_add->ShowPageHeader(); ?>
<?php
$t_examinations_add->ShowMessage();
?>
<form name="ft_examinationsadd" id="ft_examinationsadd" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="t_examinations">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_t_examinationsadd" class="table table-bordered table-striped">
<?php if ($t_examinations->Name->Visible) { // Name ?>
	<tr id="r_Name">
		<td><span id="elh_t_examinations_Name"><?php echo $t_examinations->Name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_examinations->Name->CellAttributes() ?>>
<span id="el_t_examinations_Name" class="control-group">
<input type="text" data-field="x_Name" name="x_Name" id="x_Name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($t_examinations->Name->PlaceHolder) ?>" value="<?php echo $t_examinations->Name->EditValue ?>"<?php echo $t_examinations->Name->EditAttributes() ?>>
</span>
<?php echo $t_examinations->Name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_examinations->ExaminationTypeID->Visible) { // ExaminationTypeID ?>
	<tr id="r_ExaminationTypeID">
		<td><span id="elh_t_examinations_ExaminationTypeID"><?php echo $t_examinations->ExaminationTypeID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_examinations->ExaminationTypeID->CellAttributes() ?>>
<span id="el_t_examinations_ExaminationTypeID" class="control-group">
<select data-field="x_ExaminationTypeID" id="x_ExaminationTypeID" name="x_ExaminationTypeID"<?php echo $t_examinations->ExaminationTypeID->EditAttributes() ?>>
<?php
if (is_array($t_examinations->ExaminationTypeID->EditValue)) {
	$arwrk = $t_examinations->ExaminationTypeID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_examinations->ExaminationTypeID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "t_examination_types")) { ?>
&nbsp;<a id="aol_x_ExaminationTypeID" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_ExaminationTypeID',url:'t_examination_typesaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_examinations->ExaminationTypeID->FldCaption() ?></a>
<?php } ?>
<?php
$sSqlWrk = "SELECT `ExaminationTypeID`, `ExaminationType` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_examination_types`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->ExaminationTypeID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `ExaminationType`";
?>
<input type="hidden" name="s_x_ExaminationTypeID" id="s_x_ExaminationTypeID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`ExaminationTypeID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $t_examinations->ExaminationTypeID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_examinations->Year->Visible) { // Year ?>
	<tr id="r_Year">
		<td><span id="elh_t_examinations_Year"><?php echo $t_examinations->Year->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_examinations->Year->CellAttributes() ?>>
<span id="el_t_examinations_Year" class="control-group">
<input type="text" data-field="x_Year" name="x_Year" id="x_Year" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Year->PlaceHolder) ?>" value="<?php echo $t_examinations->Year->EditValue ?>"<?php echo $t_examinations->Year->EditAttributes() ?>>
</span>
<?php echo $t_examinations->Year->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_examinations->SeminsterID->Visible) { // SeminsterID ?>
	<tr id="r_SeminsterID">
		<td><span id="elh_t_examinations_SeminsterID"><?php echo $t_examinations->SeminsterID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_examinations->SeminsterID->CellAttributes() ?>>
<span id="el_t_examinations_SeminsterID" class="control-group">
<select data-field="x_SeminsterID" id="x_SeminsterID" name="x_SeminsterID"<?php echo $t_examinations->SeminsterID->EditAttributes() ?>>
<?php
if (is_array($t_examinations->SeminsterID->EditValue)) {
	$arwrk = $t_examinations->SeminsterID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_examinations->SeminsterID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "t_semisters")) { ?>
&nbsp;<a id="aol_x_SeminsterID" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_SeminsterID',url:'t_semistersaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_examinations->SeminsterID->FldCaption() ?></a>
<?php } ?>
<?php
$sSqlWrk = "SELECT `SemisterID`, `Semister` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_semisters`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->SeminsterID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_SeminsterID" id="s_x_SeminsterID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SemisterID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $t_examinations->SeminsterID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_examinations->CourseID->Visible) { // CourseID ?>
	<tr id="r_CourseID">
		<td><span id="elh_t_examinations_CourseID"><?php echo $t_examinations->CourseID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_examinations->CourseID->CellAttributes() ?>>
<span id="el_t_examinations_CourseID" class="control-group">
<select data-field="x_CourseID" id="x_CourseID" name="x_CourseID"<?php echo $t_examinations->CourseID->EditAttributes() ?>>
<?php
if (is_array($t_examinations->CourseID->EditValue)) {
	$arwrk = $t_examinations->CourseID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_examinations->CourseID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$t_examinations->CourseID) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "t_courses")) { ?>
&nbsp;<a id="aol_x_CourseID" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_CourseID',url:'t_coursesaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_examinations->CourseID->FldCaption() ?></a>
<?php } ?>
<?php
$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_courses`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->CourseID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `CourseCode`";
?>
<input type="hidden" name="s_x_CourseID" id="s_x_CourseID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`CourseID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $t_examinations->CourseID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_examinations->InsttructorID->Visible) { // InsttructorID ?>
	<tr id="r_InsttructorID">
		<td><span id="elh_t_examinations_InsttructorID"><?php echo $t_examinations->InsttructorID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_examinations->InsttructorID->CellAttributes() ?>>
<span id="el_t_examinations_InsttructorID" class="control-group">
<select data-field="x_InsttructorID" id="x_InsttructorID" name="x_InsttructorID"<?php echo $t_examinations->InsttructorID->EditAttributes() ?>>
<?php
if (is_array($t_examinations->InsttructorID->EditValue)) {
	$arwrk = $t_examinations->InsttructorID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_examinations->InsttructorID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$t_examinations->InsttructorID) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$t_examinations->InsttructorID) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "t_users")) { ?>
&nbsp;<a id="aol_x_InsttructorID" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_InsttructorID',url:'t_usersaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_examinations->InsttructorID->FldCaption() ?></a>
<?php } ?>
<?php
$sSqlWrk = "SELECT `UserID`, `LastName` AS `DispFld`, `FirstName` AS `Disp2Fld`, `NICNumber` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_users`";
$sWhereWrk = "";
$lookuptblfilter = "`UserLevel` = '1'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->InsttructorID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `LastName`";
?>
<input type="hidden" name="s_x_InsttructorID" id="s_x_InsttructorID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`UserID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $t_examinations->InsttructorID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_examinations->NumberOfMCQs->Visible) { // NumberOfMCQs ?>
	<tr id="r_NumberOfMCQs">
		<td><span id="elh_t_examinations_NumberOfMCQs"><?php echo $t_examinations->NumberOfMCQs->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_examinations->NumberOfMCQs->CellAttributes() ?>>
<span id="el_t_examinations_NumberOfMCQs" class="control-group">
<input type="text" data-field="x_NumberOfMCQs" name="x_NumberOfMCQs" id="x_NumberOfMCQs" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->NumberOfMCQs->PlaceHolder) ?>" value="<?php echo $t_examinations->NumberOfMCQs->EditValue ?>"<?php echo $t_examinations->NumberOfMCQs->EditAttributes() ?>>
</span>
<?php echo $t_examinations->NumberOfMCQs->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_examinations->NumberOfShortAnswerQuestions->Visible) { // NumberOfShortAnswerQuestions ?>
	<tr id="r_NumberOfShortAnswerQuestions">
		<td><span id="elh_t_examinations_NumberOfShortAnswerQuestions"><?php echo $t_examinations->NumberOfShortAnswerQuestions->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_examinations->NumberOfShortAnswerQuestions->CellAttributes() ?>>
<span id="el_t_examinations_NumberOfShortAnswerQuestions" class="control-group">
<input type="text" data-field="x_NumberOfShortAnswerQuestions" name="x_NumberOfShortAnswerQuestions" id="x_NumberOfShortAnswerQuestions" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->NumberOfShortAnswerQuestions->PlaceHolder) ?>" value="<?php echo $t_examinations->NumberOfShortAnswerQuestions->EditValue ?>"<?php echo $t_examinations->NumberOfShortAnswerQuestions->EditAttributes() ?>>
</span>
<?php echo $t_examinations->NumberOfShortAnswerQuestions->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_examinations->Duration->Visible) { // Duration ?>
	<tr id="r_Duration">
		<td><span id="elh_t_examinations_Duration"><?php echo $t_examinations->Duration->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_examinations->Duration->CellAttributes() ?>>
<span id="el_t_examinations_Duration" class="control-group">
<input type="text" data-field="x_Duration" name="x_Duration" id="x_Duration" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Duration->PlaceHolder) ?>" value="<?php echo $t_examinations->Duration->EditValue ?>"<?php echo $t_examinations->Duration->EditAttributes() ?>>
</span>
<?php echo $t_examinations->Duration->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_examinations->Active->Visible) { // Active ?>
	<tr id="r_Active">
		<td><span id="elh_t_examinations_Active"><?php echo $t_examinations->Active->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->Active->CellAttributes() ?>>
<span id="el_t_examinations_Active" class="control-group">
<div id="tp_x_Active" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Active" id="x_Active" value="{value}"<?php echo $t_examinations->Active->EditAttributes() ?>></div>
<div id="dsl_x_Active" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $t_examinations->Active->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_examinations->Active->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_Active" name="x_Active" id="x_Active_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $t_examinations->Active->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $t_examinations->Active->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ft_examinationsadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$t_examinations_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_examinations_add->Page_Terminate();
?>
