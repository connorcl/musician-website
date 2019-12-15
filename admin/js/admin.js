"use strict";

// depends on util.js, database-target.js and jquery.twbsPagination.js

// class representing a database target for administration by a privileged admin user
class AdminDatabaseTarget extends DatabaseTarget
{
  // constructor
  constructor(target, colNames, child, childTarget) {
    // call parent constructor
    super("manage-db.php", target, child, childTarget);
    // record column names
    this.colNames = colNames;
    // keep track of whether current page is by search, parent or ID
    this.pageType = 0;
    // keep track of current page selector (search term or ID)
    this.pageSelector = "";
  }

  
  // generates a table header based on column names and parent/child status
  generateHeader() {
    // string of HTML for table header
    var headerHTML = '<tr>';
    // generate header item for each column
    for (var col of this.colNames) {
      if (col == "id") {
        headerHTML += `<th>${col.toUpperCase()}</th>`;
      } else {
        headerHTML += `<th>${toTitleCase(col)}</th>`;
      }
    }
    // if target is a parent of another, generate column
    // for button to view child records of parent record
    if (this.parent) {
      headerHTML += `<th>View ${this.childTarget.target}</th>`; 
    }
    // generate columns for buttons to update and delete records 
    for (var col of ["Update", "Delete"]) {
      headerHTML += `<th>${col}</th>`;
    }
    headerHTML += "</tr>";
    return headerHTML;
  }


  // generate a table row for a record
  generateRow(record) {
    // string of HTML for table row
    var rowHTML = "<tr>";
    // format each field of record as cell in table row
    for (var col of this.colNames) {
      if (col == "body" || col == "description") {
        rowHTML += `<td title='${record[col]}'>${record[col]}</td>`;
      } else {
        rowHTML += `<td>${record[col]}</td>`;
      }
    }
    // generate button to view child records if target is parent
    if (this.parent) {
      rowHTML += `<td><button class='btn btn-info ${this.childTarget.target}-view-btn' `;
      rowHTML += `value='${record.id}'>`;
      rowHTML += "View</button></td>";
    }
    // generate update and delete buttons
    for (var action of ["update", "delete"]) {
      rowHTML += "<td><button class='btn ";
      if (action == "update") {
        rowHTML += "btn-info ";
      } else if (action == "delete") {
        rowHTML += "btn-danger ";
      }
      rowHTML += `${this.target}-${action}-modal-btn' data-toggle='modal' data-target='#`;
      rowHTML += `${this.target}-${action}-modal' value='${record.id}'>`;
      rowHTML += `${toTitleCase(action)}</button></td>`;
    }
    rowHTML += "</tr>";
    return rowHTML;
  }
  

  // get a page of records from the server and display it on the page
  getPage(byMatch, selector, page) {
    // compose data to be sent
    var data = {
      target: this.target,
      page: page
    };
    // if records are selected by match with parent ID
    if (byMatch) {
      // set action and ID
      data.action = "get-page-by-match";
      data.match = selector;
    // if records are selected by search
    } else {
      // set action and searchTerm
      data.action = "get-page";
      data.searchTerm = selector;
    }
    // make asynchronous POST request for page of records
    $.post(this.backendUrl, data, response => {
      // check response for errors
      checkResponse(response, records => {
        // display page of records on success
        this.displayPage(records);
      });
    });
  }


  // display a page of records
  displayPage(records) {
    // get current object and target
    var obj = this;
    var target = this.target;
    // generate HTML table
    var recordsHTML = "<table class='table table-hover'>";
    recordsHTML += obj.generateHeader();
    for (var record of records) {
      recordsHTML += obj.generateRow(record);
    }
    recordsHTML += "</table>";
    // set table HTML
    $(`#${target}-table-area`).html(recordsHTML);

    // register update button click handler
    $(`.${target}-update-modal-btn`).click(function() {
      // keep track of record ID to operate on
      var id = $(this).val();
      $(`#${target}-id-to-update`).val(id);
      $(`#title-${target}-id-to-update`).html(id);
    });

    // register delete button click handler
    $(`.${target}-delete-modal-btn`).click(function() {
      // keep track of record ID to operate on
      var id = $(this).val();
      $(`#${target}-id-to-delete`).val(id);
      $(`#title-${target}-id-to-delete`).html(id);
    });
    
    // if record is a parent
    if (this.parent) {
      // set click handler for view button
      $(`.${obj.childTarget.target}-view-btn`).click(function() {
        // get ID of self (parent)
        var id = $(this).val();
        // keep track of page details
        obj.childTarget.pageType = 1;
        obj.childTarget.pageSelector = id;
        // display child pagination based on match
        obj.childTarget.getPagination(true, id);
      });
    }
  }


  // register event handler for search form submission
  registerSearchHandler() {
    // get target string
    var target = this.target;
    // register form handler
    $(`#${target}-search-form`).submit(event => {
      // get search term
      var searchTerm = $(`#${target}-search-term`).val();
      // keep track of page details
      this.pageType = 0;
      this.pageSelector = searchTerm;
      // update pagination based on search results (and show first page)
      this.getPagination(false, searchTerm);
      // prevent page reload
      event.preventDefault();
    });
  }
 

  // register event handler for get by ID form submission
  registerGetByIDHandler() {
    // get current object and target
    var obj = this;
    var target = this.target;
    // register form handler
    $(`#${target}-get-by-id-form`).submit(function(event) {
      // make asynchronous POST request
      $.post(obj.backendUrl, $(this).serialize(), response => {
        // check response for errors
        checkResponse(response, record => {
          if (record) {
            // keep track of page details
            obj.pageType = 2;
            obj.pageSelector = $(this.match).val();
            // destory pagination as there are either 0 or 1 results
            destroyPagination($(`#${target}-pagination-links`));
            // display record
            obj.displayPage([record]);
          }
        });
      });
      // prevent page reload
      event.preventDefault();
    });
  }


  // register event handler for record insert form
  registerInsertHandler() {
    // get current object and target
    var obj = this;
    var target = this.target;
    // register event handler
    $(`#${target}-insert-form`).submit(function(event) {
      // hide error message
      $("#error-alert").hide();
      // make asynchronous POST request
      $.post(obj.backendUrl, $(this).serialize(), response => {
        // check response for errors
        checkResponse(response, () => {
          // on success, display default page and hide insert dialog
          obj.getPagination(false, "", 1);
          // clear form inputs
          this.reset();
        });
        // hide modal
        $(`#${target}-insert-modal`).modal("hide");
      });
      // prevent page reload
      event.preventDefault();
    });
  }

  
  // register event handler for record update form submission
  registerUpdateHandler() {
    // get current object and target
    var obj = this;
    var target = this.target;
    // register event handler
    $(`#${target}-update-form`).submit(function(event) {
      // hide error message
      $("#error-alert").hide();
      // make asynchronous POST request
      $.post(obj.backendUrl, $(this).serialize(), response => {
        // check response for errors
        checkResponse(response, () => {
          // on success, clear ID to update, update current page and hide update dialog
          $(`#${target}-id-to-update`).val("");
          // reload current page of records
          switch(obj.pageType) {
            case 0:
              // reload page of search results
              obj.getPagination(
                false,
                obj.pageSelector,
                $(`#${target}-pagination-links`).twbsPagination("getCurrentPage")
              );
              break;
            case 1:
              // reload page of records selected by parent
              obj.getPagination(
                true,
                obj.pageSelector,
                $(`#${target}-pagination-links`).twbsPagination("getCurrentPage")
              );
              break;
            case 2:
              // get by ID form
              var getByIDForm = document.getElementById(`${target}-get-by-id-form`);
              // set ID value
              getByIDForm.match.value = obj.pageSelector;
              // reload record selected by ID
              $(getByIDForm).trigger("submit");
          }
          // clear form inputs
          this.reset();
        });
        // hide modal
        $(`#${target}-update-modal`).modal("hide");
      });
      // prevent page reload
      event.preventDefault();
    });
  }


  // register event handler for record deletion form submission
  registerDeleteHandler() {
    // get current object and target
    var obj = this;
    var target = this.target;
    // register event handler
    $(`#${target}-delete-form`).submit(function(event) {
      // make asynchronous POST request
      $.post(obj.backendUrl, $(this).serialize(), response => {
        // check response for errors
        checkResponse(response, () => {
          // on success, clear ID to delete, display default page and child default page and hide delete dialog
          $(`#${target}-id-to-delete`).val("");
          obj.getPagination(false, "", 1);
          if (obj.childTarget) {
            obj.childTarget.getPagination(false, "", 1);
          }
          $(`#${target}-delete-modal`).modal("hide");
        });
      });
      // prevent page reload
      event.preventDefault();
    });
  }


  // register all form handlers
  registerFormHandlers() {
    this.registerSearchHandler();
    this.registerInsertHandler();
    this.registerGetByIDHandler();
    this.registerUpdateHandler();
    this.registerDeleteHandler();
  }
}


// once page has loaded
$(document).ready(() => {

  // create object to hold target objects
  var targets = {};

  // create target objects
  targets.users = new AdminDatabaseTarget("users", ["id", "username", "email", "registered"], false, false);
  targets.posts = new AdminDatabaseTarget("posts", ["id", "thread", "body", "username", "created"], true, false);
  targets.threads = new AdminDatabaseTarget("threads", ["id", "title", "username", "created"], false, targets.posts);
  targets.tracks = new AdminDatabaseTarget("tracks", ["id", "album", "title"], true, false);
  targets.albums = new AdminDatabaseTarget("albums", ["id", "title", "release_year"], false, targets.tracks);
  targets.events = new AdminDatabaseTarget("events",  ["id", "title", "description", "location", "link", "date_and_time", "timezone", "img"], false, false);
  targets.admins = new AdminDatabaseTarget("admins", ["id", "username", "created"], false, false);

  // for each target
  for (var target in targets) {
    // register all form handlers
    targets[target].registerFormHandlers();
    // get default pagination and first page
    targets[target].getPagination(false, "");
  }

  // click handler for close button on error alert
  $("#error-close").click(() => {
    $("#error-alert").hide();
  });

  // hide error alert when form is submitted
  $("form").submit(() => {
    $("#error-alert").hide();
  });
});