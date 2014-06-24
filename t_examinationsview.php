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

$t_examinations_view = NULL; // Initialize page object first

class ct_examinations_view extends ct_examinations {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_examinations';

	// Page object name
	var $PageObjName = 't_examinations_view';

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

		// Table object (t_examinations)
		if (!isset($GLOBALS["t_examinations"]) || get_class($GLOBALS["t_examinations"]) == "ct_examinations") {
			$GLOBALS["t_examinations"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_examinations"];
		}
		$KeyUrl = "";
		if (@$_GET["ExaminationID"] <> "") {
			$this->RecKey["ExaminationID"] = $_GET["ExaminationID"];
			$KeyUrl .= "&amp;ExaminationID=" . urlencode($this->RecKey["ExaminationID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (t_users)
		if (!isset($GLOBALS['t_users'])) $GLOBALS['t_users'] = new ct_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_examinations', TRUE);

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
			$this->Page_Terminate("t_examinationslist.php");
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
		if (@$_GET["ExaminationID"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["ExaminationID"]);
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
			if (@$_GET["ExaminationID"] <> "") {
				$this->ExaminationID->setQueryStringValue($_GET["ExaminationID"]);
				$this->RecKey["ExaminationID"] = $this->ExaminationID->QueryStringValue;
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
						$this->Page_Terminate("t_examinationslist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->ExaminationID->CurrentValue) == strval($this->Recordset->fields('ExaminationID'))) {
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
						$sReturnUrl = "t_examinationslist.php"; // No matching record, return to list
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
			$sReturnUrl = "t_examinationslist.php"; // Not page request, return to list
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$item->Body = "<a id=\"emf_t_examinations\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_t_examinations',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.ft_examinationsview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", $this->TableVar, "t_examinationslist.php", $this->TableVar, TRUE);
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
if (!isset($t_examinations_view)) $t_examinations_view = new ct_examinations_view();

// Page init
$t_examinations_view->Page_Init();

// Page main
$t_examinations_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_examinations_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($t_examinations->Export == "") { ?>
<script type="text/javascript">

// Page object
var t_examinations_view = new ew_Page("t_examinations_view");
t_examinations_view.PageID = "view"; // Page ID
var EW_PAGE_ID = t_examinations_view.PageID; // For backward compatibility

// Form object
var ft_examinationsview = new ew_Form("ft_examinationsview");

// Form_CustomValidate event
ft_examinationsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_examinationsview.ValidateRequired = true;
<?php } else { ?>
ft_examinationsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_examinationsview.Lists["x_ExaminationTypeID"] = {"LinkField":"x_ExaminationTypeID","Ajax":true,"AutoFill":false,"DisplayFields":["x_ExaminationType","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationsview.Lists["x_SeminsterID"] = {"LinkField":"x_SemisterID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Semister","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationsview.Lists["x_CourseID"] = {"LinkField":"x_CourseID","Ajax":true,"AutoFill":false,"DisplayFields":["x_CourseCode","x_Course","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationsview.Lists["x_InsttructorID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_LastName","x_FirstName","x_NICNumber",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($t_examinations->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($t_examinations->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $t_examinations_view->ExportOptions->Render("body") ?>
<?php if (!$t_examinations_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($t_examinations_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $t_examinations_view->ShowPageHeader(); ?>
<?php
$t_examinations_view->ShowMessage();
?>
<?php if ($t_examinations->Export == "") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_examinations_view->Pager)) $t_examinations_view->Pager = new cPrevNextPager($t_examinations_view->StartRec, $t_examinations_view->DisplayRecs, $t_examinations_view->TotalRecs) ?>
<?php if ($t_examinations_view->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_examinations_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_view->PageUrl() ?>start=<?php echo $t_examinations_view->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_examinations_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_view->PageUrl() ?>start=<?php echo $t_examinations_view->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_examinations_view->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_examinations_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_view->PageUrl() ?>start=<?php echo $t_examinations_view->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_examinations_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_view->PageUrl() ?>start=<?php echo $t_examinations_view->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_examinations_view->Pager->PageCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<form name="ft_examinationsview" id="ft_examinationsview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="t_examinations">
<table class="ewGrid"><tr><td>
<table id="tbl_t_examinationsview" class="table table-bordered table-striped">
<?php if ($t_examinations->Name->Visible) { // Name ?>
	<tr id="r_Name">
		<td><span id="elh_t_examinations_Name"><?php echo $t_examinations->Name->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->Name->CellAttributes() ?>>
<span id="el_t_examinations_Name" class="control-group">
<span<?php echo $t_examinations->Name->ViewAttributes() ?>>
<?php echo $t_examinations->Name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_examinations->ExaminationTypeID->Visible) { // ExaminationTypeID ?>
	<tr id="r_ExaminationTypeID">
		<td><span id="elh_t_examinations_ExaminationTypeID"><?php echo $t_examinations->ExaminationTypeID->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->ExaminationTypeID->CellAttributes() ?>>
<span id="el_t_examinations_ExaminationTypeID" class="control-group">
<span<?php echo $t_examinations->ExaminationTypeID->ViewAttributes() ?>>
<?php echo $t_examinations->ExaminationTypeID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_examinations->Year->Visible) { // Year ?>
	<tr id="r_Year">
		<td><span id="elh_t_examinations_Year"><?php echo $t_examinations->Year->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->Year->CellAttributes() ?>>
<span id="el_t_examinations_Year" class="control-group">
<span<?php echo $t_examinations->Year->ViewAttributes() ?>>
<?php echo $t_examinations->Year->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_examinations->SeminsterID->Visible) { // SeminsterID ?>
	<tr id="r_SeminsterID">
		<td><span id="elh_t_examinations_SeminsterID"><?php echo $t_examinations->SeminsterID->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->SeminsterID->CellAttributes() ?>>
<span id="el_t_examinations_SeminsterID" class="control-group">
<span<?php echo $t_examinations->SeminsterID->ViewAttributes() ?>>
<?php echo $t_examinations->SeminsterID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_examinations->CourseID->Visible) { // CourseID ?>
	<tr id="r_CourseID">
		<td><span id="elh_t_examinations_CourseID"><?php echo $t_examinations->CourseID->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->CourseID->CellAttributes() ?>>
<span id="el_t_examinations_CourseID" class="control-group">
<span<?php echo $t_examinations->CourseID->ViewAttributes() ?>>
<?php echo $t_examinations->CourseID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_examinations->InsttructorID->Visible) { // InsttructorID ?>
	<tr id="r_InsttructorID">
		<td><span id="elh_t_examinations_InsttructorID"><?php echo $t_examinations->InsttructorID->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->InsttructorID->CellAttributes() ?>>
<span id="el_t_examinations_InsttructorID" class="control-group">
<span<?php echo $t_examinations->InsttructorID->ViewAttributes() ?>>
<?php echo $t_examinations->InsttructorID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_examinations->NumberOfMCQs->Visible) { // NumberOfMCQs ?>
	<tr id="r_NumberOfMCQs">
		<td><span id="elh_t_examinations_NumberOfMCQs"><?php echo $t_examinations->NumberOfMCQs->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->NumberOfMCQs->CellAttributes() ?>>
<span id="el_t_examinations_NumberOfMCQs" class="control-group">
<span<?php echo $t_examinations->NumberOfMCQs->ViewAttributes() ?>>
<?php echo $t_examinations->NumberOfMCQs->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_examinations->NumberOfShortAnswerQuestions->Visible) { // NumberOfShortAnswerQuestions ?>
	<tr id="r_NumberOfShortAnswerQuestions">
		<td><span id="elh_t_examinations_NumberOfShortAnswerQuestions"><?php echo $t_examinations->NumberOfShortAnswerQuestions->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->NumberOfShortAnswerQuestions->CellAttributes() ?>>
<span id="el_t_examinations_NumberOfShortAnswerQuestions" class="control-group">
<span<?php echo $t_examinations->NumberOfShortAnswerQuestions->ViewAttributes() ?>>
<?php echo $t_examinations->NumberOfShortAnswerQuestions->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_examinations->Duration->Visible) { // Duration ?>
	<tr id="r_Duration">
		<td><span id="elh_t_examinations_Duration"><?php echo $t_examinations->Duration->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->Duration->CellAttributes() ?>>
<span id="el_t_examinations_Duration" class="control-group">
<span<?php echo $t_examinations->Duration->ViewAttributes() ?>>
<?php echo $t_examinations->Duration->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_examinations->Active->Visible) { // Active ?>
	<tr id="r_Active">
		<td><span id="elh_t_examinations_Active"><?php echo $t_examinations->Active->FldCaption() ?></span></td>
		<td<?php echo $t_examinations->Active->CellAttributes() ?>>
<span id="el_t_examinations_Active" class="control-group">
<span<?php echo $t_examinations->Active->ViewAttributes() ?>>
<?php echo $t_examinations->Active->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($t_examinations->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($t_examinations_view->Pager)) $t_examinations_view->Pager = new cPrevNextPager($t_examinations_view->StartRec, $t_examinations_view->DisplayRecs, $t_examinations_view->TotalRecs) ?>
<?php if ($t_examinations_view->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_examinations_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_view->PageUrl() ?>start=<?php echo $t_examinations_view->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_examinations_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_view->PageUrl() ?>start=<?php echo $t_examinations_view->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_examinations_view->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_examinations_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_view->PageUrl() ?>start=<?php echo $t_examinations_view->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_examinations_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_view->PageUrl() ?>start=<?php echo $t_examinations_view->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_examinations_view->Pager->PageCount ?>
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
ft_examinationsview.Init();
</script>
<?php
$t_examinations_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($t_examinations->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$t_examinations_view->Page_Terminate();
?>
