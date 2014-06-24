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

$t_courses_addopt = NULL; // Initialize page object first

class ct_courses_addopt extends ct_courses {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{FDF9D461-C8C4-4B01-893C-EAF280CCB667}";

	// Table name
	var $TableName = 't_courses';

	// Page object name
	var $PageObjName = 't_courses_addopt';

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

		// Table object (t_courses)
		if (!isset($GLOBALS["t_courses"]) || get_class($GLOBALS["t_courses"]) == "ct_courses") {
			$GLOBALS["t_courses"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_courses"];
		}

		// Table object (t_users)
		if (!isset($GLOBALS['t_users'])) $GLOBALS['t_users'] = new ct_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_courses', TRUE);

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
			$this->Page_Terminate("t_courseslist.php");
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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		//$this->SetupBreadcrumb(); // Not used
		// Process form if post back

		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_CourseID"] = $this->CourseID->DbValue;
					$row["x_SubjectID"] = $this->SubjectID->DbValue;
					$row["x_Course"] = $this->Course->DbValue;
					$row["x_CourseCode"] = $this->CourseCode->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
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
		$this->SubjectID->CurrentValue = NULL;
		$this->SubjectID->OldValue = $this->SubjectID->CurrentValue;
		$this->Course->CurrentValue = NULL;
		$this->Course->OldValue = $this->Course->CurrentValue;
		$this->CourseCode->CurrentValue = NULL;
		$this->CourseCode->OldValue = $this->CourseCode->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->SubjectID->FldIsDetailKey) {
			$this->SubjectID->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_SubjectID")));
		}
		if (!$this->Course->FldIsDetailKey) {
			$this->Course->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_Course")));
		}
		if (!$this->CourseCode->FldIsDetailKey) {
			$this->CourseCode->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_CourseCode")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->SubjectID->CurrentValue = ew_ConvertToUtf8($this->SubjectID->FormValue);
		$this->Course->CurrentValue = ew_ConvertToUtf8($this->Course->FormValue);
		$this->CourseCode->CurrentValue = ew_ConvertToUtf8($this->CourseCode->FormValue);
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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// CourseID
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "t_courseslist.php", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, ew_CurrentUrl());
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

	// Custom validate event
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
if (!isset($t_courses_addopt)) $t_courses_addopt = new ct_courses_addopt();

// Page init
$t_courses_addopt->Page_Init();

// Page main
$t_courses_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_courses_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var t_courses_addopt = new ew_Page("t_courses_addopt");
t_courses_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = t_courses_addopt.PageID; // For backward compatibility

// Form object
var ft_coursesaddopt = new ew_Form("ft_coursesaddopt");

// Validate form
ft_coursesaddopt.Validate = function() {
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
	}
	return true;
}

// Form_CustomValidate event
ft_coursesaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_coursesaddopt.ValidateRequired = true;
<?php } else { ?>
ft_coursesaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_coursesaddopt.Lists["x_SubjectID"] = {"LinkField":"x_SubjectID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Subject","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$t_courses_addopt->ShowMessage();
?>
<form name="ft_coursesaddopt" id="ft_coursesaddopt" class="ewForm form-horizontal" action="t_coursesaddopt.php" method="post">
<input type="hidden" name="t" value="t_courses">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<div id="tbl_t_coursesaddopt">
	<div class="control-group">
		<label class="control-label" for="x_SubjectID"><?php echo $t_courses->SubjectID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_SubjectID" id="x_SubjectID" name="x_SubjectID"<?php echo $t_courses->SubjectID->EditAttributes() ?>>
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
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_Course"><?php echo $t_courses->Course->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_Course" name="x_Course" id="x_Course" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_courses->Course->PlaceHolder) ?>" value="<?php echo $t_courses->Course->EditValue ?>"<?php echo $t_courses->Course->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_CourseCode"><?php echo $t_courses->CourseCode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_CourseCode" name="x_CourseCode" id="x_CourseCode" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($t_courses->CourseCode->PlaceHolder) ?>" value="<?php echo $t_courses->CourseCode->EditValue ?>"<?php echo $t_courses->CourseCode->EditAttributes() ?>>
</div>
	</div>
</div>
</form>
<script type="text/javascript">
ft_coursesaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$t_courses_addopt->Page_Terminate();
?>
