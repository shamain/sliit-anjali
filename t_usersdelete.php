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

$t_users_delete = NULL; // Initialize page object first

class ct_users_delete extends ct_users {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_users';

	// Page object name
	var $PageObjName = 't_users_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("t_userslist.php");
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
			$this->Page_Terminate("t_userslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t_users class, t_usersinfo.php

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

		$this->_UserID->CellCssStyle = "white-space: nowrap;";

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

			// Username
			$this->Username->LinkCustomAttributes = "";
			$this->Username->HrefValue = "";
			$this->Username->TooltipValue = "";

			// UserLevel
			$this->UserLevel->LinkCustomAttributes = "";
			$this->UserLevel->HrefValue = "";
			$this->UserLevel->TooltipValue = "";

			// FirstName
			$this->FirstName->LinkCustomAttributes = "";
			$this->FirstName->HrefValue = "";
			$this->FirstName->TooltipValue = "";

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

			// DateOfBirth
			$this->DateOfBirth->LinkCustomAttributes = "";
			$this->DateOfBirth->HrefValue = "";
			$this->DateOfBirth->TooltipValue = "";

			// RegistrationValidTill
			$this->RegistrationValidTill->LinkCustomAttributes = "";
			$this->RegistrationValidTill->HrefValue = "";
			$this->RegistrationValidTill->TooltipValue = "";

			// PhotoPath
			$this->PhotoPath->LinkCustomAttributes = "";
			$this->PhotoPath->HrefValue = "";
			$this->PhotoPath->HrefValue2 = $this->PhotoPath->UploadPath . $this->PhotoPath->Upload->DbValue;
			$this->PhotoPath->TooltipValue = "";
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
				$sThisKey .= $row['UserID'];
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
		$Breadcrumb->Add("list", $this->TableVar, "t_userslist.php", $this->TableVar, TRUE);
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
if (!isset($t_users_delete)) $t_users_delete = new ct_users_delete();

// Page init
$t_users_delete->Page_Init();

// Page main
$t_users_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_users_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var t_users_delete = new ew_Page("t_users_delete");
t_users_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = t_users_delete.PageID; // For backward compatibility

// Form object
var ft_usersdelete = new ew_Form("ft_usersdelete");

// Form_CustomValidate event
ft_usersdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_usersdelete.ValidateRequired = true;
<?php } else { ?>
ft_usersdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_usersdelete.Lists["x_UserLevel"] = {"LinkField":"x_userlevelid","Ajax":null,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($t_users_delete->Recordset = $t_users_delete->LoadRecordset())
	$t_users_deleteTotalRecs = $t_users_delete->Recordset->RecordCount(); // Get record count
if ($t_users_deleteTotalRecs <= 0) { // No record found, exit
	if ($t_users_delete->Recordset)
		$t_users_delete->Recordset->Close();
	$t_users_delete->Page_Terminate("t_userslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $t_users_delete->ShowPageHeader(); ?>
<?php
$t_users_delete->ShowMessage();
?>
<form name="ft_usersdelete" id="ft_usersdelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="t_users">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t_users_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_t_usersdelete" class="ewTable ewTableSeparate">
<?php echo $t_users->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($t_users->Username->Visible) { // Username ?>
		<td><span id="elh_t_users_Username" class="t_users_Username"><?php echo $t_users->Username->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_users->UserLevel->Visible) { // UserLevel ?>
		<td><span id="elh_t_users_UserLevel" class="t_users_UserLevel"><?php echo $t_users->UserLevel->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_users->FirstName->Visible) { // FirstName ?>
		<td><span id="elh_t_users_FirstName" class="t_users_FirstName"><?php echo $t_users->FirstName->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_users->LastName->Visible) { // LastName ?>
		<td><span id="elh_t_users_LastName" class="t_users_LastName"><?php echo $t_users->LastName->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_users->_Email->Visible) { // Email ?>
		<td><span id="elh_t_users__Email" class="t_users__Email"><?php echo $t_users->_Email->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_users->RegistrationNumber->Visible) { // RegistrationNumber ?>
		<td><span id="elh_t_users_RegistrationNumber" class="t_users_RegistrationNumber"><?php echo $t_users->RegistrationNumber->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_users->NICNumber->Visible) { // NICNumber ?>
		<td><span id="elh_t_users_NICNumber" class="t_users_NICNumber"><?php echo $t_users->NICNumber->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_users->Gender->Visible) { // Gender ?>
		<td><span id="elh_t_users_Gender" class="t_users_Gender"><?php echo $t_users->Gender->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_users->DateOfBirth->Visible) { // DateOfBirth ?>
		<td><span id="elh_t_users_DateOfBirth" class="t_users_DateOfBirth"><?php echo $t_users->DateOfBirth->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_users->RegistrationValidTill->Visible) { // RegistrationValidTill ?>
		<td><span id="elh_t_users_RegistrationValidTill" class="t_users_RegistrationValidTill"><?php echo $t_users->RegistrationValidTill->FldCaption() ?></span></td>
<?php } ?>
<?php if ($t_users->PhotoPath->Visible) { // PhotoPath ?>
		<td><span id="elh_t_users_PhotoPath" class="t_users_PhotoPath"><?php echo $t_users->PhotoPath->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t_users_delete->RecCnt = 0;
$i = 0;
while (!$t_users_delete->Recordset->EOF) {
	$t_users_delete->RecCnt++;
	$t_users_delete->RowCnt++;

	// Set row properties
	$t_users->ResetAttrs();
	$t_users->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t_users_delete->LoadRowValues($t_users_delete->Recordset);

	// Render row
	$t_users_delete->RenderRow();
?>
	<tr<?php echo $t_users->RowAttributes() ?>>
<?php if ($t_users->Username->Visible) { // Username ?>
		<td<?php echo $t_users->Username->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users_Username" class="control-group t_users_Username">
<span<?php echo $t_users->Username->ViewAttributes() ?>>
<?php echo $t_users->Username->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_users->UserLevel->Visible) { // UserLevel ?>
		<td<?php echo $t_users->UserLevel->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users_UserLevel" class="control-group t_users_UserLevel">
<span<?php echo $t_users->UserLevel->ViewAttributes() ?>>
<?php echo $t_users->UserLevel->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_users->FirstName->Visible) { // FirstName ?>
		<td<?php echo $t_users->FirstName->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users_FirstName" class="control-group t_users_FirstName">
<span<?php echo $t_users->FirstName->ViewAttributes() ?>>
<?php echo $t_users->FirstName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_users->LastName->Visible) { // LastName ?>
		<td<?php echo $t_users->LastName->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users_LastName" class="control-group t_users_LastName">
<span<?php echo $t_users->LastName->ViewAttributes() ?>>
<?php echo $t_users->LastName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_users->_Email->Visible) { // Email ?>
		<td<?php echo $t_users->_Email->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users__Email" class="control-group t_users__Email">
<span<?php echo $t_users->_Email->ViewAttributes() ?>>
<?php echo $t_users->_Email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_users->RegistrationNumber->Visible) { // RegistrationNumber ?>
		<td<?php echo $t_users->RegistrationNumber->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users_RegistrationNumber" class="control-group t_users_RegistrationNumber">
<span<?php echo $t_users->RegistrationNumber->ViewAttributes() ?>>
<?php echo $t_users->RegistrationNumber->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_users->NICNumber->Visible) { // NICNumber ?>
		<td<?php echo $t_users->NICNumber->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users_NICNumber" class="control-group t_users_NICNumber">
<span<?php echo $t_users->NICNumber->ViewAttributes() ?>>
<?php echo $t_users->NICNumber->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_users->Gender->Visible) { // Gender ?>
		<td<?php echo $t_users->Gender->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users_Gender" class="control-group t_users_Gender">
<span<?php echo $t_users->Gender->ViewAttributes() ?>>
<?php echo $t_users->Gender->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_users->DateOfBirth->Visible) { // DateOfBirth ?>
		<td<?php echo $t_users->DateOfBirth->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users_DateOfBirth" class="control-group t_users_DateOfBirth">
<span<?php echo $t_users->DateOfBirth->ViewAttributes() ?>>
<?php echo $t_users->DateOfBirth->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_users->RegistrationValidTill->Visible) { // RegistrationValidTill ?>
		<td<?php echo $t_users->RegistrationValidTill->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users_RegistrationValidTill" class="control-group t_users_RegistrationValidTill">
<span<?php echo $t_users->RegistrationValidTill->ViewAttributes() ?>>
<?php echo $t_users->RegistrationValidTill->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_users->PhotoPath->Visible) { // PhotoPath ?>
		<td<?php echo $t_users->PhotoPath->CellAttributes() ?>>
<span id="el<?php echo $t_users_delete->RowCnt ?>_t_users_PhotoPath" class="control-group t_users_PhotoPath">
<span>
<?php if ($t_users->PhotoPath->LinkAttributes() <> "") { ?>
<?php if (!empty($t_users->PhotoPath->Upload->DbValue)) { ?>
<?php echo ew_GetFileViewTag($t_users->PhotoPath, $t_users->PhotoPath->ListViewValue()) ?>
<?php } elseif (!in_array($t_users->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($t_users->PhotoPath->Upload->DbValue)) { ?>
<?php echo ew_GetFileViewTag($t_users->PhotoPath, $t_users->PhotoPath->ListViewValue()) ?>
<?php } elseif (!in_array($t_users->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t_users_delete->Recordset->MoveNext();
}
$t_users_delete->Recordset->Close();
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
ft_usersdelete.Init();
</script>
<?php
$t_users_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_users_delete->Page_Terminate();
?>
