<?php

// start session
session_start();

// destroy any existing session
session_unset();
session_destroy();

// redirect to login page
header("location: login.php");
exit;

// EOF
