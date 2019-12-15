<?php

// include helper utils
require_once "util.php";

// set default target (page to redirect to on logout)
// and set to GET parameter if present
$target = "index.php";
if (isset($_GET["t"])) {
  // sanitize GET parameter for safety
  $target = sanitize($_GET["t"]);
}

// start session
session_start();

// destroy any existing session
session_unset();
session_destroy();

// redirect to target
header("location: $target");
exit;

// EOF
