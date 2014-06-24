<?php

// Global variable for table object
$t_course_instructor = NULL;

//
// Table class for t_course_instructor
//
class ct_course_instructor extends cTable {
	var $CourseInstructorID;
	var $CourseID;
	var $Year;
	var $SemisterID;
	var $InstructorID;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_course_instructor';
		$this->TableName = 't_course_instructor';
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

		// CourseInstructorID
		$this->CourseInstructorID = new cField('t_course_instructor', 't_course_instructor', 'x_CourseInstructorID', 'CourseInstructorID', '`CourseInstructorID`', '`CourseInstructorID`', 3, -1, FALSE, '`CourseInstructorID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->CourseInstructorID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CourseInstructorID'] = &$this->CourseInstructorID;

		// CourseID
		$this->CourseID = new cField('t_course_instructor', 't_course_instructor', 'x_CourseID', 'CourseID', '`CourseID`', '`CourseID`', 3, -1, FALSE, '`EV__CourseID`', TRUE, TRUE, FALSE, 'FORMATTED TEXT');
		$this->CourseID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CourseID'] = &$this->CourseID;

		// Year
		$this->Year = new cField('t_course_instructor', 't_course_instructor', 'x_Year', 'Year', '`Year`', '`Year`', 3, -1, FALSE, '`Year`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Year->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Year'] = &$this->Year;

		// SemisterID
		$this->SemisterID = new cField('t_course_instructor', 't_course_instructor', 'x_SemisterID', 'SemisterID', '`SemisterID`', '`SemisterID`', 3, -1, FALSE, '`EV__SemisterID`', TRUE, TRUE, FALSE, 'FORMATTED TEXT');
		$this->SemisterID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['SemisterID'] = &$this->SemisterID;

		// InstructorID
		$this->InstructorID = new cField('t_course_instructor', 't_course_instructor', 'x_InstructorID', 'InstructorID', '`InstructorID`', '`InstructorID`', 3, -1, FALSE, '`InstructorID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->InstructorID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['InstructorID'] = &$this->InstructorID;
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
		return "`t_course_instructor`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT CONCAT(`CourseCode`,'" . ew_ValueSeparator(1, $this->CourseID) . "',`Course`) FROM `t_courses` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`CourseID` = `t_course_instructor`.`CourseID` LIMIT 1) AS `EV__CourseID`, (SELECT `Semister` FROM `t_semisters` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`SemisterID` = `t_course_instructor`.`SemisterID` LIMIT 1) AS `EV__SemisterID` FROM `t_course_instructor`" .
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
		if (strpos($sOrderBy, " " . $this->CourseID->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->SemisterID->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "`t_course_instructor`";

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
			if (array_key_exists('CourseInstructorID', $rs))
				ew_AddFilter($where, ew_QuotedName('CourseInstructorID') . '=' . ew_QuotedValue($rs['CourseInstructorID'], $this->CourseInstructorID->FldDataType));
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
		return "`CourseInstructorID` = @CourseInstructorID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->CourseInstructorID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@CourseInstructorID@", ew_AdjustSql($this->CourseInstructorID->CurrentValue), $sKeyFilter); // Replace key value
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
			return "t_course_instructorlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_course_instructorlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("t_course_instructorview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("t_course_instructorview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "t_course_instructoradd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("t_course_instructoredit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("t_course_instructoradd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_course_instructordelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->CourseInstructorID->CurrentValue)) {
			$sUrl .= "CourseInstructorID=" . urlencode($this->CourseInstructorID->CurrentValue);
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
			$arKeys[] = @$_GET["CourseInstructorID"]; // CourseInstructorID

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
			$this->CourseInstructorID->CurrentValue = $key;
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
		$this->CourseInstructorID->setDbValue($rs->fields('CourseInstructorID'));
		$this->CourseID->setDbValue($rs->fields('CourseID'));
		$this->Year->setDbValue($rs->fields('Year'));
		$this->SemisterID->setDbValue($rs->fields('SemisterID'));
		$this->InstructorID->setDbValue($rs->fields('InstructorID'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// CourseInstructorID

		$this->CourseInstructorID->CellCssStyle = "white-space: nowrap;";

		// CourseID
		// Year
		// SemisterID
		// InstructorID
		// CourseInstructorID

		$this->CourseInstructorID->ViewValue = $this->CourseInstructorID->CurrentValue;
		$this->CourseInstructorID->ViewCustomAttributes = "";

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

		// CourseInstructorID
		$this->CourseInstructorID->LinkCustomAttributes = "";
		$this->CourseInstructorID->HrefValue = "";
		$this->CourseInstructorID->TooltipValue = "";

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
				if ($this->CourseID->Exportable) $Doc->ExportCaption($this->CourseID);
				if ($this->Year->Exportable) $Doc->ExportCaption($this->Year);
				if ($this->SemisterID->Exportable) $Doc->ExportCaption($this->SemisterID);
				if ($this->InstructorID->Exportable) $Doc->ExportCaption($this->InstructorID);
			} else {
				if ($this->CourseID->Exportable) $Doc->ExportCaption($this->CourseID);
				if ($this->Year->Exportable) $Doc->ExportCaption($this->Year);
				if ($this->SemisterID->Exportable) $Doc->ExportCaption($this->SemisterID);
				if ($this->InstructorID->Exportable) $Doc->ExportCaption($this->InstructorID);
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
					if ($this->CourseID->Exportable) $Doc->ExportField($this->CourseID);
					if ($this->Year->Exportable) $Doc->ExportField($this->Year);
					if ($this->SemisterID->Exportable) $Doc->ExportField($this->SemisterID);
					if ($this->InstructorID->Exportable) $Doc->ExportField($this->InstructorID);
				} else {
					if ($this->CourseID->Exportable) $Doc->ExportField($this->CourseID);
					if ($this->Year->Exportable) $Doc->ExportField($this->Year);
					if ($this->SemisterID->Exportable) $Doc->ExportField($this->SemisterID);
					if ($this->InstructorID->Exportable) $Doc->ExportField($this->InstructorID);
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
