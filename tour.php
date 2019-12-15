<!-- Tour page -->

<?php session_start(); ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- meta elements -->
    <meta charset="utf-8">
    <meta name="description" content="Tour page for a website dedicated to the singer-songwriter John Smith">
    <meta name="keywords" content="tour,events,music,musician,singer,songwriter,country,folk,americana">
    <meta name="author" content="Connor Claypool">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- page title -->
    <title>John Smith - Tour</title>
    <!-- Universal stylesheets -->
    <?php require_once "css-includes.php"; ?>
  </head>

  <body>
    <!-- Include cookies consent dialog -->
    <?php require_once "cookies-consent.php" ?>

    <!-- Navigation bar -->
    <?php require_once "navbar.php"; ?>

    <!-- Main content - events -->
    <main aria-labelledby="tour-header" class="container">
      <div class="row mt-5">
        <div class="col">
          <!-- Header including countdown to next event -->
          <header class="text-center">
            <h1 id="tour-header" class="text-light">John Smith on Tour</h1>
            <div class="text-light mt-3 mb-4">
              Next playing in
              <span id="event-countdown-time"></span>
              <span id="soonest-event-details"></span>
            </div>
          </header>
          <!-- Search form -->
          <form id="events-search" class="text-center" aria-label="Search forum">
            <div class="input-group std-text-input mx-auto mb-2">
              <input type="text" id="events-search-box" name="searchTerm" class="form-control basic-textbox rounded-0" placeholder="Search">
              <span class="input-group-append">
                <button id="events-search-btn" class="basic-btn" type="submit">Search</button>
              </span>
            </div>
          </form>
        </div>
      </div>

      <!-- Pagination links for events -->
      <div class="row">
        <div class="col">
          <div aria-live="polite" id="events-pagination"></div>
        </div>
      </div>
      <!-- Events listing -->
      <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
          <div aria-live="polite" id="events-page"></div>
        </div>
        <div class="col-sm-1"></div>
      </div>
    </main>

    <!-- Include footer -->
    <?php require_once "footer.php"; ?>

    <!-- Universal scripts -->
    <?php require_once "js-includes.php"; ?>
    <!-- twbsPagination jQuery plugin -->
    <script src="js/jquery.twbsPagination.js"></script>
    <!-- luxon.js -->
    <script src="js/luxon.js"></script>
    <!-- Page-specific scripts -->
    <script src="js/util.js"></script>
    <script src="js/database-target.js"></script>
    <script src="js/tour.js"></script>
  </body>
</html>