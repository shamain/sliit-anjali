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

$t_users_view = NULL; // Initialize page object first

class ct_users_view extends ct_users {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_users';

	// Page object name
	var $PageObjName = 't_users_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["_UserID"] <> "") {
			$this->RecKey["_UserID"] = $_GET["_UserID"];
			$KeyUrl .= "&amp;_UserID=" . urlencode($this->RecKey["_UserID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_users', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("t_userslist.php");
		}

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["_UserID"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["_UserID"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Update url if printer friendly for Pdf
		if ($this->PrinterFriendlyForPdf)
			$this->ExportOptions->Items["pdf"]->Body = str_replace($this->ExportPdfUrl, $this->ExportPrintUrl . "&pdf=1", $this->ExportOptions->Items["pdf"]->Body);
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();
		if ($this->Export == "print" && @$_GET["pdf"] == "1") { // Printer friendly version and with pdf=1 in URL parameters
			$pdf = new cExportPdf($GLOBALS["Table"]);
			$pdf->Text = ob_get_contents(); // Set the content as the HTML of current page (printer friendly version)
			ob_end_clean();
			$pdf->Export();
		}

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
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
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["_UserID"] <> "") {
				$this->_UserID->setQueryStringValue($_GET["_UserID"]);
				$this->RecKey["_UserID"] = $this->_UserID->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
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
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "t_userslist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "t_userslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a onclick=\"return ew_Confirm(ewLanguage.Phrase('DeleteConfirmMsg'));\" class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_t_users\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_t_users',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.ft_usersview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "v");
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$ExportDoc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "t_userslist.php", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, ew_CurrentUrl());
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
if (!isset($t_users_view)) $t_users_view = new ct_users_view();

// Page init
$t_users_view->Page_Init();

// Page main
$t_users_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_users_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($t_users->Export == "") { ?>
<script type="text/javascript">

// Page object
var t_users_view = new ew_Page("t_users_view");
t_users_view.PageID = "view"; // Page ID
var EW_PAGE_ID = t_users_view.PageID; // For backward compatibility

// Form object
var ft_usersview = new ew_Form("ft_usersview");

// Form_CustomValidate event
ft_usersview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_usersview.ValidateRequired = true;
<?php } else { ?>
ft_usersview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_usersview.Lists["x_UserLevel"] = {"LinkField":"x_userlevelid","Ajax":null,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_usersview.Lists["x_DesignationID"] = {"LinkField":"x_DesignationID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Designation","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_usersview.Lists["x_MaritalStatusID"] = {"LinkField":"x_MaritalStatusID","Ajax":true,"AutoFill":false,"DisplayFields":["x_MaritalStatus","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($t_users->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($t_users->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $t_users_view->ExportOptions->Render("body") ?>
<?php if (!$t_users_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($t_users_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $t_users_view->ShowPageHeader(); ?>
<?php
$t_users_view->ShowMessage();
?>
<?php if ($t_users->Export == "") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_users_view->Pager)) $t_users_view->Pager = new cPrevNextPager($t_users_view->StartRec, $t_users_view->DisplayRecs, $t_users_view->TotalRecs) ?>
<?php if ($t_users_view->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_users_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_view->PageUrl() ?>start=<?php echo $t_users_view->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_users_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_view->PageUrl() ?>start=<?php echo $t_users_view->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_users_view->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_users_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_view->PageUrl() ?>start=<?php echo $t_users_view->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_users_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_view->PageUrl() ?>start=<?php echo $t_users_view->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_users_view->Pager->PageCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<form name="ft_usersview" id="ft_usersview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="t_users">
<table class="ewGrid"><tr><td>
<table id="tbl_t_usersview" class="table table-bordered table-striped">
<?php if ($t_users->Username->Visible) { // Username ?>
	<tr id="r_Username">
		<td><span id="elh_t_users_Username"><?php echo $t_users->Username->FldCaption() ?></span></td>
		<td<?php echo $t_users->Username->CellAttributes() ?>>
<span id="el_t_users_Username" class="control-group">
<span<?php echo $t_users->Username->ViewAttributes() ?>>
<?php echo $t_users->Username->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->Password->Visible) { // Password ?>
	<tr id="r_Password">
		<td><span id="elh_t_users_Password"><?php echo $t_users->Password->FldCaption() ?></span></td>
		<td<?php echo $t_users->Password->CellAttributes() ?>>
<span id="el_t_users_Password" class="control-group">
<span<?php echo $t_users->Password->ViewAttributes() ?>>
<?php echo $t_users->Password->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->UserLevel->Visible) { // UserLevel ?>
	<tr id="r_UserLevel">
		<td><span id="elh_t_users_UserLevel"><?php echo $t_users->UserLevel->FldCaption() ?></span></td>
		<td<?php echo $t_users->UserLevel->CellAttributes() ?>>
<span id="el_t_users_UserLevel" class="control-group">
<span<?php echo $t_users->UserLevel->ViewAttributes() ?>>
<?php echo $t_users->UserLevel->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->Activated->Visible) { // Activated ?>
	<tr id="r_Activated">
		<td><span id="elh_t_users_Activated"><?php echo $t_users->Activated->FldCaption() ?></span></td>
		<td<?php echo $t_users->Activated->CellAttributes() ?>>
<span id="el_t_users_Activated" class="control-group">
<span<?php echo $t_users->Activated->ViewAttributes() ?>>
<?php echo $t_users->Activated->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->DesignationID->Visible) { // DesignationID ?>
	<tr id="r_DesignationID">
		<td><span id="elh_t_users_DesignationID"><?php echo $t_users->DesignationID->FldCaption() ?></span></td>
		<td<?php echo $t_users->DesignationID->CellAttributes() ?>>
<span id="el_t_users_DesignationID" class="control-group">
<span<?php echo $t_users->DesignationID->ViewAttributes() ?>>
<?php echo $t_users->DesignationID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->FirstName->Visible) { // FirstName ?>
	<tr id="r_FirstName">
		<td><span id="elh_t_users_FirstName"><?php echo $t_users->FirstName->FldCaption() ?></span></td>
		<td<?php echo $t_users->FirstName->CellAttributes() ?>>
<span id="el_t_users_FirstName" class="control-group">
<span<?php echo $t_users->FirstName->ViewAttributes() ?>>
<?php echo $t_users->FirstName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->MiddleName->Visible) { // MiddleName ?>
	<tr id="r_MiddleName">
		<td><span id="elh_t_users_MiddleName"><?php echo $t_users->MiddleName->FldCaption() ?></span></td>
		<td<?php echo $t_users->MiddleName->CellAttributes() ?>>
<span id="el_t_users_MiddleName" class="control-group">
<span<?php echo $t_users->MiddleName->ViewAttributes() ?>>
<?php echo $t_users->MiddleName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->LastName->Visible) { // LastName ?>
	<tr id="r_LastName">
		<td><span id="elh_t_users_LastName"><?php echo $t_users->LastName->FldCaption() ?></span></td>
		<td<?php echo $t_users->LastName->CellAttributes() ?>>
<span id="el_t_users_LastName" class="control-group">
<span<?php echo $t_users->LastName->ViewAttributes() ?>>
<?php echo $t_users->LastName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->_Email->Visible) { // Email ?>
	<tr id="r__Email">
		<td><span id="elh_t_users__Email"><?php echo $t_users->_Email->FldCaption() ?></span></td>
		<td<?php echo $t_users->_Email->CellAttributes() ?>>
<span id="el_t_users__Email" class="control-group">
<span<?php echo $t_users->_Email->ViewAttributes() ?>>
<?php echo $t_users->_Email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->RegistrationNumber->Visible) { // RegistrationNumber ?>
	<tr id="r_RegistrationNumber">
		<td><span id="elh_t_users_RegistrationNumber"><?php echo $t_users->RegistrationNumber->FldCaption() ?></span></td>
		<td<?php echo $t_users->RegistrationNumber->CellAttributes() ?>>
<span id="el_t_users_RegistrationNumber" class="control-group">
<span<?php echo $t_users->RegistrationNumber->ViewAttributes() ?>>
<?php echo $t_users->RegistrationNumber->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->NICNumber->Visible) { // NICNumber ?>
	<tr id="r_NICNumber">
		<td><span id="elh_t_users_NICNumber"><?php echo $t_users->NICNumber->FldCaption() ?></span></td>
		<td<?php echo $t_users->NICNumber->CellAttributes() ?>>
<span id="el_t_users_NICNumber" class="control-group">
<span<?php echo $t_users->NICNumber->ViewAttributes() ?>>
<?php echo $t_users->NICNumber->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->Gender->Visible) { // Gender ?>
	<tr id="r_Gender">
		<td><span id="elh_t_users_Gender"><?php echo $t_users->Gender->FldCaption() ?></span></td>
		<td<?php echo $t_users->Gender->CellAttributes() ?>>
<span id="el_t_users_Gender" class="control-group">
<span<?php echo $t_users->Gender->ViewAttributes() ?>>
<?php echo $t_users->Gender->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->MaritalStatusID->Visible) { // MaritalStatusID ?>
	<tr id="r_MaritalStatusID">
		<td><span id="elh_t_users_MaritalStatusID"><?php echo $t_users->MaritalStatusID->FldCaption() ?></span></td>
		<td<?php echo $t_users->MaritalStatusID->CellAttributes() ?>>
<span id="el_t_users_MaritalStatusID" class="control-group">
<span<?php echo $t_users->MaritalStatusID->ViewAttributes() ?>>
<?php echo $t_users->MaritalStatusID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->DateOfBirth->Visible) { // DateOfBirth ?>
	<tr id="r_DateOfBirth">
		<td><span id="elh_t_users_DateOfBirth"><?php echo $t_users->DateOfBirth->FldCaption() ?></span></td>
		<td<?php echo $t_users->DateOfBirth->CellAttributes() ?>>
<span id="el_t_users_DateOfBirth" class="control-group">
<span<?php echo $t_users->DateOfBirth->ViewAttributes() ?>>
<?php echo $t_users->DateOfBirth->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->RegisteredOn->Visible) { // RegisteredOn ?>
	<tr id="r_RegisteredOn">
		<td><span id="elh_t_users_RegisteredOn"><?php echo $t_users->RegisteredOn->FldCaption() ?></span></td>
		<td<?php echo $t_users->RegisteredOn->CellAttributes() ?>>
<span id="el_t_users_RegisteredOn" class="control-group">
<span<?php echo $t_users->RegisteredOn->ViewAttributes() ?>>
<?php echo $t_users->RegisteredOn->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->RegistrationValidTill->Visible) { // RegistrationValidTill ?>
	<tr id="r_RegistrationValidTill">
		<td><span id="elh_t_users_RegistrationValidTill"><?php echo $t_users->RegistrationValidTill->FldCaption() ?></span></td>
		<td<?php echo $t_users->RegistrationValidTill->CellAttributes() ?>>
<span id="el_t_users_RegistrationValidTill" class="control-group">
<span<?php echo $t_users->RegistrationValidTill->ViewAttributes() ?>>
<?php echo $t_users->RegistrationValidTill->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_users->PhotoPath->Visible) { // PhotoPath ?>
	<tr id="r_PhotoPath">
		<td><span id="elh_t_users_PhotoPath"><?php echo $t_users->PhotoPath->FldCaption() ?></span></td>
		<td<?php echo $t_users->PhotoPath->CellAttributes() ?>>
<span id="el_t_users_PhotoPath" class="control-group">
<span>
<?php if ($t_users->PhotoPath->LinkAttributes() <> "") { ?>
<?php if (!empty($t_users->PhotoPath->Upload->DbValue)) { ?>
<?php echo ew_GetFileViewTag($t_users->PhotoPath, $t_users->PhotoPath->ViewValue) ?>
<?php } elseif (!in_array($t_users->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($t_users->PhotoPath->Upload->DbValue)) { ?>
<?php echo ew_GetFileViewTag($t_users->PhotoPath, $t_users->PhotoPath->ViewValue) ?>
<?php } elseif (!in_array($t_users->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($t_users->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($t_users_view->Pager)) $t_users_view->Pager = new cPrevNextPager($t_users_view->StartRec, $t_users_view->DisplayRecs, $t_users_view->TotalRecs) ?>
<?php if ($t_users_view->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_users_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_view->PageUrl() ?>start=<?php echo $t_users_view->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_users_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_view->PageUrl() ?>start=<?php echo $t_users_view->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_users_view->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_users_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_view->PageUrl() ?>start=<?php echo $t_users_view->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_users_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_users_view->PageUrl() ?>start=<?php echo $t_users_view->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_users_view->Pager->PageCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
<?php } ?>
</td>
</tr></table>
<?php } ?>
</form>
<script type="text/javascript">
ft_usersview.Init();
</script>
<?php
$t_users_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($t_users->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$t_users_view->Page_Terminate();
?>
