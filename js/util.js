"use strict"

// destroy a pagination element so a new one can be created
function destroyPagination(pagination) {
  pagination.empty();
  pagination.removeData("twbs-pagination");
}

// convert a word to title case (capitalize first letter)
function toTitleCase(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

// display an error alert on the page
function displayError(errMsg) {
  $("#error-msg").html(errMsg);
  $("#error-alert").show();
}

// check the response from the server, display any error message
// and run a function if no error is present
function checkResponse(response, success = false) {
  // parse response
  var parsedResponse = JSON.parse(response);
  // get error
  var error = parsedResponse.error;
  // if error is not false, display error alert
  if (error) {
    displayError(error);
  // otherwise pass response data to given function
  } else if (success) {
    success(parsedResponse.data);
  }
}