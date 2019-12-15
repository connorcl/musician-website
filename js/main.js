"use strict";

// array of month names for displaying dates
const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
];


// converts a date string into a pretty format
function formatDate(dateString) {
  // create new Date object
  var date = new Date(dateString);
  // generate formatted string
  var formattedDate = "";
  formattedDate += `${monthNames[date.getMonth()]} `;
  formattedDate += `${date.getDate()} ${date.getFullYear()} `;
  formattedDate += `${date.getHours()}:`;
  formattedDate += `${date.getMinutes() < 10 ? "0" : ""}`;
  formattedDate += date.getMinutes();
  // return formatted date string
  return formattedDate;
}


// set up callback for when document is ready
$(document).ready(() => {
  
  // present cookies consent modal if appropriate
  if (localStorage.getItem("cookies-consent") !== "true") {
    $("#cookies-consent-modal").modal();
  }

  // click handler for cookies consent button
  $("#cookies-consent-btn").click(() => {
    localStorage.setItem("cookies-consent", "true");
  });
  
  // highlight navbar link corresponding to current page
  
  // get page path as array of dir elements, starting with most specific
  var fileName = window.location.pathname.split("/").reverse()[0];
  var currentNavID = "";
  // get ID of relevant link element
  // if page is index.php or not specified
  if (fileName == "" || fileName == "index.php") {
    currentNavID = "nav-home";
  } else if (fileName == "music.php") {
    currentNavID = "nav-music";
  } else if (fileName == "tour.php") {
    currentNavID = "nav-tour";
  } else if (fileName == "forum.php") {
    currentNavID = "nav-forum";
  } else if (fileName == "register.php") {
    currentNavID = "nav-register";
  } else if (fileName == "login.php") {
    currentNavID = "nav-login";
  } else if (fileName == "account.php") {
    currentNavID = "nav-account";
  }

  // if page matches one in navbar, set link class to active
  if (currentNavID) {
    $("#" + currentNavID).addClass("active");
  }
});