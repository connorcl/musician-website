<!-- Admin panel -->

<?php

// ensure admin is logged in
session_start();
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
  session_unset();
  session_destroy();
  // redirect to authentication page if admin is not logged in
  header("location: login.php");
  exit;
}


// generates a modal containing a record update form
function generateUpdateModal($target, $itemName, $message, $colsToUpdate) {
  // set id based on target and action
  echo "<div class='modal' id='$target-update-modal'>";
  echo "<div class='modal-dialog'>";
  echo "<div class='modal-content'>";
  echo "<div class='modal-header'>";
  // modal title
  $idInTitle = "(ID: <span id='title-$target-id-to-update'></span>)";
  $ucItemName = ucwords($itemName);
  echo "<h4 class='modal-title'>Update $ucItemName? $idInTitle</h4>";
  echo "</div>";
  // modal body containing form
  echo "<div class='modal-body'>";
  echo "<form id='$target-update-form'>";
  // hidden input to keep track of record ID
  echo "<input type='hidden' id='$target-id-to-update' name='match' value=''>";
  // hidden inputs for target and action
  echo "<input type='hidden' name='target' value='$target'>";
  echo "<input type='hidden' name='action' value='update'>";
  // generate form inupts
  $placeholder = "Unchanged";
  foreach ($colsToUpdate as $colName => $inputType) {
    echo "<div class='form-group'>";
    // create label
    $ucColName = ucwords($colName);
    echo "<label for='$target-update-$colName'>$ucColName:</label>";
    // create input element
    echo "<input type='$inputType' id='$target-update-$colName' name='$colName' class='form-control' placeholder='$placeholder'>";
    echo "</div>";
    // add confirm if col is passwd
    if ($colName == "passwd") {
      echo "<div class='form-group'>";
      // set label
      $ucColName = ucwords($colName);
      echo "<label for='$target-update-confirm-$colName'>Confirm $colName:</label>";
      // create input
      echo "<input type='$inputType' id='$target-update-confirm-$colName' name='confirm-$colName' class='form-control' placeholder='$placeholder'>";
      echo "</div>";
    }
  }
  // submit button
  echo "<button type='submit' class='btn btn-danger'>Update $ucItemName</button>";
  echo "</form></div>";
  echo "<div class='modal-footer'>";
  // cancel button in footer
  echo "<button type='button' class='btn btn-warning' data-dismiss='modal'>Cancel</button>";
  echo "</div></div></div></div>";
}


// generate a modal for a record insert form
function generateInsertModal($target, $itemName, $message, $colsToInsert) {
  // set id based on target and action
  echo "<div class='modal' id='$target-insert-modal'>";
  echo "<div class='modal-dialog'>";
  echo "<div class='modal-content'>";
  echo "<div class='modal-header'>";
  // modal title
  $ucItemName = ucwords($itemName);
  echo "<h4 class='modal-title'>Insert $ucItemName?</h4>";
  echo "</div>";
  echo "<div class='modal-body'>";
  // main form
  echo "<form id='$target-insert-form'>";
  // hidden inputs for target and action
  echo "<input type='hidden' name='target' value='$target'>";
  echo "<input type='hidden' name='action' value='insert'>";
  // generate form inputs
  $placeholder = "Enter a value";
  foreach ($colsToInsert as $colName => $inputType) {
    echo "<div class='form-group'>";
    // set label
    $ucColName = ucwords($colName);
    echo "<label for='$target-insert-$colName'>$ucColName:</label>";
    // create input
    echo "<input type='$inputType' id='$target-insert-$colName' name='$colName' class='form-control' placeholder='$placeholder' required>";
    echo "</div>";
    // add confirm if col is passwd
    if ($colName == "passwd") {
      echo "<div class='form-group'>";
      // set label
      $ucColName = ucwords($colName);
      echo "<label for='$target-insert-confirm-$colName'>$ucColName:</label>";
      // create input
      echo "<input type='$inputType' id='$target-insert-confirm-$colName' name='confirm-$colName' class='form-control' placeholder='$placeholder' required>";
      echo "</div>";
    }
  }
  // submit button
  echo "<button type='submit' class='btn btn-danger'>Insert $ucItemName</button>";
  echo "</form></div>";
  echo "<div class='modal-footer'>";
  // cancel button in footer
  echo "<button type='button' class='btn btn-warning' data-dismiss='modal'>Cancel</button>";
  echo "</div></div></div></div>";
}


// generate a record deletion modal
function generateDeleteModal($target, $itemName, $message) {
  // set id based on target and action
  echo "<div class='modal' id='$target-delete-modal'>";
  echo "<div class='modal-dialog'>";
  echo "<div class='modal-content'>";
  echo "<div class='modal-header'>";
  // modal title
  $idInTitle = "(ID: <span id='title-$target-id-to-delete'></span>)";
  $ucItemName = ucwords($itemName);
  echo "<h4 class='modal-title'>Delete $ucItemName? $idInTitle</h4>";
  echo "</div>";
  echo "<div class='modal-body'>";
  echo "</div>";
  echo "<div class='modal-footer'>";
  // cancel button in footer
  echo "<button type='button' class='btn btn-warning' data-dismiss='modal'>Cancel</button>";
  // deletion form
  echo "<form id='$target-delete-form' class='form-inline'>";
  // hidden inputs for target, action and record ID to delete
  echo "<input type='hidden' name='target' value='$target'>";
  echo "<input type='hidden' name='action' value='delete'>";
  echo "<input type='hidden' id='$target-id-to-delete' name='match' value=''>";
  // submit button
  echo "<button type='submit' class='btn btn-danger'>Delete $ucItemName</button>";
  echo "</form></div></div></div></div>";
}


// generate a tab section based on a target
function generateTabSection($target, $itemName, $insert = true) {
  echo "<div class='row'>";
  echo "<div class='col'>";
  // search form
  echo "<form id='$target-search-form' class='form-inline mt-3'>";
  echo "<input type='search' class='form-control mr-2 mb-2' id='$target-search-term' placeholder='Search...'>";
  echo "<button type='submit' class='btn btn-primary mb-2'>Search</button>";
  echo "</form>";
  echo "</div>";
  echo "<div class='col'>";
  // get by ID form
  echo "<form id='$target-get-by-id-form' class='form-inline mt-3'>";
  echo "<input type='hidden' name='target' value='$target'>";
  echo "<input type='hidden' name='action' value='get-by-id'>";
  echo "<input type='number' class='form-control mr-2 mb-2' id='$target-id-to-get' name='match' placeholder='Enter $itemName ID'>";
  $ucItemName = ucwords($itemName);
  echo "<button type='submit' class='btn btn-primary mb-2'>Get $ucItemName</button>";
  echo "</form></div></div>";
  // area for pagination
  echo "<div class='row'>";
  echo "<div class='col'>";
  echo "<div id='$target-pagination' class='d-inline-block'></div>";
  if ($insert) {
    // if insert is true, create insert button
    echo "<button class='btn btn-success d-inline-block ml-5' data-toggle='modal' data-target='#$target-insert-modal'>Insert $ucItemName</button>";
  }
  // create area for displaying records
  echo "<div id='$target-table-area' class='table-responsive mt-1'></div>";
  echo "</div></div>";
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
    <title>John Smith - Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
      <a class="navbar-brand" href="index.php">Admin Panel</a>
      <!-- Navbar menu toggler (for when menu is collapsed) -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-links">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Navbar links -->
      <div id="navbar-links" class="collapse navbar-collapse">
        <!-- Logout link -->
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </nav>
    
    <!-- Navigation tabs -->
    <nav>
      <ul class="nav nav-tabs mt-2">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#users-tab">Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#forum-tab">Forum</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#events-tab">Events</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#music-tab">Music</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#admins-tab">Admins</a>
        </li>
      </ul>
    </nav>

    <!-- Tab panes -->
    <main class="tab-content">

      <div class="row">
        <div class="col">
          <!-- Error message alert -->
          <div id="error-alert" class="alert alert-danger collapse">
            <button type="button" class="close" id="error-close">&times;</button>
            <span id="error-msg"></span>
          </div>
        </div>
      </div>

      <!-- User management tab -->
      <section aria-label="Users tab" class="tab-pane active container-fluid" id="users-tab">
        <?php
        // generate tab section for users
        generateTabSection("users", "user", false);
        // generate modal to update user records
        generateUpdateModal("users", "user", "", ["username"=>"text", "email"=>"email", "passwd"=>"password"]);
        // generate modal to delete user records
        generateDeleteModal("users", "user", "");
        ?>
      </section>

      <!-- Forum management tab -->
      <section aria-label="Forum tab" class="tab-pane container-fluid" id="forum-tab">
        <?php
        // generate tab section for threads
        generateTabSection("threads", "thread");
        // generate modal for updating thread records
        generateUpdateModal("threads", "thread", "", ["title"=>"text"]);
        // // generate modal for deleting threads
        generateDeleteModal("threads", "thread", "");
        // // generate modal for inserting threads
        generateInsertModal("threads", "thread", "", ["title"=>"text", "author"=>"number"]);
        // generate posts tab section
        generateTabSection("posts", "post");
        // generate modal for updating posts
        generateUpdateModal("posts", "post", "", ["body"=>"text"]);
        // generate modal for deleting posts
        generateDeleteModal("posts", "post", "");
        // generate modal for inserting posts
        generateInsertModal("posts", "post", "", ["thread"=>"number", "body"=>"text", "author"=>"number"]);
        ?>
      </section>

      <!-- Events management tab -->
      <section aria-label="Events tab" class="tab-pane container-fluid" id="events-tab">
        <?php
        // generate tab section for events
        generateTabSection("events", "event", true);
        // generate modal for creating events
        generateInsertModal("events", "event", "", ["title"=>"text", "description"=>"text", "location"=>"text", "link"=>"url", 
                                                    "date_and_time"=>"datetime-local", "timezone"=>"text", "img"=>"text"]);
        // generate modal for updating events
        generateUpdateModal("events", "event", "", ["title"=>"text", "description"=>"text", "location"=>"text", "link"=>"url", 
                                                    "date_and_time"=>"datetime-local", "timezone"=>"text", "img"=>"text"]);
        // generate delete modal for events
        generateDeleteModal("events", "event", "");
        ?>
      </section>
      
      <!-- Music management tab -->
      <section aria-label="Music tab" class="tab-pane container-fluid" id="music-tab">
        <?php
        // generate albums tab section
        generateTabSection("albums", "album", true);
        // generate modal to update albums
        generateUpdateModal("albums", "album", "", ["title"=>"text", "release_year"=>"number", "img"=>"text"]);
        // generate modal to delete albums
        generateDeleteModal("albums", "album", "");
        // generate modal to delete albums
        generateInsertModal("albums", "album", "", ["title"=>"text", "release_year"=>"number", "img"=>"text"]);
        // generate tracks tab section
        generateTabSection("tracks", "track", true);
        // generate modal to update tracks
        generateUpdateModal("tracks", "track", "", ["title"=>"text"]);
        // generate modal to delete tracks
        generateDeleteModal("tracks", "track", "");
        // generate modal to insert tracks
        generateInsertModal("tracks", "track", "", ["title"=>"text", "album"=>"number"]);
        ?>
      </section>

      <!-- Admins management tab -->
      <section aria-label="Admins tab" class="tab-pane container-fluid" id="admins-tab">
        <?php
        // generate tab section for admins
        generateTabSection("admins", "admin", true);
        // generate modal to update admin records
        generateInsertModal("admins", "admin", "", ["username"=>"text", "passwd"=>"password"]);
        // generate modal to update admin records
        generateUpdateModal("admins", "admin", "", ["username"=>"text", "passwd"=>"password"]);
        // generate modal to delete admin records
        generateDeleteModal("admins", "admin", "");
        ?>
      </section>
    </main>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- jQuery pagination plugin -->
    <script src="../js/jquery.twbsPagination.js"></script>
    <!-- Page-specific scripts -->
    <script src="../js/util.js"></script>
    <script src="../js/database-target.js"></script>
    <script src="js/admin.js"></script>
  </body>
</html>