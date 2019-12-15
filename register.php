<!-- User registration page -->

<?php

// require helper utils
require_once "util.php";

// set default target (page to redirect to once logged in)
// and set to GET parameter if present
$target = "index.php";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["t"])) {
    // sanitize for safety
    $target = sanitize($_GET["t"]);
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
$username = $email = $passwd = $confirmPasswd = $privacyConsent = "";
// error messages indicating inputs are invalid
$usernameErrMsg = $emailErrMsg = $passwdErrMsg = $confirmPasswdErrMsg = $privacyErrMsg = NULL;

// if input is sent via POST (i.e form has been submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  // get target from POST parameter
  // and sanitize for safety
  $target = sanitize($_POST["target-page"]);

  // connect to database
  require_once "config.php";

  // validate username
  $username = sanitize($_POST["username"]);
  // ensure username only contains valid characters
  if (preg_replace("/[^A-Za-z0-9\.]/", "", $username) === $username) {
    // ensure username is at least 4 characters long
    if (strlen($username) >= 4 && strlen($username) <= 20) {
      // check username is unique
      $query = "SELECT COUNT(*) AS num FROM users WHERE username = :username";
      $result = executePreparedStmt($query, [":username"=>$username], false);
      // get number of rows from result
      $numRows = $result["num"];
      // set error message if number of rows is not 0
      if ($numRows != 0) {
        $usernameErrMsg = "This username is already taken.";
      }
    } else {
      $usernameErrMsg = "Username must be between 4 and 20 characters long";
    }
  } else {
    $usernameErrMsg = "Username must only include letters, numbers and .";
  }

  // validate email address
  $email = sanitize($_POST["email"]);
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // check email address is unique
    $query = "SELECT COUNT(*) AS num FROM users WHERE email = :email";
    $result = executePreparedStmt($query, [":email"=>$email], false);
    // get number of rows from result
    $numRows = $result["num"];
    // set error message if number of rows is not 0
    if ($numRows != 0) {
      $emailErrMsg = "An account with this email address already exists";
    }
  // set error message if email address is not valid
  } else {
    $emailErrMsg = "Please enter a valid email address.";
  }

  // validate password
  $passwd = trim($_POST["password"]);
  if (strlen($passwd) < 12) {
    $passwdErrMsg = "Please enter a password at least 12 characters long.";
  }

  // validate password confirmation
  $confirmPasswd = trim($_POST["confirm-password"]);
  if ($confirmPasswd !== $passwd) {
    $confirmPasswdErrMsg = "Passwords did not match.";
  }

  // validate privacy policy consent
  $privacyConsent = trim($_POST["privacy-consent"]);
  if ($privacyConsent !== "true") {
    $privacyErrMsg = "You must agree to the privacy policy.";
  }

  // get communications preferences
  $emailConsent = trim($_POST["email-consent"]);
  $emailConsent = ($emailConsent === "true") ? true : false;

  // add user to database if inputs were valid
  if (is_null($usernameErrMsg) && 
      is_null($emailErrMsg) && 
      is_null($passwdErrMsg) && 
      is_null($confirmPasswdErrMsg) &&
      is_null($privacyErrMsg)) {
    // get salted and hashed password
    $hashedPasswd = password_hash($passwd, PASSWORD_DEFAULT);
    // insert user into database
    $query = "INSERT INTO users (username, email, passwd, email_consent) VALUES (:username, :email, :passwd, :email_consent)";
    executePreparedStmt($query, [":username"=>$username, ":email"=>$email, ":passwd"=>$hashedPasswd, ":email_consent"=>$emailConsent]);
    // redirect to login page
    header("location: login.php?new=true&t=$target");
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
    <meta name="description" content="Registration page for an unoffical website dedicated to the singer-songwriter John Smith">
    <meta name="keywords" content="register,account,forum,music,musician,singer,songwriter,country,folk,americana,tour">
    <meta name="author" content="Connor Claypool">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- page title -->
    <title>John Smith - Register</title>
    <!-- Universal stylesheets -->
    <?php require_once "css-includes.php"; ?>
  </head>

  <body>
    <!-- Include navigation bar -->
    <?php require_once "navbar.php"; ?>

    <!-- Main content - registration form -->
    <main class="container mt-5" aria-labelledby="register-header">
      <header aria-labelledby="register-header">
        <h1 id="register-header" class="text-light text-center">Create an Account</h1>
        <p class="text-light text-center">Enter your details below to register</p>
      </header>
      <!-- Registration form -->
      <form aria-labelledby="register-header" method="post" class="basic-form">
        <!-- Where to redirect on success -->
        <input type="hidden" name="target-page" value="<?= $target; ?>">
        <!-- Usernmame input -->
        <div class="form-group">
          <label class="text-light" for="username">Username</label>
          <input name="username"
                 id="username"
                 type="text"
                 class="shadow basic-textbox rounded-0 form-control <?= is_null($usernameErrMsg) ? "" : "invalid-input"; ?>"
                 value="<?= $username; ?>">
          <?= is_null($usernameErrMsg) ? "" :  "<div class='basic-help-block shadow'>$usernameErrMsg</div>"; ?>
        </div>
        <!-- Email input -->
        <div class="form-group">
          <label class="text-light" for="email">Email address</label>
          <input name="email"
                 id="email"
                 type="email"
                 class="shadow basic-textbox rounded-0 form-control <?= is_null($emailErrMsg) ? "" : "invalid-input"; ?>"
                 value="<?= $email; ?>">
          <?= is_null($emailErrMsg) ? "" :  "<div class='basic-help-block shadow'>$emailErrMsg</div>"; ?>
        </div>
        <!-- Password input -->
        <div class="form-group">
          <label class="text-light" for="password">Password</label>
          <input name="password"
                 id="password"
                 type="password"
                 class="shadow basic-textbox rounded-0 form-control <?= is_null($passwdErrMsg) ? "" : "invalid-input"; ?>"
                 value="<?= sanitize($passwd); ?>">
          <?= is_null($passwdErrMsg) ? "" :  "<div class='basic-help-block shadow'>$passwdErrMsg</div>"; ?>
        </div>
        <!-- Password confirmation input -->
        <div class="form-group">
          <label class="text-light" for="confirm-password">Confirm password</label>
          <input name="confirm-password" 
                 id="confirm-password"
                 type="password" 
                 class="shadow basic-textbox rounded-0 form-control <?= is_null($confirmPasswdErrMsg) ? "" : "invalid-input"; ?>"
                 value="<?= sanitize($confirmPasswd); ?>">
          <?= is_null($confirmPasswdErrMsg) ? "" :  "<div class='basic-help-block shadow'>$confirmPasswdErrMsg</div>"; ?>
        </div>
        <div class="text-center">
          <!-- Privacy consent -->
          <div class="form-group mb-1">
            <input type="checkbox" id="privacy-consent" name="privacy-consent" value="true">
            <label class="text-light" for="privacy-consent">I agree to the <a class="text-light" href="privacy.php">Privacy Policy</a>.</label>
            <?= is_null($privacyErrMsg) ? "" :  "<div class='basic-help-block shadow'>$privacyErrMsg</div>"; ?>
          </div>
          <!-- Email consent -->
          <div class="form-group mb-1">
            <input type="checkbox" id="email-consent" name="email-consent" value="true">
            <label class="text-light d-inline" for="email-consent">
              I agree to receive occasional updates and marketing information
              by way of the email address given. I understand that I can opt out at any time.
            </label>
          </div>
          <button type="submit" class="basic-btn major-btn my-2">Create Account</button>
        </div>
      </form>
    </main>

    <!-- Include footer -->
    <?php require_once "footer.php"; ?>

    <!-- Universal scripts -->
    <?php require_once "js-includes.php"; ?>
  </body>
</html>