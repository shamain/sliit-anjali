<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "t_coursesinfo.php" ?>
<?php include_once "t_usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$t_courses_list = NULL; // Initialize page object first

class ct_courses_list extends ct_courses {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_courses';

	// Page object name
	var $PageObjName = 't_courses_list';

	// Grid form hidden field names
	var $FormName = 'ft_courseslist';
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

		// Table object (t_courses)
		if (!isset($GLOBALS["t_courses"]) || get_class($GLOBALS["t_courses"]) == "ct_courses") {
			$GLOBALS["t_courses"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_courses"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "t_coursesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "t_coursesdelete.php";
		$this->MultiUpdateUrl = "t_coursesupdate.php";

		// Table object (t_users)
		if (!isset($GLOBALS['t_users'])) $GLOBALS['t_users'] = new ct_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_courses', TRUE);

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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

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
		$this->setKey("CourseID", ""); // Clear inline edit key
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
		if (@$_GET["CourseID"] <> "") {
			$this->CourseID->setQueryStringValue($_GET["CourseID"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("CourseID", $this->CourseID->CurrentValue); // Set up inline edit key
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
		if (strval($this->getKey("CourseID")) <> strval($this->CourseID->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["CourseID"] <> "") {
				$this->CourseID->setQueryStringValue($_GET["CourseID"]);
				$this->setKey("CourseID", $this->CourseID->CurrentValue); // Set up key
			} else {
				$this->setKey("CourseID", ""); // Clear key
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
			$this->CourseID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->CourseID->FormValue))
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
					$sKey .= $this->CourseID->CurrentValue;

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
		if ($objForm->HasValue("x_SubjectID") && $objForm->HasValue("o_SubjectID") && $this->SubjectID->CurrentValue <> $this->SubjectID->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Course") && $objForm->HasValue("o_Course") && $this->Course->CurrentValue <> $this->Course->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_CourseCode") && $objForm->HasValue("o_CourseCode") && $this->CourseCode->CurrentValue <> $this->CourseCode->OldValue)
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

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->SubjectID, FALSE); // SubjectID
		$this->BuildSearchSql($sWhere, $this->Course, FALSE); // Course
		$this->BuildSearchSql($sWhere, $this->CourseCode, FALSE); // CourseCode

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->SubjectID->AdvancedSearch->Save(); // SubjectID
			$this->Course->AdvancedSearch->Save(); // Course
			$this->CourseCode->AdvancedSearch->Save(); // CourseCode
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->Course, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->CourseCode, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->SubjectID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Course->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CourseCode->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->SubjectID->AdvancedSearch->UnsetSession();
		$this->Course->AdvancedSearch->UnsetSession();
		$this->CourseCode->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->SubjectID->AdvancedSearch->Load();
		$this->Course->AdvancedSearch->Load();
		$this->CourseCode->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->SubjectID); // SubjectID
			$this->UpdateSort($this->Course); // Course
			$this->UpdateSort($this->CourseCode); // CourseCode
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

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->SubjectID->setSort("");
				$this->Course->setSort("");
				$this->CourseCode->setSort("");
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
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->CourseID->CurrentValue) . "\">";
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
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->CourseID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->CourseID->CurrentValue . "\">";
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" href=\"\" onclick=\"ew_SubmitSelected(document.ft_courseslist, '" . $this->MultiDeleteUrl . "', ewLanguage.Phrase('DeleteMultiConfirmMsg'));return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.ft_courseslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->SubjectID->CurrentValue = NULL;
		$this->SubjectID->OldValue = $this->SubjectID->CurrentValue;
		$this->Course->CurrentValue = NULL;
		$this->Course->OldValue = $this->Course->CurrentValue;
		$this->CourseCode->CurrentValue = NULL;
		$this->CourseCode->OldValue = $this->CourseCode->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// SubjectID

		$this->SubjectID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_SubjectID"]);
		if ($this->SubjectID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->SubjectID->AdvancedSearch->SearchOperator = @$_GET["z_SubjectID"];

		// Course
		$this->Course->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Course"]);
		if ($this->Course->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Course->AdvancedSearch->SearchOperator = @$_GET["z_Course"];

		// CourseCode
		$this->CourseCode->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CourseCode"]);
		if ($this->CourseCode->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CourseCode->AdvancedSearch->SearchOperator = @$_GET["z_CourseCode"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->SubjectID->FldIsDetailKey) {
			$this->SubjectID->setFormValue($objForm->GetValue("x_SubjectID"));
		}
		$this->SubjectID->setOldValue($objForm->GetValue("o_SubjectID"));
		if (!$this->Course->FldIsDetailKey) {
			$this->Course->setFormValue($objForm->GetValue("x_Course"));
		}
		$this->Course->setOldValue($objForm->GetValue("o_Course"));
		if (!$this->CourseCode->FldIsDetailKey) {
			$this->CourseCode->setFormValue($objForm->GetValue("x_CourseCode"));
		}
		$this->CourseCode->setOldValue($objForm->GetValue("o_CourseCode"));
		if (!$this->CourseID->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->CourseID->setFormValue($objForm->GetValue("x_CourseID"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->CourseID->CurrentValue = $this->CourseID->FormValue;
		$this->SubjectID->CurrentValue = $this->SubjectID->FormValue;
		$this->Course->CurrentValue = $this->Course->FormValue;
		$this->CourseCode->CurrentValue = $this->CourseCode->FormValue;
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
		$this->CourseID->setDbValue($rs->fields('CourseID'));
		$this->SubjectID->setDbValue($rs->fields('SubjectID'));
		if (array_key_exists('EV__SubjectID', $rs->fields)) {
			$this->SubjectID->VirtualValue = $rs->fields('EV__SubjectID'); // Set up virtual field value
		} else {
			$this->SubjectID->VirtualValue = ""; // Clear value
		}
		$this->Course->setDbValue($rs->fields('Course'));
		$this->CourseCode->setDbValue($rs->fields('CourseCode'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CourseID->DbValue = $row['CourseID'];
		$this->SubjectID->DbValue = $row['SubjectID'];
		$this->Course->DbValue = $row['Course'];
		$this->CourseCode->DbValue = $row['CourseCode'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("CourseID")) <> "")
			$this->CourseID->CurrentValue = $this->getKey("CourseID"); // CourseID
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
		// CourseID

		$this->CourseID->CellCssStyle = "white-space: nowrap;";

		// SubjectID
		// Course
		// CourseCode

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// SubjectID
			if ($this->SubjectID->VirtualValue <> "") {
				$this->SubjectID->ViewValue = $this->SubjectID->VirtualValue;
			} else {
			if (strval($this->SubjectID->CurrentValue) <> "") {
				$sFilterWrk = "`SubjectID`" . ew_SearchString("=", $this->SubjectID->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `SubjectID`, `Subject` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_subjects`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->SubjectID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Subject`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->SubjectID->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->SubjectID->ViewValue = $this->SubjectID->CurrentValue;
				}
			} else {
				$this->SubjectID->ViewValue = NULL;
			}
			}
			$this->SubjectID->ViewCustomAttributes = "";

			// Course
			$this->Course->ViewValue = $this->Course->CurrentValue;
			$this->Course->ViewCustomAttributes = "";

			// CourseCode
			$this->CourseCode->ViewValue = $this->CourseCode->CurrentValue;
			$this->CourseCode->ViewCustomAttributes = "";

			// SubjectID
			$this->SubjectID->LinkCustomAttributes = "";
			$this->SubjectID->HrefValue = "";
			$this->SubjectID->TooltipValue = "";

			// Course
			$this->Course->LinkCustomAttributes = "";
			$this->Course->HrefValue = "";
			$this->Course->TooltipValue = "";

			// CourseCode
			$this->CourseCode->LinkCustomAttributes = "";
			$this->CourseCode->HrefValue = "";
			$this->CourseCode->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// SubjectID
			$this->SubjectID->EditCustomAttributes = "";
			if (trim(strval($this->SubjectID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`SubjectID`" . ew_SearchString("=", $this->SubjectID->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `SubjectID`, `Subject` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_subjects`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->SubjectID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Subject`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->SubjectID->EditValue = $arwrk;

			// Course
			$this->Course->EditCustomAttributes = "";
			$this->Course->EditValue = ew_HtmlEncode($this->Course->CurrentValue);
			$this->Course->PlaceHolder = ew_RemoveHtml($this->Course->FldCaption());

			// CourseCode
			$this->CourseCode->EditCustomAttributes = "";
			$this->CourseCode->EditValue = ew_HtmlEncode($this->CourseCode->CurrentValue);
			$this->CourseCode->PlaceHolder = ew_RemoveHtml($this->CourseCode->FldCaption());

			// Edit refer script
			// SubjectID

			$this->SubjectID->HrefValue = "";

			// Course
			$this->Course->HrefValue = "";

			// CourseCode
			$this->CourseCode->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// SubjectID
			$this->SubjectID->EditCustomAttributes = "";
			if (trim(strval($this->SubjectID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`SubjectID`" . ew_SearchString("=", $this->SubjectID->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `SubjectID`, `Subject` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_subjects`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->SubjectID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Subject`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->SubjectID->EditValue = $arwrk;

			// Course
			$this->Course->EditCustomAttributes = "";
			$this->Course->EditValue = ew_HtmlEncode($this->Course->CurrentValue);
			$this->Course->PlaceHolder = ew_RemoveHtml($this->Course->FldCaption());

			// CourseCode
			$this->CourseCode->EditCustomAttributes = "";
			$this->CourseCode->EditValue = ew_HtmlEncode($this->CourseCode->CurrentValue);
			$this->CourseCode->PlaceHolder = ew_RemoveHtml($this->CourseCode->FldCaption());

			// Edit refer script
			// SubjectID

			$this->SubjectID->HrefValue = "";

			// Course
			$this->Course->HrefValue = "";

			// CourseCode
			$this->CourseCode->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// SubjectID
			$this->SubjectID->EditCustomAttributes = "";
			if (trim(strval($this->SubjectID->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`SubjectID`" . ew_SearchString("=", $this->SubjectID->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `SubjectID`, `Subject` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_subjects`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->SubjectID, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Subject`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->SubjectID->EditValue = $arwrk;

			// Course
			$this->Course->EditCustomAttributes = "";
			$this->Course->EditValue = ew_HtmlEncode($this->Course->AdvancedSearch->SearchValue);
			$this->Course->PlaceHolder = ew_RemoveHtml($this->Course->FldCaption());

			// CourseCode
			$this->CourseCode->EditCustomAttributes = "";
			$this->CourseCode->EditValue = ew_HtmlEncode($this->CourseCode->AdvancedSearch->SearchValue);
			$this->CourseCode->PlaceHolder = ew_RemoveHtml($this->CourseCode->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->SubjectID->FldIsDetailKey && !is_null($this->SubjectID->FormValue) && $this->SubjectID->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->SubjectID->FldCaption());
		}
		if (!$this->Course->FldIsDetailKey && !is_null($this->Course->FormValue) && $this->Course->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Course->FldCaption());
		}
		if (!$this->CourseCode->FldIsDetailKey && !is_null($this->CourseCode->FormValue) && $this->CourseCode->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->CourseCode->FldCaption());
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
				$sThisKey .= $row['CourseID'];
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
		if ($this->Course->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`Course` = '" . ew_AdjustSql($this->Course->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->Course->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->Course->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		if ($this->CourseCode->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`CourseCode` = '" . ew_AdjustSql($this->CourseCode->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->CourseCode->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->CourseCode->CurrentValue, $sIdxErrMsg);
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
			$rsnew = array();

			// SubjectID
			$this->SubjectID->SetDbValueDef($rsnew, $this->SubjectID->CurrentValue, NULL, $this->SubjectID->ReadOnly);

			// Course
			$this->Course->SetDbValueDef($rsnew, $this->Course->CurrentValue, NULL, $this->Course->ReadOnly);

			// CourseCode
			$this->CourseCode->SetDbValueDef($rsnew, $this->CourseCode->CurrentValue, NULL, $this->CourseCode->ReadOnly);

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
		if ($this->Course->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(Course = '" . ew_AdjustSql($this->Course->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->Course->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->Course->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		if ($this->CourseCode->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(CourseCode = '" . ew_AdjustSql($this->CourseCode->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->CourseCode->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->CourseCode->CurrentValue, $sIdxErrMsg);
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

		// SubjectID
		$this->SubjectID->SetDbValueDef($rsnew, $this->SubjectID->CurrentValue, NULL, FALSE);

		// Course
		$this->Course->SetDbValueDef($rsnew, $this->Course->CurrentValue, NULL, FALSE);

		// CourseCode
		$this->CourseCode->SetDbValueDef($rsnew, $this->CourseCode->CurrentValue, NULL, FALSE);

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
			$this->CourseID->setDbValue($conn->Insert_ID());
			$rsnew['CourseID'] = $this->CourseID->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->SubjectID->AdvancedSearch->Load();
		$this->Course->AdvancedSearch->Load();
		$this->CourseCode->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_t_courses\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_t_courses',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.ft_courseslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($t_courses_list)) $t_courses_list = new ct_courses_list();

// Page init
$t_courses_list->Page_Init();

// Page main
$t_courses_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_courses_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($t_courses->Export == "") { ?>
<script type="text/javascript">

// Page object
var t_courses_list = new ew_Page("t_courses_list");
t_courses_list.PageID = "list"; // Page ID
var EW_PAGE_ID = t_courses_list.PageID; // For backward compatibility

// Form object
var ft_courseslist = new ew_Form("ft_courseslist");
ft_courseslist.FormKeyCountName = '<?php echo $t_courses_list->FormKeyCountName ?>';

// Validate form
ft_courseslist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_SubjectID");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_courses->SubjectID->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_Course");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_courses->Course->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_CourseCode");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($t_courses->CourseCode->FldCaption()) ?>");

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
ft_courseslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "SubjectID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Course", false)) return false;
	if (ew_ValueChanged(fobj, infix, "CourseCode", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_courseslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_courseslist.ValidateRequired = true;
<?php } else { ?>
ft_courseslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_courseslist.Lists["x_SubjectID"] = {"LinkField":"x_SubjectID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Subject","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var ft_courseslistsrch = new ew_Form("ft_courseslistsrch");

// Validate function for search
ft_courseslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
ft_courseslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_courseslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ft_courseslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
ft_courseslistsrch.Lists["x_SubjectID"] = {"LinkField":"x_SubjectID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Subject","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (ft_courseslistsrch) ft_courseslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($t_courses->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($t_courses_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $t_courses_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($t_courses->CurrentAction == "gridadd") {
	$t_courses->CurrentFilter = "0=1";
	$t_courses_list->StartRec = 1;
	$t_courses_list->DisplayRecs = $t_courses->GridAddRowCount;
	$t_courses_list->TotalRecs = $t_courses_list->DisplayRecs;
	$t_courses_list->StopRec = $t_courses_list->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$t_courses_list->TotalRecs = $t_courses->SelectRecordCount();
	} else {
		if ($t_courses_list->Recordset = $t_courses_list->LoadRecordset())
			$t_courses_list->TotalRecs = $t_courses_list->Recordset->RecordCount();
	}
	$t_courses_list->StartRec = 1;
	if ($t_courses_list->DisplayRecs <= 0 || ($t_courses->Export <> "" && $t_courses->ExportAll)) // Display all records
		$t_courses_list->DisplayRecs = $t_courses_list->TotalRecs;
	if (!($t_courses->Export <> "" && $t_courses->ExportAll))
		$t_courses_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$t_courses_list->Recordset = $t_courses_list->LoadRecordset($t_courses_list->StartRec-1, $t_courses_list->DisplayRecs);
}
$t_courses_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($t_courses->Export == "" && $t_courses->CurrentAction == "") { ?>
<form name="ft_courseslistsrch" id="ft_courseslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="ft_courseslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#ft_courseslistsrch_SearchGroup" href="#ft_courseslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="ft_courseslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="ft_courseslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="t_courses">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$t_courses_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$t_courses->RowType = EW_ROWTYPE_SEARCH;

// Render row
$t_courses->ResetAttrs();
$t_courses_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($t_courses->SubjectID->Visible) { // SubjectID ?>
	<span id="xsc_SubjectID" class="ewCell">
		<span class="ewSearchCaption"><?php echo $t_courses->SubjectID->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_SubjectID" id="z_SubjectID" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_SubjectID" id="x_SubjectID" name="x_SubjectID"<?php echo $t_courses->SubjectID->EditAttributes() ?>>
<?php
if (is_array($t_courses->SubjectID->EditValue)) {
	$arwrk = $t_courses->SubjectID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_courses->SubjectID->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_courses->SubjectID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `SubjectID`, `Subject` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_subjects`";
$sWhereWrk = "";

// Call Lookup selecting
$t_courses->Lookup_Selecting($t_courses->SubjectID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Subject`";
?>
<input type="hidden" name="s_x_SubjectID" id="s_x_SubjectID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SubjectID` = {filter_value}"); ?>&amp;t0=3">
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($t_courses_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $t_courses_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
	</div>
</div>
<div id="xsr_3" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($t_courses_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($t_courses_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($t_courses_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $t_courses_list->ShowPageHeader(); ?>
<?php
$t_courses_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<?php if ($t_courses->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($t_courses->CurrentAction <> "gridadd" && $t_courses->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_courses_list->Pager)) $t_courses_list->Pager = new cPrevNextPager($t_courses_list->StartRec, $t_courses_list->DisplayRecs, $t_courses_list->TotalRecs) ?>
<?php if ($t_courses_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_courses_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_courses_list->PageUrl() ?>start=<?php echo $t_courses_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_courses_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_courses_list->PageUrl() ?>start=<?php echo $t_courses_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_courses_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_courses_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_courses_list->PageUrl() ?>start=<?php echo $t_courses_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_courses_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_courses_list->PageUrl() ?>start=<?php echo $t_courses_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_courses_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_courses_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_courses_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_courses_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($t_courses_list->SearchWhere == "0=101") { ?>
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
	foreach ($t_courses_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="ft_courseslist" id="ft_courseslist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="t_courses">
<div id="gmp_t_courses" class="ewGridMiddlePanel">
<?php if ($t_courses_list->TotalRecs > 0 || $t_courses->CurrentAction == "add" || $t_courses->CurrentAction == "copy") { ?>
<table id="tbl_t_courseslist" class="ewTable ewTableSeparate">
<?php echo $t_courses->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$t_courses_list->RenderListOptions();

// Render list options (header, left)
$t_courses_list->ListOptions->Render("header", "left");
?>
<?php if ($t_courses->SubjectID->Visible) { // SubjectID ?>
	<?php if ($t_courses->SortUrl($t_courses->SubjectID) == "") { ?>
		<td><div id="elh_t_courses_SubjectID" class="t_courses_SubjectID"><div class="ewTableHeaderCaption"><?php echo $t_courses->SubjectID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_courses->SortUrl($t_courses->SubjectID) ?>',1);"><div id="elh_t_courses_SubjectID" class="t_courses_SubjectID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_courses->SubjectID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_courses->SubjectID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_courses->SubjectID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_courses->Course->Visible) { // Course ?>
	<?php if ($t_courses->SortUrl($t_courses->Course) == "") { ?>
		<td><div id="elh_t_courses_Course" class="t_courses_Course"><div class="ewTableHeaderCaption"><?php echo $t_courses->Course->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_courses->SortUrl($t_courses->Course) ?>',1);"><div id="elh_t_courses_Course" class="t_courses_Course">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_courses->Course->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_courses->Course->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_courses->Course->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_courses->CourseCode->Visible) { // CourseCode ?>
	<?php if ($t_courses->SortUrl($t_courses->CourseCode) == "") { ?>
		<td><div id="elh_t_courses_CourseCode" class="t_courses_CourseCode"><div class="ewTableHeaderCaption"><?php echo $t_courses->CourseCode->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_courses->SortUrl($t_courses->CourseCode) ?>',1);"><div id="elh_t_courses_CourseCode" class="t_courses_CourseCode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_courses->CourseCode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_courses->CourseCode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_courses->CourseCode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_courses_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($t_courses->CurrentAction == "add" || $t_courses->CurrentAction == "copy") {
		$t_courses_list->RowIndex = 0;
		$t_courses_list->KeyCount = $t_courses_list->RowIndex;
		if ($t_courses->CurrentAction == "copy" && !$t_courses_list->LoadRow())
				$t_courses->CurrentAction = "add";
		if ($t_courses->CurrentAction == "add")
			$t_courses_list->LoadDefaultValues();
		if ($t_courses->EventCancelled) // Insert failed
			$t_courses_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$t_courses->ResetAttrs();
		$t_courses->RowAttrs = array_merge($t_courses->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_t_courses', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$t_courses->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_courses_list->RenderRow();

		// Render list options
		$t_courses_list->RenderListOptions();
		$t_courses_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_courses->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_courses_list->ListOptions->Render("body", "left", $t_courses_list->RowCnt);
?>
	<?php if ($t_courses->SubjectID->Visible) { // SubjectID ?>
		<td>
<span id="el<?php echo $t_courses_list->RowCnt ?>_t_courses_SubjectID" class="control-group t_courses_SubjectID">
<select data-field="x_SubjectID" id="x<?php echo $t_courses_list->RowIndex ?>_SubjectID" name="x<?php echo $t_courses_list->RowIndex ?>_SubjectID"<?php echo $t_courses->SubjectID->EditAttributes() ?>>
<?php
if (is_array($t_courses->SubjectID->EditValue)) {
	$arwrk = $t_courses->SubjectID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_courses->SubjectID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_courses->SubjectID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `SubjectID`, `Subject` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_subjects`";
$sWhereWrk = "";

// Call Lookup selecting
$t_courses->Lookup_Selecting($t_courses->SubjectID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Subject`";
?>
<input type="hidden" name="s_x<?php echo $t_courses_list->RowIndex ?>_SubjectID" id="s_x<?php echo $t_courses_list->RowIndex ?>_SubjectID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SubjectID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_SubjectID" name="o<?php echo $t_courses_list->RowIndex ?>_SubjectID" id="o<?php echo $t_courses_list->RowIndex ?>_SubjectID" value="<?php echo ew_HtmlEncode($t_courses->SubjectID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_courses->Course->Visible) { // Course ?>
		<td>
<span id="el<?php echo $t_courses_list->RowCnt ?>_t_courses_Course" class="control-group t_courses_Course">
<input type="text" data-field="x_Course" name="x<?php echo $t_courses_list->RowIndex ?>_Course" id="x<?php echo $t_courses_list->RowIndex ?>_Course" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_courses->Course->PlaceHolder) ?>" value="<?php echo $t_courses->Course->EditValue ?>"<?php echo $t_courses->Course->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Course" name="o<?php echo $t_courses_list->RowIndex ?>_Course" id="o<?php echo $t_courses_list->RowIndex ?>_Course" value="<?php echo ew_HtmlEncode($t_courses->Course->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_courses->CourseCode->Visible) { // CourseCode ?>
		<td>
<span id="el<?php echo $t_courses_list->RowCnt ?>_t_courses_CourseCode" class="control-group t_courses_CourseCode">
<input type="text" data-field="x_CourseCode" name="x<?php echo $t_courses_list->RowIndex ?>_CourseCode" id="x<?php echo $t_courses_list->RowIndex ?>_CourseCode" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($t_courses->CourseCode->PlaceHolder) ?>" value="<?php echo $t_courses->CourseCode->EditValue ?>"<?php echo $t_courses->CourseCode->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_CourseCode" name="o<?php echo $t_courses_list->RowIndex ?>_CourseCode" id="o<?php echo $t_courses_list->RowIndex ?>_CourseCode" value="<?php echo ew_HtmlEncode($t_courses->CourseCode->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_courses_list->ListOptions->Render("body", "right", $t_courses_list->RowCnt);
?>
<script type="text/javascript">
ft_courseslist.UpdateOpts(<?php echo $t_courses_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($t_courses->ExportAll && $t_courses->Export <> "") {
	$t_courses_list->StopRec = $t_courses_list->TotalRecs;
} else {

	// Set the last record to display
	if ($t_courses_list->TotalRecs > $t_courses_list->StartRec + $t_courses_list->DisplayRecs - 1)
		$t_courses_list->StopRec = $t_courses_list->StartRec + $t_courses_list->DisplayRecs - 1;
	else
		$t_courses_list->StopRec = $t_courses_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_courses_list->FormKeyCountName) && ($t_courses->CurrentAction == "gridadd" || $t_courses->CurrentAction == "gridedit" || $t_courses->CurrentAction == "F")) {
		$t_courses_list->KeyCount = $objForm->GetValue($t_courses_list->FormKeyCountName);
		$t_courses_list->StopRec = $t_courses_list->StartRec + $t_courses_list->KeyCount - 1;
	}
}
$t_courses_list->RecCnt = $t_courses_list->StartRec - 1;
if ($t_courses_list->Recordset && !$t_courses_list->Recordset->EOF) {
	$t_courses_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $t_courses_list->StartRec > 1)
		$t_courses_list->Recordset->Move($t_courses_list->StartRec - 1);
} elseif (!$t_courses->AllowAddDeleteRow && $t_courses_list->StopRec == 0) {
	$t_courses_list->StopRec = $t_courses->GridAddRowCount;
}

// Initialize aggregate
$t_courses->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_courses->ResetAttrs();
$t_courses_list->RenderRow();
$t_courses_list->EditRowCnt = 0;
if ($t_courses->CurrentAction == "edit")
	$t_courses_list->RowIndex = 1;
if ($t_courses->CurrentAction == "gridadd")
	$t_courses_list->RowIndex = 0;
if ($t_courses->CurrentAction == "gridedit")
	$t_courses_list->RowIndex = 0;
while ($t_courses_list->RecCnt < $t_courses_list->StopRec) {
	$t_courses_list->RecCnt++;
	if (intval($t_courses_list->RecCnt) >= intval($t_courses_list->StartRec)) {
		$t_courses_list->RowCnt++;
		if ($t_courses->CurrentAction == "gridadd" || $t_courses->CurrentAction == "gridedit" || $t_courses->CurrentAction == "F") {
			$t_courses_list->RowIndex++;
			$objForm->Index = $t_courses_list->RowIndex;
			if ($objForm->HasValue($t_courses_list->FormActionName))
				$t_courses_list->RowAction = strval($objForm->GetValue($t_courses_list->FormActionName));
			elseif ($t_courses->CurrentAction == "gridadd")
				$t_courses_list->RowAction = "insert";
			else
				$t_courses_list->RowAction = "";
		}

		// Set up key count
		$t_courses_list->KeyCount = $t_courses_list->RowIndex;

		// Init row class and style
		$t_courses->ResetAttrs();
		$t_courses->CssClass = "";
		if ($t_courses->CurrentAction == "gridadd") {
			$t_courses_list->LoadDefaultValues(); // Load default values
		} else {
			$t_courses_list->LoadRowValues($t_courses_list->Recordset); // Load row values
		}
		$t_courses->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_courses->CurrentAction == "gridadd") // Grid add
			$t_courses->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_courses->CurrentAction == "gridadd" && $t_courses->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_courses_list->RestoreCurrentRowFormValues($t_courses_list->RowIndex); // Restore form values
		if ($t_courses->CurrentAction == "edit") {
			if ($t_courses_list->CheckInlineEditKey() && $t_courses_list->EditRowCnt == 0) { // Inline edit
				$t_courses->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($t_courses->CurrentAction == "gridedit") { // Grid edit
			if ($t_courses->EventCancelled) {
				$t_courses_list->RestoreCurrentRowFormValues($t_courses_list->RowIndex); // Restore form values
			}
			if ($t_courses_list->RowAction == "insert")
				$t_courses->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_courses->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_courses->CurrentAction == "edit" && $t_courses->RowType == EW_ROWTYPE_EDIT && $t_courses->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$t_courses_list->RestoreFormValues(); // Restore form values
		}
		if ($t_courses->CurrentAction == "gridedit" && ($t_courses->RowType == EW_ROWTYPE_EDIT || $t_courses->RowType == EW_ROWTYPE_ADD) && $t_courses->EventCancelled) // Update failed
			$t_courses_list->RestoreCurrentRowFormValues($t_courses_list->RowIndex); // Restore form values
		if ($t_courses->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_courses_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$t_courses->RowAttrs = array_merge($t_courses->RowAttrs, array('data-rowindex'=>$t_courses_list->RowCnt, 'id'=>'r' . $t_courses_list->RowCnt . '_t_courses', 'data-rowtype'=>$t_courses->RowType));

		// Render row
		$t_courses_list->RenderRow();

		// Render list options
		$t_courses_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_courses_list->RowAction <> "delete" && $t_courses_list->RowAction <> "insertdelete" && !($t_courses_list->RowAction == "insert" && $t_courses->CurrentAction == "F" && $t_courses_list->EmptyRow())) {
?>
	<tr<?php echo $t_courses->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_courses_list->ListOptions->Render("body", "left", $t_courses_list->RowCnt);
?>
	<?php if ($t_courses->SubjectID->Visible) { // SubjectID ?>
		<td<?php echo $t_courses->SubjectID->CellAttributes() ?>>
<?php if ($t_courses->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_courses_list->RowCnt ?>_t_courses_SubjectID" class="control-group t_courses_SubjectID">
<select data-field="x_SubjectID" id="x<?php echo $t_courses_list->RowIndex ?>_SubjectID" name="x<?php echo $t_courses_list->RowIndex ?>_SubjectID"<?php echo $t_courses->SubjectID->EditAttributes() ?>>
<?php
if (is_array($t_courses->SubjectID->EditValue)) {
	$arwrk = $t_courses->SubjectID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_courses->SubjectID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_courses->SubjectID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `SubjectID`, `Subject` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_subjects`";
$sWhereWrk = "";

// Call Lookup selecting
$t_courses->Lookup_Selecting($t_courses->SubjectID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Subject`";
?>
<input type="hidden" name="s_x<?php echo $t_courses_list->RowIndex ?>_SubjectID" id="s_x<?php echo $t_courses_list->RowIndex ?>_SubjectID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SubjectID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_SubjectID" name="o<?php echo $t_courses_list->RowIndex ?>_SubjectID" id="o<?php echo $t_courses_list->RowIndex ?>_SubjectID" value="<?php echo ew_HtmlEncode($t_courses->SubjectID->OldValue) ?>">
<?php } ?>
<?php if ($t_courses->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_courses_list->RowCnt ?>_t_courses_SubjectID" class="control-group t_courses_SubjectID">
<select data-field="x_SubjectID" id="x<?php echo $t_courses_list->RowIndex ?>_SubjectID" name="x<?php echo $t_courses_list->RowIndex ?>_SubjectID"<?php echo $t_courses->SubjectID->EditAttributes() ?>>
<?php
if (is_array($t_courses->SubjectID->EditValue)) {
	$arwrk = $t_courses->SubjectID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_courses->SubjectID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_courses->SubjectID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `SubjectID`, `Subject` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_subjects`";
$sWhereWrk = "";

// Call Lookup selecting
$t_courses->Lookup_Selecting($t_courses->SubjectID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Subject`";
?>
<input type="hidden" name="s_x<?php echo $t_courses_list->RowIndex ?>_SubjectID" id="s_x<?php echo $t_courses_list->RowIndex ?>_SubjectID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SubjectID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($t_courses->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_courses->SubjectID->ViewAttributes() ?>>
<?php echo $t_courses->SubjectID->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $t_courses_list->PageObjName . "_row_" . $t_courses_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_courses->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_CourseID" name="x<?php echo $t_courses_list->RowIndex ?>_CourseID" id="x<?php echo $t_courses_list->RowIndex ?>_CourseID" value="<?php echo ew_HtmlEncode($t_courses->CourseID->CurrentValue) ?>">
<input type="hidden" data-field="x_CourseID" name="o<?php echo $t_courses_list->RowIndex ?>_CourseID" id="o<?php echo $t_courses_list->RowIndex ?>_CourseID" value="<?php echo ew_HtmlEncode($t_courses->CourseID->OldValue) ?>">
<?php } ?>
<?php if ($t_courses->RowType == EW_ROWTYPE_EDIT || $t_courses->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_CourseID" name="x<?php echo $t_courses_list->RowIndex ?>_CourseID" id="x<?php echo $t_courses_list->RowIndex ?>_CourseID" value="<?php echo ew_HtmlEncode($t_courses->CourseID->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_courses->Course->Visible) { // Course ?>
		<td<?php echo $t_courses->Course->CellAttributes() ?>>
<?php if ($t_courses->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_courses_list->RowCnt ?>_t_courses_Course" class="control-group t_courses_Course">
<input type="text" data-field="x_Course" name="x<?php echo $t_courses_list->RowIndex ?>_Course" id="x<?php echo $t_courses_list->RowIndex ?>_Course" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_courses->Course->PlaceHolder) ?>" value="<?php echo $t_courses->Course->EditValue ?>"<?php echo $t_courses->Course->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Course" name="o<?php echo $t_courses_list->RowIndex ?>_Course" id="o<?php echo $t_courses_list->RowIndex ?>_Course" value="<?php echo ew_HtmlEncode($t_courses->Course->OldValue) ?>">
<?php } ?>
<?php if ($t_courses->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_courses_list->RowCnt ?>_t_courses_Course" class="control-group t_courses_Course">
<input type="text" data-field="x_Course" name="x<?php echo $t_courses_list->RowIndex ?>_Course" id="x<?php echo $t_courses_list->RowIndex ?>_Course" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_courses->Course->PlaceHolder) ?>" value="<?php echo $t_courses->Course->EditValue ?>"<?php echo $t_courses->Course->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_courses->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_courses->Course->ViewAttributes() ?>>
<?php echo $t_courses->Course->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_courses->CourseCode->Visible) { // CourseCode ?>
		<td<?php echo $t_courses->CourseCode->CellAttributes() ?>>
<?php if ($t_courses->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_courses_list->RowCnt ?>_t_courses_CourseCode" class="control-group t_courses_CourseCode">
<input type="text" data-field="x_CourseCode" name="x<?php echo $t_courses_list->RowIndex ?>_CourseCode" id="x<?php echo $t_courses_list->RowIndex ?>_CourseCode" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($t_courses->CourseCode->PlaceHolder) ?>" value="<?php echo $t_courses->CourseCode->EditValue ?>"<?php echo $t_courses->CourseCode->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_CourseCode" name="o<?php echo $t_courses_list->RowIndex ?>_CourseCode" id="o<?php echo $t_courses_list->RowIndex ?>_CourseCode" value="<?php echo ew_HtmlEncode($t_courses->CourseCode->OldValue) ?>">
<?php } ?>
<?php if ($t_courses->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_courses_list->RowCnt ?>_t_courses_CourseCode" class="control-group t_courses_CourseCode">
<input type="text" data-field="x_CourseCode" name="x<?php echo $t_courses_list->RowIndex ?>_CourseCode" id="x<?php echo $t_courses_list->RowIndex ?>_CourseCode" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($t_courses->CourseCode->PlaceHolder) ?>" value="<?php echo $t_courses->CourseCode->EditValue ?>"<?php echo $t_courses->CourseCode->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_courses->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_courses->CourseCode->ViewAttributes() ?>>
<?php echo $t_courses->CourseCode->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_courses_list->ListOptions->Render("body", "right", $t_courses_list->RowCnt);
?>
	</tr>
<?php if ($t_courses->RowType == EW_ROWTYPE_ADD || $t_courses->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_courseslist.UpdateOpts(<?php echo $t_courses_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_courses->CurrentAction <> "gridadd")
		if (!$t_courses_list->Recordset->EOF) $t_courses_list->Recordset->MoveNext();
}
?>
<?php
	if ($t_courses->CurrentAction == "gridadd" || $t_courses->CurrentAction == "gridedit") {
		$t_courses_list->RowIndex = '$rowindex$';
		$t_courses_list->LoadDefaultValues();

		// Set row properties
		$t_courses->ResetAttrs();
		$t_courses->RowAttrs = array_merge($t_courses->RowAttrs, array('data-rowindex'=>$t_courses_list->RowIndex, 'id'=>'r0_t_courses', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_courses->RowAttrs["class"], "ewTemplate");
		$t_courses->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_courses_list->RenderRow();

		// Render list options
		$t_courses_list->RenderListOptions();
		$t_courses_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_courses->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_courses_list->ListOptions->Render("body", "left", $t_courses_list->RowIndex);
?>
	<?php if ($t_courses->SubjectID->Visible) { // SubjectID ?>
		<td>
<span id="el$rowindex$_t_courses_SubjectID" class="control-group t_courses_SubjectID">
<select data-field="x_SubjectID" id="x<?php echo $t_courses_list->RowIndex ?>_SubjectID" name="x<?php echo $t_courses_list->RowIndex ?>_SubjectID"<?php echo $t_courses->SubjectID->EditAttributes() ?>>
<?php
if (is_array($t_courses->SubjectID->EditValue)) {
	$arwrk = $t_courses->SubjectID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_courses->SubjectID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_courses->SubjectID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `SubjectID`, `Subject` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_subjects`";
$sWhereWrk = "";

// Call Lookup selecting
$t_courses->Lookup_Selecting($t_courses->SubjectID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Subject`";
?>
<input type="hidden" name="s_x<?php echo $t_courses_list->RowIndex ?>_SubjectID" id="s_x<?php echo $t_courses_list->RowIndex ?>_SubjectID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SubjectID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_SubjectID" name="o<?php echo $t_courses_list->RowIndex ?>_SubjectID" id="o<?php echo $t_courses_list->RowIndex ?>_SubjectID" value="<?php echo ew_HtmlEncode($t_courses->SubjectID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_courses->Course->Visible) { // Course ?>
		<td>
<span id="el$rowindex$_t_courses_Course" class="control-group t_courses_Course">
<input type="text" data-field="x_Course" name="x<?php echo $t_courses_list->RowIndex ?>_Course" id="x<?php echo $t_courses_list->RowIndex ?>_Course" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_courses->Course->PlaceHolder) ?>" value="<?php echo $t_courses->Course->EditValue ?>"<?php echo $t_courses->Course->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Course" name="o<?php echo $t_courses_list->RowIndex ?>_Course" id="o<?php echo $t_courses_list->RowIndex ?>_Course" value="<?php echo ew_HtmlEncode($t_courses->Course->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_courses->CourseCode->Visible) { // CourseCode ?>
		<td>
<span id="el$rowindex$_t_courses_CourseCode" class="control-group t_courses_CourseCode">
<input type="text" data-field="x_CourseCode" name="x<?php echo $t_courses_list->RowIndex ?>_CourseCode" id="x<?php echo $t_courses_list->RowIndex ?>_CourseCode" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($t_courses->CourseCode->PlaceHolder) ?>" value="<?php echo $t_courses->CourseCode->EditValue ?>"<?php echo $t_courses->CourseCode->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_CourseCode" name="o<?php echo $t_courses_list->RowIndex ?>_CourseCode" id="o<?php echo $t_courses_list->RowIndex ?>_CourseCode" value="<?php echo ew_HtmlEncode($t_courses->CourseCode->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_courses_list->ListOptions->Render("body", "right", $t_courses_list->RowCnt);
?>
<script type="text/javascript">
ft_courseslist.UpdateOpts(<?php echo $t_courses_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($t_courses->CurrentAction == "add" || $t_courses->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $t_courses_list->FormKeyCountName ?>" id="<?php echo $t_courses_list->FormKeyCountName ?>" value="<?php echo $t_courses_list->KeyCount ?>">
<?php } ?>
<?php if ($t_courses->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_courses_list->FormKeyCountName ?>" id="<?php echo $t_courses_list->FormKeyCountName ?>" value="<?php echo $t_courses_list->KeyCount ?>">
<?php echo $t_courses_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_courses->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $t_courses_list->FormKeyCountName ?>" id="<?php echo $t_courses_list->FormKeyCountName ?>" value="<?php echo $t_courses_list->KeyCount ?>">
<?php } ?>
<?php if ($t_courses->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_courses_list->FormKeyCountName ?>" id="<?php echo $t_courses_list->FormKeyCountName ?>" value="<?php echo $t_courses_list->KeyCount ?>">
<?php echo $t_courses_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_courses->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($t_courses_list->Recordset)
	$t_courses_list->Recordset->Close();
?>
<?php if ($t_courses_list->TotalRecs > 0) { ?>
<?php if ($t_courses->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($t_courses->CurrentAction <> "gridadd" && $t_courses->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_courses_list->Pager)) $t_courses_list->Pager = new cPrevNextPager($t_courses_list->StartRec, $t_courses_list->DisplayRecs, $t_courses_list->TotalRecs) ?>
<?php if ($t_courses_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_courses_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_courses_list->PageUrl() ?>start=<?php echo $t_courses_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_courses_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_courses_list->PageUrl() ?>start=<?php echo $t_courses_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_courses_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_courses_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_courses_list->PageUrl() ?>start=<?php echo $t_courses_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_courses_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_courses_list->PageUrl() ?>start=<?php echo $t_courses_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_courses_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_courses_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_courses_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_courses_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($t_courses_list->SearchWhere == "0=101") { ?>
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
	foreach ($t_courses_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($t_courses->Export == "") { ?>
<script type="text/javascript">
ft_courseslistsrch.Init();
ft_courseslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$t_courses_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($t_courses->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$t_courses_list->Page_Terminate();
?>
