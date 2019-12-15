<?php

// include forum database target base class file
require_once "class-forum-database-target.php";


// class for operating on forum posts in the database, used by the forum backend
class ForumPostsDatabaseTarget extends ForumDatabaseTarget
{
  // parent database target (threads target, as each post belongs to a thread)
  protected $parentTarget;


  // constructor
  public function __construct($authRequired, $parentTarget) {
    $this->tableName = "posts";
    $this->joinString = "INNER JOIN users ON posts.author = users.id INNER JOIN threads ON posts.thread = threads.id";
    $this->colsToSelect = ["posts.id", "posts.thread", "threads.title", "posts.body", "users.username", "posts.created"];
    $this->colToOrderBy = "posts.created";
    $this->orderAsc = true;
    $this->colsToInsert = ["thread", "body", "author"];
    $this->colsToUpdate = ["body"];
    $this->colsToSearch = ["posts.body"];
    $this->ownerIDCol = "author";
    $this->parentTarget = $parentTarget;
    $this->linkColToParentID = "thread";
    $this->idCol = "posts.id";
    $this->ownerIDVal = $authRequired ? $_SESSION["id"] : null;
    $this->maxInsertsPerDay = 10;
  }


  // setup method for creating a post
  protected function setupInsertRecord() {
    // check user is logged in
    if (!$this->requireAuth()) {
      return false;
    }
    // check if user has reached their maximum number of posts per day
    if (!$this->preventSpam()) {
      return false;
    }
    // check parent thread exists
    $thread = intval(sanitize($_POST["thread"]));
    if (!$this->parentTarget->checkExists($thread)) {
      return false;
    }
    $this->colValues["thread"] = $thread;
    // check post body text is valid
    $body = sanitize($_POST["body"]);
    if (!$this->setupText($body, 2000, "body", "insert")) {
      return false;
    }
    // check author ID
    return $this->setupAuthor();
  }


  // setup method for updating a post 
  protected function setupUpdateRecord() {
    // check user is logged in
    if (!$this->requireAuth()) {
      return false;
    }
    // validate ID
    if (!$this->validateID()) {
      return false;
    }
    // get updated post body
    $body = sanitize($_POST["body"]);
    // check post body text is valid
    return $this->setupText($body, 2000, "body", "update");
  }
}

// EOF
