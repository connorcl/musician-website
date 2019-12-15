<!-- Homepage -->

<?php session_start(); ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Meta elements -->
    <meta charset="utf-8">
    <meta name="description" content="The homepage of an unoffical website dedicated to the singer-songwriter John Smith">
    <meta name="keywords" content="music,musician,singer,songwriter,country,folk,americana,tour,forum">
    <meta name="author" content="Connor Claypool">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title -->
    <title>John Smith - Home</title>
    <!-- Universal stylesheets -->
    <?php require_once "css-includes.php"; ?>
  </head>

  <body>
    <!-- Include cookies consent dialog -->
    <?php require_once "cookies-consent.php"; ?>

    <!-- Include navigation bar -->
    <?php require_once "navbar.php"; ?>

    <!-- Main page content -->
    <main aria-labelledby="main-header" class="container pb-3">
      <!-- Row 1 -->
      <div class="row mt-5">
        <!-- Column 1-->
        <div class="col-lg-4 mb-3">
          <!-- Section 1: Main title, navigation buttons and intro text -->
          <section aria-labelledby="main-header" class="text-center vertically-centered">
            <h1 id="main-header" class="text-light">John Smith</h1>
            <p class="text-light">
              Raised in Maine but based in Austin, Texas, Americana 
              musician John Smith is regarded as one of the finest 
              singer-songwriters in the state.
            </p>
            <div class="mb-3">
              <a aria-label="go to about section" class="basic-btn page-main-btn" href="#about">Read More</a>
              <a class="basic-btn page-main-btn" href="music.php">Music</a>
              <a class="basic-btn page-main-btn" href="tour.php">Tour</a>
            </div>
          </section>
        </div>
        <!-- Column 2 -->
        <div class="col-lg-8 mb-3">
          <!-- Section 2: Embedded YouTube video -->
          <section aria-labelledby="main-video-caption" class="midground shadow">
            <div class="embed-responsive embed-responsive-16by9">
              <iframe class="embed-responsive-item" src="https://youtube.com/embed/8xvEKshRM28"></iframe>
            </div>
            <p id="main-video-caption" class="basic-caption text-dark text-center"><strong>If I Had A Heart</strong>, <em>Ghost on the Car Radio</em> (2017)</p>
          </section>
        </div>
      </div>

      <!-- Divider line -->
      <hr class="midground mt-2 mb-2">

      <!-- Section 3: About -->
      <section aria-labelledby="about">
        <!-- Row 2 -->
        <div class="row">
          <!-- Column 1 -->
          <div class="col">
            <!-- About section header -->
            <h2 id="about" class="text-light">About John Smith</h2>
          </div>
        </div>
        <!-- Row 3 -->
        <div class="row mt-3">
          <!-- Column 1 -->
          <div class="col-lg-6 mb-3">
            <!-- Image carousel -->
            <div aria-live="polite" id="main-carousel" class="carousel slide carousel-fade shadow">
              <div class="carousel-inner">
                <div class="carousel-item active" aria-hidden="false">
                  <img class="img-w100" src="img/john_smith_1.jpeg" alt="John Smith sitting on a sofa outdoors">
                </div>
                <div class="carousel-item" aria-hidden="true">
                  <img class="img-w100" src="img/john_smith_2.jpeg" alt="John Smith standing in an Alaskan wilderness">
                </div>
                <div class="carousel-item" aria-hidden="true">
                  <img class="img-w100" src="img/john_smith_3.jpeg" alt="Black and white outdoor portrait photo of John Smith">
                </div>
              </div>
            </div>
            <!-- Carousel controls -->
            <div class="text-center">
              <div id="main-carousel-controls" class="text-center" aria-label="carousel controls" aria-controls="main-carousel">
                <button class="basic-btn cs-ctrl-btn" id="main-cs-prev" aria-label="previous">Prev</button>
                <button class="basic-btn cs-ctrl-btn cs-goto-btn cs-goto-btn-current" id="main-cs-one">1</button>
                <button class="cs-goto-btn basic-btn cs-ctrl-btn" id="main-cs-two">2</button>
                <button class="cs-goto-btn basic-btn cs-ctrl-btn" id="main-cs-three">3</button>
                <button class="basic-btn cs-ctrl-btn" id="main-cs-next">Next</button>
                <button class="basic-btn cs-ctrl-btn" id="main-cs-play">Play</button>
                <button class="basic-btn cs-ctrl-btn" id="main-cs-pause">Pause</button>
              </div>
            </div>
          </div>
          <!-- Column 2 -->
          <div class="col-lg-6 mb-3">
            <!-- About text -->
            <p aria-labelledby="about" class="text-light">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
              sed do eiusmod tempor incididunt ut labore et dolore 
              magna aliqua. Urna condimentum mattis pellentesque id 
              nibh tortor id aliquet lectus. Integer enim neque 
              volutpat ac tincidunt vitae semper quis lectus.
              <br><br>
              Sem nulla pharetra diam sit amet nisl suscipit. Quis 
              eleifend quam adipiscing vitae proin sagittis. Amet 
              volutpat consequat mauris nunc. Pellentesque habitant 
              morbi tristique senectus et netus. Ultrices eros in 
              cursus turpis massa tincidunt dui. Ultricies leo integer 
              malesuada nunc vel risus commodo viverra. Pulvinar 
              mattis nunc sed blandit. Sed tempus urna et pharetra 
              pharetra massa. Nulla facilisi cras fermentum odio eu 
              feugiat. Donec massa sapien faucibus et. Arcu vitae 
              elementum curabitur vitae nunc sed. Donec adipiscing 
              tristique risus nec feugiat in fermentum posuere. Sit 
              amet aliquam id diam maecenas ultricies mi eget mauris. 
              Libero enim sed faucibus turpis in.
            </p>
          </div>
        </div>
      </section>
      <!-- Row 4 -->
      <div class="row mb-5">
        <!-- Column 1 -->
        <div class="col-lg-6 mx-auto">
          <!-- Section 4: Random lyric -->
          <section aria-labelledby="random-lyric-header" class="text-center">
            <h2 id="random-lyric-header" class="text-light">Song Lyrics by John Smith</h2>
            <div id="random-lyric-area" aria-live="polite" class="midground shadow">
              <p id="random-lyric"></p>
              <p id="lyric-caption" class="text-dark"></p>
            </div>
            <button id="random-lyric-btn" class="basic-btn" aria-controls="random-lyric-area">Get Random Lyric</button>
          </section>
        </div>
      </div>
    </main>
    
    <!-- Include footer -->
    <?php require_once "footer.php"; ?>

    <!-- Universal scripts -->
    <?php require_once "js-includes.php"; ?>
    <!-- Page-specific script -->
    <script src="js/home.js"></script>
  </body>
</html>