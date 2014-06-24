<?php

// Global variable for table object
$t_users = NULL;

//
// Table class for t_users
//
class ct_users extends cTable {
	var $_UserID;
	var $Username;
	var $Password;
	var $UserLevel;
	var $Activated;
	var $DesignationID;
	var $FirstName;
	var $MiddleName;
	var $LastName;
	var $_Email;
	var $RegistrationNumber;
	var $NICNumber;
	var $Gender;
	var $MaritalStatusID;
	var $DateOfBirth;
	var $RegisteredOn;
	var $RegistrationValidTill;
	var $PhotoPath;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_users';
		$this->TableName = 't_users';
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

		// UserID
		$this->_UserID = new cField('t_users', 't_users', 'x__UserID', 'UserID', '`UserID`', '`UserID`', 3, -1, FALSE, '`UserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_UserID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['UserID'] = &$this->_UserID;

		// Username
		$this->Username = new cField('t_users', 't_users', 'x_Username', 'Username', '`Username`', '`Username`', 200, -1, FALSE, '`Username`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Username'] = &$this->Username;

		// Password
		$this->Password = new cField('t_users', 't_users', 'x_Password', 'Password', '`Password`', '`Password`', 200, -1, FALSE, '`Password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Password'] = &$this->Password;

		// UserLevel
		$this->UserLevel = new cField('t_users', 't_users', 'x_UserLevel', 'UserLevel', '`UserLevel`', '`UserLevel`', 3, -1, FALSE, '`UserLevel`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->UserLevel->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['UserLevel'] = &$this->UserLevel;

		// Activated
		$this->Activated = new cField('t_users', 't_users', 'x_Activated', 'Activated', '`Activated`', '`Activated`', 16, -1, FALSE, '`Activated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Activated->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Activated'] = &$this->Activated;

		// DesignationID
		$this->DesignationID = new cField('t_users', 't_users', 'x_DesignationID', 'DesignationID', '`DesignationID`', '`DesignationID`', 3, -1, FALSE, '`EV__DesignationID`', TRUE, TRUE, FALSE, 'FORMATTED TEXT');
		$this->DesignationID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['DesignationID'] = &$this->DesignationID;

		// FirstName
		$this->FirstName = new cField('t_users', 't_users', 'x_FirstName', 'FirstName', '`FirstName`', '`FirstName`', 200, -1, FALSE, '`FirstName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FirstName'] = &$this->FirstName;

		// MiddleName
		$this->MiddleName = new cField('t_users', 't_users', 'x_MiddleName', 'MiddleName', '`MiddleName`', '`MiddleName`', 200, -1, FALSE, '`MiddleName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['MiddleName'] = &$this->MiddleName;

		// LastName
		$this->LastName = new cField('t_users', 't_users', 'x_LastName', 'LastName', '`LastName`', '`LastName`', 200, -1, FALSE, '`LastName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['LastName'] = &$this->LastName;

		// Email
		$this->_Email = new cField('t_users', 't_users', 'x__Email', 'Email', '`Email`', '`Email`', 200, -1, FALSE, '`Email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Email'] = &$this->_Email;

		// RegistrationNumber
		$this->RegistrationNumber = new cField('t_users', 't_users', 'x_RegistrationNumber', 'RegistrationNumber', '`RegistrationNumber`', '`RegistrationNumber`', 200, -1, FALSE, '`RegistrationNumber`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['RegistrationNumber'] = &$this->RegistrationNumber;

		// NICNumber
		$this->NICNumber = new cField('t_users', 't_users', 'x_NICNumber', 'NICNumber', '`NICNumber`', '`NICNumber`', 200, -1, FALSE, '`NICNumber`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NICNumber'] = &$this->NICNumber;

		// Gender
		$this->Gender = new cField('t_users', 't_users', 'x_Gender', 'Gender', '`Gender`', '`Gender`', 16, -1, FALSE, '`Gender`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Gender->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Gender'] = &$this->Gender;

		// MaritalStatusID
		$this->MaritalStatusID = new cField('t_users', 't_users', 'x_MaritalStatusID', 'MaritalStatusID', '`MaritalStatusID`', '`MaritalStatusID`', 3, -1, FALSE, '`EV__MaritalStatusID`', TRUE, TRUE, FALSE, 'FORMATTED TEXT');
		$this->MaritalStatusID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['MaritalStatusID'] = &$this->MaritalStatusID;

		// DateOfBirth
		$this->DateOfBirth = new cField('t_users', 't_users', 'x_DateOfBirth', 'DateOfBirth', '`DateOfBirth`', 'DATE_FORMAT(`DateOfBirth`, \'%Y/%m/%d\')', 133, 5, FALSE, '`DateOfBirth`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->DateOfBirth->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['DateOfBirth'] = &$this->DateOfBirth;

		// RegisteredOn
		$this->RegisteredOn = new cField('t_users', 't_users', 'x_RegisteredOn', 'RegisteredOn', '`RegisteredOn`', 'DATE_FORMAT(`RegisteredOn`, \'%Y/%m/%d\')', 133, 5, FALSE, '`RegisteredOn`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->RegisteredOn->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['RegisteredOn'] = &$this->RegisteredOn;

		// RegistrationValidTill
		$this->RegistrationValidTill = new cField('t_users', 't_users', 'x_RegistrationValidTill', 'RegistrationValidTill', '`RegistrationValidTill`', 'DATE_FORMAT(`RegistrationValidTill`, \'%Y/%m/%d\')', 133, 5, FALSE, '`RegistrationValidTill`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->RegistrationValidTill->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['RegistrationValidTill'] = &$this->RegistrationValidTill;

		// PhotoPath
		$this->PhotoPath = new cField('t_users', 't_users', 'x_PhotoPath', 'PhotoPath', '`PhotoPath`', '`PhotoPath`', 200, -1, TRUE, '`PhotoPath`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['PhotoPath'] = &$this->PhotoPath;
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
		return "`t_users`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT `Designation` FROM `t_designations` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`DesignationID` = `t_users`.`DesignationID` LIMIT 1) AS `EV__DesignationID`, (SELECT `MaritalStatus` FROM `t_marital_statuses` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`MaritalStatusID` = `t_users`.`MaritalStatusID` LIMIT 1) AS `EV__MaritalStatusID` FROM `t_users`" .
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
		if (strpos($sOrderBy, " " . $this->DesignationID->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->MaritalStatusID->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "`t_users`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'Password')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
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
			if (EW_ENCRYPTED_PASSWORD && $name == 'Password') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
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
			if (array_key_exists('UserID', $rs))
				ew_AddFilter($where, ew_QuotedName('UserID') . '=' . ew_QuotedValue($rs['UserID'], $this->_UserID->FldDataType));
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
		return "`UserID` = @_UserID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->_UserID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@_UserID@", ew_AdjustSql($this->_UserID->CurrentValue), $sKeyFilter); // Replace key value
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
			return "t_userslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_userslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("t_usersview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("t_usersview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "t_usersadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("t_usersedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("t_usersadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_usersdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->_UserID->CurrentValue)) {
			$sUrl .= "_UserID=" . urlencode($this->_UserID->CurrentValue);
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
			$arKeys[] = @$_GET["_UserID"]; // UserID

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
			$this->_UserID->CurrentValue = $key;
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
		$this->_UserID->setDbValue($rs->fields('UserID'));
		$this->Username->setDbValue($rs->fields('Username'));
		$this->Password->setDbValue($rs->fields('Password'));
		$this->UserLevel->setDbValue($rs->fields('UserLevel'));
		$this->Activated->setDbValue($rs->fields('Activated'));
		$this->DesignationID->setDbValue($rs->fields('DesignationID'));
		$this->FirstName->setDbValue($rs->fields('FirstName'));
		$this->MiddleName->setDbValue($rs->fields('MiddleName'));
		$this->LastName->setDbValue($rs->fields('LastName'));
		$this->_Email->setDbValue($rs->fields('Email'));
		$this->RegistrationNumber->setDbValue($rs->fields('RegistrationNumber'));
		$this->NICNumber->setDbValue($rs->fields('NICNumber'));
		$this->Gender->setDbValue($rs->fields('Gender'));
		$this->MaritalStatusID->setDbValue($rs->fields('MaritalStatusID'));
		$this->DateOfBirth->setDbValue($rs->fields('DateOfBirth'));
		$this->RegisteredOn->setDbValue($rs->fields('RegisteredOn'));
		$this->RegistrationValidTill->setDbValue($rs->fields('RegistrationValidTill'));
		$this->PhotoPath->Upload->DbValue = $rs->fields('PhotoPath');
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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
		// UserID

		$this->_UserID->ViewValue = $this->_UserID->CurrentValue;
		$this->_UserID->ViewCustomAttributes = "";

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

		// UserID
		$this->_UserID->LinkCustomAttributes = "";
		$this->_UserID->HrefValue = "";
		$this->_UserID->TooltipValue = "";

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
				if ($this->Username->Exportable) $Doc->ExportCaption($this->Username);
				if ($this->Password->Exportable) $Doc->ExportCaption($this->Password);
				if ($this->UserLevel->Exportable) $Doc->ExportCaption($this->UserLevel);
				if ($this->Activated->Exportable) $Doc->ExportCaption($this->Activated);
				if ($this->DesignationID->Exportable) $Doc->ExportCaption($this->DesignationID);
				if ($this->FirstName->Exportable) $Doc->ExportCaption($this->FirstName);
				if ($this->MiddleName->Exportable) $Doc->ExportCaption($this->MiddleName);
				if ($this->LastName->Exportable) $Doc->ExportCaption($this->LastName);
				if ($this->_Email->Exportable) $Doc->ExportCaption($this->_Email);
				if ($this->RegistrationNumber->Exportable) $Doc->ExportCaption($this->RegistrationNumber);
				if ($this->NICNumber->Exportable) $Doc->ExportCaption($this->NICNumber);
				if ($this->Gender->Exportable) $Doc->ExportCaption($this->Gender);
				if ($this->MaritalStatusID->Exportable) $Doc->ExportCaption($this->MaritalStatusID);
				if ($this->DateOfBirth->Exportable) $Doc->ExportCaption($this->DateOfBirth);
				if ($this->RegisteredOn->Exportable) $Doc->ExportCaption($this->RegisteredOn);
				if ($this->RegistrationValidTill->Exportable) $Doc->ExportCaption($this->RegistrationValidTill);
				if ($this->PhotoPath->Exportable) $Doc->ExportCaption($this->PhotoPath);
			} else {
				if ($this->Username->Exportable) $Doc->ExportCaption($this->Username);
				if ($this->Password->Exportable) $Doc->ExportCaption($this->Password);
				if ($this->UserLevel->Exportable) $Doc->ExportCaption($this->UserLevel);
				if ($this->Activated->Exportable) $Doc->ExportCaption($this->Activated);
				if ($this->DesignationID->Exportable) $Doc->ExportCaption($this->DesignationID);
				if ($this->FirstName->Exportable) $Doc->ExportCaption($this->FirstName);
				if ($this->MiddleName->Exportable) $Doc->ExportCaption($this->MiddleName);
				if ($this->LastName->Exportable) $Doc->ExportCaption($this->LastName);
				if ($this->_Email->Exportable) $Doc->ExportCaption($this->_Email);
				if ($this->RegistrationNumber->Exportable) $Doc->ExportCaption($this->RegistrationNumber);
				if ($this->NICNumber->Exportable) $Doc->ExportCaption($this->NICNumber);
				if ($this->Gender->Exportable) $Doc->ExportCaption($this->Gender);
				if ($this->MaritalStatusID->Exportable) $Doc->ExportCaption($this->MaritalStatusID);
				if ($this->DateOfBirth->Exportable) $Doc->ExportCaption($this->DateOfBirth);
				if ($this->RegisteredOn->Exportable) $Doc->ExportCaption($this->RegisteredOn);
				if ($this->RegistrationValidTill->Exportable) $Doc->ExportCaption($this->RegistrationValidTill);
				if ($this->PhotoPath->Exportable) $Doc->ExportCaption($this->PhotoPath);
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
					if ($this->Username->Exportable) $Doc->ExportField($this->Username);
					if ($this->Password->Exportable) $Doc->ExportField($this->Password);
					if ($this->UserLevel->Exportable) $Doc->ExportField($this->UserLevel);
					if ($this->Activated->Exportable) $Doc->ExportField($this->Activated);
					if ($this->DesignationID->Exportable) $Doc->ExportField($this->DesignationID);
					if ($this->FirstName->Exportable) $Doc->ExportField($this->FirstName);
					if ($this->MiddleName->Exportable) $Doc->ExportField($this->MiddleName);
					if ($this->LastName->Exportable) $Doc->ExportField($this->LastName);
					if ($this->_Email->Exportable) $Doc->ExportField($this->_Email);
					if ($this->RegistrationNumber->Exportable) $Doc->ExportField($this->RegistrationNumber);
					if ($this->NICNumber->Exportable) $Doc->ExportField($this->NICNumber);
					if ($this->Gender->Exportable) $Doc->ExportField($this->Gender);
					if ($this->MaritalStatusID->Exportable) $Doc->ExportField($this->MaritalStatusID);
					if ($this->DateOfBirth->Exportable) $Doc->ExportField($this->DateOfBirth);
					if ($this->RegisteredOn->Exportable) $Doc->ExportField($this->RegisteredOn);
					if ($this->RegistrationValidTill->Exportable) $Doc->ExportField($this->RegistrationValidTill);
					if ($this->PhotoPath->Exportable) $Doc->ExportField($this->PhotoPath);
				} else {
					if ($this->Username->Exportable) $Doc->ExportField($this->Username);
					if ($this->Password->Exportable) $Doc->ExportField($this->Password);
					if ($this->UserLevel->Exportable) $Doc->ExportField($this->UserLevel);
					if ($this->Activated->Exportable) $Doc->ExportField($this->Activated);
					if ($this->DesignationID->Exportable) $Doc->ExportField($this->DesignationID);
					if ($this->FirstName->Exportable) $Doc->ExportField($this->FirstName);
					if ($this->MiddleName->Exportable) $Doc->ExportField($this->MiddleName);
					if ($this->LastName->Exportable) $Doc->ExportField($this->LastName);
					if ($this->_Email->Exportable) $Doc->ExportField($this->_Email);
					if ($this->RegistrationNumber->Exportable) $Doc->ExportField($this->RegistrationNumber);
					if ($this->NICNumber->Exportable) $Doc->ExportField($this->NICNumber);
					if ($this->Gender->Exportable) $Doc->ExportField($this->Gender);
					if ($this->MaritalStatusID->Exportable) $Doc->ExportField($this->MaritalStatusID);
					if ($this->DateOfBirth->Exportable) $Doc->ExportField($this->DateOfBirth);
					if ($this->RegisteredOn->Exportable) $Doc->ExportField($this->RegisteredOn);
					if ($this->RegistrationValidTill->Exportable) $Doc->ExportField($this->RegistrationValidTill);
					if ($this->PhotoPath->Exportable) $Doc->ExportField($this->PhotoPath);
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
