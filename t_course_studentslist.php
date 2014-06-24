<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "t_course_studentsinfo.php" ?>
<?php include_once "t_usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$t_course_students_list = NULL; // Initialize page object first

class ct_course_students_list extends ct_course_students {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_course_students';

	// Page object name
	var $PageObjName = 't_course_students_list';

	// Grid form hidden field names
	var $FormName = 'ft_course_studentslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Table object (t_course_students)
		if (!isset($GLOBALS["t_course_students"]) || get_class($GLOBALS["t_course_students"]) == "ct_course_students") {
			$GLOBALS["t_course_students"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_course_students"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "t_course_studentsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "t_course_studentsdelete.php";
		$this->MultiUpdateUrl = "t_course_studentsupdate.php";

		// Table object (t_users)
		if (!isset($GLOBALS['t_users'])) $GLOBALS['t_users'] = new ct_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_course_students', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("login.php");
		}

		// Create form object
		$objForm = new cFormObj();

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;

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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$this->GridUpdate();
						} else {
							$this->setFailureMessage($gsFormError);
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$this->GridInsert();
						} else {
							$this->setFailureMessage($gsFormError);
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("CourseStudentID", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["CourseStudentID"] <> "") {
			$this->CourseStudentID->setQueryStringValue($_GET["CourseStudentID"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("CourseStudentID", $this->CourseStudentID->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("CourseStudentID")) <> strval($this->CourseStudentID->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["CourseStudentID"] <> "") {
				$this->CourseStudentID->setQueryStringValue($_GET["CourseStudentID"]);
				$this->setKey("CourseStudentID", $this->CourseStudentID->CurrentValue); // Set up key
			} else {
				$this->setKey("CourseStudentID", ""); // Clear key
				$this->CurrentAction = "add";
			}
		}
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Perform update to grid
	function GridUpdate() {
		global $conn, $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Begin transaction
		$conn->BeginTrans();

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		$sSql = $this->SQL();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->CourseStudentID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->CourseStudentID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $conn, $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->CourseStudentID->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "gridadd"; // Stay in gridadd mode
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_CourseID") && $objForm->HasValue("o_CourseID") && $this->CourseID->CurrentValue <> $this->CourseID->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_StudentID") && $objForm->HasValue("o_StudentID") && $this->StudentID->CurrentValue <> $this->StudentID->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->CourseID); // CourseID
			$this->UpdateSort($this->StudentID); // StudentID
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->CourseID->setSort("");
				$this->StudentID->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = FALSE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->CourseStudentID->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<span class=\"ewSeparator\">&nbsp;|&nbsp;</span>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			$oListOpt->Body .= "<span class=\"ewSeparator\">&nbsp;|&nbsp;</span>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . $Language->Phrase("InlineCopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->CourseStudentID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->CourseStudentID->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" href=\"\" onclick=\"ew_SubmitSelected(document.ft_course_studentslist, '" . $this->MultiDeleteUrl . "', ewLanguage.Phrase('DeleteMultiConfirmMsg'));return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.ft_course_studentslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
		}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->CourseID->CurrentValue = NULL;
		$this->CourseID->OldValue = $this->CourseID->CurrentValue;
		$this->StudentID->CurrentValue = NULL;
		$this->StudentID->OldValue = $this->StudentID->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->CourseID->FldIsDetailKey) {
			$this->CourseID->setFormValue($objForm->GetValue("x_CourseID"));
		}
		$this->CourseID->setOldValue($objForm->GetValue("o_CourseID"));
		if (!$this->StudentID->FldIsDetailKey) {
			$this->StudentID->setFormValue($objForm->GetValue("x_StudentID"));
		}
		$this->StudentID->setOldValue($objForm->GetValue("o_StudentID"));
		if (!$this->CourseStudentID->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->CourseStudentID->setFormValue($objForm->GetValue("x_CourseStudentID"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->CourseStudentID->CurrentValue = $this->CourseStudentID->FormValue;
		$this->CourseID->CurrentValue = $this->CourseID->FormValue;
		$this->StudentID->CurrentValue = $this->StudentID->FormValue;
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
		$this->CourseStudentID->setDbValue($rs->fields('CourseStudentID'));
		$this->CourseID->setDbValue($rs->fields('CourseID'));
		if (array_key_exists('EV__CourseID', $rs->fields)) {
			$this->CourseID->VirtualValue = $rs->fields('EV__CourseID'); // Set up virtual field value
		} else {
			$this->CourseID->VirtualValue = ""; // Clear value
		}
		$this->StudentID->setDbValue($rs->fields('StudentID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CourseStudentID->DbValue = $row['CourseStudentID'];
		$this->CourseID->DbValue = $row['CourseID'];
		$this->StudentID->DbValue = $row['StudentID'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("CourseStudentID")) <> "")
			$this->CourseStudentID->CurrentValue = $this->getKey("CourseStudentID"); // CourseStudentID
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// CourseStudentID

		$this->CourseStudentID->CellCssStyle = "white-space: nowrap;";

		// CourseID
		// StudentID

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

			// StudentID
			$this->StudentID->ViewValue = $this->StudentID->CurrentValue;
			if (strval($this->StudentID->CurrentValue) <> "") {
				$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->StudentID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `UserID`, `RegistrationNumber` AS `DispFld`, `LastName` AS `Disp2Fld`, `FirstName` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_users`";
			$sWhereWrk = "";
			$lookuptblfilter = "`UserLevel` = '2'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->StudentID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `RegistrationNumber`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->StudentID->ViewValue = $rswrk->fields('DispFld');
					$this->StudentID->ViewValue .= ew_ValueSeparator(1,$this->StudentID) . $rswrk->fields('Disp2Fld');
					$this->StudentID->ViewValue .= ew_ValueSeparator(2,$this->StudentID) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->StudentID->ViewValue = $this->StudentID->CurrentValue;
				}
			} else {
				$this->StudentID->ViewValue = NULL;
			}
			$this->StudentID->ViewCustomAttributes = "";

			// CourseID
			$this->CourseID->LinkCustomAttributes = "";
			$this->CourseID->HrefValue = "";
			$this->CourseID->TooltipValue = "";

			// StudentID
			$this->StudentID->LinkCustomAttributes = "";
			$this->StudentID->HrefValue = "";
			$this->StudentID->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// StudentID
			$this->StudentID->EditCustomAttributes = "";
			$this->StudentID->EditValue = ew_HtmlEncode($this->StudentID->CurrentValue);
			if (strval($this->StudentID->CurrentValue) <> "") {
				$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->StudentID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `UserID`, `RegistrationNumber` AS `DispFld`, `LastName` AS `Disp2Fld`, `FirstName` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_users`";
			$sWhereWrk = "";
			$lookuptblfilter = "`UserLevel` = '2'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->StudentID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `RegistrationNumber`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->StudentID->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->StudentID->EditValue .= ew_ValueSeparator(1,$this->StudentID) . ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->StudentID->EditValue .= ew_ValueSeparator(2,$this->StudentID) . ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$rswrk->Close();
				} else {
					$this->StudentID->EditValue = ew_HtmlEncode($this->StudentID->CurrentValue);
				}
			} else {
				$this->StudentID->EditValue = NULL;
			}
			$this->StudentID->PlaceHolder = ew_RemoveHtml($this->StudentID->FldCaption());

			// Edit refer script
			// CourseID

			$this->CourseID->HrefValue = "";

			// StudentID
			$this->StudentID->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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

			// StudentID
			$this->StudentID->EditCustomAttributes = "";
			$this->StudentID->EditValue = ew_HtmlEncode($this->StudentID->CurrentValue);
			if (strval($this->StudentID->CurrentValue) <> "") {
				$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->StudentID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `UserID`, `RegistrationNumber` AS `DispFld`, `LastName` AS `Disp2Fld`, `FirstName` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_users`";
			$sWhereWrk = "";
			$lookuptblfilter = "`UserLevel` = '2'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->StudentID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `RegistrationNumber`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->StudentID->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->StudentID->EditValue .= ew_ValueSeparator(1,$this->StudentID) . ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->StudentID->EditValue .= ew_ValueSeparator(2,$this->StudentID) . ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$rswrk->Close();
				} else {
					$this->StudentID->EditValue = ew_HtmlEncode($this->StudentID->CurrentValue);
				}
			} else {
				$this->StudentID->EditValue = NULL;
			}
			$this->StudentID->PlaceHolder = ew_RemoveHtml($this->StudentID->FldCaption());

			// Edit refer script
			// CourseID

			$this->CourseID->HrefValue = "";

			// StudentID
			$this->StudentID->HrefValue = "";
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
		if (!$this->StudentID->FldIsDetailKey && !is_null($this->StudentID->FormValue) && $this->StudentID->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->StudentID->FldCaption());
		}
		if (!ew_CheckInteger($this->StudentID->FormValue)) {
			ew_AddMessage($gsFormError, $this->StudentID->FldErrMsg());
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
				$sThisKey .= $row['CourseStudentID'];
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
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

			// StudentID
			$this->StudentID->SetDbValueDef($rsnew, $this->StudentID->CurrentValue, NULL, $this->StudentID->ReadOnly);

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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// CourseID
		$this->CourseID->SetDbValueDef($rsnew, $this->CourseID->CurrentValue, NULL, FALSE);

		// StudentID
		$this->StudentID->SetDbValueDef($rsnew, $this->StudentID->CurrentValue, NULL, FALSE);

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
			$this->CourseStudentID->setDbValue($conn->Insert_ID());
			$rsnew['CourseStudentID'] = $this->CourseStudentID->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
		$item->Body = "<a id=\"emf_t_course_students\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_t_course_students',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.ft_course_studentslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "");
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
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_course_students_list)) $t_course_students_list = new ct_course_students_list();

// Page init
$t_course_students_list->Page_Init();

// Page main
$t_course_students_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_course_students_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($t_course_students->Export == "") { ?>
<script type="text/javascript">

// Page object
var t_course_students_list = new ew_Page("t_course_students_list");
t_course_students_list.PageID = "list"; // Page ID
var EW_PAGE_ID = t_course_students_list.PageID; // For backward compatibility

// Form object
var ft_course_studentslist = new ew_Form("ft_course_studentslist");
ft_course_studentslist.FormKeyCountName = '<?php echo $t_course_students_list->FormKeyCountName ?>';

// Validate form
ft_course_studentslist.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_CourseID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_course_students->CourseID->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_StudentID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_course_students->StudentID->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_StudentID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_course_students->StudentID->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
ft_course_studentslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "CourseID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "StudentID", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_course_studentslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_course_studentslist.ValidateRequired = true;
<?php } else { ?>
ft_course_studentslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_course_studentslist.Lists["x_CourseID"] = {"LinkField":"x_CourseID","Ajax":true,"AutoFill":false,"DisplayFields":["x_CourseCode","x_Course","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_course_studentslist.Lists["x_StudentID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_RegistrationNumber","x_LastName","x_FirstName",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($t_course_students->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($t_course_students_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $t_course_students_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($t_course_students->CurrentAction == "gridadd") {
	$t_course_students->CurrentFilter = "0=1";
	$t_course_students_list->StartRec = 1;
	$t_course_students_list->DisplayRecs = $t_course_students->GridAddRowCount;
	$t_course_students_list->TotalRecs = $t_course_students_list->DisplayRecs;
	$t_course_students_list->StopRec = $t_course_students_list->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$t_course_students_list->TotalRecs = $t_course_students->SelectRecordCount();
	} else {
		if ($t_course_students_list->Recordset = $t_course_students_list->LoadRecordset())
			$t_course_students_list->TotalRecs = $t_course_students_list->Recordset->RecordCount();
	}
	$t_course_students_list->StartRec = 1;
	if ($t_course_students_list->DisplayRecs <= 0 || ($t_course_students->Export <> "" && $t_course_students->ExportAll)) // Display all records
		$t_course_students_list->DisplayRecs = $t_course_students_list->TotalRecs;
	if (!($t_course_students->Export <> "" && $t_course_students->ExportAll))
		$t_course_students_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$t_course_students_list->Recordset = $t_course_students_list->LoadRecordset($t_course_students_list->StartRec-1, $t_course_students_list->DisplayRecs);
}
$t_course_students_list->RenderOtherOptions();
?>
<?php $t_course_students_list->ShowPageHeader(); ?>
<?php
$t_course_students_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<?php if ($t_course_students->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($t_course_students->CurrentAction <> "gridadd" && $t_course_students->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_course_students_list->Pager)) $t_course_students_list->Pager = new cPrevNextPager($t_course_students_list->StartRec, $t_course_students_list->DisplayRecs, $t_course_students_list->TotalRecs) ?>
<?php if ($t_course_students_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_course_students_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_students_list->PageUrl() ?>start=<?php echo $t_course_students_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_course_students_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_students_list->PageUrl() ?>start=<?php echo $t_course_students_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_course_students_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_course_students_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_students_list->PageUrl() ?>start=<?php echo $t_course_students_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_course_students_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_students_list->PageUrl() ?>start=<?php echo $t_course_students_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_course_students_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_course_students_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_course_students_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_course_students_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($t_course_students_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoPermission") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_course_students_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="ft_course_studentslist" id="ft_course_studentslist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="t_course_students">
<div id="gmp_t_course_students" class="ewGridMiddlePanel">
<?php if ($t_course_students_list->TotalRecs > 0 || $t_course_students->CurrentAction == "add" || $t_course_students->CurrentAction == "copy") { ?>
<table id="tbl_t_course_studentslist" class="ewTable ewTableSeparate">
<?php echo $t_course_students->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$t_course_students_list->RenderListOptions();

// Render list options (header, left)
$t_course_students_list->ListOptions->Render("header", "left");
?>
<?php if ($t_course_students->CourseID->Visible) { // CourseID ?>
	<?php if ($t_course_students->SortUrl($t_course_students->CourseID) == "") { ?>
		<td><div id="elh_t_course_students_CourseID" class="t_course_students_CourseID"><div class="ewTableHeaderCaption"><?php echo $t_course_students->CourseID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_course_students->SortUrl($t_course_students->CourseID) ?>',1);"><div id="elh_t_course_students_CourseID" class="t_course_students_CourseID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_course_students->CourseID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_course_students->CourseID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_course_students->CourseID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_course_students->StudentID->Visible) { // StudentID ?>
	<?php if ($t_course_students->SortUrl($t_course_students->StudentID) == "") { ?>
		<td><div id="elh_t_course_students_StudentID" class="t_course_students_StudentID"><div class="ewTableHeaderCaption"><?php echo $t_course_students->StudentID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_course_students->SortUrl($t_course_students->StudentID) ?>',1);"><div id="elh_t_course_students_StudentID" class="t_course_students_StudentID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_course_students->StudentID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_course_students->StudentID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_course_students->StudentID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_course_students_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($t_course_students->CurrentAction == "add" || $t_course_students->CurrentAction == "copy") {
		$t_course_students_list->RowIndex = 0;
		$t_course_students_list->KeyCount = $t_course_students_list->RowIndex;
		if ($t_course_students->CurrentAction == "copy" && !$t_course_students_list->LoadRow())
				$t_course_students->CurrentAction = "add";
		if ($t_course_students->CurrentAction == "add")
			$t_course_students_list->LoadDefaultValues();
		if ($t_course_students->EventCancelled) // Insert failed
			$t_course_students_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$t_course_students->ResetAttrs();
		$t_course_students->RowAttrs = array_merge($t_course_students->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_t_course_students', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$t_course_students->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_course_students_list->RenderRow();

		// Render list options
		$t_course_students_list->RenderListOptions();
		$t_course_students_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_course_students->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_course_students_list->ListOptions->Render("body", "left", $t_course_students_list->RowCnt);
?>
	<?php if ($t_course_students->CourseID->Visible) { // CourseID ?>
		<td>
<span id="el<?php echo $t_course_students_list->RowCnt ?>_t_course_students_CourseID" class="control-group t_course_students_CourseID">
<select data-field="x_CourseID" id="x<?php echo $t_course_students_list->RowIndex ?>_CourseID" name="x<?php echo $t_course_students_list->RowIndex ?>_CourseID"<?php echo $t_course_students->CourseID->EditAttributes() ?>>
<?php
if (is_array($t_course_students->CourseID->EditValue)) {
	$arwrk = $t_course_students->CourseID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_course_students->CourseID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$t_course_students->CourseID) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_course_students->CourseID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_courses`";
$sWhereWrk = "";

// Call Lookup selecting
$t_course_students->Lookup_Selecting($t_course_students->CourseID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `CourseCode`";
?>
<input type="hidden" name="s_x<?php echo $t_course_students_list->RowIndex ?>_CourseID" id="s_x<?php echo $t_course_students_list->RowIndex ?>_CourseID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`CourseID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_CourseID" name="o<?php echo $t_course_students_list->RowIndex ?>_CourseID" id="o<?php echo $t_course_students_list->RowIndex ?>_CourseID" value="<?php echo ew_HtmlEncode($t_course_students->CourseID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_course_students->StudentID->Visible) { // StudentID ?>
		<td>
<span id="el<?php echo $t_course_students_list->RowCnt ?>_t_course_students_StudentID" class="control-group t_course_students_StudentID">
<?php
	$wrkonchange = trim(" " . @$t_course_students->StudentID->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$t_course_students->StudentID->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" style="white-space: nowrap; z-index: <?php echo (9000 - $t_course_students_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="sv_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo $t_course_students->StudentID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($t_course_students->StudentID->PlaceHolder) ?>"<?php echo $t_course_students->StudentID->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" style="display: inline; z-index: <?php echo (9000 - $t_course_students_list->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_StudentID" name="x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo ew_HtmlEncode($t_course_students->StudentID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `UserID`, `RegistrationNumber` AS `DispFld`, `LastName` AS `Disp2Fld`, `FirstName` AS `Disp3Fld` FROM `t_users`";
$sWhereWrk = "`RegistrationNumber` LIKE '{query_value}%' OR CONCAT(`RegistrationNumber`,'" . ew_ValueSeparator(1, $Page->StudentID) . "',`LastName`,'" . ew_ValueSeparator(2, $Page->StudentID) . "',`FirstName`) LIKE '{query_value}%'";
$lookuptblfilter = "`UserLevel` = '2'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$t_course_students->Lookup_Selecting($t_course_students->StudentID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `RegistrationNumber`";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="q_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $t_course_students_list->RowIndex ?>_StudentID", ft_course_studentslist, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $t_course_students_list->RowIndex ?>_StudentID") + ar[i] : "";
	return dv;
}
ft_course_studentslist.AutoSuggests["x<?php echo $t_course_students_list->RowIndex ?>_StudentID"] = oas;
</script>
</span>
<input type="hidden" data-field="x_StudentID" name="o<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="o<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo ew_HtmlEncode($t_course_students->StudentID->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_course_students_list->ListOptions->Render("body", "right", $t_course_students_list->RowCnt);
?>
<script type="text/javascript">
ft_course_studentslist.UpdateOpts(<?php echo $t_course_students_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($t_course_students->ExportAll && $t_course_students->Export <> "") {
	$t_course_students_list->StopRec = $t_course_students_list->TotalRecs;
} else {

	// Set the last record to display
	if ($t_course_students_list->TotalRecs > $t_course_students_list->StartRec + $t_course_students_list->DisplayRecs - 1)
		$t_course_students_list->StopRec = $t_course_students_list->StartRec + $t_course_students_list->DisplayRecs - 1;
	else
		$t_course_students_list->StopRec = $t_course_students_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_course_students_list->FormKeyCountName) && ($t_course_students->CurrentAction == "gridadd" || $t_course_students->CurrentAction == "gridedit" || $t_course_students->CurrentAction == "F")) {
		$t_course_students_list->KeyCount = $objForm->GetValue($t_course_students_list->FormKeyCountName);
		$t_course_students_list->StopRec = $t_course_students_list->StartRec + $t_course_students_list->KeyCount - 1;
	}
}
$t_course_students_list->RecCnt = $t_course_students_list->StartRec - 1;
if ($t_course_students_list->Recordset && !$t_course_students_list->Recordset->EOF) {
	$t_course_students_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $t_course_students_list->StartRec > 1)
		$t_course_students_list->Recordset->Move($t_course_students_list->StartRec - 1);
} elseif (!$t_course_students->AllowAddDeleteRow && $t_course_students_list->StopRec == 0) {
	$t_course_students_list->StopRec = $t_course_students->GridAddRowCount;
}

// Initialize aggregate
$t_course_students->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_course_students->ResetAttrs();
$t_course_students_list->RenderRow();
$t_course_students_list->EditRowCnt = 0;
if ($t_course_students->CurrentAction == "edit")
	$t_course_students_list->RowIndex = 1;
if ($t_course_students->CurrentAction == "gridadd")
	$t_course_students_list->RowIndex = 0;
if ($t_course_students->CurrentAction == "gridedit")
	$t_course_students_list->RowIndex = 0;
while ($t_course_students_list->RecCnt < $t_course_students_list->StopRec) {
	$t_course_students_list->RecCnt++;
	if (intval($t_course_students_list->RecCnt) >= intval($t_course_students_list->StartRec)) {
		$t_course_students_list->RowCnt++;
		if ($t_course_students->CurrentAction == "gridadd" || $t_course_students->CurrentAction == "gridedit" || $t_course_students->CurrentAction == "F") {
			$t_course_students_list->RowIndex++;
			$objForm->Index = $t_course_students_list->RowIndex;
			if ($objForm->HasValue($t_course_students_list->FormActionName))
				$t_course_students_list->RowAction = strval($objForm->GetValue($t_course_students_list->FormActionName));
			elseif ($t_course_students->CurrentAction == "gridadd")
				$t_course_students_list->RowAction = "insert";
			else
				$t_course_students_list->RowAction = "";
		}

		// Set up key count
		$t_course_students_list->KeyCount = $t_course_students_list->RowIndex;

		// Init row class and style
		$t_course_students->ResetAttrs();
		$t_course_students->CssClass = "";
		if ($t_course_students->CurrentAction == "gridadd") {
			$t_course_students_list->LoadDefaultValues(); // Load default values
		} else {
			$t_course_students_list->LoadRowValues($t_course_students_list->Recordset); // Load row values
		}
		$t_course_students->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_course_students->CurrentAction == "gridadd") // Grid add
			$t_course_students->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_course_students->CurrentAction == "gridadd" && $t_course_students->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_course_students_list->RestoreCurrentRowFormValues($t_course_students_list->RowIndex); // Restore form values
		if ($t_course_students->CurrentAction == "edit") {
			if ($t_course_students_list->CheckInlineEditKey() && $t_course_students_list->EditRowCnt == 0) { // Inline edit
				$t_course_students->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($t_course_students->CurrentAction == "gridedit") { // Grid edit
			if ($t_course_students->EventCancelled) {
				$t_course_students_list->RestoreCurrentRowFormValues($t_course_students_list->RowIndex); // Restore form values
			}
			if ($t_course_students_list->RowAction == "insert")
				$t_course_students->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_course_students->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_course_students->CurrentAction == "edit" && $t_course_students->RowType == EW_ROWTYPE_EDIT && $t_course_students->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$t_course_students_list->RestoreFormValues(); // Restore form values
		}
		if ($t_course_students->CurrentAction == "gridedit" && ($t_course_students->RowType == EW_ROWTYPE_EDIT || $t_course_students->RowType == EW_ROWTYPE_ADD) && $t_course_students->EventCancelled) // Update failed
			$t_course_students_list->RestoreCurrentRowFormValues($t_course_students_list->RowIndex); // Restore form values
		if ($t_course_students->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_course_students_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$t_course_students->RowAttrs = array_merge($t_course_students->RowAttrs, array('data-rowindex'=>$t_course_students_list->RowCnt, 'id'=>'r' . $t_course_students_list->RowCnt . '_t_course_students', 'data-rowtype'=>$t_course_students->RowType));

		// Render row
		$t_course_students_list->RenderRow();

		// Render list options
		$t_course_students_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_course_students_list->RowAction <> "delete" && $t_course_students_list->RowAction <> "insertdelete" && !($t_course_students_list->RowAction == "insert" && $t_course_students->CurrentAction == "F" && $t_course_students_list->EmptyRow())) {
?>
	<tr<?php echo $t_course_students->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_course_students_list->ListOptions->Render("body", "left", $t_course_students_list->RowCnt);
?>
	<?php if ($t_course_students->CourseID->Visible) { // CourseID ?>
		<td<?php echo $t_course_students->CourseID->CellAttributes() ?>>
<?php if ($t_course_students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_course_students_list->RowCnt ?>_t_course_students_CourseID" class="control-group t_course_students_CourseID">
<select data-field="x_CourseID" id="x<?php echo $t_course_students_list->RowIndex ?>_CourseID" name="x<?php echo $t_course_students_list->RowIndex ?>_CourseID"<?php echo $t_course_students->CourseID->EditAttributes() ?>>
<?php
if (is_array($t_course_students->CourseID->EditValue)) {
	$arwrk = $t_course_students->CourseID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_course_students->CourseID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$t_course_students->CourseID) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_course_students->CourseID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_courses`";
$sWhereWrk = "";

// Call Lookup selecting
$t_course_students->Lookup_Selecting($t_course_students->CourseID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `CourseCode`";
?>
<input type="hidden" name="s_x<?php echo $t_course_students_list->RowIndex ?>_CourseID" id="s_x<?php echo $t_course_students_list->RowIndex ?>_CourseID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`CourseID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_CourseID" name="o<?php echo $t_course_students_list->RowIndex ?>_CourseID" id="o<?php echo $t_course_students_list->RowIndex ?>_CourseID" value="<?php echo ew_HtmlEncode($t_course_students->CourseID->OldValue) ?>">
<?php } ?>
<?php if ($t_course_students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_course_students_list->RowCnt ?>_t_course_students_CourseID" class="control-group t_course_students_CourseID">
<select data-field="x_CourseID" id="x<?php echo $t_course_students_list->RowIndex ?>_CourseID" name="x<?php echo $t_course_students_list->RowIndex ?>_CourseID"<?php echo $t_course_students->CourseID->EditAttributes() ?>>
<?php
if (is_array($t_course_students->CourseID->EditValue)) {
	$arwrk = $t_course_students->CourseID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_course_students->CourseID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$t_course_students->CourseID) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_course_students->CourseID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_courses`";
$sWhereWrk = "";

// Call Lookup selecting
$t_course_students->Lookup_Selecting($t_course_students->CourseID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `CourseCode`";
?>
<input type="hidden" name="s_x<?php echo $t_course_students_list->RowIndex ?>_CourseID" id="s_x<?php echo $t_course_students_list->RowIndex ?>_CourseID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`CourseID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($t_course_students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_course_students->CourseID->ViewAttributes() ?>>
<?php echo $t_course_students->CourseID->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $t_course_students_list->PageObjName . "_row_" . $t_course_students_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_course_students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_CourseStudentID" name="x<?php echo $t_course_students_list->RowIndex ?>_CourseStudentID" id="x<?php echo $t_course_students_list->RowIndex ?>_CourseStudentID" value="<?php echo ew_HtmlEncode($t_course_students->CourseStudentID->CurrentValue) ?>">
<input type="hidden" data-field="x_CourseStudentID" name="o<?php echo $t_course_students_list->RowIndex ?>_CourseStudentID" id="o<?php echo $t_course_students_list->RowIndex ?>_CourseStudentID" value="<?php echo ew_HtmlEncode($t_course_students->CourseStudentID->OldValue) ?>">
<?php } ?>
<?php if ($t_course_students->RowType == EW_ROWTYPE_EDIT || $t_course_students->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_CourseStudentID" name="x<?php echo $t_course_students_list->RowIndex ?>_CourseStudentID" id="x<?php echo $t_course_students_list->RowIndex ?>_CourseStudentID" value="<?php echo ew_HtmlEncode($t_course_students->CourseStudentID->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_course_students->StudentID->Visible) { // StudentID ?>
		<td<?php echo $t_course_students->StudentID->CellAttributes() ?>>
<?php if ($t_course_students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_course_students_list->RowCnt ?>_t_course_students_StudentID" class="control-group t_course_students_StudentID">
<?php
	$wrkonchange = trim(" " . @$t_course_students->StudentID->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$t_course_students->StudentID->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" style="white-space: nowrap; z-index: <?php echo (9000 - $t_course_students_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="sv_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo $t_course_students->StudentID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($t_course_students->StudentID->PlaceHolder) ?>"<?php echo $t_course_students->StudentID->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" style="display: inline; z-index: <?php echo (9000 - $t_course_students_list->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_StudentID" name="x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo ew_HtmlEncode($t_course_students->StudentID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `UserID`, `RegistrationNumber` AS `DispFld`, `LastName` AS `Disp2Fld`, `FirstName` AS `Disp3Fld` FROM `t_users`";
$sWhereWrk = "`RegistrationNumber` LIKE '{query_value}%' OR CONCAT(`RegistrationNumber`,'" . ew_ValueSeparator(1, $Page->StudentID) . "',`LastName`,'" . ew_ValueSeparator(2, $Page->StudentID) . "',`FirstName`) LIKE '{query_value}%'";
$lookuptblfilter = "`UserLevel` = '2'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$t_course_students->Lookup_Selecting($t_course_students->StudentID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `RegistrationNumber`";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="q_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $t_course_students_list->RowIndex ?>_StudentID", ft_course_studentslist, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $t_course_students_list->RowIndex ?>_StudentID") + ar[i] : "";
	return dv;
}
ft_course_studentslist.AutoSuggests["x<?php echo $t_course_students_list->RowIndex ?>_StudentID"] = oas;
</script>
</span>
<input type="hidden" data-field="x_StudentID" name="o<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="o<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo ew_HtmlEncode($t_course_students->StudentID->OldValue) ?>">
<?php } ?>
<?php if ($t_course_students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_course_students_list->RowCnt ?>_t_course_students_StudentID" class="control-group t_course_students_StudentID">
<?php
	$wrkonchange = trim(" " . @$t_course_students->StudentID->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$t_course_students->StudentID->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" style="white-space: nowrap; z-index: <?php echo (9000 - $t_course_students_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="sv_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo $t_course_students->StudentID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($t_course_students->StudentID->PlaceHolder) ?>"<?php echo $t_course_students->StudentID->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" style="display: inline; z-index: <?php echo (9000 - $t_course_students_list->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_StudentID" name="x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo ew_HtmlEncode($t_course_students->StudentID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `UserID`, `RegistrationNumber` AS `DispFld`, `LastName` AS `Disp2Fld`, `FirstName` AS `Disp3Fld` FROM `t_users`";
$sWhereWrk = "`RegistrationNumber` LIKE '{query_value}%' OR CONCAT(`RegistrationNumber`,'" . ew_ValueSeparator(1, $Page->StudentID) . "',`LastName`,'" . ew_ValueSeparator(2, $Page->StudentID) . "',`FirstName`) LIKE '{query_value}%'";
$lookuptblfilter = "`UserLevel` = '2'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$t_course_students->Lookup_Selecting($t_course_students->StudentID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `RegistrationNumber`";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="q_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $t_course_students_list->RowIndex ?>_StudentID", ft_course_studentslist, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $t_course_students_list->RowIndex ?>_StudentID") + ar[i] : "";
	return dv;
}
ft_course_studentslist.AutoSuggests["x<?php echo $t_course_students_list->RowIndex ?>_StudentID"] = oas;
</script>
</span>
<?php } ?>
<?php if ($t_course_students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_course_students->StudentID->ViewAttributes() ?>>
<?php echo $t_course_students->StudentID->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_course_students_list->ListOptions->Render("body", "right", $t_course_students_list->RowCnt);
?>
	</tr>
<?php if ($t_course_students->RowType == EW_ROWTYPE_ADD || $t_course_students->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_course_studentslist.UpdateOpts(<?php echo $t_course_students_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_course_students->CurrentAction <> "gridadd")
		if (!$t_course_students_list->Recordset->EOF) $t_course_students_list->Recordset->MoveNext();
}
?>
<?php
	if ($t_course_students->CurrentAction == "gridadd" || $t_course_students->CurrentAction == "gridedit") {
		$t_course_students_list->RowIndex = '$rowindex$';
		$t_course_students_list->LoadDefaultValues();

		// Set row properties
		$t_course_students->ResetAttrs();
		$t_course_students->RowAttrs = array_merge($t_course_students->RowAttrs, array('data-rowindex'=>$t_course_students_list->RowIndex, 'id'=>'r0_t_course_students', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_course_students->RowAttrs["class"], "ewTemplate");
		$t_course_students->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_course_students_list->RenderRow();

		// Render list options
		$t_course_students_list->RenderListOptions();
		$t_course_students_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_course_students->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_course_students_list->ListOptions->Render("body", "left", $t_course_students_list->RowIndex);
?>
	<?php if ($t_course_students->CourseID->Visible) { // CourseID ?>
		<td>
<span id="el$rowindex$_t_course_students_CourseID" class="control-group t_course_students_CourseID">
<select data-field="x_CourseID" id="x<?php echo $t_course_students_list->RowIndex ?>_CourseID" name="x<?php echo $t_course_students_list->RowIndex ?>_CourseID"<?php echo $t_course_students->CourseID->EditAttributes() ?>>
<?php
if (is_array($t_course_students->CourseID->EditValue)) {
	$arwrk = $t_course_students->CourseID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_course_students->CourseID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$t_course_students->CourseID) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_course_students->CourseID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_courses`";
$sWhereWrk = "";

// Call Lookup selecting
$t_course_students->Lookup_Selecting($t_course_students->CourseID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `CourseCode`";
?>
<input type="hidden" name="s_x<?php echo $t_course_students_list->RowIndex ?>_CourseID" id="s_x<?php echo $t_course_students_list->RowIndex ?>_CourseID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`CourseID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_CourseID" name="o<?php echo $t_course_students_list->RowIndex ?>_CourseID" id="o<?php echo $t_course_students_list->RowIndex ?>_CourseID" value="<?php echo ew_HtmlEncode($t_course_students->CourseID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_course_students->StudentID->Visible) { // StudentID ?>
		<td>
<span id="el$rowindex$_t_course_students_StudentID" class="control-group t_course_students_StudentID">
<?php
	$wrkonchange = trim(" " . @$t_course_students->StudentID->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$t_course_students->StudentID->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" style="white-space: nowrap; z-index: <?php echo (9000 - $t_course_students_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="sv_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo $t_course_students->StudentID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($t_course_students->StudentID->PlaceHolder) ?>"<?php echo $t_course_students->StudentID->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" style="display: inline; z-index: <?php echo (9000 - $t_course_students_list->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_StudentID" name="x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo ew_HtmlEncode($t_course_students->StudentID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `UserID`, `RegistrationNumber` AS `DispFld`, `LastName` AS `Disp2Fld`, `FirstName` AS `Disp3Fld` FROM `t_users`";
$sWhereWrk = "`RegistrationNumber` LIKE '{query_value}%' OR CONCAT(`RegistrationNumber`,'" . ew_ValueSeparator(1, $Page->StudentID) . "',`LastName`,'" . ew_ValueSeparator(2, $Page->StudentID) . "',`FirstName`) LIKE '{query_value}%'";
$lookuptblfilter = "`UserLevel` = '2'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$t_course_students->Lookup_Selecting($t_course_students->StudentID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `RegistrationNumber`";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="q_x<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $t_course_students_list->RowIndex ?>_StudentID", ft_course_studentslist, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $t_course_students_list->RowIndex ?>_StudentID") + ar[i] : "";
	return dv;
}
ft_course_studentslist.AutoSuggests["x<?php echo $t_course_students_list->RowIndex ?>_StudentID"] = oas;
</script>
</span>
<input type="hidden" data-field="x_StudentID" name="o<?php echo $t_course_students_list->RowIndex ?>_StudentID" id="o<?php echo $t_course_students_list->RowIndex ?>_StudentID" value="<?php echo ew_HtmlEncode($t_course_students->StudentID->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_course_students_list->ListOptions->Render("body", "right", $t_course_students_list->RowCnt);
?>
<script type="text/javascript">
ft_course_studentslist.UpdateOpts(<?php echo $t_course_students_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($t_course_students->CurrentAction == "add" || $t_course_students->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $t_course_students_list->FormKeyCountName ?>" id="<?php echo $t_course_students_list->FormKeyCountName ?>" value="<?php echo $t_course_students_list->KeyCount ?>">
<?php } ?>
<?php if ($t_course_students->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_course_students_list->FormKeyCountName ?>" id="<?php echo $t_course_students_list->FormKeyCountName ?>" value="<?php echo $t_course_students_list->KeyCount ?>">
<?php echo $t_course_students_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_course_students->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $t_course_students_list->FormKeyCountName ?>" id="<?php echo $t_course_students_list->FormKeyCountName ?>" value="<?php echo $t_course_students_list->KeyCount ?>">
<?php } ?>
<?php if ($t_course_students->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_course_students_list->FormKeyCountName ?>" id="<?php echo $t_course_students_list->FormKeyCountName ?>" value="<?php echo $t_course_students_list->KeyCount ?>">
<?php echo $t_course_students_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_course_students->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($t_course_students_list->Recordset)
	$t_course_students_list->Recordset->Close();
?>
<?php if ($t_course_students_list->TotalRecs > 0) { ?>
<?php if ($t_course_students->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($t_course_students->CurrentAction <> "gridadd" && $t_course_students->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_course_students_list->Pager)) $t_course_students_list->Pager = new cPrevNextPager($t_course_students_list->StartRec, $t_course_students_list->DisplayRecs, $t_course_students_list->TotalRecs) ?>
<?php if ($t_course_students_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_course_students_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_students_list->PageUrl() ?>start=<?php echo $t_course_students_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_course_students_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_students_list->PageUrl() ?>start=<?php echo $t_course_students_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_course_students_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_course_students_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_students_list->PageUrl() ?>start=<?php echo $t_course_students_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_course_students_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_course_students_list->PageUrl() ?>start=<?php echo $t_course_students_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_course_students_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_course_students_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_course_students_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_course_students_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($t_course_students_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoPermission") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_course_students_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($t_course_students->Export == "") { ?>
<script type="text/javascript">
ft_course_studentslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$t_course_students_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($t_course_students->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$t_course_students_list->Page_Terminate();
?>
