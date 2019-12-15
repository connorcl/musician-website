<!-- Admin login page -->

<?php

// check if admin is already logged in
session_start();
if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true) {
  header("location: index.php");
  exit;
} else {
  session_unset();
  session_destroy();
}

// include helper utils
require_once "../util.php";

// inputs
$username = $password = ""; 
// error messages indicating validity of inputs
$usernameErrMsg = $passwordErrMsg = NULL;

// handle submission of POST data
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  // connect to database
  require_once "../config.php";

  // validate username input
  $username = sanitize($_POST["username"]);
  if (strlen($username) < 1 || strlen($username) > 25) {
    $usernameErrMsg = "Please enter your username.";
  }

  // validate password input
  $password = trim($_POST["password"]);
  if (strlen($password) < 1) {
    $passwordErrMsg = "Please enter your password.";
  }

  // check credentials if inputs are valid
  if (is_null($usernameErrMsg) && is_null($passwordErrMsg)) {
    // prepare select statement
    $query = "SELECT COUNT(*) AS num, id, passwd FROM admins WHERE username = :username";
    $result = executePreparedStmt($query, [":username"=>$username], false);
    // check if user exists and password matches
    if (intval($result["num"]) === 1 && password_verify($password, $result["passwd"])) {
      // start session if login successful
      session_start();
      $_SESSION["admin_logged_in"] = true;
      $_SESSION["id"] = intval($result["id"]);
      $_SESSION["username"] = $username;
      header("location: index.php");
    } else {
      $passwordErrMsg = "Incorrect username or password";
    }
  }

  // close database connection
  unset($conn);
}

?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="author", content="Connor Claypool">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title -->
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>
    <main aria-labelledby="admin-login-header" class="container">
      <div class="row mt-5">
        <div class="col">
          <!-- Login box -->
          <div class="admin-form mx-auto p-3 shadow">
            <h1 id="admin-login-header">Admin Login</h1>
            <!-- Login form -->
            <form method="post">
              <!-- Username input -->
              <div class="form-group">
                <label for="username">Username</label>
                <input id="username"
                       name="username"
                       type="text"
                       class="form-control <?= is_null($usernameErrMsg) ? "" : "is-invalid"; ?>"
                       value="<?= $username; ?>"
                       maxlength=25>
                <?= is_null($usernameErrMsg) ? '' : "<span class='form-err-msg'>$usernameErrMsg</span>"; ?>
              </div>
              <!-- Password input -->
              <div class="form-group">
                <label for="password">Password</label>
                <input id="password"
                       name="password"
                       type="password"
                       class="form-control <?= is_null($passwordErrMsg) ? "" : "is-invalid"; ?>"
                       value="<?= sanitize($password); ?>">
                <?= is_null($passwordErrMsg) ? "" : "<span class='form-err-msg'>$passwordErrMsg</span>"; ?>
              </div>
              <!-- Login button -->
              <button type="submit" class="btn btn-primary">Login</button>
            </form>
          </div>
        </div>
      </div>
    </main>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>