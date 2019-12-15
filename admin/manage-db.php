<?php

// process POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{ 
  // ensure admin is logged in
  session_start();
  if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    session_unset();
    session_destroy();
    exit;
  }

  // require admin database target class file
  require_once "class-admin-database-target.php";
  // connect to database
  require_once "../config.php";
  
  // associative array of target string => target object pairs
  $targets = array();

  // create users target
  $args = array();
  $args["tableName"] = "users";
  $args["joinString"] = "";
  $args["colsToSelect"] = ["id", "username", "email", "registered"];
  $args["colsToSearch"] = ["username", "email"];
  $args["colsToInsert"] = $args["colsToUpdate"] = ["username", "email", "passwd"];
  $args["idCol"] = "id";
  $args["linkColToParentID"] = null;
  $targets["users"] = new AdminDatabaseTarget($args);

  // create posts target
  $args = array();
  $args["tableName"] = "posts";
  $args["joinString"] = "INNER JOIN users ON posts.author = users.id";
  $args["colsToSelect"] = ["posts.id", "posts.thread", "posts.body", "users.username", "posts.created"];
  $args["colsToInsert"] = $args["colsToUpdate"] = ["thread", "body", "author"];
  $args["colsToSearch"] = ["posts.body"];
  $args["idCol"] = "posts.id";
  $args["linkColToParentID"] = "thread";
  $targets["posts"] = new AdminDatabaseTarget($args);

  // create threads target
  $args = array();
  $args["tableName"] = "threads";
  $args["joinString"] = "INNER JOIN users ON threads.author = users.id";
  $args["colsToSelect"] = ["threads.id", "threads.title", "users.username", "threads.created"];
  $args["colsToUpdate"] = ["title"];
  $args["colsToInsert"] = ["title", "author"];
  $args["colsToSearch"] = ["threads.title"];
  $args["idCol"] = "threads.id";
  $args["linkColToParentID"] = null;
  $targets["threads"] = new AdminDatabaseTarget($args);

  // create tracks target
  $args = array();
  $args["tableName"] = "tracks";
  $args["joinString"] = "";
  $args["colsToSelect"] = ["id", "album", "title"];
  $args["colsToUpdate"] = ["title"];
  $args["colsToInsert"] = ["title", "album"];
  $args["colsToSearch"] = ["title"];
  $args["idCol"] = "id";
  $args["linkColToParentID"] = "album";
  $targets["tracks"] = new AdminDatabaseTarget($args);

  // create albums target
  $args = array();
  $args["tableName"] = "albums";
  $args["joinString"] = "";
  $args["colsToSelect"] = ["id", "title", "release_year", "img"];
  $args["colsToUpdate"] = $args["colsToInsert"] = ["title", "release_year", "img"];
  $args["colsToSearch"] = ["title", "release_year"];
  $args["idCol"] = "id";
  $args["linkColToParentID"] = null;
  $targets["albums"] = new AdminDatabaseTarget($args);

  // create events target
  $args = array();
  $args["tableName"] = "events";
  $args["joinString"] = "";
  $args["colsToSelect"] = ["id", "title", "description", "location", "link", "date_and_time", "timezone", "img"];
  $args["colsToUpdate"] = $args["colsToInsert"] = ["title", "description", "location", "link", "date_and_time", "timezone", "img"];
  $args["colsToSearch"] = ["title", "description", "location", "link", "date_and_time"];
  $args["idCol"] = "id";
  $args["linkColToParentID"] = null;
  $targets["events"] = new AdminDatabaseTarget($args);

  // create admins target
  $args = array();
  $args["tableName"] = "admins";
  $args["joinString"] = "";
  $args["colsToSelect"] = ["id", "username", "created"];
  $args["colsToSearch"] = ["username"];
  $args["colsToInsert"] = $args["colsToUpdate"] = ["username", "passwd"];
  $args["idCol"] = "id";
  $args["linkColToParentID"] = null;
  $targets["admins"] = new AdminDatabaseTarget($args);

  // get target and action from POST parameters
  $target = trim($_POST["target"]);
  $action = trim($_POST["action"]);

  // if there is an entry for target and action sent via POST
  if (isset($targets[$target])) {
    // run action on target
    echo json_encode($targets[$target]->runAction($action));
  }

  // close database connection
  unset($conn);
}

// EOF
