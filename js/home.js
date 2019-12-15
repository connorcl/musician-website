"use strict";

// get a random lyric asynchronously with AJAX
function getRandomLyric() {
  // create object used for asynchronous request
  var xmlhttp = new XMLHttpRequest();

  // set up callback for when ready state changes
  xmlhttp.onreadystatechange = function() {
    // if valid reply is received
    if (this.readyState == 4 && this.status == 200) {
      // decode JSON
      var lyric = JSON.parse(this.responseText);
      // get lyric text from lyric object and insert
      // into document, preserving line breaks
      var lyricTextArray = lyric.text.split("\n");
      var lyricHtml = "";
      lyricTextArray.forEach(item => {
        lyricHtml += (item + "<br>");
      });
      document.getElementById("random-lyric").innerHTML = lyricHtml;
      // generate and insert lyric caption
      var lyricCaption = "";
      lyricCaption += `<strong>${lyric.song}</strong>, `;
      lyricCaption += `<em>${lyric.album}</em> `;
      lyricCaption += `(${lyric.year})`;
      document.getElementById("lyric-caption").innerHTML = lyricCaption;
    }
  }

  // make asynchronous GET request
  xmlhttp.open("GET", "random-lyric.php", true);
  xmlhttp.send();
}

// set up callback for when document is ready
$(document).ready(() => {

  // set up main image carousel
  var mainCarousel = $("#main-carousel");
  mainCarousel.carousel();
  mainCarousel.carousel('cycle');
  
  // hide play button initially
  $("#main-cs-play").hide();

  // enable carousel control buttons
  $("#main-cs-prev").click(() => {
    mainCarousel.carousel('prev');
  });
  $("#main-cs-next").click(() => {
    mainCarousel.carousel('next');
  });
  $("#main-cs-one").click(() => {
    mainCarousel.carousel(0);
  });
  $("#main-cs-two").click(() => {
    mainCarousel.carousel(1);
  });
  $("#main-cs-three").click(() => {
    mainCarousel.carousel(2);
  });
  $("#main-cs-pause").click(function() {
    mainCarousel.carousel('pause');
    // when pause is clicked, hide pause and show play button
    $(this).hide();
    $("#main-cs-play").show();
  });
  $("#main-cs-play").click(function() {
    mainCarousel.carousel('cycle');
    // when play is clicked, hide play and show pause button
    $(this).hide();
    $("#main-cs-pause").show();
  });

  // set carousel slide event handler
  mainCarousel.on("slide.bs.carousel", ev => {
    // set aria-hidden attribute accordingly
    var carouselItems = $("#main-carousel .carousel-item");
    $(carouselItems[ev.from]).attr("aria-hidden", "true");
    $(carouselItems[ev.to]).attr("aria-hidden", "false");
    // set identifying class on button corresponding to current image
    var toButtons = $(".cs-goto-btn");
    $(toButtons[ev.from]).removeClass("cs-goto-btn-current");
    $(toButtons[ev.to]).addClass("cs-goto-btn-current");
  });

  // set event handler for random lyric button
  document.getElementById("random-lyric-btn").onclick = getRandomLyric;

  // get first random lyric
  getRandomLyric();
});