<?php

// include helper utils
require_once "util.php";


abstract class DatabaseTarget
{
  // name of table in database
  protected $tableName;
  // joins to other tables for select and search
  protected $joinString;

  // columns to select
  protected $colsToSelect;
  // col to order by
  protected $colToOrderBy;
  // whether to order selection ascending
  protected $orderAsc;
  // columns required to insert a record
  protected $colsToInsert;
  // columns which may be updated
  protected $colsToUpdate;
  // columns to search
  protected $colsToSearch;

  // column containing owner id for authentication
  protected $ownerIDCol;
  // link to parent id
  protected $linkColToParentID;
  // name of ID column
  protected $idCol;
  // value compared with owner ID col for authentication
  protected $ownerIDVal;
  // number of records per page
  protected $pageSize = 2;

  // parameters for prepared statements
  protected $params;
  // associative array of columns and values to insert/update
  protected $colValues;
  // search term
  protected $searchTerm;
  // value to match by
  protected $matchVal;
  // offset for select queries
  protected $offset = -1;
  // error message
  protected $error = false;


  // generate query text to count records
  protected function generateCountQuery() {
    return "SELECT COUNT(*) AS num FROM " . $this->tableName;
  }

  
  // generate query text for a select statement
  protected function generateSelectQuery() {
    // convert array of cols to select into string
    $colsString = implode(", ", $this->colsToSelect);
    // generate select query
    $query = "SELECT $colsString FROM " . $this->tableName;
    // add join string if not empty
    if (!empty($this->joinString)) {
      $query .= (" " . $this->joinString);
    }
    return $query;
  }


  // generate insert query text and set up prepared statement parameters
  protected function generateInsertQuery() {
    // convert array of cols to insert into into string
    $colsString = implode(", ", $this->colsToInsert);
    // generate first part of insert query (up to values)
    $query = "INSERT INTO " . $this->tableName . " ($colsString) VALUES (";
    // set up prepared statement params
    $params = array();
    foreach ($this->colValues as $col => $val) {
      $paramName = formatParamName($col);
      $params[] = ":$paramName";
      $this->params[":$paramName"] = $val;
    }
    // convert array of params into string and append to query
    $query .= implode(", ", $params);
    $query .= ")";
    return $query;
  }


  // generate update query text and set up prepared statement parameters
  protected function generateUpdateQuery() {
    // generate first part of update query text
    $query = "UPDATE " . $this->tableName . " SET ";
    // set up prepared statement params
    $params = array();
    foreach ($this->colValues as $col => $val) {
      $paramName = formatParamName($col);
      $params[] = "$col = :$paramName";
      $this->params[":$paramName"] = $val;
    }
    // convert array of column-parameter pairs to string and append to query
    $query .= implode(", ", $params);
    return $query;
  }


  // generate delete query text
  protected function generateDeleteQuery() {
    return "DELETE FROM " . $this->tableName;
  }


  // exetend query text with match
  protected function extendQueryWithMatch($query, $colToMatch) {
    $this->params[":match"] = $this->matchVal;
    return $query . " WHERE $colToMatch = :match";
  }


  // extend query text with search
  protected function extendQueryWithSearch($query) {
    $query .= " WHERE ";
    // generate array of LIKE statements
    $params = array();
    foreach ($this->colsToSearch as $col) {
      $paramName = formatParamName($col);
      $params[] = "$col LIKE :$paramName";
      $this->params[":$paramName"] = $this->searchTerm;
    }
    // join LIKE statements with OR
    $query .= implode(" OR ", $params);
    return $query;
  }


  // extend match query text with authentication as owner
  protected function extendQueryWithAuth($query) {
    $this->params[":auth"] = $this->ownerIDVal;
    return $query . " AND " . $this->ownerIDCol . " = :auth";
  }


  // extend query text with limit and offset for pagination
  protected function extendQueryWithLimitAndOffset($query) {
    $this->params[":lim"] = $this->pageSize;
    $this->params[":offs"] = $this->offset;
    return $query . " LIMIT :lim OFFSET :offs";
  }


  // extend a query with order by
  protected function extendQueryWithOrderBy($query) {
    return $query . " ORDER BY " . $this->colToOrderBy . ($this->orderAsc === true ? " ASC" : " DESC");
  }


  // generate a search query
  protected function generateFullSearchQuery($query, $orderBy = false) {
    // set up search term
    if (!empty($this->searchTerm)) {
      $query = $this->extendQueryWithSearch($query);
    }
    // add order by if necessary
    if ($orderBy && !is_null($this->colToOrderBy)) {
      $query = $this->extendQueryWithOrderBy($query);
    }
    // add limit and offset to query if necessary
    if ($this->offset >= 0) {
      $query = $this->extendQueryWithLimitAndOffset($query);
    }
    return $query;
  }


  // generate a match query
  protected function generateFullMatchQuery($query, $colToMatch, $orderBy = false, $auth = true) {
    // ensure match value is present
    if (!(intval($this->matchVal) > 0)) {
      return false;
    }
    $query = $this->extendQueryWithMatch($query, $colToMatch);
    // add order by if necessary
    if ($orderBy && !is_null($this->colToOrderBy)) {
      $query = $this->extendQueryWithOrderBy($query);
    }
    // add limit and offset to query if necessary
    if ($this->offset >= 0) {
      $query = $this->extendQueryWithLimitAndOffset($query);
    }
    // add authentication as owner if specified
    if ($auth) {
      $query = $this->extendQueryWithAuth($query);
    }
    return $query;
  }


  // abstract method to set up data for checkExists()
  abstract protected function setupCheckExists($id);


  // check a record exists by its ID
  public function checkExists($id) {
    // access global database connection
    global $conn;
    // set up required data
    if (!$this->setupCheckExists($id)) {
      return false;
    }
    // clear params
    $this->params = array();
    // clear offset
    $this->offset = -1;
    // generate query text
    $query = $this->generateCountQuery();
    $query = $this->generateFullMatchQuery($query, $this->idCol, false, false);
    // get result
    $result = executePreparedStmt($query, $this->params, false);
    // return whether record exists
    return intval($result["num"]) > 0;
  }


  // abstract method to set up data for getPageCount()
  abstract protected function setupGetPageCount($byParent);


  // get number of pages based on search or parent
  protected function getPageCount($byParent = false) {
    // set up required data
    if (!$this->setupGetPageCount($byParent)) {
      return false;
    }
    // clear params
    $this->params = array();
    // generate count query text
    $query = $this->generateCountQuery();
    // clear offet
    $this->offset = -1;
    // generate complete query and get result
    if ($byParent) {
      $query = $this->generateFullMatchQuery($query, $this->linkColToParentID, false, false);
    } else {
      $query = $this->generateFullSearchQuery($query);
    }
    // get result of query
    $result = executePreparedStmt($query, $this->params, false);
    // calculate and return page count
    return ceil($result["num"] / $this->pageSize);
  }


  // abstract method to set up data for getPage()
  abstract protected function setupGetPage($byParent);


  // get a page of records
  protected function getPage($byParent = false) {
    // set up required data
    if (!$this->setupGetPage($byParent)) {
      return false;
    }
    // clear params
    $this->params = array();
    // generate select query text
    $query = $this->generateSelectQuery();
    // generate complete query
    if ($byParent) {
      $query = $this->generateFullMatchQuery($query, $this->linkColToParentID, true, false);
    } else {
      $query = $this->generateFullSearchQuery($query, true);
    }
    // return result
    return executePreparedStmt($query, $this->params);
  }


  // abstract method to set up data for getRecordByID()
  abstract protected function setupGetRecordByID();


  // get a record by its ID
  protected function getRecordByID() {
    // set up required data
    if (!$this->setupGetRecordByID()) {
      return false;
    }
    // clear params
    $this->params = array();
    // clear offset
    $this->offset = -1;
    // generate query text and set up params
    $query = $this->generateSelectQuery();
    $query = $this->generateFullMatchQuery($query, $this->idCol, false, false);
    // return result
    return executePreparedStmt($query, $this->params, false);
  }


  // abstract method to set up data for insertRecord()
  abstract protected function setupInsertRecord();


  // insert a new record
  protected function insertRecord() {
    // access global databse connection
    global $conn;
    // set up required data
    if (!$this->setupInsertRecord()) {
      return false;
    }
    // clear params
    $this->params = array();
    // generate insert query text
    $query = $this->generateInsertQuery();
    // execute
    return executePreparedStmt($query, $this->params, false, false);
  }


  // abstract method to set up data for updateRecord()
  abstract protected function setupUpdateRecord();


  // update an existing record
  protected function updateRecord() {
    // access global databse connection
    global $conn;
    // set up required data
    if (!$this->setupUpdateRecord()) {
      return false;
    }
    // clear params
    $this->params = array();
    // generate update query text
    $query = $this->generateUpdateQuery();
    $query = $this->extendQueryWithMatch($query, $this->idCol);
    if (!is_null($this->ownerIDVal)) {
      $query = $this->extendQueryWithAuth($query);
    }
    // execute
    return executePreparedStmt($query, $this->params, false, false);
  }


  // abstract method to set up data for deleteRecord()
  abstract protected function setupDeleteRecord();


  // delete a record
  protected function deleteRecord() {
    // set up required data
    if (!$this->setupDeleteRecord()) {
      return false;
    }
    // clear offset
    $this->offset = -1;
    // generate delete query text
    $query = $this->generateDeleteQuery();
    $query = $this->generateFullMatchQuery($query, $this->idCol, false, !is_null($this->ownerIDVal));
    // execute
    return executePreparedStmt($query, $this->params, false, false);
  }


  // run relevant function based on action string
  protected function getActionResult($action) {
    if ($action == "get-count") {
      return $this->getPageCount();
    } elseif ($action == "get-page") {
      return $this->getPage();
    } elseif ($action == "get-by-id") {
      return $this->getRecordByID();
    } elseif ($action == "update") {
      return $this->updateRecord();
    } elseif ($action == "delete") {
      return $this->deleteRecord();
    } elseif ($action == "get-count-by-match") {
      return $this->getPageCount(true);
    } elseif ($action == "get-page-by-match") {
      return $this->getPage(true);
    } elseif ($action == "insert") {
      return $this->insertRecord();
    } else {
      return false;
    }
  }

  
  // run action based on action string and return action result along with error message
  public function runAction($action) {
    return ["data" => $this->getActionResult($action), "error" => $this->error];
  }
}


// trait which includes validation helper methods and basic validation for read queries
trait BasicDatabaseTargetSetup
{
  // validate ID and set match value if valid
  protected function validateID() {
    // get ID from POST parameter
    $id = intval(sanitize($_POST["match"]));
    // check ID is positive
    if ($id <= 0) {
      $this->error = "Invalid record ID";
      return false;
    }
    $this->matchVal = $id;
    return true;
  }


  // validate search term
  protected function validateSearchTerm() {
    // get search term from POST parameter
    $searchTerm = str_replace("%", "\%", sanitize($_POST["searchTerm"]));
    // check search term is 
    if (strlen($searchTerm) > 100) {
      $this->error = "Please enter a search term shorter than 100 characters";
      return false;
    }
    $this->searchTerm = "%$searchTerm%";
    return true;
  }


  // validate password
  protected function validatePassword($update = true) {
    // get password and confirmation
    $passwd = trim($_POST["passwd"]);
    $confirm = trim($_POST["confirm-passwd"]);
    // if password is not empty
    if (!empty($passwd)) {
      // if password and confirmation are equal
      if ($passwd === $confirm) {
        // if password is at least 12 characters
        if (strlen($passwd) >= 12) {
          // set value as hashed password
          $passwd = password_hash($passwd, PASSWORD_DEFAULT);
          $this->colValues["passwd"] = $passwd;
          return true;
        } else {
          // set error if password is too short
          $this->error = "Password must be at least 12 characters";
          return false;
        }
      } else {
        // set error if passwords don't match
        $this->error = "Passwords do not match";
        return false;
      }
    }
    return false;
  }


  // basic validation for check exists query
  protected function setupCheckExists($id) {
    if ($id <= 0) {
      $this->error = "Invalid record ID";
      return false;
    }
    $this->matchVal = $id;
    return true;
  }


  // basic validation for page count query
  protected function setupGetPageCount($byParent) {
    if ($byParent) {
      return $this->validateID();
    } else {
      return $this->validateSearchTerm();
    }
  }


  // basic validation for get page query
  protected function setupGetPage($byParent) {
    // get page number from POST parameter
    $page = intval(sanitize($_POST["page"]));
    // check page number is positive
    if ($page <= 0) {
      $this->error = "Invalid page number";
      return false;
    }
    // set offset
    $this->offset = ($page - 1) * $this->pageSize;
    // if page is selected by parent ID
    if ($byParent) {
      // validate parent ID
      return $this->validateID();
    // otherwise
    } else {
      // validate search term
      return $this->validateSearchTerm();
    }
  }


  // validate ID for get record by ID query
  protected function setupGetRecordByID() {
    return $this->validateID();
  }


  // validate ID for delete query
  protected function setupDeleteRecord() {
    return $this->validateID();
  }
}

// EOF
