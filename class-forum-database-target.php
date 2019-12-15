<?php

// include database target class file
require_once "class-database-target.php";


// forum database target which includes both standard traits
abstract class ForumDatabaseTarget extends DatabaseTarget
{
  // maximum permitted insertions per day
  protected $maxInsertsPerDay;


  // include basic validation helper methods
  use BasicDatabaseTargetSetup;


  // setup helper method to check if max insertions per day has been reached
  protected function preventSpam() {
    // access global database connection object
    global $conn;
    // clear params
    $this->params = array();
    // generate query to count number of insertions by current user in current day
    $query = $this->generateCountQuery();
    $query .= " WHERE date(created) = CURRENT_DATE";
    $query = $this->extendQueryWithAuth($query);
    // get result
    $result = executePreparedStmt($query, $this->params, false);
    // if number is less than maximum, return true
    if ($result["num"] < $this->maxInsertsPerDay) {
      return true;
    // otherwise, record an error message and return false
    } else {
      $this->error = "Maximum number of " . $this->tableName . " per day has been reached";
      return false;
    }
  }


  // setup helper method for validating author ID
  protected function setupAuthor() {
    // if owner ID is not already set
    if (is_null($this->ownerIDVal)) {
      // get author from POST parameter
      $author = intval(sanitize($_POST["author"]));
      // if author ID is not positive
      if ($author <= 0) {
        // record error message and return false
        $this->error = "Invalid author ID";
        return false;
      }
      // record author ID
      $this->colValues["author"] = $author;
    // otherwise (author ID has been set)
    } else {
      // record author ID
      $this->colValues["author"] = $this->ownerIDVal;
    }
    return true;
  }


  // setup helper method to validate that text is not empty and shorter than limit
  protected function setupText($text, $maxLen, $colName, $action) {
    $ucColName = ucwords($colName);
    // if text is longer than maximum length, record error
    if (strlen($text) > $maxLen) {
      $this->error = "$ucColName text must be shorter than $maxLen characters";
      return false;
    // if text is empty, record error
    } elseif (strlen($text) < 1) {
      $this->error = "$ucColName text is empty";
      return false;
    }
    // save text in appropriate property
    $this->colValues[$colName] = $text;
    return true;
  }


  // setup helper method to ensure user is logged in
  protected function requireAuth() {
    // if user is not logged in, record error and return false, otherwise return true
    if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
      $this->error = "Please log in to continue";
      return false;
    }
    return true;
  }
}

// EOF
