<?php

// handle POST data
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  // connect to database
  require_once "config.php";

  // include account database target base class
  require_once "class-account-database-target.php";

  // ensure user is logged in
  session_start();
  if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    session_unset();
    session_destroy();
    exit;
  }

  // create account database target object
  $accountTarget = new AccountDatabaseTarget();

  // get action from POST parameter
  $action = trim($_POST["action"]);

  // run action and output results
  echo json_encode($accountTarget->runAction($action));

  // close database connection
  unset($conn);
}

// EOF
