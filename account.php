<!-- User account management page -->

<?php 

// ensure user is logged in
session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
  session_unset();
  session_destroy();
  header("location: login.php");
  exit;
}

?>


<!doctype html>
<html lang="en">
  <head>
    <!-- meta elements -->
    <meta charset="utf-8">
    <meta name="description" content="User account page for a website dedicated to the singer-songwriter John Smith">
    <meta name="keywords" content="forum,music,musician,singer,songwriter,country,folk,americana,tour">
    <meta name="author" content="Connor Claypool">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- page title -->
    <title>John Smith - My Account</title>
    <!-- Universal stylesheets -->
    <?php require_once "css-includes.php"; ?>
  </head>

  <body>
    <!-- Include cookies consent dialog -->
    <?php require_once "cookies-consent.php"; ?>

    <!-- Navigation bar -->
    <?php require_once "navbar.php"; ?>

    <!-- Main content - account management -->
    <main aria-labelledby="account-header" class="container text-light">
      <div class="row">
        <div class="col">
          <!-- Error message alert -->
          <div id="error-alert" class="alert alert-danger collapse">
            <button type="button" class="close" id="error-close">&times;</button>
            <span id="error-msg"></span>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col">
          <!-- Loading alert -->
          <div id="loading-alert" class="alert alert-dark collapse">
            Loading, please wait...
          </div>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col text-center">
          <!-- Header -->
          <h1>My Account</h1>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col">
          <!-- Account details update form -->
          <form id="update-details" class="basic-form">
            <!-- Hidden input for action -->
            <input type="hidden" name="action" value="update">
            <!-- Username -->
            <div class="form-group">
              <label for="username">New username:</label>
              <input type="text" id="username" name="username" class="basic-textbox form-control rounded-0" minlength=4 maxlength=20>
            </div>
            <!-- Email -->
            <div class="form-group">
              <label for="email">New email:</label>
              <input type="email" id="email" name="email" class="basic-textbox form-control rounded-0">
            </div>
            <!-- Password -->
            <div class="form-group">
              <label for="password">New password:</label>
              <input type="password" id="password" name="passwd" class="basic-textbox form-control rounded-0" minlength=12>
            </div>
            <!-- Confirm password -->
            <div class="form-group">
              <label for="confirm-password">Confirm new password:</label>
              <input type="password" id="confirm-password" name="confirm-passwd" class="basic-textbox form-control rounded-0" minlength=12>
            </div>
            <!-- Email consent -->
            <div class="form-group">
              <input type="radio" id="email-consent-true" name="email_consent" value="1">Receive marketing emails<br>
              <input type="radio" id="email-consent-false" name="email_consent" value="0">Do not receive marketing emails<br>
            </div>
            <!-- Submit button -->
            <div class="form-group text-center">
              <button type="submit" class="basic-btn major-btn">Update details</button>
            </div>
          </form>

          <!-- Delete account button -->
          <div class="text-center">
            <button type="button" class="basic-btn major-btn" data-toggle="modal" data-target="#delete-account-modal">Delete account...</button>
          </div>

          <!-- Delete account modal -->
          <div class="modal" id="delete-account-modal">
            <div class="modal-dialog text-dark">
              <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header basic-modal">
                  <h4 class="modal-title">Delete your account?</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body basic-modal">
                  This action cannot be undone. Your personal information will be deleted,
                  along with any posts you published on the website forum. Any threads you
                  started will still exist, but will no longer be associated with your account.
                </div>
                <!-- Modal footer -->
                <div class="modal-footer basic-modal">
                  <form id="delete-account" class="form-inline">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="basic-btn major-btn">Delete account</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Include footer -->
    <?php require_once "footer.php"; ?>

    <!-- Universal scripts -->
    <?php require_once "js-includes.php"; ?>
    <!-- Page-specific scripts -->
    <script src="js/util.js"></script>
    <script src="js/account.js"></script>
  </body>
</html>