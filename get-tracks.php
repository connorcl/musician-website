<?php

// connect to database
require_once "config.php";

// include helper utils
require_once "util.php";

// get album id from POST parameter
$album = intval(trim($_POST["album"]));

// get tracks from database
$query = "SELECT title FROM tracks WHERE album = :album";
echo json_encode(executePreparedStmt($query, [":album"=>$album]));

// close database connection
unset($conn);

// EOF
