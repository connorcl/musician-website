<?php

// handle POST data
if ($_SERVER["REQUEST_METHOD"] == "POST")
{  
  // start session
  session_start();

  // connect to database
  require_once "config.php";

  // include forum database class files
  require_once "class-forum-threads-database-target.php";
  require_once "class-forum-posts-database-target.php";

  // actions which require user authentication
  $authActions = ["insert", "update", "delete"];
  // whether authentication is required
  $authRequired = true;

  // get target and action from POST parameters
  $target = trim($_POST["target"]);
  $action = trim($_POST["action"]);

  // ensure user is logged in if necessary
  if (!in_array(strtolower($action), $authActions)) {
    $authRequired = false;
  }

  // create target objects
  $threadsTarget = new ForumThreadsDatabaseTarget($authRequired);
  $postsTarget = new ForumPostsDatabaseTarget($authRequired, $threadsTarget);

  // run action on relevant target
  if ($target == "threads") {
    echo json_encode($threadsTarget->runAction($action));
  } elseif ($target == "posts") {
    echo json_encode($postsTarget->runAction($action));
  }

  // close database connection
  unset($conn);
}

// EOF
