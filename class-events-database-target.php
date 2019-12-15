<?php

// include database target class file
require_once "class-database-target.php";


// database target for viewing events
class EventsDatabaseTarget extends DatabaseTarget
{
  // include standard setup/validation methods
  use BasicDatabaseTargetSetup;

  // constructor
  public function __construct() {
    $this->tableName = "events";
    $this->joinString = "";
    $this->colsToSelect = ["id", "title", "description", "location", "link", "date_and_time", "timezone", "img"];
    $this->colToOrderBy = "date_and_time";
    $this->orderAsc = true;
    $this->colsToInsert = null;
    $this->colsToUpdate = null;
    $this->colsToSearch = ["title", "description", "location"];
    $this->ownerIDCol = null;
    $this->linkColToParentID = null;
    $this->idCol = "id";
    $this->ownerIDVal = null;
  }

  
  // disallow update
  protected function setupUpdateRecord() {
    return false;
  }

  
  // disallow insert
  protected function setupInsertRecord() {
    return false;
  }


  // get soonest event
  protected function getSoonest() {
    // access global database connection
    global $conn;
    // generate select query
    $query = $this->generateSelectQuery() . " WHERE DATE(date_and_time) >= CURRENT_DATE";
    $query = $this->extendQueryWithOrderBy($query) . " LIMIT 1";
    // fetch event record
    $result = executePreparedStmt($query, [], false);
    return $result;
  }


  // run relevant function based on action string
  protected function getActionResult($action) {
    if ($action == "get-count") {
      return $this->getPageCount();
    } elseif ($action == "get-page") {
      return $this->getPage();
    } elseif ($action == "get-soonest") {
      return $this->getSoonest();
    } else {
      return false;
    }
  }
}

// EOF
