<?php

// include forum database target base class file
require_once "class-forum-database-target.php";


// class for operating on forum threads in the database, used by the forum backend
class ForumThreadsDatabaseTarget extends ForumDatabaseTarget
{
  // constructor
  public function __construct($authRequired) {
    $this->tableName = "threads";
    $this->joinString = "INNER JOIN users ON threads.author = users.id";
    $this->colsToSelect = ["threads.id", "threads.title", "users.username", "threads.created"];
    $this->colToOrderBy = "threads.created";
    $this->orderAsc = false;
    $this->colsToInsert = ["title", "author"];
    $this->colsToUpdate = ["title"];
    $this->colsToSearch = ["threads.title"];
    $this->ownerIDCol = "author";
    $this->linkColToParentID = null;
    $this->idCol = "threads.id";
    $this->ownerIDVal = $authRequired ? $_SESSION["id"] : null;
    $this->maxInsertsPerDay = 3;
  }


  // setup function for creating a new thread
  protected function setupInsertRecord() {
    // check user is logged in
    if (!$this->requireAuth()) {
      return false;
    }
    // check maxium threads per day has not been reached
    if (!$this->preventSpam()) {
      return false;
    }
    // check title is valid
    $title = sanitize($_POST["title"]);
    if (!$this->setupText($title, 200, "title", "insert")) {
      return false;
    }
    // set up author ID
    return $this->setupAuthor();
  }


  // setup function for updating a thread
  protected function setupUpdateRecord() {
    // check user is logged in
    if (!$this->requireAuth()) {
      return false;
    }
    // check id is valid
    if (!$this->validateID()) {
      return false;
    }
    // check new title is valid
    $title = sanitize($_POST["title"]);
    return $this->setupText($title, 200, "title", "update");
  }
}

// EOF
