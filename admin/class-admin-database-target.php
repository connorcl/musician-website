<?php

// include database target base class
require_once "../class-database-target.php";


// class representing a target for administrative database management
class AdminDatabaseTarget extends DatabaseTarget
{
  // include basic setup/validation methods
  use BasicDatabaseTargetSetup;

  
  // constructor which takes associative array as argument
  public function __construct($args) {
    $this->tableName = $args["tableName"];
    $this->joinString = $args["joinString"];
    $this->colsToSelect = $args["colsToSelect"];
    $this->colsToInsert = $args["colsToInsert"];
    $this->colsToUpdate = $args["colsToUpdate"];
    $this->colsToSearch = $args["colsToSearch"];
    $this->ownerIDCol = null;
    $this->linkColToParentID = $args["linkColToParentID"];
    $this->idCol = $args["idCol"];
    $this->ownerIDVal = null;
  }


  // setup and validation method for inserting a record
  protected function setupInsertRecord() {
    // create array which will store column-value pairs
    $this->colValues = array();
    // for each column to be inserted
    foreach ($this->colsToInsert as $col) {
      // if column is not password
      if ($col != "passwd") {
        // get value from POST parameter
        $val = sanitize($_POST[$col]);
        // add value to array if value is not empty
        if (!empty($val)) {
          $this->colValues[$col] = $val;
        } else {
          // fail if parameter is not set, as all fields are required for insert
          $this->colValues = array();
          return false;
        }
      // if column is password
      } else {
        if (!$this->validatePassword(false)) {
          // fail if password does not validate
          $this->colValues = array();
          return false;
        }
      }
    }
    return true;
  }


  // setup and validation method for updating a record
  protected function setupUpdateRecord() {
    // validate ID parameter
    if (!$this->validateID()) {
      return false;
    }
    // whether to perform update (at least one value to update is given)
    $update = false;
    // array of column-value pairs
    $this->colValues = array();
    // for each column which may be updated
    foreach ($this->colsToUpdate as $col) {
      if ($col != "passwd") {
        // get value from POST parameter
        $val = sanitize($_POST[$col]);
        // if value is set
        if (!empty($val)) {
          // add column and value to array
          $this->colValues[$col] = $val;
          if (!$update) {
            // set update to true when the first set value is found
            $update = true;
          }
        }
      } else {
        $update = $this->validatePassword() || $update;
      }
    }
    // return whether there is at least one updated value and no errors
    return $update && !$this->error;
  }
}

// EOF
