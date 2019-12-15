"use strict";

// depends on util.js, database-target.js and jquery.twbsPagination.js

// subclass of database target for interacting with the forum backend
class ForumDatabaseTarget extends DatabaseTarget
{
  // constructor
  constructor(target, child, childTarget) {
    super("forum-backend.php", target, child, childTarget);
  }
}


// subclass of forum database target for managing forum threads
class ThreadsDatabaseTarget extends ForumDatabaseTarget
{  
  // constructor
  constructor(childTarget) {
    super("threads", false, childTarget);
  }


  // format an object representing a thread record as HTML
  formatThread(thread) {
    var threadHTML = "<div class='forum-item text-light card rounded-0 mt-3 mb-3'>";
    threadHTML += "<div class='card-body'>";
    threadHTML += `<a class='text-light' href='#${thread.id}'>${thread.title}</a><br>`;
    threadHTML += "<span class='float-right'>";
    threadHTML += `Started by: ${thread.username} (${formatDate(thread.created)})`;
    threadHTML += "</span>";
    // if thread was authored by current user, show edit button
    if (thread.username === $("#navbar-username").html()) {
      threadHTML += `<button class='thread-update-btn basic-btn d-inline-block mr-1 mt-2 p-1'
        data-toggle='modal' data-target='#thread-update-modal' value='${thread.id}'>Edit</button>`;
    }
    threadHTML += "</div></div>";
    return threadHTML;
  }


  // display a page of records
  displayPage(threads) {
    // format threads as HTML
    var threadsHTML = "";
    for (var thread of threads) {
      threadsHTML += this.formatThread(thread);
    }
    // get target name
    var target = this.target;
    // display on page
    $(`#${target}-page`).html(threadsHTML);
    // set update button event handler
    $(".thread-update-btn").click(function() {
      $("#thread-id-to-update").val($(this).val());
    });
  }
  

  // get a page of records from the server and display on page
  getPage(byMatch, selector, page) {
    // compose data to be sent
    var data = {
      target: this.target,
      action: "get-page",
      searchTerm: selector,
      page: page
    };
    // make asynchronous POST request for page of threads
    $.ajax(this.backendUrl, {
      type: "POST", 
      data: data, 
      success: response => {
        checkResponse(response, data => {
          this.displayPage(data);
        });
      }
    });
  }


  // register form handler for thread creation
  registerInsertHandler() {
    // get current object
    var obj = this;
    // handle submission of new thread form
    $("#new-thread").submit(function(event) {
      // show loading alert
      $("#loading-alert").show();
      // make asynchronous POST request
      $.ajax(obj.backendUrl, {
        type: "POST",
        data: $(this).serialize(),
        success: response => {
          // check response for error
          checkResponse(response, () => {
            // hide new thread form
            $("#new-thread").slideUp(() => {
              // show first page of threads
              obj.getPagination(false, "");
              // clear title value
              $("#new-thread #title").val("");
            });
            sessionStorage.removeItem("title");
            // clear form inputs
            this.reset();
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
  }


  // register form handler for thread update
  registerUpdateHandler() {
    // get current object
    var obj = this;
    // handle submission of thread update form
    $("#update-thread").submit(function(event) {
      // show loading alert
      $("#loading-alert").show();
      // make AJAX request
      $.ajax(obj.backendUrl, {
        type: "POST",
        data: $(this).serialize(),
        success: response => {
          checkResponse(response, () => {
            // get current page
            var currentPage = $("#threads-pagination-links").twbsPagination("getCurrentPage");
            var searchTerm = "";
            // check if threads search term is set
            if ($('input[name=search-opt]:checked', '#forum-search').val() === "threads") {
              searchTerm = $("#forum-search-box").val();
            }
            // update view
            obj.getPagination(false, searchTerm, currentPage);
            // clear title input
            $("#thread-update-title").val("");
            // clear form inputs
            this.reset();
          });
        },
        complete: () => {
          // hide loading alert after 1.5s
          setTimeout(() => { $("#loading-alert").fadeOut() }, 1500);
          // hide modal
          $("#thread-update-modal").modal("hide");
        }
      });
      event.preventDefault();
    });  
  }


  // register all form handlers for this target
  registerFormHandlers() {
    this.registerInsertHandler();
    this.registerUpdateHandler();
  }
}


// subclass of forum database target for managing forum posts
class PostsDatabaseTarget extends ForumDatabaseTarget
{
  // constructor
  constructor() {
    super("posts", true, false);
  }


  // format an object representing a post record as HTML
  formatPost(post, showThread = false) {
    var postHTML = "<div class='card rounded-0 forum-item text-light mt-3 mb-3'>";
    postHTML += "<div class='card-body'>";
    postHTML += `<p>${post.body}</p><br>`;
    postHTML += `<p>- ${post.username} (${formatDate(post.created)})</p>`;
    if (showThread) {
      postHTML += `<br><p>${post.title}</p>`;
    }
    if (post.username === $("#navbar-username").html()) {
      postHTML += `<button class='post-update-btn basic-btn d-inline-block mr-1 mt-2 p-1' 
        data-toggle='modal' data-target='#post-update-modal' value='${post.id}'>Update</button>`;
      postHTML += `<button class='post-delete-btn basic-btn d-inline-block mr-1 mt-2 p-1' 
        data-toggle='modal' data-target='#post-delete-modal' value='${post.id}'>Delete</button>`;
    }
    postHTML += "</div></div>";
    return postHTML;
  }


  // get a page of records from the server and display on page
  getPage(byMatch, selector, page) {
    // compose data to be sent
    var data = {
      target: this.target,
      page: page
    };
    // if records are selected by match with parent ID
    var showThread;
    if (byMatch) {
      // set action and ID
      data.action = "get-page-by-match";
      data.match = selector;
      showThread = false;
      // if records are selected by search
    } else {
      // set action and searchTerm
      data.action = "get-page";
      data.searchTerm = selector;
      showThread = true;
    }
    // make asynchronous POST request for page of posts
    $.ajax(this.backendUrl, {
      type: "POST",
      data: data,
      success: response => {
        // check response for error
        checkResponse(response, data => {
          this.displayPage(data, showThread);
        });
      }
    });
  }


  // display a page of posts
  displayPage(posts, showThread) {
    // format records as HTML
    var postsHTML = "";
    if (posts.length == 0) {
      postsHTML = "<div class='text-center text-light mt-3'><p>No posts yet...</p></div>";
    } else {
      for (var post of posts) {
        postsHTML += this.formatPost(post, showThread);
      }
    }
    var target = this.target;
    // display on page
    $(`#${target}-page`).html(postsHTML);
    // set button click handlers
    $(".post-update-btn").click(function() {
      $("#post-id-to-update").val($(this).val());
    });
    $(".post-delete-btn").click(function() {
      $("#post-id-to-delete").val($(this).val());
    });
  }


  // register form handler for post creation
  registerInsertHandler() {
    // get current object
    var obj = this;
    // handle submission of new post form
    $("#new-post").submit(function(event) {
      // show loading alert
      $("#loading-alert").show();
      // make asynchronous POST request
      $.ajax(obj.backendUrl, {
        type: "POST",
        data: $(this).serialize(), 
        success: response => {
          // check response for error
          checkResponse(response, () => {
            // hide new post form
            $("#new-post").slideUp(() => {
              // show last page of current thread's posts
              obj.getPagination(true, $("#new-post #thread").val(), -1);
              // clear body value
              $("#new-post #body").val("");
            });
            sessionStorage.removeItem("body");
            // clear form inputs
            this.reset();
          });
        },
        complete: () => {
          // hide loading alert after 1.5s
          setTimeout(() => { $("#loading-alert").fadeOut() }, 1500);
        }
      });
      // prevent default action of submission to page
      event.preventDefault();
    });
  }


  // register form handler for updating post
  registerUpdateHandler() {
    // get current object
    var obj = this;
    // handle form submission event
    $("#update-post").submit(function(event) {
      // show loading alert
      $("#loading-alert").show();
      // make asynchronous POST request
      $.ajax(obj.backendUrl, {
        type: "POST",
        data: $(this).serialize(),
        success: response => {
          // check response for errors
          checkResponse(response, () => {
            var currentPage = $("#posts-pagination-links").twbsPagination("getCurrentPage");
            var hashNum = parseInt(location.hash.slice(1));
            var byMatch = hashNum > 0;
            var selector;
            if (byMatch) {
              selector = hashNum;
            } else {
              selector = "";
              if ($('input[name=search-opt]:checked', '#forum-search').val() === "posts") {
                selector = $("#forum-search-box").val();
              }
            }
            // update view
            obj.getPagination(byMatch, selector, currentPage);
            // clear form inputs
            this.reset();
          });
        },
        complete: () => {
          // hide loading alert after 1.5s
          setTimeout(() => { $("#loading-alert").fadeOut() }, 1500);
          // hide modal
          $("#post-update-modal").modal("hide");
        }
      });
      // prevent page reload
      event.preventDefault();
    });
  }


  // register form handler for post deletion
  registerDeleteHandler() {
    // get current object
    var obj = this;
    // handle form submission event
    $("#delete-post").submit(function(event) {
      // show loading alert
      $("#loading-alert").show();
      // make asynchronous POST request
      $.ajax(obj.backendUrl, {
        type: "POST",
        data: $(this).serialize(),
        success: response => {
          checkResponse(response, responseData => {
            var hashNum = parseInt(location.hash.slice(1));
            var byMatch = hashNum > 0;
            var selector;
            if (byMatch) {
              selector = hashNum;
            } else {
              selector = "";
              if ($('input[name=search-opt]:checked', '#forum-search').val() === "posts") {
                selector = $("#forum-search-box").val();
              }
            }
            obj.getPagination(byMatch, selector, 1);
          });
        },
        complete: () => {
          // hide loading alert after 1.5s
          setTimeout(() => { $("#loading-alert").fadeOut() }, 1500);
          // hide modal
          $("#post-delete-modal").modal("hide");
        }
      });
      // prevent page reload
      event.preventDefault();
    });
  }


  // register all form handlers
  registerFormHandlers() {
    this.registerInsertHandler();
    this.registerUpdateHandler();
    this.registerDeleteHandler();
  }
}


// helper function to get other forum target
function getOtherTarget(target) {
  if (target == "posts") {
    return "threads";
  } else if (target == "threads") {
    return "posts";
  }
}


// switch between viewing posts and threads
function switchTargetView(target) {
  // get other target
  var otherTarget = getOtherTarget(target);
  // hide other target area
  $("#" + otherTarget).hide();
  // show given target area
  $("#" + target).show();
}


// once page has loaded
$(document).ready(function() {

  // create target objects and register form handlers
  var postsTarget = new PostsDatabaseTarget();
  postsTarget.registerFormHandlers();
  var threadsTarget = new ThreadsDatabaseTarget(postsTarget);
  threadsTarget.registerFormHandlers();

  // function to process the page hash
  function processHash() {
    // get hash
    var hashNum = parseInt(location.hash.slice(1));
    // set login/register/logout targets in navbar
    var targetUrl = location.pathname;
    if (hashNum > 0) {
      targetUrl += `#${hashNum}`;
    }
    if ($("#nav-login").length > 0) {
      $("#nav-login").attr("href", "login.php?t=" + targetUrl);
      $("#nav-register").attr("href", "register.php?t=" + targetUrl);
    } else {
      $("#nav-logout").attr("href", "logout.php?t=" + targetUrl);
    }
    // if hash is empty
    if (!location.hash) {
      // hide new post form
      $("#new-post").hide();
      // clear posts page
      $("#posts-page").html("");
      // switch to threads view
      switchTargetView("threads");
      // hide option to create new post
      $("#new-post-expand-btn").hide();
      // show option to create new thread
      $("#new-thread-expand-btn").show();
    // if hash is number > 0
    } else if (hashNum > 0) {
      // hide thread cration form
      $("#new-thread").hide();
      // set current thread
      $("#thread").val(hashNum);
      // get posts by thread number specified in hash
      postsTarget.getPagination(true, hashNum);
      // switch view to posts
      switchTargetView("posts");
      // hide option to create new thread
      $("#new-thread-expand-btn").hide();
      // show option to create new post
      $("#new-post-expand-btn").show();
    }
  }
  
  // set up click event handler for main search button
  $("#forum-search-expand-btn").click(() => {
    // slide up thread creation element
    $("#new-thread").slideUp("slow", () => {
      // slide up post creation element
      $("#new-post").slideUp("slow", () => {
        // toggle slide of search element
        $("#forum-search").slideToggle("slow");
      });
    });
  });

  // set up click event handler for show all threads button
  $("#forum-showall-btn").click(() => {
    // clear current thread value
    $("#new-post #thread").val("");
    // clear search box
    $("#forum-search-box").val("");
    // clear threads page
    $("#threads-page").html("");
    // if hash is not set, force processing of hash
    if (!location.hash) {
      processHash();
    // otherwise, clear hash
    } else {
      location.hash = "";
    }
    // show first page of threads
    threadsTarget.getPagination(false, "");
  });

  // set up click event handler for main new thread button
  $("#new-thread-expand-btn").click(() => {
    // slide up search element
    $("#forum-search").slideUp("slow", () => {
      // toggle slide of thread creation element
      $("#new-thread").slideToggle("slow");
    });
  });

  // set up click event handler for main new post button
  $("#new-post-expand-btn").click(() => {
    // slide up search element
    $("#forum-search").slideUp("slow", () => {
      // toggle slide of new post element
      $("#new-post").slideToggle("slow");
    });
  });

  // handle sumbission of search form
  $("#forum-search").submit(event => {
    // get target and search term
    var target = $('input[name=search-opt]:checked', '#forum-search').val();
    var searchTerm = $("#forum-search-box").val();
    // display relevant pagination and page
    if (target == "threads") {
      threadsTarget.getPagination(false, searchTerm);
    }
    else if (target == "posts") {
      postsTarget.getPagination(false, searchTerm);
    }
    // switch to relevant view
    switchTargetView(target);
    // prevent page reload
    event.preventDefault();
  });

  // click handler for close button on error alert
  $("#error-close").click(() => {
    $("#error-alert").hide();
  });

  // hide error alert when form is submitted
  $("form").submit(() => {
    $("#error-alert").hide();
  });

  // handlers to save form data in web storage in case
  // the page is reloaded e.g. if the user has to log in
  $("#new-thread #title").change(function() {
    sessionStorage.setItem("title", $(this).val());
  });

  $("#new-post #body").change(function() {
    sessionStorage.setItem("body", $(this).val());
  });

  // restore previous title text if present
  var savedTitle = sessionStorage.getItem("title");
  if (savedTitle) {
    $("#new-thread #title").val(savedTitle);
    // if on threads view, show thread creation form
    if (!location.hash) {
      $("#new-thread").show();
    }
  }
  
  // restore previous body text if present
  var savedBody = sessionStorage.getItem("body");
  if (savedBody) {
    $("#new-post #body").val(savedBody);
    // show post creation form if on posts view
    if (location.hash) {
      $("#new-post").show();
    }
  }

  // set up hash change event handler
  window.onhashchange = processHash;

  // ensure correct content is displayed based on hash
  processHash();

  // show first page of threads
  threadsTarget.getPagination(false, "");
});