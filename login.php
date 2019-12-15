<!-- Login page -->

<?php

// require helper utils
require_once "util.php";

// page to redirect to on login
$target = "index.php";
// whether account has been successfully created
$justRegistered = false;

// process GET data
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["t"])) {
    // sanitize for safety
    $target = sanitize($_GET["t"]);
  }
  if (isset($_GET["new"]) && trim($_GET["new"]) === "true") {
    // set whether user has just registered
    $justRegistered = true;
  }
}

// redirect if user is logged in
session_start();
if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true) {
  header("location: $target");
  exit;
} else {
  session_unset();
  session_destroy();
}

// inputs
$username = $passwd = "";
// error messages indicating inputs are invalid
$usernameErrMsg = $passwdErrMsg = NULL;

// if data is recieved via POST
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  // get target from POST parameter
  // and sanitize for safety
  $target = sanitize($_POST["target-page"]);

  // whether account has just been created
  $justRegistered = trim($_POST["new-account"]) === "true" ? true : false;

  // connect to database
  require_once "config.php";

  // check valid username was entered
  $username = sanitize($_POST["username"]);
  if (empty($username)) {
    $usernameErrMsg = "Please enter your username.";
  }

  // check password was entered
  $passwd = trim($_POST["password"]);
  if (empty($passwd)) {
    $passwdErrMsg = "Please enter your password.";
  }

  // check credentials if inputs were valid
  if (is_null($usernameErrMsg) && is_null($passwdErrMsg)) {
    // attempt to prepare select statement
    $query = "SELECT COUNT(*) AS num, id, username, email, email_consent, passwd FROM users WHERE username = :username";
    $record = executePreparedStmt($query, [":username"=>$username], false);
    // get number of rows from result
    $numRecords = $record["num"];
    // if a single result is present
    if ($numRecords == 1) {
      // verify password is correct
      if (password_verify($passwd, $record["passwd"])) {
        // set up a new session
        session_start();
        $_SESSION["user_logged_in"] = true;
        $_SESSION["id"] = $record["id"];
        $_SESSION["username"] = $username;
        // redirect to target
        header("location: $target");
      } else {
        $passwdErrMsg = "Incorrect password.";
      }
    } else {
      $usernameErrMsg = "No account with this username exists";
    }
  }

  // close database connection
  unset($conn);
}

?>


<!doctype html>
<html lang="en">
  <head>
    <!-- meta elements -->
    <meta charset="utf-8">
    <meta name="description" content="Login page for an unoffical website dedicated to the singer-songwriter John Smith">
    <meta name="keywords" content="login,account,forum,music,musician,singer,songwriter,country,folk,americana,tour">
    <meta name="author" content="Connor Claypool">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- page title -->
    <title>John Smith - Login</title>
    <!-- Universal stylesheets -->
    <?php require_once "css-includes.php";?>
  </head>

  <body>
    <!-- Navigation bar -->
    <?php require_once "navbar.php"; ?>

    <!-- Main content - login form -->
    <main aria-labelledby="login-header" class="container">
      <?php
      if ($justRegistered) {
        echo '<div class="alert alert-dark alert-dismissable">';
        echo 'Account created successfully! You may now log in.';
        echo '<button aria-label="close" type="button" class="close" data-dismiss="alert">&times;</button>';
        echo '</div>';
      }
      ?>
      <h1 id="login-header" class="text-light text-center mt-5">Sign in</h1>
      <p class="text-light text-center">Enter your login details below</p>
      <!-- Login form -->
      <form aria-labelledby="login-header" method="post" class="basic-form">
        <!-- Where to redirect on success -->
        <input type="hidden" name="target-page" value="<?= $target; ?>">
        <!-- Whether account was just created -->
        <input type="hidden" name="new-account" value="<?= $justRegistered; ?>">
        <!-- Email input -->
        <div class="form-group">
          <label class="text-light" for="username">Username</label>
          <input id="username"
                 name="username"
                 type="text"
                 class="shadow basic-textbox rounded-0 form-control <?= is_null($usernameErrMsg) ? "" : "invalid-input"; ?>"
                 value="<?= $username; ?>">
          <?= is_null($usernameErrMsg) ? "" :  "<div class='basic-help-block shadow'>$usernameErrMsg</div>"; ?>
        </div>
        <!-- Password input -->
        <div class="form-group">
          <label class="text-light" for="password">Password</label>
          <input id="password"
                 name="password" 
                 type="password"
                 class="shadow basic-textbox rounded-0 form-control <?= is_null($passwdErrMsg) ? "" : "invalid-input"; ?>"
                 value="<?= sanitize($passwd); ?>">
          <?= is_null($passwdErrMsg) ? "" :  "<div class='basic-help-block shadow'>$passwdErrMsg</div>"; ?>
        </div>
        <div class="text-center">
          <button type="submit" class="basic-btn major-btn my-2">Sign In</button>
        </div>
      </form>
    </main>

     <!-- Universal scripts -->
     <?php require_once "js-includes.php";?>
  </body>
</html>