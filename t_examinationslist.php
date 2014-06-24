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

$t_examinations_list = NULL; // Initialize page object first

class ct_examinations_list extends ct_examinations {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_examinations';

	// Page object name
	var $PageObjName = 't_examinations_list';

	// Grid form hidden field names
	var $FormName = 'ft_examinationslist';
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

		// Table object (t_examinations)
		if (!isset($GLOBALS["t_examinations"]) || get_class($GLOBALS["t_examinations"]) == "ct_examinations") {
			$GLOBALS["t_examinations"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_examinations"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "t_examinationsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "t_examinationsdelete.php";
		$this->MultiUpdateUrl = "t_examinationsupdate.php";

		// Table object (t_users)
		if (!isset($GLOBALS['t_users'])) $GLOBALS['t_users'] = new ct_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_examinations', TRUE);

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
		$this->setKey("ExaminationID", ""); // Clear inline edit key
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
		if (@$_GET["ExaminationID"] <> "") {
			$this->ExaminationID->setQueryStringValue($_GET["ExaminationID"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("ExaminationID", $this->ExaminationID->CurrentValue); // Set up inline edit key
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
		if (strval($this->getKey("ExaminationID")) <> strval($this->ExaminationID->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["ExaminationID"] <> "") {
				$this->ExaminationID->setQueryStringValue($_GET["ExaminationID"]);
				$this->setKey("ExaminationID", $this->ExaminationID->CurrentValue); // Set up key
			} else {
				$this->setKey("ExaminationID", ""); // Clear key
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
			$this->ExaminationID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->ExaminationID->FormValue))
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
					$sKey .= $this->ExaminationID->CurrentValue;

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
		if ($objForm->HasValue("x_Name") && $objForm->HasValue("o_Name") && $this->Name->CurrentValue <> $this->Name->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ExaminationTypeID") && $objForm->HasValue("o_ExaminationTypeID") && $this->ExaminationTypeID->CurrentValue <> $this->ExaminationTypeID->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Year") && $objForm->HasValue("o_Year") && $this->Year->CurrentValue <> $this->Year->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_SeminsterID") && $objForm->HasValue("o_SeminsterID") && $this->SeminsterID->CurrentValue <> $this->SeminsterID->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_CourseID") && $objForm->HasValue("o_CourseID") && $this->CourseID->CurrentValue <> $this->CourseID->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_InsttructorID") && $objForm->HasValue("o_InsttructorID") && $this->InsttructorID->CurrentValue <> $this->InsttructorID->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_NumberOfMCQs") && $objForm->HasValue("o_NumberOfMCQs") && $this->NumberOfMCQs->CurrentValue <> $this->NumberOfMCQs->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_NumberOfShortAnswerQuestions") && $objForm->HasValue("o_NumberOfShortAnswerQuestions") && $this->NumberOfShortAnswerQuestions->CurrentValue <> $this->NumberOfShortAnswerQuestions->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Duration") && $objForm->HasValue("o_Duration") && $this->Duration->CurrentValue <> $this->Duration->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Active") && $objForm->HasValue("o_Active") && $this->Active->CurrentValue <> $this->Active->OldValue)
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
		$this->BuildSearchSql($sWhere, $this->Name, FALSE); // Name
		$this->BuildSearchSql($sWhere, $this->ExaminationTypeID, FALSE); // ExaminationTypeID
		$this->BuildSearchSql($sWhere, $this->Year, FALSE); // Year
		$this->BuildSearchSql($sWhere, $this->SeminsterID, FALSE); // SeminsterID
		$this->BuildSearchSql($sWhere, $this->CourseID, FALSE); // CourseID
		$this->BuildSearchSql($sWhere, $this->InsttructorID, FALSE); // InsttructorID
		$this->BuildSearchSql($sWhere, $this->NumberOfMCQs, FALSE); // NumberOfMCQs
		$this->BuildSearchSql($sWhere, $this->NumberOfShortAnswerQuestions, FALSE); // NumberOfShortAnswerQuestions
		$this->BuildSearchSql($sWhere, $this->Duration, FALSE); // Duration
		$this->BuildSearchSql($sWhere, $this->Active, FALSE); // Active

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->Name->AdvancedSearch->Save(); // Name
			$this->ExaminationTypeID->AdvancedSearch->Save(); // ExaminationTypeID
			$this->Year->AdvancedSearch->Save(); // Year
			$this->SeminsterID->AdvancedSearch->Save(); // SeminsterID
			$this->CourseID->AdvancedSearch->Save(); // CourseID
			$this->InsttructorID->AdvancedSearch->Save(); // InsttructorID
			$this->NumberOfMCQs->AdvancedSearch->Save(); // NumberOfMCQs
			$this->NumberOfShortAnswerQuestions->AdvancedSearch->Save(); // NumberOfShortAnswerQuestions
			$this->Duration->AdvancedSearch->Save(); // Duration
			$this->Active->AdvancedSearch->Save(); // Active
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
		$this->BuildBasicSearchSQL($sWhere, $this->Name, $Keyword);
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
		if ($this->Name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ExaminationTypeID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Year->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->SeminsterID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CourseID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->InsttructorID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NumberOfMCQs->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NumberOfShortAnswerQuestions->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Duration->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Active->AdvancedSearch->IssetSession())
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
		$this->Name->AdvancedSearch->UnsetSession();
		$this->ExaminationTypeID->AdvancedSearch->UnsetSession();
		$this->Year->AdvancedSearch->UnsetSession();
		$this->SeminsterID->AdvancedSearch->UnsetSession();
		$this->CourseID->AdvancedSearch->UnsetSession();
		$this->InsttructorID->AdvancedSearch->UnsetSession();
		$this->NumberOfMCQs->AdvancedSearch->UnsetSession();
		$this->NumberOfShortAnswerQuestions->AdvancedSearch->UnsetSession();
		$this->Duration->AdvancedSearch->UnsetSession();
		$this->Active->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->Name->AdvancedSearch->Load();
		$this->ExaminationTypeID->AdvancedSearch->Load();
		$this->Year->AdvancedSearch->Load();
		$this->SeminsterID->AdvancedSearch->Load();
		$this->CourseID->AdvancedSearch->Load();
		$this->InsttructorID->AdvancedSearch->Load();
		$this->NumberOfMCQs->AdvancedSearch->Load();
		$this->NumberOfShortAnswerQuestions->AdvancedSearch->Load();
		$this->Duration->AdvancedSearch->Load();
		$this->Active->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Name); // Name
			$this->UpdateSort($this->ExaminationTypeID); // ExaminationTypeID
			$this->UpdateSort($this->Year); // Year
			$this->UpdateSort($this->SeminsterID); // SeminsterID
			$this->UpdateSort($this->CourseID); // CourseID
			$this->UpdateSort($this->InsttructorID); // InsttructorID
			$this->UpdateSort($this->NumberOfMCQs); // NumberOfMCQs
			$this->UpdateSort($this->NumberOfShortAnswerQuestions); // NumberOfShortAnswerQuestions
			$this->UpdateSort($this->Duration); // Duration
			$this->UpdateSort($this->Active); // Active
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
				$this->Name->setSort("");
				$this->ExaminationTypeID->setSort("");
				$this->Year->setSort("");
				$this->SeminsterID->setSort("");
				$this->CourseID->setSort("");
				$this->InsttructorID->setSort("");
				$this->NumberOfMCQs->setSort("");
				$this->NumberOfShortAnswerQuestions->setSort("");
				$this->Duration->setSort("");
				$this->Active->setSort("");
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
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->ExaminationID->CurrentValue) . "\">";
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
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->ExaminationID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->ExaminationID->CurrentValue . "\">";
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" href=\"\" onclick=\"ew_SubmitSelected(document.ft_examinationslist, '" . $this->MultiDeleteUrl . "', ewLanguage.Phrase('DeleteMultiConfirmMsg'));return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.ft_examinationslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->Active->OldValue = $this->Active->CurrentValue;
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
		// Name

		$this->Name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Name"]);
		if ($this->Name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Name->AdvancedSearch->SearchOperator = @$_GET["z_Name"];

		// ExaminationTypeID
		$this->ExaminationTypeID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ExaminationTypeID"]);
		if ($this->ExaminationTypeID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ExaminationTypeID->AdvancedSearch->SearchOperator = @$_GET["z_ExaminationTypeID"];

		// Year
		$this->Year->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Year"]);
		if ($this->Year->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Year->AdvancedSearch->SearchOperator = @$_GET["z_Year"];

		// SeminsterID
		$this->SeminsterID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_SeminsterID"]);
		if ($this->SeminsterID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->SeminsterID->AdvancedSearch->SearchOperator = @$_GET["z_SeminsterID"];

		// CourseID
		$this->CourseID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CourseID"]);
		if ($this->CourseID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CourseID->AdvancedSearch->SearchOperator = @$_GET["z_CourseID"];

		// InsttructorID
		$this->InsttructorID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_InsttructorID"]);
		if ($this->InsttructorID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->InsttructorID->AdvancedSearch->SearchOperator = @$_GET["z_InsttructorID"];

		// NumberOfMCQs
		$this->NumberOfMCQs->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NumberOfMCQs"]);
		if ($this->NumberOfMCQs->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NumberOfMCQs->AdvancedSearch->SearchOperator = @$_GET["z_NumberOfMCQs"];

		// NumberOfShortAnswerQuestions
		$this->NumberOfShortAnswerQuestions->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NumberOfShortAnswerQuestions"]);
		if ($this->NumberOfShortAnswerQuestions->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NumberOfShortAnswerQuestions->AdvancedSearch->SearchOperator = @$_GET["z_NumberOfShortAnswerQuestions"];

		// Duration
		$this->Duration->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Duration"]);
		if ($this->Duration->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Duration->AdvancedSearch->SearchOperator = @$_GET["z_Duration"];

		// Active
		$this->Active->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Active"]);
		if ($this->Active->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Active->AdvancedSearch->SearchOperator = @$_GET["z_Active"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Name->FldIsDetailKey) {
			$this->Name->setFormValue($objForm->GetValue("x_Name"));
		}
		$this->Name->setOldValue($objForm->GetValue("o_Name"));
		if (!$this->ExaminationTypeID->FldIsDetailKey) {
			$this->ExaminationTypeID->setFormValue($objForm->GetValue("x_ExaminationTypeID"));
		}
		$this->ExaminationTypeID->setOldValue($objForm->GetValue("o_ExaminationTypeID"));
		if (!$this->Year->FldIsDetailKey) {
			$this->Year->setFormValue($objForm->GetValue("x_Year"));
		}
		$this->Year->setOldValue($objForm->GetValue("o_Year"));
		if (!$this->SeminsterID->FldIsDetailKey) {
			$this->SeminsterID->setFormValue($objForm->GetValue("x_SeminsterID"));
		}
		$this->SeminsterID->setOldValue($objForm->GetValue("o_SeminsterID"));
		if (!$this->CourseID->FldIsDetailKey) {
			$this->CourseID->setFormValue($objForm->GetValue("x_CourseID"));
		}
		$this->CourseID->setOldValue($objForm->GetValue("o_CourseID"));
		if (!$this->InsttructorID->FldIsDetailKey) {
			$this->InsttructorID->setFormValue($objForm->GetValue("x_InsttructorID"));
		}
		$this->InsttructorID->setOldValue($objForm->GetValue("o_InsttructorID"));
		if (!$this->NumberOfMCQs->FldIsDetailKey) {
			$this->NumberOfMCQs->setFormValue($objForm->GetValue("x_NumberOfMCQs"));
		}
		$this->NumberOfMCQs->setOldValue($objForm->GetValue("o_NumberOfMCQs"));
		if (!$this->NumberOfShortAnswerQuestions->FldIsDetailKey) {
			$this->NumberOfShortAnswerQuestions->setFormValue($objForm->GetValue("x_NumberOfShortAnswerQuestions"));
		}
		$this->NumberOfShortAnswerQuestions->setOldValue($objForm->GetValue("o_NumberOfShortAnswerQuestions"));
		if (!$this->Duration->FldIsDetailKey) {
			$this->Duration->setFormValue($objForm->GetValue("x_Duration"));
		}
		$this->Duration->setOldValue($objForm->GetValue("o_Duration"));
		if (!$this->Active->FldIsDetailKey) {
			$this->Active->setFormValue($objForm->GetValue("x_Active"));
		}
		$this->Active->setOldValue($objForm->GetValue("o_Active"));
		if (!$this->ExaminationID->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->ExaminationID->setFormValue($objForm->GetValue("x_ExaminationID"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->ExaminationID->CurrentValue = $this->ExaminationID->FormValue;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Name
			$this->Name->EditCustomAttributes = "";
			$this->Name->EditValue = ew_HtmlEncode($this->Name->AdvancedSearch->SearchValue);
			$this->Name->PlaceHolder = ew_RemoveHtml($this->Name->FldCaption());

			// ExaminationTypeID
			$this->ExaminationTypeID->EditCustomAttributes = "";
			if (trim(strval($this->ExaminationTypeID->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ExaminationTypeID`" . ew_SearchString("=", $this->ExaminationTypeID->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
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
			$this->Year->EditValue = ew_HtmlEncode($this->Year->AdvancedSearch->SearchValue);
			$this->Year->PlaceHolder = ew_RemoveHtml($this->Year->FldCaption());

			// SeminsterID
			$this->SeminsterID->EditCustomAttributes = "";
			if (trim(strval($this->SeminsterID->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`SemisterID`" . ew_SearchString("=", $this->SeminsterID->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
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
			if (trim(strval($this->CourseID->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`CourseID`" . ew_SearchString("=", $this->CourseID->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
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
			if (trim(strval($this->InsttructorID->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->InsttructorID->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
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
			$this->NumberOfMCQs->EditValue = ew_HtmlEncode($this->NumberOfMCQs->AdvancedSearch->SearchValue);
			$this->NumberOfMCQs->PlaceHolder = ew_RemoveHtml($this->NumberOfMCQs->FldCaption());

			// NumberOfShortAnswerQuestions
			$this->NumberOfShortAnswerQuestions->EditCustomAttributes = "";
			$this->NumberOfShortAnswerQuestions->EditValue = ew_HtmlEncode($this->NumberOfShortAnswerQuestions->AdvancedSearch->SearchValue);
			$this->NumberOfShortAnswerQuestions->PlaceHolder = ew_RemoveHtml($this->NumberOfShortAnswerQuestions->FldCaption());

			// Duration
			$this->Duration->EditCustomAttributes = "";
			$this->Duration->EditValue = ew_HtmlEncode($this->Duration->AdvancedSearch->SearchValue);
			$this->Duration->PlaceHolder = ew_RemoveHtml($this->Duration->FldCaption());

			// Active
			$this->Active->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Active->FldTagValue(1), $this->Active->FldTagCaption(1) <> "" ? $this->Active->FldTagCaption(1) : $this->Active->FldTagValue(1));
			$arwrk[] = array($this->Active->FldTagValue(2), $this->Active->FldTagCaption(2) <> "" ? $this->Active->FldTagCaption(2) : $this->Active->FldTagValue(2));
			$this->Active->EditValue = $arwrk;
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
		if (!ew_CheckInteger($this->Year->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Year->FldErrMsg());
		}

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
		if ($this->Name->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`Name` = '" . ew_AdjustSql($this->Name->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->Name->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->Name->CurrentValue, $sIdxErrMsg);
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

			// Name
			$this->Name->SetDbValueDef($rsnew, $this->Name->CurrentValue, NULL, $this->Name->ReadOnly);

			// ExaminationTypeID
			$this->ExaminationTypeID->SetDbValueDef($rsnew, $this->ExaminationTypeID->CurrentValue, NULL, $this->ExaminationTypeID->ReadOnly);

			// Year
			$this->Year->SetDbValueDef($rsnew, $this->Year->CurrentValue, NULL, $this->Year->ReadOnly);

			// SeminsterID
			$this->SeminsterID->SetDbValueDef($rsnew, $this->SeminsterID->CurrentValue, NULL, $this->SeminsterID->ReadOnly);

			// CourseID
			$this->CourseID->SetDbValueDef($rsnew, $this->CourseID->CurrentValue, NULL, $this->CourseID->ReadOnly);

			// InsttructorID
			$this->InsttructorID->SetDbValueDef($rsnew, $this->InsttructorID->CurrentValue, NULL, $this->InsttructorID->ReadOnly);

			// NumberOfMCQs
			$this->NumberOfMCQs->SetDbValueDef($rsnew, $this->NumberOfMCQs->CurrentValue, NULL, $this->NumberOfMCQs->ReadOnly);

			// NumberOfShortAnswerQuestions
			$this->NumberOfShortAnswerQuestions->SetDbValueDef($rsnew, $this->NumberOfShortAnswerQuestions->CurrentValue, NULL, $this->NumberOfShortAnswerQuestions->ReadOnly);

			// Duration
			$this->Duration->SetDbValueDef($rsnew, $this->Duration->CurrentValue, NULL, $this->Duration->ReadOnly);

			// Active
			$this->Active->SetDbValueDef($rsnew, $this->Active->CurrentValue, NULL, $this->Active->ReadOnly);

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

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->Name->AdvancedSearch->Load();
		$this->ExaminationTypeID->AdvancedSearch->Load();
		$this->Year->AdvancedSearch->Load();
		$this->SeminsterID->AdvancedSearch->Load();
		$this->CourseID->AdvancedSearch->Load();
		$this->InsttructorID->AdvancedSearch->Load();
		$this->NumberOfMCQs->AdvancedSearch->Load();
		$this->NumberOfShortAnswerQuestions->AdvancedSearch->Load();
		$this->Duration->AdvancedSearch->Load();
		$this->Active->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_t_examinations\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_t_examinations',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.ft_examinationslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($t_examinations_list)) $t_examinations_list = new ct_examinations_list();

// Page init
$t_examinations_list->Page_Init();

// Page main
$t_examinations_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_examinations_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($t_examinations->Export == "") { ?>
<script type="text/javascript">

// Page object
var t_examinations_list = new ew_Page("t_examinations_list");
t_examinations_list.PageID = "list"; // Page ID
var EW_PAGE_ID = t_examinations_list.PageID; // For backward compatibility

// Form object
var ft_examinationslist = new ew_Form("ft_examinationslist");
ft_examinationslist.FormKeyCountName = '<?php echo $t_examinations_list->FormKeyCountName ?>';

// Validate form
ft_examinationslist.Validate = function() {
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
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
ft_examinationslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Name", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ExaminationTypeID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Year", false)) return false;
	if (ew_ValueChanged(fobj, infix, "SeminsterID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "CourseID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "InsttructorID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "NumberOfMCQs", false)) return false;
	if (ew_ValueChanged(fobj, infix, "NumberOfShortAnswerQuestions", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Duration", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Active", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_examinationslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_examinationslist.ValidateRequired = true;
<?php } else { ?>
ft_examinationslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_examinationslist.Lists["x_ExaminationTypeID"] = {"LinkField":"x_ExaminationTypeID","Ajax":true,"AutoFill":false,"DisplayFields":["x_ExaminationType","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationslist.Lists["x_SeminsterID"] = {"LinkField":"x_SemisterID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Semister","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationslist.Lists["x_CourseID"] = {"LinkField":"x_CourseID","Ajax":true,"AutoFill":false,"DisplayFields":["x_CourseCode","x_Course","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationslist.Lists["x_InsttructorID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_LastName","x_FirstName","x_NICNumber",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var ft_examinationslistsrch = new ew_Form("ft_examinationslistsrch");

// Validate function for search
ft_examinationslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_Year");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($t_examinations->Year->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
ft_examinationslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_examinationslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ft_examinationslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
ft_examinationslistsrch.Lists["x_ExaminationTypeID"] = {"LinkField":"x_ExaminationTypeID","Ajax":true,"AutoFill":false,"DisplayFields":["x_ExaminationType","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationslistsrch.Lists["x_SeminsterID"] = {"LinkField":"x_SemisterID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Semister","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationslistsrch.Lists["x_CourseID"] = {"LinkField":"x_CourseID","Ajax":true,"AutoFill":false,"DisplayFields":["x_CourseCode","x_Course","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ft_examinationslistsrch.Lists["x_InsttructorID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_LastName","x_FirstName","x_NICNumber",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (ft_examinationslistsrch) ft_examinationslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($t_examinations->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($t_examinations_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $t_examinations_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($t_examinations->CurrentAction == "gridadd") {
	$t_examinations->CurrentFilter = "0=1";
	$t_examinations_list->StartRec = 1;
	$t_examinations_list->DisplayRecs = $t_examinations->GridAddRowCount;
	$t_examinations_list->TotalRecs = $t_examinations_list->DisplayRecs;
	$t_examinations_list->StopRec = $t_examinations_list->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$t_examinations_list->TotalRecs = $t_examinations->SelectRecordCount();
	} else {
		if ($t_examinations_list->Recordset = $t_examinations_list->LoadRecordset())
			$t_examinations_list->TotalRecs = $t_examinations_list->Recordset->RecordCount();
	}
	$t_examinations_list->StartRec = 1;
	if ($t_examinations_list->DisplayRecs <= 0 || ($t_examinations->Export <> "" && $t_examinations->ExportAll)) // Display all records
		$t_examinations_list->DisplayRecs = $t_examinations_list->TotalRecs;
	if (!($t_examinations->Export <> "" && $t_examinations->ExportAll))
		$t_examinations_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$t_examinations_list->Recordset = $t_examinations_list->LoadRecordset($t_examinations_list->StartRec-1, $t_examinations_list->DisplayRecs);
}
$t_examinations_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($t_examinations->Export == "" && $t_examinations->CurrentAction == "") { ?>
<form name="ft_examinationslistsrch" id="ft_examinationslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="ft_examinationslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#ft_examinationslistsrch_SearchGroup" href="#ft_examinationslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="ft_examinationslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="ft_examinationslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="t_examinations">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$t_examinations_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$t_examinations->RowType = EW_ROWTYPE_SEARCH;

// Render row
$t_examinations->ResetAttrs();
$t_examinations_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($t_examinations->Name->Visible) { // Name ?>
	<span id="xsc_Name" class="ewCell">
		<span class="ewSearchCaption"><?php echo $t_examinations->Name->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Name" id="z_Name" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_Name" name="x_Name" id="x_Name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($t_examinations->Name->PlaceHolder) ?>" value="<?php echo $t_examinations->Name->EditValue ?>"<?php echo $t_examinations->Name->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($t_examinations->ExaminationTypeID->Visible) { // ExaminationTypeID ?>
	<span id="xsc_ExaminationTypeID" class="ewCell">
		<span class="ewSearchCaption"><?php echo $t_examinations->ExaminationTypeID->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_ExaminationTypeID" id="z_ExaminationTypeID" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_ExaminationTypeID" id="x_ExaminationTypeID" name="x_ExaminationTypeID"<?php echo $t_examinations->ExaminationTypeID->EditAttributes() ?>>
<?php
if (is_array($t_examinations->ExaminationTypeID->EditValue)) {
	$arwrk = $t_examinations->ExaminationTypeID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_examinations->ExaminationTypeID->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_examinations->ExaminationTypeID->OldValue = "";
?>
</select>
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
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($t_examinations->Year->Visible) { // Year ?>
	<span id="xsc_Year" class="ewCell">
		<span class="ewSearchCaption"><?php echo $t_examinations->Year->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Year" id="z_Year" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_Year" name="x_Year" id="x_Year" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Year->PlaceHolder) ?>" value="<?php echo $t_examinations->Year->EditValue ?>"<?php echo $t_examinations->Year->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($t_examinations->SeminsterID->Visible) { // SeminsterID ?>
	<span id="xsc_SeminsterID" class="ewCell">
		<span class="ewSearchCaption"><?php echo $t_examinations->SeminsterID->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_SeminsterID" id="z_SeminsterID" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_SeminsterID" id="x_SeminsterID" name="x_SeminsterID"<?php echo $t_examinations->SeminsterID->EditAttributes() ?>>
<?php
if (is_array($t_examinations->SeminsterID->EditValue)) {
	$arwrk = $t_examinations->SeminsterID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_examinations->SeminsterID->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $t_examinations->SeminsterID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `SemisterID`, `Semister` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_semisters`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->SeminsterID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_SeminsterID" id="s_x_SeminsterID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SemisterID` = {filter_value}"); ?>&amp;t0=3">
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($t_examinations->CourseID->Visible) { // CourseID ?>
	<span id="xsc_CourseID" class="ewCell">
		<span class="ewSearchCaption"><?php echo $t_examinations->CourseID->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_CourseID" id="z_CourseID" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_CourseID" id="x_CourseID" name="x_CourseID"<?php echo $t_examinations->CourseID->EditAttributes() ?>>
<?php
if (is_array($t_examinations->CourseID->EditValue)) {
	$arwrk = $t_examinations->CourseID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_examinations->CourseID->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
if (@$emptywrk) $t_examinations->CourseID->OldValue = "";
?>
</select>
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
	</span>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($t_examinations->InsttructorID->Visible) { // InsttructorID ?>
	<span id="xsc_InsttructorID" class="ewCell">
		<span class="ewSearchCaption"><?php echo $t_examinations->InsttructorID->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_InsttructorID" id="z_InsttructorID" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_InsttructorID" id="x_InsttructorID" name="x_InsttructorID"<?php echo $t_examinations->InsttructorID->EditAttributes() ?>>
<?php
if (is_array($t_examinations->InsttructorID->EditValue)) {
	$arwrk = $t_examinations->InsttructorID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($t_examinations->InsttructorID->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
if (@$emptywrk) $t_examinations->InsttructorID->OldValue = "";
?>
</select>
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
	</span>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($t_examinations_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $t_examinations_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
	</div>
</div>
<div id="xsr_8" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($t_examinations_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($t_examinations_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($t_examinations_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $t_examinations_list->ShowPageHeader(); ?>
<?php
$t_examinations_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<?php if ($t_examinations->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($t_examinations->CurrentAction <> "gridadd" && $t_examinations->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_examinations_list->Pager)) $t_examinations_list->Pager = new cPrevNextPager($t_examinations_list->StartRec, $t_examinations_list->DisplayRecs, $t_examinations_list->TotalRecs) ?>
<?php if ($t_examinations_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_examinations_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_list->PageUrl() ?>start=<?php echo $t_examinations_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_examinations_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_list->PageUrl() ?>start=<?php echo $t_examinations_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_examinations_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_examinations_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_list->PageUrl() ?>start=<?php echo $t_examinations_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_examinations_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_list->PageUrl() ?>start=<?php echo $t_examinations_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_examinations_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_examinations_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_examinations_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_examinations_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($t_examinations_list->SearchWhere == "0=101") { ?>
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
	foreach ($t_examinations_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="ft_examinationslist" id="ft_examinationslist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="t_examinations">
<div id="gmp_t_examinations" class="ewGridMiddlePanel">
<?php if ($t_examinations_list->TotalRecs > 0 || $t_examinations->CurrentAction == "add" || $t_examinations->CurrentAction == "copy") { ?>
<table id="tbl_t_examinationslist" class="ewTable ewTableSeparate">
<?php echo $t_examinations->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$t_examinations_list->RenderListOptions();

// Render list options (header, left)
$t_examinations_list->ListOptions->Render("header", "left");
?>
<?php if ($t_examinations->Name->Visible) { // Name ?>
	<?php if ($t_examinations->SortUrl($t_examinations->Name) == "") { ?>
		<td><div id="elh_t_examinations_Name" class="t_examinations_Name"><div class="ewTableHeaderCaption"><?php echo $t_examinations->Name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_examinations->SortUrl($t_examinations->Name) ?>',1);"><div id="elh_t_examinations_Name" class="t_examinations_Name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_examinations->Name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_examinations->Name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_examinations->Name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_examinations->ExaminationTypeID->Visible) { // ExaminationTypeID ?>
	<?php if ($t_examinations->SortUrl($t_examinations->ExaminationTypeID) == "") { ?>
		<td><div id="elh_t_examinations_ExaminationTypeID" class="t_examinations_ExaminationTypeID"><div class="ewTableHeaderCaption"><?php echo $t_examinations->ExaminationTypeID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_examinations->SortUrl($t_examinations->ExaminationTypeID) ?>',1);"><div id="elh_t_examinations_ExaminationTypeID" class="t_examinations_ExaminationTypeID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_examinations->ExaminationTypeID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_examinations->ExaminationTypeID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_examinations->ExaminationTypeID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_examinations->Year->Visible) { // Year ?>
	<?php if ($t_examinations->SortUrl($t_examinations->Year) == "") { ?>
		<td><div id="elh_t_examinations_Year" class="t_examinations_Year"><div class="ewTableHeaderCaption"><?php echo $t_examinations->Year->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_examinations->SortUrl($t_examinations->Year) ?>',1);"><div id="elh_t_examinations_Year" class="t_examinations_Year">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_examinations->Year->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_examinations->Year->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_examinations->Year->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_examinations->SeminsterID->Visible) { // SeminsterID ?>
	<?php if ($t_examinations->SortUrl($t_examinations->SeminsterID) == "") { ?>
		<td><div id="elh_t_examinations_SeminsterID" class="t_examinations_SeminsterID"><div class="ewTableHeaderCaption"><?php echo $t_examinations->SeminsterID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_examinations->SortUrl($t_examinations->SeminsterID) ?>',1);"><div id="elh_t_examinations_SeminsterID" class="t_examinations_SeminsterID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_examinations->SeminsterID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_examinations->SeminsterID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_examinations->SeminsterID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_examinations->CourseID->Visible) { // CourseID ?>
	<?php if ($t_examinations->SortUrl($t_examinations->CourseID) == "") { ?>
		<td><div id="elh_t_examinations_CourseID" class="t_examinations_CourseID"><div class="ewTableHeaderCaption"><?php echo $t_examinations->CourseID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_examinations->SortUrl($t_examinations->CourseID) ?>',1);"><div id="elh_t_examinations_CourseID" class="t_examinations_CourseID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_examinations->CourseID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_examinations->CourseID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_examinations->CourseID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_examinations->InsttructorID->Visible) { // InsttructorID ?>
	<?php if ($t_examinations->SortUrl($t_examinations->InsttructorID) == "") { ?>
		<td><div id="elh_t_examinations_InsttructorID" class="t_examinations_InsttructorID"><div class="ewTableHeaderCaption"><?php echo $t_examinations->InsttructorID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_examinations->SortUrl($t_examinations->InsttructorID) ?>',1);"><div id="elh_t_examinations_InsttructorID" class="t_examinations_InsttructorID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_examinations->InsttructorID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_examinations->InsttructorID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_examinations->InsttructorID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_examinations->NumberOfMCQs->Visible) { // NumberOfMCQs ?>
	<?php if ($t_examinations->SortUrl($t_examinations->NumberOfMCQs) == "") { ?>
		<td><div id="elh_t_examinations_NumberOfMCQs" class="t_examinations_NumberOfMCQs"><div class="ewTableHeaderCaption"><?php echo $t_examinations->NumberOfMCQs->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_examinations->SortUrl($t_examinations->NumberOfMCQs) ?>',1);"><div id="elh_t_examinations_NumberOfMCQs" class="t_examinations_NumberOfMCQs">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_examinations->NumberOfMCQs->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_examinations->NumberOfMCQs->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_examinations->NumberOfMCQs->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_examinations->NumberOfShortAnswerQuestions->Visible) { // NumberOfShortAnswerQuestions ?>
	<?php if ($t_examinations->SortUrl($t_examinations->NumberOfShortAnswerQuestions) == "") { ?>
		<td><div id="elh_t_examinations_NumberOfShortAnswerQuestions" class="t_examinations_NumberOfShortAnswerQuestions"><div class="ewTableHeaderCaption"><?php echo $t_examinations->NumberOfShortAnswerQuestions->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_examinations->SortUrl($t_examinations->NumberOfShortAnswerQuestions) ?>',1);"><div id="elh_t_examinations_NumberOfShortAnswerQuestions" class="t_examinations_NumberOfShortAnswerQuestions">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_examinations->NumberOfShortAnswerQuestions->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_examinations->NumberOfShortAnswerQuestions->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_examinations->NumberOfShortAnswerQuestions->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_examinations->Duration->Visible) { // Duration ?>
	<?php if ($t_examinations->SortUrl($t_examinations->Duration) == "") { ?>
		<td><div id="elh_t_examinations_Duration" class="t_examinations_Duration"><div class="ewTableHeaderCaption"><?php echo $t_examinations->Duration->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_examinations->SortUrl($t_examinations->Duration) ?>',1);"><div id="elh_t_examinations_Duration" class="t_examinations_Duration">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_examinations->Duration->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_examinations->Duration->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_examinations->Duration->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($t_examinations->Active->Visible) { // Active ?>
	<?php if ($t_examinations->SortUrl($t_examinations->Active) == "") { ?>
		<td><div id="elh_t_examinations_Active" class="t_examinations_Active"><div class="ewTableHeaderCaption"><?php echo $t_examinations->Active->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_examinations->SortUrl($t_examinations->Active) ?>',1);"><div id="elh_t_examinations_Active" class="t_examinations_Active">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_examinations->Active->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_examinations->Active->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_examinations->Active->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_examinations_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($t_examinations->CurrentAction == "add" || $t_examinations->CurrentAction == "copy") {
		$t_examinations_list->RowIndex = 0;
		$t_examinations_list->KeyCount = $t_examinations_list->RowIndex;
		if ($t_examinations->CurrentAction == "copy" && !$t_examinations_list->LoadRow())
				$t_examinations->CurrentAction = "add";
		if ($t_examinations->CurrentAction == "add")
			$t_examinations_list->LoadDefaultValues();
		if ($t_examinations->EventCancelled) // Insert failed
			$t_examinations_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$t_examinations->ResetAttrs();
		$t_examinations->RowAttrs = array_merge($t_examinations->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_t_examinations', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$t_examinations->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_examinations_list->RenderRow();

		// Render list options
		$t_examinations_list->RenderListOptions();
		$t_examinations_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_examinations->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_examinations_list->ListOptions->Render("body", "left", $t_examinations_list->RowCnt);
?>
	<?php if ($t_examinations->Name->Visible) { // Name ?>
		<td>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Name" class="control-group t_examinations_Name">
<input type="text" data-field="x_Name" name="x<?php echo $t_examinations_list->RowIndex ?>_Name" id="x<?php echo $t_examinations_list->RowIndex ?>_Name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($t_examinations->Name->PlaceHolder) ?>" value="<?php echo $t_examinations->Name->EditValue ?>"<?php echo $t_examinations->Name->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Name" name="o<?php echo $t_examinations_list->RowIndex ?>_Name" id="o<?php echo $t_examinations_list->RowIndex ?>_Name" value="<?php echo ew_HtmlEncode($t_examinations->Name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->ExaminationTypeID->Visible) { // ExaminationTypeID ?>
		<td>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_ExaminationTypeID" class="control-group t_examinations_ExaminationTypeID">
<select data-field="x_ExaminationTypeID" id="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" name="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID"<?php echo $t_examinations->ExaminationTypeID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->ExaminationTypeID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `ExaminationTypeID`, `ExaminationType` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_examination_types`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->ExaminationTypeID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `ExaminationType`";
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`ExaminationTypeID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_ExaminationTypeID" name="o<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" id="o<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" value="<?php echo ew_HtmlEncode($t_examinations->ExaminationTypeID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->Year->Visible) { // Year ?>
		<td>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Year" class="control-group t_examinations_Year">
<input type="text" data-field="x_Year" name="x<?php echo $t_examinations_list->RowIndex ?>_Year" id="x<?php echo $t_examinations_list->RowIndex ?>_Year" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Year->PlaceHolder) ?>" value="<?php echo $t_examinations->Year->EditValue ?>"<?php echo $t_examinations->Year->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Year" name="o<?php echo $t_examinations_list->RowIndex ?>_Year" id="o<?php echo $t_examinations_list->RowIndex ?>_Year" value="<?php echo ew_HtmlEncode($t_examinations->Year->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->SeminsterID->Visible) { // SeminsterID ?>
		<td>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_SeminsterID" class="control-group t_examinations_SeminsterID">
<select data-field="x_SeminsterID" id="x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" name="x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID"<?php echo $t_examinations->SeminsterID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->SeminsterID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `SemisterID`, `Semister` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_semisters`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->SeminsterID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SemisterID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_SeminsterID" name="o<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" id="o<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" value="<?php echo ew_HtmlEncode($t_examinations->SeminsterID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->CourseID->Visible) { // CourseID ?>
		<td>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_CourseID" class="control-group t_examinations_CourseID">
<select data-field="x_CourseID" id="x<?php echo $t_examinations_list->RowIndex ?>_CourseID" name="x<?php echo $t_examinations_list->RowIndex ?>_CourseID"<?php echo $t_examinations->CourseID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->CourseID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_courses`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->CourseID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `CourseCode`";
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_CourseID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_CourseID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`CourseID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_CourseID" name="o<?php echo $t_examinations_list->RowIndex ?>_CourseID" id="o<?php echo $t_examinations_list->RowIndex ?>_CourseID" value="<?php echo ew_HtmlEncode($t_examinations->CourseID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->InsttructorID->Visible) { // InsttructorID ?>
		<td>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_InsttructorID" class="control-group t_examinations_InsttructorID">
<select data-field="x_InsttructorID" id="x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" name="x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID"<?php echo $t_examinations->InsttructorID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->InsttructorID->OldValue = "";
?>
</select>
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
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`UserID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_InsttructorID" name="o<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" id="o<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" value="<?php echo ew_HtmlEncode($t_examinations->InsttructorID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->NumberOfMCQs->Visible) { // NumberOfMCQs ?>
		<td>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_NumberOfMCQs" class="control-group t_examinations_NumberOfMCQs">
<input type="text" data-field="x_NumberOfMCQs" name="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" id="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->NumberOfMCQs->PlaceHolder) ?>" value="<?php echo $t_examinations->NumberOfMCQs->EditValue ?>"<?php echo $t_examinations->NumberOfMCQs->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_NumberOfMCQs" name="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" id="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" value="<?php echo ew_HtmlEncode($t_examinations->NumberOfMCQs->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->NumberOfShortAnswerQuestions->Visible) { // NumberOfShortAnswerQuestions ?>
		<td>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_NumberOfShortAnswerQuestions" class="control-group t_examinations_NumberOfShortAnswerQuestions">
<input type="text" data-field="x_NumberOfShortAnswerQuestions" name="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" id="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->NumberOfShortAnswerQuestions->PlaceHolder) ?>" value="<?php echo $t_examinations->NumberOfShortAnswerQuestions->EditValue ?>"<?php echo $t_examinations->NumberOfShortAnswerQuestions->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_NumberOfShortAnswerQuestions" name="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" id="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" value="<?php echo ew_HtmlEncode($t_examinations->NumberOfShortAnswerQuestions->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->Duration->Visible) { // Duration ?>
		<td>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Duration" class="control-group t_examinations_Duration">
<input type="text" data-field="x_Duration" name="x<?php echo $t_examinations_list->RowIndex ?>_Duration" id="x<?php echo $t_examinations_list->RowIndex ?>_Duration" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Duration->PlaceHolder) ?>" value="<?php echo $t_examinations->Duration->EditValue ?>"<?php echo $t_examinations->Duration->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Duration" name="o<?php echo $t_examinations_list->RowIndex ?>_Duration" id="o<?php echo $t_examinations_list->RowIndex ?>_Duration" value="<?php echo ew_HtmlEncode($t_examinations->Duration->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->Active->Visible) { // Active ?>
		<td>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Active" class="control-group t_examinations_Active">
<div id="tp_x<?php echo $t_examinations_list->RowIndex ?>_Active" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $t_examinations_list->RowIndex ?>_Active" id="x<?php echo $t_examinations_list->RowIndex ?>_Active" value="{value}"<?php echo $t_examinations->Active->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_examinations_list->RowIndex ?>_Active" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio"><input type="radio" data-field="x_Active" name="x<?php echo $t_examinations_list->RowIndex ?>_Active" id="x<?php echo $t_examinations_list->RowIndex ?>_Active_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $t_examinations->Active->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $t_examinations->Active->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_Active" name="o<?php echo $t_examinations_list->RowIndex ?>_Active" id="o<?php echo $t_examinations_list->RowIndex ?>_Active" value="<?php echo ew_HtmlEncode($t_examinations->Active->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_examinations_list->ListOptions->Render("body", "right", $t_examinations_list->RowCnt);
?>
<script type="text/javascript">
ft_examinationslist.UpdateOpts(<?php echo $t_examinations_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($t_examinations->ExportAll && $t_examinations->Export <> "") {
	$t_examinations_list->StopRec = $t_examinations_list->TotalRecs;
} else {

	// Set the last record to display
	if ($t_examinations_list->TotalRecs > $t_examinations_list->StartRec + $t_examinations_list->DisplayRecs - 1)
		$t_examinations_list->StopRec = $t_examinations_list->StartRec + $t_examinations_list->DisplayRecs - 1;
	else
		$t_examinations_list->StopRec = $t_examinations_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_examinations_list->FormKeyCountName) && ($t_examinations->CurrentAction == "gridadd" || $t_examinations->CurrentAction == "gridedit" || $t_examinations->CurrentAction == "F")) {
		$t_examinations_list->KeyCount = $objForm->GetValue($t_examinations_list->FormKeyCountName);
		$t_examinations_list->StopRec = $t_examinations_list->StartRec + $t_examinations_list->KeyCount - 1;
	}
}
$t_examinations_list->RecCnt = $t_examinations_list->StartRec - 1;
if ($t_examinations_list->Recordset && !$t_examinations_list->Recordset->EOF) {
	$t_examinations_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $t_examinations_list->StartRec > 1)
		$t_examinations_list->Recordset->Move($t_examinations_list->StartRec - 1);
} elseif (!$t_examinations->AllowAddDeleteRow && $t_examinations_list->StopRec == 0) {
	$t_examinations_list->StopRec = $t_examinations->GridAddRowCount;
}

// Initialize aggregate
$t_examinations->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_examinations->ResetAttrs();
$t_examinations_list->RenderRow();
$t_examinations_list->EditRowCnt = 0;
if ($t_examinations->CurrentAction == "edit")
	$t_examinations_list->RowIndex = 1;
if ($t_examinations->CurrentAction == "gridadd")
	$t_examinations_list->RowIndex = 0;
if ($t_examinations->CurrentAction == "gridedit")
	$t_examinations_list->RowIndex = 0;
while ($t_examinations_list->RecCnt < $t_examinations_list->StopRec) {
	$t_examinations_list->RecCnt++;
	if (intval($t_examinations_list->RecCnt) >= intval($t_examinations_list->StartRec)) {
		$t_examinations_list->RowCnt++;
		if ($t_examinations->CurrentAction == "gridadd" || $t_examinations->CurrentAction == "gridedit" || $t_examinations->CurrentAction == "F") {
			$t_examinations_list->RowIndex++;
			$objForm->Index = $t_examinations_list->RowIndex;
			if ($objForm->HasValue($t_examinations_list->FormActionName))
				$t_examinations_list->RowAction = strval($objForm->GetValue($t_examinations_list->FormActionName));
			elseif ($t_examinations->CurrentAction == "gridadd")
				$t_examinations_list->RowAction = "insert";
			else
				$t_examinations_list->RowAction = "";
		}

		// Set up key count
		$t_examinations_list->KeyCount = $t_examinations_list->RowIndex;

		// Init row class and style
		$t_examinations->ResetAttrs();
		$t_examinations->CssClass = "";
		if ($t_examinations->CurrentAction == "gridadd") {
			$t_examinations_list->LoadDefaultValues(); // Load default values
		} else {
			$t_examinations_list->LoadRowValues($t_examinations_list->Recordset); // Load row values
		}
		$t_examinations->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_examinations->CurrentAction == "gridadd") // Grid add
			$t_examinations->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_examinations->CurrentAction == "gridadd" && $t_examinations->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_examinations_list->RestoreCurrentRowFormValues($t_examinations_list->RowIndex); // Restore form values
		if ($t_examinations->CurrentAction == "edit") {
			if ($t_examinations_list->CheckInlineEditKey() && $t_examinations_list->EditRowCnt == 0) { // Inline edit
				$t_examinations->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($t_examinations->CurrentAction == "gridedit") { // Grid edit
			if ($t_examinations->EventCancelled) {
				$t_examinations_list->RestoreCurrentRowFormValues($t_examinations_list->RowIndex); // Restore form values
			}
			if ($t_examinations_list->RowAction == "insert")
				$t_examinations->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_examinations->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_examinations->CurrentAction == "edit" && $t_examinations->RowType == EW_ROWTYPE_EDIT && $t_examinations->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$t_examinations_list->RestoreFormValues(); // Restore form values
		}
		if ($t_examinations->CurrentAction == "gridedit" && ($t_examinations->RowType == EW_ROWTYPE_EDIT || $t_examinations->RowType == EW_ROWTYPE_ADD) && $t_examinations->EventCancelled) // Update failed
			$t_examinations_list->RestoreCurrentRowFormValues($t_examinations_list->RowIndex); // Restore form values
		if ($t_examinations->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_examinations_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$t_examinations->RowAttrs = array_merge($t_examinations->RowAttrs, array('data-rowindex'=>$t_examinations_list->RowCnt, 'id'=>'r' . $t_examinations_list->RowCnt . '_t_examinations', 'data-rowtype'=>$t_examinations->RowType));

		// Render row
		$t_examinations_list->RenderRow();

		// Render list options
		$t_examinations_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_examinations_list->RowAction <> "delete" && $t_examinations_list->RowAction <> "insertdelete" && !($t_examinations_list->RowAction == "insert" && $t_examinations->CurrentAction == "F" && $t_examinations_list->EmptyRow())) {
?>
	<tr<?php echo $t_examinations->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_examinations_list->ListOptions->Render("body", "left", $t_examinations_list->RowCnt);
?>
	<?php if ($t_examinations->Name->Visible) { // Name ?>
		<td<?php echo $t_examinations->Name->CellAttributes() ?>>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Name" class="control-group t_examinations_Name">
<input type="text" data-field="x_Name" name="x<?php echo $t_examinations_list->RowIndex ?>_Name" id="x<?php echo $t_examinations_list->RowIndex ?>_Name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($t_examinations->Name->PlaceHolder) ?>" value="<?php echo $t_examinations->Name->EditValue ?>"<?php echo $t_examinations->Name->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Name" name="o<?php echo $t_examinations_list->RowIndex ?>_Name" id="o<?php echo $t_examinations_list->RowIndex ?>_Name" value="<?php echo ew_HtmlEncode($t_examinations->Name->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Name" class="control-group t_examinations_Name">
<input type="text" data-field="x_Name" name="x<?php echo $t_examinations_list->RowIndex ?>_Name" id="x<?php echo $t_examinations_list->RowIndex ?>_Name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($t_examinations->Name->PlaceHolder) ?>" value="<?php echo $t_examinations->Name->EditValue ?>"<?php echo $t_examinations->Name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_examinations->Name->ViewAttributes() ?>>
<?php echo $t_examinations->Name->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $t_examinations_list->PageObjName . "_row_" . $t_examinations_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_ExaminationID" name="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationID" id="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationID" value="<?php echo ew_HtmlEncode($t_examinations->ExaminationID->CurrentValue) ?>">
<input type="hidden" data-field="x_ExaminationID" name="o<?php echo $t_examinations_list->RowIndex ?>_ExaminationID" id="o<?php echo $t_examinations_list->RowIndex ?>_ExaminationID" value="<?php echo ew_HtmlEncode($t_examinations->ExaminationID->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT || $t_examinations->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_ExaminationID" name="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationID" id="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationID" value="<?php echo ew_HtmlEncode($t_examinations->ExaminationID->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_examinations->ExaminationTypeID->Visible) { // ExaminationTypeID ?>
		<td<?php echo $t_examinations->ExaminationTypeID->CellAttributes() ?>>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_ExaminationTypeID" class="control-group t_examinations_ExaminationTypeID">
<select data-field="x_ExaminationTypeID" id="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" name="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID"<?php echo $t_examinations->ExaminationTypeID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->ExaminationTypeID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `ExaminationTypeID`, `ExaminationType` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_examination_types`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->ExaminationTypeID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `ExaminationType`";
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`ExaminationTypeID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_ExaminationTypeID" name="o<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" id="o<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" value="<?php echo ew_HtmlEncode($t_examinations->ExaminationTypeID->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_ExaminationTypeID" class="control-group t_examinations_ExaminationTypeID">
<select data-field="x_ExaminationTypeID" id="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" name="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID"<?php echo $t_examinations->ExaminationTypeID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->ExaminationTypeID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `ExaminationTypeID`, `ExaminationType` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_examination_types`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->ExaminationTypeID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `ExaminationType`";
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`ExaminationTypeID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_examinations->ExaminationTypeID->ViewAttributes() ?>>
<?php echo $t_examinations->ExaminationTypeID->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_examinations->Year->Visible) { // Year ?>
		<td<?php echo $t_examinations->Year->CellAttributes() ?>>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Year" class="control-group t_examinations_Year">
<input type="text" data-field="x_Year" name="x<?php echo $t_examinations_list->RowIndex ?>_Year" id="x<?php echo $t_examinations_list->RowIndex ?>_Year" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Year->PlaceHolder) ?>" value="<?php echo $t_examinations->Year->EditValue ?>"<?php echo $t_examinations->Year->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Year" name="o<?php echo $t_examinations_list->RowIndex ?>_Year" id="o<?php echo $t_examinations_list->RowIndex ?>_Year" value="<?php echo ew_HtmlEncode($t_examinations->Year->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Year" class="control-group t_examinations_Year">
<input type="text" data-field="x_Year" name="x<?php echo $t_examinations_list->RowIndex ?>_Year" id="x<?php echo $t_examinations_list->RowIndex ?>_Year" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Year->PlaceHolder) ?>" value="<?php echo $t_examinations->Year->EditValue ?>"<?php echo $t_examinations->Year->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_examinations->Year->ViewAttributes() ?>>
<?php echo $t_examinations->Year->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_examinations->SeminsterID->Visible) { // SeminsterID ?>
		<td<?php echo $t_examinations->SeminsterID->CellAttributes() ?>>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_SeminsterID" class="control-group t_examinations_SeminsterID">
<select data-field="x_SeminsterID" id="x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" name="x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID"<?php echo $t_examinations->SeminsterID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->SeminsterID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `SemisterID`, `Semister` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_semisters`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->SeminsterID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SemisterID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_SeminsterID" name="o<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" id="o<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" value="<?php echo ew_HtmlEncode($t_examinations->SeminsterID->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_SeminsterID" class="control-group t_examinations_SeminsterID">
<select data-field="x_SeminsterID" id="x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" name="x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID"<?php echo $t_examinations->SeminsterID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->SeminsterID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `SemisterID`, `Semister` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_semisters`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->SeminsterID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SemisterID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_examinations->SeminsterID->ViewAttributes() ?>>
<?php echo $t_examinations->SeminsterID->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_examinations->CourseID->Visible) { // CourseID ?>
		<td<?php echo $t_examinations->CourseID->CellAttributes() ?>>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_CourseID" class="control-group t_examinations_CourseID">
<select data-field="x_CourseID" id="x<?php echo $t_examinations_list->RowIndex ?>_CourseID" name="x<?php echo $t_examinations_list->RowIndex ?>_CourseID"<?php echo $t_examinations->CourseID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->CourseID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_courses`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->CourseID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `CourseCode`";
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_CourseID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_CourseID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`CourseID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_CourseID" name="o<?php echo $t_examinations_list->RowIndex ?>_CourseID" id="o<?php echo $t_examinations_list->RowIndex ?>_CourseID" value="<?php echo ew_HtmlEncode($t_examinations->CourseID->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_CourseID" class="control-group t_examinations_CourseID">
<select data-field="x_CourseID" id="x<?php echo $t_examinations_list->RowIndex ?>_CourseID" name="x<?php echo $t_examinations_list->RowIndex ?>_CourseID"<?php echo $t_examinations->CourseID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->CourseID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_courses`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->CourseID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `CourseCode`";
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_CourseID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_CourseID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`CourseID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_examinations->CourseID->ViewAttributes() ?>>
<?php echo $t_examinations->CourseID->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_examinations->InsttructorID->Visible) { // InsttructorID ?>
		<td<?php echo $t_examinations->InsttructorID->CellAttributes() ?>>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_InsttructorID" class="control-group t_examinations_InsttructorID">
<select data-field="x_InsttructorID" id="x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" name="x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID"<?php echo $t_examinations->InsttructorID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->InsttructorID->OldValue = "";
?>
</select>
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
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`UserID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_InsttructorID" name="o<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" id="o<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" value="<?php echo ew_HtmlEncode($t_examinations->InsttructorID->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_InsttructorID" class="control-group t_examinations_InsttructorID">
<select data-field="x_InsttructorID" id="x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" name="x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID"<?php echo $t_examinations->InsttructorID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->InsttructorID->OldValue = "";
?>
</select>
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
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`UserID` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_examinations->InsttructorID->ViewAttributes() ?>>
<?php echo $t_examinations->InsttructorID->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_examinations->NumberOfMCQs->Visible) { // NumberOfMCQs ?>
		<td<?php echo $t_examinations->NumberOfMCQs->CellAttributes() ?>>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_NumberOfMCQs" class="control-group t_examinations_NumberOfMCQs">
<input type="text" data-field="x_NumberOfMCQs" name="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" id="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->NumberOfMCQs->PlaceHolder) ?>" value="<?php echo $t_examinations->NumberOfMCQs->EditValue ?>"<?php echo $t_examinations->NumberOfMCQs->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_NumberOfMCQs" name="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" id="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" value="<?php echo ew_HtmlEncode($t_examinations->NumberOfMCQs->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_NumberOfMCQs" class="control-group t_examinations_NumberOfMCQs">
<input type="text" data-field="x_NumberOfMCQs" name="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" id="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->NumberOfMCQs->PlaceHolder) ?>" value="<?php echo $t_examinations->NumberOfMCQs->EditValue ?>"<?php echo $t_examinations->NumberOfMCQs->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_examinations->NumberOfMCQs->ViewAttributes() ?>>
<?php echo $t_examinations->NumberOfMCQs->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_examinations->NumberOfShortAnswerQuestions->Visible) { // NumberOfShortAnswerQuestions ?>
		<td<?php echo $t_examinations->NumberOfShortAnswerQuestions->CellAttributes() ?>>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_NumberOfShortAnswerQuestions" class="control-group t_examinations_NumberOfShortAnswerQuestions">
<input type="text" data-field="x_NumberOfShortAnswerQuestions" name="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" id="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->NumberOfShortAnswerQuestions->PlaceHolder) ?>" value="<?php echo $t_examinations->NumberOfShortAnswerQuestions->EditValue ?>"<?php echo $t_examinations->NumberOfShortAnswerQuestions->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_NumberOfShortAnswerQuestions" name="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" id="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" value="<?php echo ew_HtmlEncode($t_examinations->NumberOfShortAnswerQuestions->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_NumberOfShortAnswerQuestions" class="control-group t_examinations_NumberOfShortAnswerQuestions">
<input type="text" data-field="x_NumberOfShortAnswerQuestions" name="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" id="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->NumberOfShortAnswerQuestions->PlaceHolder) ?>" value="<?php echo $t_examinations->NumberOfShortAnswerQuestions->EditValue ?>"<?php echo $t_examinations->NumberOfShortAnswerQuestions->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_examinations->NumberOfShortAnswerQuestions->ViewAttributes() ?>>
<?php echo $t_examinations->NumberOfShortAnswerQuestions->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_examinations->Duration->Visible) { // Duration ?>
		<td<?php echo $t_examinations->Duration->CellAttributes() ?>>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Duration" class="control-group t_examinations_Duration">
<input type="text" data-field="x_Duration" name="x<?php echo $t_examinations_list->RowIndex ?>_Duration" id="x<?php echo $t_examinations_list->RowIndex ?>_Duration" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Duration->PlaceHolder) ?>" value="<?php echo $t_examinations->Duration->EditValue ?>"<?php echo $t_examinations->Duration->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Duration" name="o<?php echo $t_examinations_list->RowIndex ?>_Duration" id="o<?php echo $t_examinations_list->RowIndex ?>_Duration" value="<?php echo ew_HtmlEncode($t_examinations->Duration->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Duration" class="control-group t_examinations_Duration">
<input type="text" data-field="x_Duration" name="x<?php echo $t_examinations_list->RowIndex ?>_Duration" id="x<?php echo $t_examinations_list->RowIndex ?>_Duration" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Duration->PlaceHolder) ?>" value="<?php echo $t_examinations->Duration->EditValue ?>"<?php echo $t_examinations->Duration->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_examinations->Duration->ViewAttributes() ?>>
<?php echo $t_examinations->Duration->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_examinations->Active->Visible) { // Active ?>
		<td<?php echo $t_examinations->Active->CellAttributes() ?>>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Active" class="control-group t_examinations_Active">
<div id="tp_x<?php echo $t_examinations_list->RowIndex ?>_Active" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $t_examinations_list->RowIndex ?>_Active" id="x<?php echo $t_examinations_list->RowIndex ?>_Active" value="{value}"<?php echo $t_examinations->Active->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_examinations_list->RowIndex ?>_Active" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio"><input type="radio" data-field="x_Active" name="x<?php echo $t_examinations_list->RowIndex ?>_Active" id="x<?php echo $t_examinations_list->RowIndex ?>_Active_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $t_examinations->Active->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $t_examinations->Active->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_Active" name="o<?php echo $t_examinations_list->RowIndex ?>_Active" id="o<?php echo $t_examinations_list->RowIndex ?>_Active" value="<?php echo ew_HtmlEncode($t_examinations->Active->OldValue) ?>">
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_examinations_list->RowCnt ?>_t_examinations_Active" class="control-group t_examinations_Active">
<div id="tp_x<?php echo $t_examinations_list->RowIndex ?>_Active" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $t_examinations_list->RowIndex ?>_Active" id="x<?php echo $t_examinations_list->RowIndex ?>_Active" value="{value}"<?php echo $t_examinations->Active->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_examinations_list->RowIndex ?>_Active" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio"><input type="radio" data-field="x_Active" name="x<?php echo $t_examinations_list->RowIndex ?>_Active" id="x<?php echo $t_examinations_list->RowIndex ?>_Active_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $t_examinations->Active->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $t_examinations->Active->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($t_examinations->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $t_examinations->Active->ViewAttributes() ?>>
<?php echo $t_examinations->Active->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_examinations_list->ListOptions->Render("body", "right", $t_examinations_list->RowCnt);
?>
	</tr>
<?php if ($t_examinations->RowType == EW_ROWTYPE_ADD || $t_examinations->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_examinationslist.UpdateOpts(<?php echo $t_examinations_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_examinations->CurrentAction <> "gridadd")
		if (!$t_examinations_list->Recordset->EOF) $t_examinations_list->Recordset->MoveNext();
}
?>
<?php
	if ($t_examinations->CurrentAction == "gridadd" || $t_examinations->CurrentAction == "gridedit") {
		$t_examinations_list->RowIndex = '$rowindex$';
		$t_examinations_list->LoadDefaultValues();

		// Set row properties
		$t_examinations->ResetAttrs();
		$t_examinations->RowAttrs = array_merge($t_examinations->RowAttrs, array('data-rowindex'=>$t_examinations_list->RowIndex, 'id'=>'r0_t_examinations', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_examinations->RowAttrs["class"], "ewTemplate");
		$t_examinations->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_examinations_list->RenderRow();

		// Render list options
		$t_examinations_list->RenderListOptions();
		$t_examinations_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_examinations->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_examinations_list->ListOptions->Render("body", "left", $t_examinations_list->RowIndex);
?>
	<?php if ($t_examinations->Name->Visible) { // Name ?>
		<td>
<span id="el$rowindex$_t_examinations_Name" class="control-group t_examinations_Name">
<input type="text" data-field="x_Name" name="x<?php echo $t_examinations_list->RowIndex ?>_Name" id="x<?php echo $t_examinations_list->RowIndex ?>_Name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($t_examinations->Name->PlaceHolder) ?>" value="<?php echo $t_examinations->Name->EditValue ?>"<?php echo $t_examinations->Name->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Name" name="o<?php echo $t_examinations_list->RowIndex ?>_Name" id="o<?php echo $t_examinations_list->RowIndex ?>_Name" value="<?php echo ew_HtmlEncode($t_examinations->Name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->ExaminationTypeID->Visible) { // ExaminationTypeID ?>
		<td>
<span id="el$rowindex$_t_examinations_ExaminationTypeID" class="control-group t_examinations_ExaminationTypeID">
<select data-field="x_ExaminationTypeID" id="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" name="x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID"<?php echo $t_examinations->ExaminationTypeID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->ExaminationTypeID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `ExaminationTypeID`, `ExaminationType` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_examination_types`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->ExaminationTypeID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `ExaminationType`";
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`ExaminationTypeID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_ExaminationTypeID" name="o<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" id="o<?php echo $t_examinations_list->RowIndex ?>_ExaminationTypeID" value="<?php echo ew_HtmlEncode($t_examinations->ExaminationTypeID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->Year->Visible) { // Year ?>
		<td>
<span id="el$rowindex$_t_examinations_Year" class="control-group t_examinations_Year">
<input type="text" data-field="x_Year" name="x<?php echo $t_examinations_list->RowIndex ?>_Year" id="x<?php echo $t_examinations_list->RowIndex ?>_Year" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Year->PlaceHolder) ?>" value="<?php echo $t_examinations->Year->EditValue ?>"<?php echo $t_examinations->Year->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Year" name="o<?php echo $t_examinations_list->RowIndex ?>_Year" id="o<?php echo $t_examinations_list->RowIndex ?>_Year" value="<?php echo ew_HtmlEncode($t_examinations->Year->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->SeminsterID->Visible) { // SeminsterID ?>
		<td>
<span id="el$rowindex$_t_examinations_SeminsterID" class="control-group t_examinations_SeminsterID">
<select data-field="x_SeminsterID" id="x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" name="x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID"<?php echo $t_examinations->SeminsterID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->SeminsterID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `SemisterID`, `Semister` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_semisters`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->SeminsterID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`SemisterID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_SeminsterID" name="o<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" id="o<?php echo $t_examinations_list->RowIndex ?>_SeminsterID" value="<?php echo ew_HtmlEncode($t_examinations->SeminsterID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->CourseID->Visible) { // CourseID ?>
		<td>
<span id="el$rowindex$_t_examinations_CourseID" class="control-group t_examinations_CourseID">
<select data-field="x_CourseID" id="x<?php echo $t_examinations_list->RowIndex ?>_CourseID" name="x<?php echo $t_examinations_list->RowIndex ?>_CourseID"<?php echo $t_examinations->CourseID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->CourseID->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `CourseID`, `CourseCode` AS `DispFld`, `Course` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_courses`";
$sWhereWrk = "";

// Call Lookup selecting
$t_examinations->Lookup_Selecting($t_examinations->CourseID, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `CourseCode`";
?>
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_CourseID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_CourseID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`CourseID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_CourseID" name="o<?php echo $t_examinations_list->RowIndex ?>_CourseID" id="o<?php echo $t_examinations_list->RowIndex ?>_CourseID" value="<?php echo ew_HtmlEncode($t_examinations->CourseID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->InsttructorID->Visible) { // InsttructorID ?>
		<td>
<span id="el$rowindex$_t_examinations_InsttructorID" class="control-group t_examinations_InsttructorID">
<select data-field="x_InsttructorID" id="x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" name="x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID"<?php echo $t_examinations->InsttructorID->EditAttributes() ?>>
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
if (@$emptywrk) $t_examinations->InsttructorID->OldValue = "";
?>
</select>
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
<input type="hidden" name="s_x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" id="s_x<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`UserID` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_InsttructorID" name="o<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" id="o<?php echo $t_examinations_list->RowIndex ?>_InsttructorID" value="<?php echo ew_HtmlEncode($t_examinations->InsttructorID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->NumberOfMCQs->Visible) { // NumberOfMCQs ?>
		<td>
<span id="el$rowindex$_t_examinations_NumberOfMCQs" class="control-group t_examinations_NumberOfMCQs">
<input type="text" data-field="x_NumberOfMCQs" name="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" id="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->NumberOfMCQs->PlaceHolder) ?>" value="<?php echo $t_examinations->NumberOfMCQs->EditValue ?>"<?php echo $t_examinations->NumberOfMCQs->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_NumberOfMCQs" name="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" id="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfMCQs" value="<?php echo ew_HtmlEncode($t_examinations->NumberOfMCQs->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->NumberOfShortAnswerQuestions->Visible) { // NumberOfShortAnswerQuestions ?>
		<td>
<span id="el$rowindex$_t_examinations_NumberOfShortAnswerQuestions" class="control-group t_examinations_NumberOfShortAnswerQuestions">
<input type="text" data-field="x_NumberOfShortAnswerQuestions" name="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" id="x<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->NumberOfShortAnswerQuestions->PlaceHolder) ?>" value="<?php echo $t_examinations->NumberOfShortAnswerQuestions->EditValue ?>"<?php echo $t_examinations->NumberOfShortAnswerQuestions->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_NumberOfShortAnswerQuestions" name="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" id="o<?php echo $t_examinations_list->RowIndex ?>_NumberOfShortAnswerQuestions" value="<?php echo ew_HtmlEncode($t_examinations->NumberOfShortAnswerQuestions->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->Duration->Visible) { // Duration ?>
		<td>
<span id="el$rowindex$_t_examinations_Duration" class="control-group t_examinations_Duration">
<input type="text" data-field="x_Duration" name="x<?php echo $t_examinations_list->RowIndex ?>_Duration" id="x<?php echo $t_examinations_list->RowIndex ?>_Duration" size="30" placeholder="<?php echo ew_HtmlEncode($t_examinations->Duration->PlaceHolder) ?>" value="<?php echo $t_examinations->Duration->EditValue ?>"<?php echo $t_examinations->Duration->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_Duration" name="o<?php echo $t_examinations_list->RowIndex ?>_Duration" id="o<?php echo $t_examinations_list->RowIndex ?>_Duration" value="<?php echo ew_HtmlEncode($t_examinations->Duration->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_examinations->Active->Visible) { // Active ?>
		<td>
<span id="el$rowindex$_t_examinations_Active" class="control-group t_examinations_Active">
<div id="tp_x<?php echo $t_examinations_list->RowIndex ?>_Active" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $t_examinations_list->RowIndex ?>_Active" id="x<?php echo $t_examinations_list->RowIndex ?>_Active" value="{value}"<?php echo $t_examinations->Active->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_examinations_list->RowIndex ?>_Active" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio"><input type="radio" data-field="x_Active" name="x<?php echo $t_examinations_list->RowIndex ?>_Active" id="x<?php echo $t_examinations_list->RowIndex ?>_Active_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $t_examinations->Active->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $t_examinations->Active->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_Active" name="o<?php echo $t_examinations_list->RowIndex ?>_Active" id="o<?php echo $t_examinations_list->RowIndex ?>_Active" value="<?php echo ew_HtmlEncode($t_examinations->Active->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_examinations_list->ListOptions->Render("body", "right", $t_examinations_list->RowCnt);
?>
<script type="text/javascript">
ft_examinationslist.UpdateOpts(<?php echo $t_examinations_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($t_examinations->CurrentAction == "add" || $t_examinations->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $t_examinations_list->FormKeyCountName ?>" id="<?php echo $t_examinations_list->FormKeyCountName ?>" value="<?php echo $t_examinations_list->KeyCount ?>">
<?php } ?>
<?php if ($t_examinations->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_examinations_list->FormKeyCountName ?>" id="<?php echo $t_examinations_list->FormKeyCountName ?>" value="<?php echo $t_examinations_list->KeyCount ?>">
<?php echo $t_examinations_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_examinations->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $t_examinations_list->FormKeyCountName ?>" id="<?php echo $t_examinations_list->FormKeyCountName ?>" value="<?php echo $t_examinations_list->KeyCount ?>">
<?php } ?>
<?php if ($t_examinations->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_examinations_list->FormKeyCountName ?>" id="<?php echo $t_examinations_list->FormKeyCountName ?>" value="<?php echo $t_examinations_list->KeyCount ?>">
<?php echo $t_examinations_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_examinations->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($t_examinations_list->Recordset)
	$t_examinations_list->Recordset->Close();
?>
<?php if ($t_examinations_list->TotalRecs > 0) { ?>
<?php if ($t_examinations->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($t_examinations->CurrentAction <> "gridadd" && $t_examinations->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($t_examinations_list->Pager)) $t_examinations_list->Pager = new cPrevNextPager($t_examinations_list->StartRec, $t_examinations_list->DisplayRecs, $t_examinations_list->TotalRecs) ?>
<?php if ($t_examinations_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($t_examinations_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_list->PageUrl() ?>start=<?php echo $t_examinations_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_examinations_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_list->PageUrl() ?>start=<?php echo $t_examinations_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_examinations_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($t_examinations_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_list->PageUrl() ?>start=<?php echo $t_examinations_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_examinations_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $t_examinations_list->PageUrl() ?>start=<?php echo $t_examinations_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_examinations_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_examinations_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_examinations_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_examinations_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($t_examinations_list->SearchWhere == "0=101") { ?>
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
	foreach ($t_examinations_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($t_examinations->Export == "") { ?>
<script type="text/javascript">
ft_examinationslistsrch.Init();
ft_examinationslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$t_examinations_list->ShowPageFooter();
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
$t_examinations_list->Page_Terminate();
?>
