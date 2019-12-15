<?php

// handle POST data
if ($_SERVER["REQUEST_METHOD"] == "POST")
{  
  // connect to database
  require_once "config.php";

  // include events target class file
  require_once "class-events-database-target.php";

  // create target object
  $eventsTarget = new EventsDatabaseTarget();

  // get action from POST parameter
  $action = trim($_POST["action"]);

  // run action and output results as JSON
  echo json_encode($eventsTarget->runAction($action));

  // close database connection
  unset($conn);
}

// EOF
