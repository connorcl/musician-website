<?php

// include database target base class file
require_once "class-database-target.php";


// database target class for managing account
class AccountDatabaseTarget extends DatabaseTarget
{
  // include basic setup/validation functions
  use BasicDatabaseTargetSetup;
  
  
  // constructor
  public function __construct() {
    $this->tableName = "users";
    $this->joinString = "";
    $this->colsToSelect = ["id", "username", "email", "email_consent"];
    $this->colToOrderBy = "username";
    $this->orderAsc = true;
    $this->colsToInsert = null;
    $this->colsToUpdate = ["username", "email", "passwd", "email_consent"];
    $this->colsToSearch = null;
    $this->ownerIDCol = "id";
    $this->linkColToParentID = null;
    $this->idCol = "id";
    $this->ownerIDVal = $_SESSION["id"];
  }


  // validate and use ID from session as match value
  protected function validateIDFromSession() {
    if (intval($_SESSION["id"]) > 0) {
      $this->matchVal = intval($_SESSION["id"]);
      return true;
    }
    return false;
  }


  // validate username
  protected function validateUsername() {
    // access global database connection
    global $conn;
    // get username from POST data
    $username = sanitize($_POST["username"]);
    // if username is not empty
    if (!empty($username)) {
      // if username contains only valid characters
      if (preg_replace("/[^A-Za-z0-9\.]/", "", $username) === $username) {
        // if username is between 4 and 20 characters long
        if (strlen($username) >= 4 && strlen($username) <= 20) {
          // check username is not taken
          $query = $this->generateCountQuery() . " WHERE username = :username";
          $result = executePreparedStmt($query, [":username"=>$username], false);
          if ($result["num"] == 0) {
            // set value to update
            $this->colValues["username"] = $username;
            // update username session variable
            $_SESSION["username"] = $username;
            return true;
          } else {
            // set error if username is taken
            $this->error = "This username is already taken, or it is your current username.";
            return false;
          }
        } else {
          // set error if username is too short or long
          $this->error = "Username must be between 4 and 20 characters long";
          return false;
        }
      } else {
        // set error if username is invalid
        $this->error = "Username must only contain numbers, letters or .";
        return false;
      }
    }
    return false;
  }


  // validate email
  protected function validateEmail() {
    // access global database connection
    global $conn;
    // get email from POST data
    $email = sanitize($_POST["email"]);
    // if email is not empty
    if (!empty($email)) {
      // if email matches email pattern
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // check email is unique
        $query = $this->generateCountQuery() . " WHERE email = :email";
        $result = executePreparedStmt($query, [":email"=>$email], false);
        if ($result["num"] == 0) {
          // set value to update
          $this->colValues["email"] = $email;
          return true;
        } else {
          // set error if email is taken
          $this->error = "An account with this email address already exists, or it is your current email address.";
        }
      } else {
        // set error if email address is invalid
        $this->error = "Please enter a valid email address";
        return false;
      }
    }
    return false;
  }
  

  // validate email consent
  protected function validateEmailConsent() {
    // get email consent from POST data
    $emailConsent = sanitize($_POST["email_consent"]);
    if ($emailConsent === "1") {
      $this->colValues["email_consent"] = 1;
      return true;
    } elseif ($emailConsent === "0") {
      $this->colValues["email_consent"] = 0;
      return true;
    }
    return false;
  }

  
  // disallow insertion
  protected function setupInsertRecord() {
    return false;
  }


  // set up values to update
  protected function setupUpdateRecord() {
    // validate ID
    if (!$this->validateIDFromSession()) {
      return false;
    }
    // set update vals to empty array
    $this->colValues = array();
    // whether to perform update (at least one value to update is given)
    $update = $this->validateUsername();
    $update = $this->validateEmail() || $update;
    $update = $this->validatePassword() || $update;
    $update = $this->validateEmailConsent() || $update;
    // return whether there is at least one updated value
    return $update && !$this->error;
  }


  // get ID from session instead of POST
  protected function setupGetRecordByID() {
    return $this->validateIDFromSession();
  }


  // get delete ID from session instead of POST
  protected function setupDeleteRecord() {
    return $this->validateIDFromSession();
  }

  
  // run relevant function based on action string
  protected function getActionResult($action) {
    if ($action === "update") {
      return $this->updateRecord();
    } elseif ($action === "delete") {
      return $this->deleteRecord();
    } elseif($action === "get-by-id") {
      return $this->getRecordByID();
    } else {
      return false;
    }
  }
}

// EOF
