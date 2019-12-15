"use strict";

// depends on util.js

// get account details from database backend
function getDetails() {
  // make AJAX request
  $.ajax("account-backend.php", {
    type: "POST",
    data: {action: "get-by-id"}, 
    success: response => {
      // check response for error
      checkResponse(response, details => {
        // set username and email placeholders
        $("#username").attr("placeholder", details.username);
        $("#email").attr("placeholder", details.email);
        // check correct email consent radio button
        if (details.email_consent == 1) {
          $("#email-consent-true").attr("checked", "checked");
        } else {
          $("#email-consent-false").attr("checked", "checked");
        }
        // set username in navbar
        $("#navbar-username").html(details.username);
      });
    }
  });
}

// when page is ready
$(document).ready(() => {

  // set update details form handler
  $("#update-details").submit(function() {
    // hide old error alert
    $("#error-alert").hide();
    // show loading alert
    $("#loading-alert").show();
    // make ajax request
    $.ajax("account-backend.php", {
      type: "POST",
      data: $(this).serialize(),
      success: response => {
        // check response for errors
        checkResponse(response, () => {
          // get updated details if update was successful
          getDetails();
        });
      },
      complete: () => {
        // hide loading alert after 1.5s
        setTimeout(() => { $("#loading-alert").fadeOut() }, 1500);
      }
    });
    // prevent page reload
    event.preventDefault();
  });

  // set account deletion form handler
  $("#delete-account").submit(function() {
    // make ajax request
    $.ajax("account-backend.php", {
      type: "POST",
      data: $(this).serialize(), 
      success: response => {
        // check response for errors
        checkResponse(response, () => {
          // log out
          window.location.href = "logout.php";
        });
      }
    });
    // prevent page reload
    event.preventDefault();
  });

  // set error alert close button click handler
  $("#error-close").click(() => {
    $("#error-alert").hide();
  });

  // get account details
  getDetails();
});