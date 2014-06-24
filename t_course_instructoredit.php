<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "t_course_instructorinfo.php" ?>
<?php include_once "t_usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$t_course_instructor_edit = NULL; // Initialize page object first

class ct_course_instructor_edit extends ct_course_instructor {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_course_instructor';

	// Page object name
	var $PageObjName = 't_course_instructor_edit';

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

		// Table object (t_course_instructor)
		if (!isset($GLOBALS["t_course_instructor"]) || get_class($GLOBALS["t_course_instructor"]) == "ct_course_instructor") {
			$GLOBALS["t_course_instructor"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_course_instructor"];
		}

		// Table object (t_users)
		if (!isset($GLOBALS['t_users'])) $GLOBALS['t_users'] = new ct_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_course_instructor', TRUE);

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
			$this->Page_Terminate("t_course_instructorlist.php");
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
		if (@$_GET["CourseInstructorID"] <> "") {
			$this->CourseInstructorID->setQueryStringValue($_GET["CourseInstructorID"]);
			$this->RecKey["CourseInstructorID"] = $this->CourseInstructorID->QueryStringValue;
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
			$this->Page_Terminate("t_course_instructorlist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->CourseInstructorID->CurrentValue) == strval($this->Recordset->fields('CourseInstructorID'))) {
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
					$this->Page_Terminate("t_course_instructorlist.php"); // Return to list page
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
					if (ew_GetPageName($sReturnUrl) == "t_course_instructorview.php")
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
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->CourseID->FldIsDetailKey) {
			$this->CourseID->setFormValue($objForm->GetValue("x_CourseID"));
		}
		if (!$this->Year->FldIsDetailKey) {
			$this->Year->setFormValue($objForm->GetValue("x_Year"));
		}
		if (!$this->SemisterID->FldIsDetailKey) {
			$this->SemisterID->setFormValue($objForm->GetValue("x_SemisterID"));
		}
		if (!$this->InstructorID->FldIsDetailKey) {
			$this->InstructorID->setFormValue($objForm->GetValue("x_InstructorID"));
		}
		if (!$this->CourseInstructorID->FldIsDetailKey)
			$this->CourseInstructorID->setFormValue($objForm->GetValue("x_CourseInstructorID"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->CourseInstructorID->CurrentValue = $this->CourseInstructorID->FormValue;
		$this->CourseID->CurrentValue = $this->CourseID->FormValue;
		$this->Year->CurrentValue = $this->Year->FormValue;
		$this->SemisterID->CurrentValue = $this->SemisterID->FormValue;
		$this->InstructorID->CurrentValue = $this->InstructorID->FormValue;
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
		$this->CourseInstructorID->setDbValue($rs->fields('CourseInstructorID'));
		$this->CourseID->setDbValue($rs->fields('CourseID'));
		if (array_key_exists('EV__CourseID', $rs->fields)) {
			$this->CourseID->VirtualValue = $rs->fields('EV__CourseID'); // Set up virtual field value
		} else {
			$this->CourseID->VirtualValue = ""; // Clear value
		}
		$this->Year->setDbValue($rs->fields('Year'));
		$this->SemisterID->setDbValue($rs->fields('SemisterID'));
		if (array_key_exists('EV__SemisterID', $rs->fields)) {
			$this->SemisterID->VirtualValue = $rs->fields('EV__SemisterID'); // Set up virtual field value
		} else {
			$this->SemisterID->VirtualValue = ""; // Clear value
		}
		$this->InstructorID->setDbValue($rs->fields('InstructorID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CourseInstructorID->DbValue = $row['CourseInstructorID'];
		$this->CourseID->DbValue = $row['CourseID'];
		$this->Year->DbValue = $row['Year'];
		$this->SemisterID->DbValue = $row['SemisterID'];
		$this->InstructorID->DbValue = $row['InstructorID'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// CourseInstructorID
		// CourseID
		// Year
		// SemisterID
		// InstructorID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// Year
			$this->Year->ViewValue = $this->Year->CurrentValue;
			$this->Year->ViewCustomAttributes = "";

			// SemisterID
			if ($this->SemisterID->VirtualValue <> "") {
				$this->SemisterID->ViewValue = $this->SemisterID->VirtualValue;
			} else {
			if (strval($this->SemisterID->CurrentValue) <> "") {
				$sFilterWrk = "`SemisterID`" . ew_SearchString("=", $this->SemisterID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `SemisterID`, `Semister` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_semisters`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->SemisterID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->SemisterID->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->SemisterID->ViewValue = $this->SemisterID->CurrentValue;
				}
			} else {
				$this->SemisterID->ViewValue = NULL;
			}
			}
			$this->SemisterID->ViewCustomAttributes = "";

			// InstructorID
			if (strval($this->InstructorID->CurrentValue) <> "") {
				$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->InstructorID->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->InstructorID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `LastName`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->InstructorID->ViewValue = $rswrk->fields('DispFld');
					$this->InstructorID->ViewValue .= ew_ValueSeparator(1,$this->InstructorID) . $rswrk->fields('Disp2Fld');
					$this->InstructorID->ViewValue .= ew_ValueSeparator(2,$this->InstructorID) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->InstructorID->ViewValue = $this->InstructorID->CurrentValue;
				}
			} else {
				$this->InstructorID->ViewValue = NULL;
			}
			$this->InstructorID->ViewCustomAttributes = "";

			// CourseID
			$this->CourseID->LinkCustomAttributes = "";
			$this->CourseID->HrefValue = "";
			$this->CourseID->TooltipValue = "";

			// Year
			$this->Year->LinkCustomAttributes = "";
			$this->Year->HrefValue = "";
			$this->Year->TooltipValue = "";

			// SemisterID
			$this->SemisterID->LinkCustomAttributes = "";
			$this->SemisterID->HrefValue = "";
			$this->SemisterID->TooltipValue = "";

			// InstructorID
			$this->InstructorID->LinkCustomAttributes = "";
			$this->InstructorID->HrefValue = "";
			$this->InstructorID->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// CourseID
			$this->CourseID->EditCustomAttributes = "";
			$sFilterWrk = "";
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

			// Year
			$this->Year->EditCustomAttributes = "";
			$this->Year->EditValue = ew_HtmlEncode($this->Year->CurrentValue);
			$this->Year->PlaceHolder = ew_RemoveHtml($this->Year->FldCaption());

			// SemisterID
			$this->SemisterID->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `SemisterID`, `Semister` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_semisters`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->SemisterID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->SemisterID->EditValue = $arwrk;

			// InstructorID
			$this->InstructorID->EditCustomAttributes = "";
			$sFilterWrk = "";
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
			$this->Lookup_Selecting($this->InstructorID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `LastName`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->InstructorID->EditValue = $arwrk;

			// Edit refer script
			// CourseID

			$this->CourseID->HrefValue = "";

			// Year
			$this->Year->HrefValue = "";

			// SemisterID
			$this->SemisterID->HrefValue = "";

			// InstructorID
			$this->InstructorID->HrefValue = "";
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
		if (!$this->CourseID->FldIsDetailKey && !is_null($this->CourseID->FormValue) && $this->CourseID->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->CourseID->FldCaption());
		}
		if (!$this->Year->FldIsDetailKey && !is_null($this->Year->FormValue) && $this->Year->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Year->FldCaption());
		}
		if (!ew_CheckInteger($this->Year->FormValue)) {
			ew_AddMessage($gsFormError, $this->Year->FldErrMsg());
		}
		if (!$this->SemisterID->FldIsDetailKey && !is_null($this->SemisterID->FormValue) && $this->SemisterID->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->SemisterID->FldCaption());
		}
		if (!$this->InstructorID->FldIsDetailKey && !is_null($this->InstructorID->FormValue) && $this->InstructorID->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->InstructorID->FldCaption());
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
			$rsnew = array();

			// CourseID
			$this->CourseID->SetDbValueDef($rsnew, $this->CourseID->CurrentValue, NULL, $this->CourseID->ReadOnly);

			// Year
			$this->Year->SetDbValueDef($rsnew, $this->Year->CurrentValue, NULL, $this->Year->ReadOnly);

			// SemisterID
			$this->SemisterID->SetDbValueDef($rsnew, $this->SemisterID->CurrentValue, NULL, $this->SemisterID->ReadOnly);

			// InstructorID
			$this->InstructorID->SetDbValueDef($rsnew, $this->InstructorID->CurrentValue, NULL, $this->InstructorID->ReadOnly);

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
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "t_course_instructorlist.php", $this->TableVar, TRUE);
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
if (!isset($t_course_instructor_edit)) $t_course_instructor_edit = new ct_course_instructor_edit();

// Page init
$t_course_instructor_edit->Page_Init();

// Page main
$t_course_instructor_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_course_instructor_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var t_course_instructor_edit = new ew_Page("t_course_instructor_edit");
t_course_instructor_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = t_course_instructor_edit.PageID; // For backward compatibility

// Form object
var ft_course_instructoredit = new ew_Form("ft_course_instructoredit");

// Validate form
ft_course_instructoredit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_CourseID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_course_instructor->CourseID->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Year");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_course_instructor->Year->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Year");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_course_instructor->Year->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_SemisterID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_course_instructor->SemisterID->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_InstructorID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_course_instructor->InstructorID->FldCaption()) ?>");

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
ft_course_instructoredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_course_instructoredit.ValidateRequired = true;
<?php } else { ?>
ft_course_instructoredit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_course_instructoredit.Lists["x_CourseID"] = {"LinkField":"x_CourseID","Ajax":null,"AutoFill":false,"DisplayFields":["x_CourseCode","x_Course","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_course_instructoredit.Lists["x_SemisterID"] = {"LinkField":"x_SemisterID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Semister","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_course_instructoredit.Lists["x_InstructorID"] = {"LinkField":"x__UserID","Ajax":null,"AutoFill":false,"DisplayFields":["x_LastName","x_FirstName","x_NICNumber",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $t_course_instructor_edit->ShowPageHeader(); ?>
<?php
$t_course_instructor_edit->ShowMessage();
?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_course_instructor_edit->Pager)) $t_course_instructor_edit->Pager = new cPrevNextPager($t_course_instructor_edit->StartRec, $t_course_instructor_edit->DisplayRecs, $t_course_instructor_edit->TotalRecs) ?>
<?php if ($t_course_instructor_edit->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_course_instructor_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_instructor_edit->PageUrl() ?>start=<?php echo $t_course_instructor_edit->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_course_instructor_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_instructor_edit->PageUrl() ?>start=<?php echo $t_course_instructor_edit->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_course_instructor_edit->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_course_instructor_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_instructor_edit->PageUrl() ?>start=<?php echo $t_course_instructor_edit->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_course_instructor_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_instructor_edit->PageUrl() ?>start=<?php echo $t_course_instructor_edit->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_course_instructor_edit->Pager->PageCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
<?php } ?>
</td>
</tr></table>
</form>
<form name="ft_course_instructoredit" id="ft_course_instructoredit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="t_course_instructor">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_t_course_instructoredit" class="table table-bordered table-striped">
<?php if ($t_course_instructor->CourseID->Visible) { // CourseID ?>
	<tr id="r_CourseID">
		<td><span id="elh_t_course_instructor_CourseID"><?php echo $t_course_instructor->CourseID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_course_instructor->CourseID->CellAttributes() ?>>
<span id="el_t_course_instructor_CourseID" class="control-group">
<select data-field="x_CourseID" id="x_CourseID" name="x_CourseID"<?php echo $t_course_instructor->CourseID->EditAttributes() ?>>
<?php
if (is_array($t_course_instructor->CourseID->EditValue)) {
	$arwrk = $t_course_instructor->CourseID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_course_instructor->CourseID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$t_course_instructor->CourseID) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "t_courses")) { ?>
&nbsp;<a id="aol_x_CourseID" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_CourseID',url:'t_coursesaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_course_instructor->CourseID->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
ft_course_instructoredit.Lists["x_CourseID"].Options = <?php echo (is_array($t_course_instructor->CourseID->EditValue)) ? ew_ArrayToJson($t_course_instructor->CourseID->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $t_course_instructor->CourseID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_course_instructor->Year->Visible) { // Year ?>
	<tr id="r_Year">
		<td><span id="elh_t_course_instructor_Year"><?php echo $t_course_instructor->Year->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_course_instructor->Year->CellAttributes() ?>>
<span id="el_t_course_instructor_Year" class="control-group">
<input type="text" data-field="x_Year" name="x_Year" id="x_Year" size="30" placeholder="<?php echo ew_HtmlEncode($t_course_instructor->Year->PlaceHolder) ?>" value="<?php echo $t_course_instructor->Year->EditValue ?>"<?php echo $t_course_instructor->Year->EditAttributes() ?>>
</span>
<?php echo $t_course_instructor->Year->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_course_instructor->SemisterID->Visible) { // SemisterID ?>
	<tr id="r_SemisterID">
		<td><span id="elh_t_course_instructor_SemisterID"><?php echo $t_course_instructor->SemisterID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_course_instructor->SemisterID->CellAttributes() ?>>
<span id="el_t_course_instructor_SemisterID" class="control-group">
<select data-field="x_SemisterID" id="x_SemisterID" name="x_SemisterID"<?php echo $t_course_instructor->SemisterID->EditAttributes() ?>>
<?php
if (is_array($t_course_instructor->SemisterID->EditValue)) {
	$arwrk = $t_course_instructor->SemisterID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_course_instructor->SemisterID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
&nbsp;<a id="aol_x_SemisterID" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_SemisterID',url:'t_semistersaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_course_instructor->SemisterID->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
ft_course_instructoredit.Lists["x_SemisterID"].Options = <?php echo (is_array($t_course_instructor->SemisterID->EditValue)) ? ew_ArrayToJson($t_course_instructor->SemisterID->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $t_course_instructor->SemisterID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($t_course_instructor->InstructorID->Visible) { // InstructorID ?>
	<tr id="r_InstructorID">
		<td><span id="elh_t_course_instructor_InstructorID"><?php echo $t_course_instructor->InstructorID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $t_course_instructor->InstructorID->CellAttributes() ?>>
<span id="el_t_course_instructor_InstructorID" class="control-group">
<select data-field="x_InstructorID" id="x_InstructorID" name="x_InstructorID"<?php echo $t_course_instructor->InstructorID->EditAttributes() ?>>
<?php
if (is_array($t_course_instructor->InstructorID->EditValue)) {
	$arwrk = $t_course_instructor->InstructorID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_course_instructor->InstructorID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$t_course_instructor->InstructorID) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
<?php if ($arwrk[$rowcntwrk][3] <> "") { ?>
<?php echo ew_ValueSeparator(2,$t_course_instructor->InstructorID) ?><?php echo $arwrk[$rowcntwrk][3] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "t_users")) { ?>
&nbsp;<a id="aol_x_InstructorID" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_InstructorID',url:'t_usersaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_course_instructor->InstructorID->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
ft_course_instructoredit.Lists["x_InstructorID"].Options = <?php echo (is_array($t_course_instructor->InstructorID->EditValue)) ? ew_ArrayToJson($t_course_instructor->InstructorID->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $t_course_instructor->InstructorID->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_CourseInstructorID" name="x_CourseInstructorID" id="x_CourseInstructorID" value="<?php echo ew_HtmlEncode($t_course_instructor->CourseInstructorID->CurrentValue) ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_course_instructor_edit->Pager)) $t_course_instructor_edit->Pager = new cPrevNextPager($t_course_instructor_edit->StartRec, $t_course_instructor_edit->DisplayRecs, $t_course_instructor_edit->TotalRecs) ?>
<?php if ($t_course_instructor_edit->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_course_instructor_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_instructor_edit->PageUrl() ?>start=<?php echo $t_course_instructor_edit->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_course_instructor_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_instructor_edit->PageUrl() ?>start=<?php echo $t_course_instructor_edit->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_course_instructor_edit->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_course_instructor_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_instructor_edit->PageUrl() ?>start=<?php echo $t_course_instructor_edit->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_course_instructor_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_instructor_edit->PageUrl() ?>start=<?php echo $t_course_instructor_edit->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_course_instructor_edit->Pager->PageCount ?>
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
ft_course_instructoredit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$t_course_instructor_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_course_instructor_edit->Page_Terminate();
?>
