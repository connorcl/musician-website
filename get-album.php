<?php

// connect to database
require_once "config.php";

// include helper utils
require_once "util.php";

// get id from POST parameter
$id = intval(trim($_POST["id"]));

// get album from database
$query = "SELECT title, release_year, img FROM albums WHERE id = :id";
echo json_encode(executePreparedStmt($query, [":id"=>$id], false));

// close database connection
unset($conn);

// EOF
