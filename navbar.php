<?php

// include helper utils
require_once "util.php";

// if target is already set (by login/register.php when included there), set redirect target to target
if (isset($target)) {
  $redirectTarget = $target;
// otherwise set target to current page
} else {
  $redirectTarget = sanitize($_SERVER["PHP_SELF"]);
}

?>


<!-- Navigation bar -->
<nav class="navbar navbar-expand-md navbar-dark">
  <!-- Main logo/sitename -->
  <a class="navbar-brand" href="index.php">John Smith</a>
  <!-- Navbar menu toggler (for when menu is collapsed) -->
  <button aria-label="toggle navigation links on small screens" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-links">
    <span class="navbar-toggler-icon"></span>
  </button>
  <!-- Navbar links -->
  <div id="navbar-links" class="collapse navbar-collapse">
    <!-- Main page links -->
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a id="nav-home" class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a id="nav-music" class="nav-link" href="music.php">Music</a>
      </li>
      <li class="nav-item">
        <a id="nav-tour" class="nav-link" href="tour.php">Tour</a>
      </li>
      <li class="nav-item">
        <a id="nav-forum" class="nav-link" href="forum.php">Forum</a>
      </li>
    </ul>
    <!-- User login and registration links -->
    <ul class="navbar-nav ml-auto">
      <?php
      if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] == true) {
        echo "<li class='nav-item'>";
        echo "<a id='nav-account' class='nav-link' href='account.php'>";
        echo "<span id='navbar-username'>" . $_SESSION["username"] . "</span>";
        echo "</a>";
        echo "</li>";
        echo "<li class='nav-item'>";
        echo "<a id='nav-logout' class='nav-link' href='logout.php?t=$redirectTarget'>Logout</a>";
        echo "</li>";
      } else {
        echo "<li class='nav-item'>";
        echo "<a id='nav-login' class='nav-link' href='login.php?t=$redirectTarget'>Login</a>";
        echo "</li>";
        echo "<li>";
        echo "<a id='nav-register' class='nav-link' href='register.php?t=$redirectTarget'>Register</a>";
        echo '</li>';
      }
      ?>
    </ul>
  </div>
</nav>