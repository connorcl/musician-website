<?php

// database details and credentials
$server = "phpmyadmin.abertay.ac.uk";
$username = "sql1802565";
$password = "b8WIYmFFuV2d";
$database = "sql1802565";

try {
  // connect to database
  $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
  // set error mode
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connection successful";
} catch(PDOException $e) {
  //echo $e->getMessage();
}

// EOF
