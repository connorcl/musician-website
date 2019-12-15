"use strict";

// get album details from database with AJAX
function getAlbumDetails(albumID) {
  // make asynchronous POST request
  $.ajax({url: "get-album.php", type: "POST", data: {id: albumID}, success: data => {
    // decode JSON
    var album = JSON.parse(data);
    // set album details image
    var albumImg = $("#album-details-img");
    albumImg.attr("src", "img/albums/" + album.img);
    albumImg.attr("alt", "Album cover of " + album.title);

    // set album details caption
    $("#album-details-caption").html(
      `<p class='text-dark text-center basic-caption'><strong><em>${album.title}</em></strong> (${album.release_year})</p>`);
  }});
}

// get tracks for a specific album with AJAX
function getTracks(albumID) {
  // make asynchronous POST request
  $.ajax({url: "get-tracks.php", type: "POST", data: {album: albumID}, success: data => {
    // decode JSON
    var tracks = JSON.parse(data);
    // generate track listing table
    var trackListingHTML = "<thead><tr><th class='text-center'>Tracks</tr></th></thead>";
    trackListingHTML += "<tbody>";
    tracks.forEach(function(item) {
      trackListingHTML += "<tr><td>";
      trackListingHTML += `<strong>${item.title}</strong>`;
      trackListingHTML += "</td></tr>";
    });
    trackListingHTML += "</tbody>";
    // set track listing HTML
    $("#track-listing").html(trackListingHTML);
  }});
}

// alter page based on hash
function processHash() {
  // if no hash, show main listing and hide details
  if (!location.hash) {
    document.getElementById("album-details").style.display = "none";
    var albumImg = document.getElementById("album-details-img");
    albumImg.setAttribute("src", "");
    albumImg.setAttribute("alt", "");
    document.getElementById("track-listing").innerHTML = "";
    document.getElementById("album-listing").style.display = "block";
    return;
  }
  // if hash is int, show details of relevant album
  var id = parseInt(location.hash.replace('#', ''));
  if (id >= 1) {
    getAlbumDetails(id);
    getTracks(id);
    document.getElementById("album-listing").style.display = "none";
    document.getElementById("album-details").style.display = "block";
  }
}

$(document).ready(() => {
  // set up hash change event handler
  window.onhashchange = processHash;
  // ensure correct content is shown on page load
  processHash();
});