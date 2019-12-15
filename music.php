<!-- Discography page -->

<?php session_start(); ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Meta elements -->
    <meta charset="utf-8">
    <meta name="description" content="The music of singer-songwriter John Smith">
    <meta name="keywords" content="music,musician,singer,songwriter,country,folk,americana">
    <meta name="author" content="Connor Claypool">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title -->
    <title>John Smith - Music</title>
    <!-- Universal stylesheets -->
    <?php require_once "css-includes.php";?>
  </head>

  <body>
    <!-- Include cookies consent dialog -->
    <?php require_once "cookies-consent.php"; ?>

    <!-- Include navigation bar -->
    <?php require_once "navbar.php";?>

    <!-- Main content - discography -->
    <main aria-labelledby="music-header" class="container pb-3">
      <!-- Row 1 -->
      <div class="row mt-5 mb-3">
        <!-- Column 1 -->
        <div class="col text-center text-light">
          <header aria-labelledby="music-header">
            <h1 id="music-header">Music by John Smith</h1>
            <p aria-labelledby="music-header">
              Since his debut album <em>The Promise</em> (1990), John Smith 
              has relseased 13 albums and one EP, his most recent release being the
              2017 album <em>Ghost on the Car Radio</em>.
            </p>
          </header>
        </div>
      </div>
      <section id="album-details" aria-label="Album details" aria-live="polite" class="collapse">
        <!-- Row 2 -->
        <div class="row mb-3">
          <!-- Column 1 -->
          <div class="col-md-6">
            <div class="midground shadow text-center">
              <img id="album-details-img" aria-labelledby="album-details-caption" class="img-w100">
              <span id="album-details-caption"></span>
            </div>
          </div>
          <!-- Column 2 -->
          <div class="col-md-6">
            <table class="table table-striped midground shadow" id="track-listing">
            </table>
          </div>
        </div>
      </section>

      <!-- Album cards -->
      <section id="album-listing" aria-label="Albums">
        <?php

        // connect to database
        require_once "config.php";

        // include helper utils
        require_once "util.php";

        // displays an album card
        function displayAlbum($record, $num) {
          // get fields from record
          $id = $record["id"];
          $title = $record["title"];
          $img_path = $record["img"];
          $release_year = $record["release_year"];
          // start row of 3 if item no. is multiple of 3 + 1
          if (($num - 1) % 3 === 0) {
            echo "<div class='row mt-3 music-listing-row'>";
          }
          echo "<div class='col-md-4 mb-3'>";
          echo "<article aria-label=\"$title\" class='card shadow rounded-0'>";
          echo "<div class='card-body midground pb-0'>";
          echo "<img class='img-w100' src=\"img/albums/$img_path\" alt=\"Album cover of $title\">";
          echo "</div>";
          echo "<div class='card-footer midground text-center border-0'>";
          echo "<a aria-label='View album details' href='#$id' class='album-card-link stretched-link'>";
          echo "<span class='text-dark'><strong><em>$title</em></strong> ($release_year)</span></a>";
          echo "</div>";
          echo "</article>";
          echo "</div>";
          // end row of 3 if item no. is multiple of 3
          if ($num % 3 === 0) {
            echo "</div>";
          }
        }

        // get albums from database
        $query = "SELECT * FROM albums ORDER BY release_year DESC";
        $albums = executePreparedStmt($query, []);

        // display albums on page
        $num = 0;
        foreach ($albums as $album) {
          $num++;
          displayAlbum($album, $num);
        }
        // end last row if last item number was not multiple of 3
        if ($num % 3 !== 0) {
          echo "</div>";
        }

        ?>
      </section>
    </main>

    <!-- Include footer -->
    <?php require_once "footer.php"; ?>

    <!-- Universal scripts -->
    <?php require_once "js-includes.php"; ?>
    <!-- Page-specific-scripts -->
    <script src="js/music.js"></script>
  </body>
</html>