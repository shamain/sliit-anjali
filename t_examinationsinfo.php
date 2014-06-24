<?php

// Global variable for table object
$t_examinations = NULL;

//
// Table class for t_examinations
//
class ct_examinations extends cTable {
	var $ExaminationID;
	var $Name;
	var $ExaminationTypeID;
	var $Year;
	var $SeminsterID;
	var $CourseID;
	var $InsttructorID;
	var $NumberOfMCQs;
	var $NumberOfShortAnswerQuestions;
	var $Duration;
	var $Active;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_examinations';
		$this->TableName = 't_examinations';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// ExaminationID
		$this->ExaminationID = new cField('t_examinations', 't_examinations', 'x_ExaminationID', 'ExaminationID', '`ExaminationID`', '`ExaminationID`', 3, -1, FALSE, '`ExaminationID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ExaminationID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ExaminationID'] = &$this->ExaminationID;

		// Name
		$this->Name = new cField('t_examinations', 't_examinations', 'x_Name', 'Name', '`Name`', '`Name`', 200, -1, FALSE, '`Name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Name'] = &$this->Name;

		// ExaminationTypeID
		$this->ExaminationTypeID = new cField('t_examinations', 't_examinations', 'x_ExaminationTypeID', 'ExaminationTypeID', '`ExaminationTypeID`', '`ExaminationTypeID`', 3, -1, FALSE, '`EV__ExaminationTypeID`', TRUE, TRUE, FALSE, 'FORMATTED TEXT');
		$this->ExaminationTypeID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ExaminationTypeID'] = &$this->ExaminationTypeID;

		// Year
		$this->Year = new cField('t_examinations', 't_examinations', 'x_Year', 'Year', '`Year`', '`Year`', 3, -1, FALSE, '`Year`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Year->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Year'] = &$this->Year;

		// SeminsterID
		$this->SeminsterID = new cField('t_examinations', 't_examinations', 'x_SeminsterID', 'SeminsterID', '`SeminsterID`', '`SeminsterID`', 3, -1, FALSE, '`EV__SeminsterID`', TRUE, TRUE, FALSE, 'FORMATTED TEXT');
		$this->SeminsterID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['SeminsterID'] = &$this->SeminsterID;

		// CourseID
		$this->CourseID = new cField('t_examinations', 't_examinations', 'x_CourseID', 'CourseID', '`CourseID`', '`CourseID`', 3, -1, FALSE, '`EV__CourseID`', TRUE, TRUE, FALSE, 'FORMATTED TEXT');
		$this->CourseID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CourseID'] = &$this->CourseID;

		// InsttructorID
		$this->InsttructorID = new cField('t_examinations', 't_examinations', 'x_InsttructorID', 'InsttructorID', '`InsttructorID`', '`InsttructorID`', 3, -1, FALSE, '`InsttructorID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->InsttructorID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['InsttructorID'] = &$this->InsttructorID;

		// NumberOfMCQs
		$this->NumberOfMCQs = new cField('t_examinations', 't_examinations', 'x_NumberOfMCQs', 'NumberOfMCQs', '`NumberOfMCQs`', '`NumberOfMCQs`', 3, -1, FALSE, '`NumberOfMCQs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NumberOfMCQs->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NumberOfMCQs'] = &$this->NumberOfMCQs;

		// NumberOfShortAnswerQuestions
		$this->NumberOfShortAnswerQuestions = new cField('t_examinations', 't_examinations', 'x_NumberOfShortAnswerQuestions', 'NumberOfShortAnswerQuestions', '`NumberOfShortAnswerQuestions`', '`NumberOfShortAnswerQuestions`', 3, -1, FALSE, '`NumberOfShortAnswerQuestions`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NumberOfShortAnswerQuestions->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NumberOfShortAnswerQuestions'] = &$this->NumberOfShortAnswerQuestions;

		// Duration
		$this->Duration = new cField('t_examinations', 't_examinations', 'x_Duration', 'Duration', '`Duration`', '`Duration`', 3, -1, FALSE, '`Duration`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Duration->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Duration'] = &$this->Duration;

		// Active
		$this->Active = new cField('t_examinations', 't_examinations', 'x_Active', 'Active', '`Active`', '`Active`', 16, -1, FALSE, '`Active`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Active->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Active'] = &$this->Active;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`t_examinations`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT `ExaminationType` FROM `t_examination_types` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`ExaminationTypeID` = `t_examinations`.`ExaminationTypeID` LIMIT 1) AS `EV__ExaminationTypeID`, (SELECT `Semister` FROM `t_semisters` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`SemisterID` = `t_examinations`.`SeminsterID` LIMIT 1) AS `EV__SeminsterID`, (SELECT CONCAT(`CourseCode`,'" . ew_ValueSeparator(1, $this->CourseID) . "',`Course`) FROM `t_courses` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`CourseID` = `t_examinations`.`CourseID` LIMIT 1) AS `EV__CourseID` FROM `t_examinations`" .
			") `EW_TMP_TABLE`";
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->SqlSelectList(), $this->SqlWhere(), $this->SqlGroupBy(), 
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if (strpos($sOrderBy, " " . $this->ExaminationTypeID->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->SeminsterID->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->CourseID->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`t_examinations`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('ExaminationID', $rs))
				ew_AddFilter($where, ew_QuotedName('ExaminationID') . '=' . ew_QuotedValue($rs['ExaminationID'], $this->ExaminationID->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`ExaminationID` = @ExaminationID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->ExaminationID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@ExaminationID@", ew_AdjustSql($this->ExaminationID->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "t_examinationslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_examinationslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("t_examinationsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("t_examinationsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "t_examinationsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("t_examinationsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("t_examinationsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_examinationsdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->ExaminationID->CurrentValue)) {
			$sUrl .= "ExaminationID=" . urlencode($this->ExaminationID->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["ExaminationID"]; // ExaminationID

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->ExaminationID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->ExaminationID->setDbValue($rs->fields('ExaminationID'));
		$this->Name->setDbValue($rs->fields('Name'));
		$this->ExaminationTypeID->setDbValue($rs->fields('ExaminationTypeID'));
		$this->Year->setDbValue($rs->fields('Year'));
		$this->SeminsterID->setDbValue($rs->fields('SeminsterID'));
		$this->CourseID->setDbValue($rs->fields('CourseID'));
		$this->InsttructorID->setDbValue($rs->fields('InsttructorID'));
		$this->NumberOfMCQs->setDbValue($rs->fields('NumberOfMCQs'));
		$this->NumberOfShortAnswerQuestions->setDbValue($rs->fields('NumberOfShortAnswerQuestions'));
		$this->Duration->setDbValue($rs->fields('Duration'));
		$this->Active->setDbValue($rs->fields('Active'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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
		// ExaminationID

		$this->ExaminationID->ViewValue = $this->ExaminationID->CurrentValue;
		$this->ExaminationID->ViewCustomAttributes = "";

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

		// ExaminationID
		$this->ExaminationID->LinkCustomAttributes = "";
		$this->ExaminationID->HrefValue = "";
		$this->ExaminationID->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->Name->Exportable) $Doc->ExportCaption($this->Name);
				if ($this->ExaminationTypeID->Exportable) $Doc->ExportCaption($this->ExaminationTypeID);
				if ($this->Year->Exportable) $Doc->ExportCaption($this->Year);
				if ($this->SeminsterID->Exportable) $Doc->ExportCaption($this->SeminsterID);
				if ($this->CourseID->Exportable) $Doc->ExportCaption($this->CourseID);
				if ($this->InsttructorID->Exportable) $Doc->ExportCaption($this->InsttructorID);
				if ($this->NumberOfMCQs->Exportable) $Doc->ExportCaption($this->NumberOfMCQs);
				if ($this->NumberOfShortAnswerQuestions->Exportable) $Doc->ExportCaption($this->NumberOfShortAnswerQuestions);
				if ($this->Duration->Exportable) $Doc->ExportCaption($this->Duration);
				if ($this->Active->Exportable) $Doc->ExportCaption($this->Active);
			} else {
				if ($this->Name->Exportable) $Doc->ExportCaption($this->Name);
				if ($this->ExaminationTypeID->Exportable) $Doc->ExportCaption($this->ExaminationTypeID);
				if ($this->Year->Exportable) $Doc->ExportCaption($this->Year);
				if ($this->SeminsterID->Exportable) $Doc->ExportCaption($this->SeminsterID);
				if ($this->CourseID->Exportable) $Doc->ExportCaption($this->CourseID);
				if ($this->InsttructorID->Exportable) $Doc->ExportCaption($this->InsttructorID);
				if ($this->NumberOfMCQs->Exportable) $Doc->ExportCaption($this->NumberOfMCQs);
				if ($this->NumberOfShortAnswerQuestions->Exportable) $Doc->ExportCaption($this->NumberOfShortAnswerQuestions);
				if ($this->Duration->Exportable) $Doc->ExportCaption($this->Duration);
				if ($this->Active->Exportable) $Doc->ExportCaption($this->Active);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->Name->Exportable) $Doc->ExportField($this->Name);
					if ($this->ExaminationTypeID->Exportable) $Doc->ExportField($this->ExaminationTypeID);
					if ($this->Year->Exportable) $Doc->ExportField($this->Year);
					if ($this->SeminsterID->Exportable) $Doc->ExportField($this->SeminsterID);
					if ($this->CourseID->Exportable) $Doc->ExportField($this->CourseID);
					if ($this->InsttructorID->Exportable) $Doc->ExportField($this->InsttructorID);
					if ($this->NumberOfMCQs->Exportable) $Doc->ExportField($this->NumberOfMCQs);
					if ($this->NumberOfShortAnswerQuestions->Exportable) $Doc->ExportField($this->NumberOfShortAnswerQuestions);
					if ($this->Duration->Exportable) $Doc->ExportField($this->Duration);
					if ($this->Active->Exportable) $Doc->ExportField($this->Active);
				} else {
					if ($this->Name->Exportable) $Doc->ExportField($this->Name);
					if ($this->ExaminationTypeID->Exportable) $Doc->ExportField($this->ExaminationTypeID);
					if ($this->Year->Exportable) $Doc->ExportField($this->Year);
					if ($this->SeminsterID->Exportable) $Doc->ExportField($this->SeminsterID);
					if ($this->CourseID->Exportable) $Doc->ExportField($this->CourseID);
					if ($this->InsttructorID->Exportable) $Doc->ExportField($this->InsttructorID);
					if ($this->NumberOfMCQs->Exportable) $Doc->ExportField($this->NumberOfMCQs);
					if ($this->NumberOfShortAnswerQuestions->Exportable) $Doc->ExportField($this->NumberOfShortAnswerQuestions);
					if ($this->Duration->Exportable) $Doc->ExportField($this->Duration);
					if ($this->Active->Exportable) $Doc->ExportField($this->Active);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
