<!-- Main forum page -->

<?php session_start(); ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- meta elements -->
    <meta charset="utf-8">
    <meta name="description" content="Main page for a forum dedicated to the singer-songwriter John Smith">
    <meta name="keywords" content="forum,music,musician,singer,songwriter,country,folk,americana,tour">
    <meta name="author" content="Connor Claypool">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- page title -->
    <title>John Smith - Forum</title>
    <!-- Universal stylesheets -->
    <?php require_once "css-includes.php"; ?>
  </head>

  <body>
    <!-- Include cookies consent dialog -->
    <?php require_once "cookies-consent.php"; ?>

    <!-- Navigation bar -->
    <?php require_once "navbar.php"; ?>

    <!-- Main content - forum -->
    <main aria-labelledby="forum-header" class="container">
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
        <div class="col">
          <header class="text-center">
            <!-- Main page title and buttons -->
            <h1 id="forum-header" class="text-light">Forum</h1>
            <div class="mt-4 mb-2">
              <button id="forum-search-expand-btn" class="basic-btn page-main-btn">Search Forum...</button>
              <button id="forum-showall-btn" class="basic-btn page-main-btn">Show All Threads</button>
              <button id="new-thread-expand-btn" class="basic-btn page-main-btn">Start New Thread...</button>
              <button id="new-post-expand-btn" class="basic-btn page-main-btn collapse">Reply to This Thread...</button>
            </div>
          </header>
        </div>
      </div>

      <!-- Area for expandable elements - search, new post, new thread -->
      <div class="row">
        <div class="col">
          <!-- Forum search form -->
          <form id="forum-search" class="mt-2 text-center collapse" aria-label="Search forum">
            <!-- Search text -->
            <div class="input-group std-text-input mx-auto mb-2">
              <input type="text" id="forum-search-box" name="searchTerm" class="form-control basic-textbox rounded-0" placeholder="Search" maxlength=100>
              <span class="input-group-append">
                <button id="forum-search-btn" class="basic-btn" type="submit">Search</button>
              </span>
            </div>
            <!-- Search option radio buttons -->
            <div class="form-group form-check-inline">
              <label class="text-light form-check-label">
                <input type="radio" class="form-check-input" name="search-opt" value="threads" checked>Search Threads
              </label>
            </div>
            <div class="form-group form-check-inline">
              <label class="text-light form-check-label">
                <input type="radio" class="form-check-input" name="search-opt" value="posts">Search Posts
              </label>
            </div>
          </form>

          <!-- Thread creation form -->
          <form id="new-thread" class="mt-2 collapse" method="post" aria-label="Create a new thread">
            <!-- Hidden inputs to identify the form -->
            <input type="hidden" name="target" value="threads">
            <input type="hidden" name="action" value="insert">
            <!-- Text input for thread title -->
            <div class="form-group std-text-input mx-auto">
              <label class="text-light" for="title">Thread title:</label>
              <input type="text" id="title" name="title" class="form-control basic-textbox rounded-0" maxlength=200>
            </div>
            <!-- Submit button -->
            <div class="form-group text-center">
              <button id="new-thread-btn" type="submit" class="basic-btn major-btn">Create Thread</button>
            </div>
          </form>

          <!-- Post creation form -->
          <form id="new-post" class="collapse mt-2 mx-auto" method="post" aria-label="Create a new post">
            <!-- Hidden inputs to identify form -->
            <input type="hidden" name="target" value="posts">
            <input type="hidden" name="action" value="insert">
            <!-- Hidden input to identify current thread -->
            <input type="hidden" id="thread" name="thread" value="">
            <!-- Text area input for post body -->
            <div class="form-group">
              <label class="text-light" for="body">Write post here:</label>
              <textarea id="body" name="body" class="form-control basic-textbox rounded-0" rows="5" maxlength=2000></textarea>
            </div>
            <!-- Submit button -->
            <div class="form-group text-center">
              <button id="new-post-btn" type="submit" class="basic-btn major-btn">Submit Post</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Areas dynamically populated with forum threads and posts -->
      <div class="row">
        <div class="col">
          <section aria-label="Forum threads" id="threads">
            <div aria-live="polite" id="threads-pagination"></div>
            <div aria-live="polite" id="threads-page"></div>
          </section>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <section aria-label="Forum posts" id="posts" class="collapse">
            <div aria-live="polite" id="posts-pagination"></div>
            <div aria-live="polite" id="posts-page"></div>
          </section>
        </div>
      </div>

      <!-- Modal dialog containing thread update form -->
      <div id="thread-update-modal" class="modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header basic-modal">
              <h4>Edit thread</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body basic-modal">
              <form id="update-thread">
                <input type="hidden" id="thread-id-to-update" name="match">
                <input type="hidden" name="target" value="threads">
                <input type="hidden" name="action" value="update">
                <div class="form-group">
                  <label for="thread-update-title">Title:</label>
                  <input type="text" id="thread-update-title" name="title" class="form-control rounded-0" maxlength=200>
                </div>
                <button type="submit" class="basic-btn major-btn">Submit</button>
              </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer basic-modal">
              <button type="button" class="basic-btn major-btn" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal dialog containing post update form -->
      <div id="post-update-modal" class="modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header basic-modal">
              <h4>Edit post</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body basic-modal">
              <form id="update-post">
                <input type="hidden" id="post-id-to-update" name="match">
                <input type="hidden" name="target" value="posts">
                <input type="hidden" name="action" value="update">
                <div class="form-group">
                  <label for="post-update-body">Body:</label>
                  <textarea id="post-update-body" name="body" class="form-control rounded-0" maxlength=2000></textarea>
                </div>
                <button type="submit" class="basic-btn major-btn">Submit</button>
              </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer basic-modal">
              <button type="button" class="basic-btn major-btn" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal dialog for deleting post -->
      <div id="post-delete-modal" class="modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header basic-modal">
              <h4>Delete post?</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body basic-modal">
              <p>This action cannot be undone.</p>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer basic-modal">
              <form id="delete-post">
                <input type="hidden" id="post-id-to-delete" name="match">
                <input type="hidden" name="target" value="posts">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="basic-btn major-btn">Delete</button>
              </form>
              <button type="button" class="basic-btn major-btn" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Include footer -->
    <?php require_once "footer.php"; ?>

    <!-- Universal scripts -->
    <?php require_once "js-includes.php"; ?>
    <!-- twbsPagination jQuery plugin -->
    <script src="js/jquery.twbsPagination.js"></script>
    <!-- Page-specific scripts -->
    <script src="js/util.js"></script>
    <script src="js/database-target.js"></script>
    <script src="js/forum.js"></script>
  </body>
</html>