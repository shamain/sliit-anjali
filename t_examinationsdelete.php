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

$t_examinations_delete = NULL; // Initialize page object first

class ct_examinations_delete extends ct_examinations {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_examinations';

	// Page object name
	var $PageObjName = 't_examinations_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("t_examinationslist.php");
		}
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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("t_examinationslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t_examinations class, t_examinationsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['ExaminationID'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "t_examinationslist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_examinations_delete)) $t_examinations_delete = new ct_examinations_delete();

// Page init
$t_examinations_delete->Page_Init();

// Page main
$t_examinations_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_examinations_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var t_examinations_delete = new ew_Page("t_examinations_delete");
t_examinations_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = t_examinations_delete.PageID; // For backward compatibility

// Form object
var ft_examinationsdelete = new ew_Form("ft_examinationsdelete");

// Form_CustomValidate event
ft_examinationsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_examinationsdelete.ValidateRequired = true;
<?php } else { ?>
ft_examinationsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_examinationsdelete.Lists["x_ExaminationTypeID"] = {"LinkField":"x_ExaminationTypeID","Ajax":true,"AutoFill":false,"DisplayFields":["x_ExaminationType","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationsdelete.Lists["x_SeminsterID"] = {"LinkField":"x_SemisterID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Semister","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationsdelete.Lists["x_CourseID"] = {"LinkField":"x_CourseID","Ajax":true,"AutoFill":false,"DisplayFields":["x_CourseCode","x_Course","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationsdelete.Lists["x_InsttructorID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_LastName","x_FirstName","x_NICNumber",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($t_examinations_delete->Recordset = $t_examinations_delete->LoadRecordset())
	$t_examinations_deleteTotalRecs = $t_examinations_delete->Recordset->RecordCount(); // Get record count
if ($t_examinations_deleteTotalRecs <= 0) { // No record found, exit
	if ($t_examinations_delete->Recordset)
		$t_examinations_delete->Recordset->Close();
	$t_examinations_delete->Page_Terminate("t_examinationslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $t_examinations_delete->ShowPageHeader(); ?>
<?php
$t_examinations_delete->ShowMessage();
?>
<form name="ft_examinationsdelete" id="ft_examinationsdelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="t_examinations">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t_examinations_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_t_examinationsdelete" class="ewTable ewTableSeparate">
<?php echo $t_examinations->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($t_examinations->Name->Visible) { // Name ?>
		<td><span id="elh_t_examinations_Name" class="t_examinations_Name"><?php echo $t_examinations->Name->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_examinations->ExaminationTypeID->Visible) { // ExaminationTypeID ?>
		<td><span id="elh_t_examinations_ExaminationTypeID" class="t_examinations_ExaminationTypeID"><?php echo $t_examinations->ExaminationTypeID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_examinations->Year->Visible) { // Year ?>
		<td><span id="elh_t_examinations_Year" class="t_examinations_Year"><?php echo $t_examinations->Year->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_examinations->SeminsterID->Visible) { // SeminsterID ?>
		<td><span id="elh_t_examinations_SeminsterID" class="t_examinations_SeminsterID"><?php echo $t_examinations->SeminsterID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_examinations->CourseID->Visible) { // CourseID ?>
		<td><span id="elh_t_examinations_CourseID" class="t_examinations_CourseID"><?php echo $t_examinations->CourseID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_examinations->InsttructorID->Visible) { // InsttructorID ?>
		<td><span id="elh_t_examinations_InsttructorID" class="t_examinations_InsttructorID"><?php echo $t_examinations->InsttructorID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_examinations->NumberOfMCQs->Visible) { // NumberOfMCQs ?>
		<td><span id="elh_t_examinations_NumberOfMCQs" class="t_examinations_NumberOfMCQs"><?php echo $t_examinations->NumberOfMCQs->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_examinations->NumberOfShortAnswerQuestions->Visible) { // NumberOfShortAnswerQuestions ?>
		<td><span id="elh_t_examinations_NumberOfShortAnswerQuestions" class="t_examinations_NumberOfShortAnswerQuestions"><?php echo $t_examinations->NumberOfShortAnswerQuestions->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_examinations->Duration->Visible) { // Duration ?>
		<td><span id="elh_t_examinations_Duration" class="t_examinations_Duration"><?php echo $t_examinations->Duration->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_examinations->Active->Visible) { // Active ?>
		<td><span id="elh_t_examinations_Active" class="t_examinations_Active"><?php echo $t_examinations->Active->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t_examinations_delete->RecCnt = 0;
$i = 0;
while (!$t_examinations_delete->Recordset->EOF) {
	$t_examinations_delete->RecCnt++;
	$t_examinations_delete->RowCnt++;

	// Set row properties
	$t_examinations->ResetAttrs();
	$t_examinations->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t_examinations_delete->LoadRowValues($t_examinations_delete->Recordset);

	// Render row
	$t_examinations_delete->RenderRow();
?>
	<tr<?php echo $t_examinations->RowAttributes() ?>>
<?php if ($t_examinations->Name->Visible) { // Name ?>
		<td<?php echo $t_examinations->Name->CellAttributes() ?>>
<span id="el<?php echo $t_examinations_delete->RowCnt ?>_t_examinations_Name" class="control-group t_examinations_Name">
<span<?php echo $t_examinations->Name->ViewAttributes() ?>>
<?php echo $t_examinations->Name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_examinations->ExaminationTypeID->Visible) { // ExaminationTypeID ?>
		<td<?php echo $t_examinations->ExaminationTypeID->CellAttributes() ?>>
<span id="el<?php echo $t_examinations_delete->RowCnt ?>_t_examinations_ExaminationTypeID" class="control-group t_examinations_ExaminationTypeID">
<span<?php echo $t_examinations->ExaminationTypeID->ViewAttributes() ?>>
<?php echo $t_examinations->ExaminationTypeID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_examinations->Year->Visible) { // Year ?>
		<td<?php echo $t_examinations->Year->CellAttributes() ?>>
<span id="el<?php echo $t_examinations_delete->RowCnt ?>_t_examinations_Year" class="control-group t_examinations_Year">
<span<?php echo $t_examinations->Year->ViewAttributes() ?>>
<?php echo $t_examinations->Year->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_examinations->SeminsterID->Visible) { // SeminsterID ?>
		<td<?php echo $t_examinations->SeminsterID->CellAttributes() ?>>
<span id="el<?php echo $t_examinations_delete->RowCnt ?>_t_examinations_SeminsterID" class="control-group t_examinations_SeminsterID">
<span<?php echo $t_examinations->SeminsterID->ViewAttributes() ?>>
<?php echo $t_examinations->SeminsterID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_examinations->CourseID->Visible) { // CourseID ?>
		<td<?php echo $t_examinations->CourseID->CellAttributes() ?>>
<span id="el<?php echo $t_examinations_delete->RowCnt ?>_t_examinations_CourseID" class="control-group t_examinations_CourseID">
<span<?php echo $t_examinations->CourseID->ViewAttributes() ?>>
<?php echo $t_examinations->CourseID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_examinations->InsttructorID->Visible) { // InsttructorID ?>
		<td<?php echo $t_examinations->InsttructorID->CellAttributes() ?>>
<span id="el<?php echo $t_examinations_delete->RowCnt ?>_t_examinations_InsttructorID" class="control-group t_examinations_InsttructorID">
<span<?php echo $t_examinations->InsttructorID->ViewAttributes() ?>>
<?php echo $t_examinations->InsttructorID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_examinations->NumberOfMCQs->Visible) { // NumberOfMCQs ?>
		<td<?php echo $t_examinations->NumberOfMCQs->CellAttributes() ?>>
<span id="el<?php echo $t_examinations_delete->RowCnt ?>_t_examinations_NumberOfMCQs" class="control-group t_examinations_NumberOfMCQs">
<span<?php echo $t_examinations->NumberOfMCQs->ViewAttributes() ?>>
<?php echo $t_examinations->NumberOfMCQs->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_examinations->NumberOfShortAnswerQuestions->Visible) { // NumberOfShortAnswerQuestions ?>
		<td<?php echo $t_examinations->NumberOfShortAnswerQuestions->CellAttributes() ?>>
<span id="el<?php echo $t_examinations_delete->RowCnt ?>_t_examinations_NumberOfShortAnswerQuestions" class="control-group t_examinations_NumberOfShortAnswerQuestions">
<span<?php echo $t_examinations->NumberOfShortAnswerQuestions->ViewAttributes() ?>>
<?php echo $t_examinations->NumberOfShortAnswerQuestions->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_examinations->Duration->Visible) { // Duration ?>
		<td<?php echo $t_examinations->Duration->CellAttributes() ?>>
<span id="el<?php echo $t_examinations_delete->RowCnt ?>_t_examinations_Duration" class="control-group t_examinations_Duration">
<span<?php echo $t_examinations->Duration->ViewAttributes() ?>>
<?php echo $t_examinations->Duration->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_examinations->Active->Visible) { // Active ?>
		<td<?php echo $t_examinations->Active->CellAttributes() ?>>
<span id="el<?php echo $t_examinations_delete->RowCnt ?>_t_examinations_Active" class="control-group t_examinations_Active">
<span<?php echo $t_examinations->Active->ViewAttributes() ?>>
<?php echo $t_examinations->Active->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t_examinations_delete->Recordset->MoveNext();
}
$t_examinations_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ft_examinationsdelete.Init();
</script>
<?php
$t_examinations_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_examinations_delete->Page_Terminate();
?>
