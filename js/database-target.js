"use strict";

// depends on util.js and jquery.twbsPagination.js

// abstract base class for class which interacts with the backend database via AJAX requests
class DatabaseTarget
{
  // constructor
  constructor(backendUrl, target, child, childTarget) {
    // url of backend application
    this.backendUrl = backendUrl;
    // target string used to associate with a database table
    this.target = target;
    // whether the database target is the child of another,
    // i.e. its records are owned by a record from another target
    this.child = child;
    // child database target object
    this.childTarget = childTarget;
    // whether the database target is the parent of another
    this.parent = (this.childTarget) ? true : false;
  }
  

  // get the number of pages of records from the server
  // and use this to create pagination links
  getPagination(byMatch, selector, startPage = 1) {
    // compose data to be sent
    var data = {
      target: this.target
    };
    // if records are selected by match with parent ID
    if (byMatch) {
      // set action and ID accordingly
      data.action = "get-count-by-match";
      data.match = selector;
    // if records are selected by search
    } else {
      // set action and searchTerm accordingly
      data.action = "get-count";
      data.searchTerm = selector;
    }
    // make asynchronous POST request
    $.post(this.backendUrl, data, response => {
      checkResponse(response, responseData => {
        this.displayPagination(responseData, byMatch, selector, startPage);
      });
    });
  }


  // display pagination links on the page
  displayPagination(pages, byMatch, selector, startPage) {
    // get number of pages
    pages = parseInt(pages);
    // process start page
    if (startPage == -1) {
      startPage = pages;
    }
    // get target
    var target = this.target;
    // get pagination elements
    var pagination = $(`#${target}-pagination`);
    var paginationLinks = $(`#${target}-pagination-links`);
    // destroy previous pagination
    destroyPagination(paginationLinks);
    pagination.html("");
    // display new pagination if number of pages is positive
    if (pages > 0) {
      // create pagination links element within pagination element
      pagination.html(`<ul id='${target}-pagination-links' class='pagination-sm justify-content-center mt-3'></ul>`);
      // get new pagination links element
      paginationLinks = $(`#${target}-pagination-links`);
      // set up pagination links (using twbsPagination jQuery plugin)
      paginationLinks.twbsPagination({
        totalPages: pages,
        visiblePages: 3,
        startPage: startPage,
        // page click event for pagination
        onPageClick: (event, page) => {
          // display page that was clicked
          this.getPage(byMatch, selector, page);
        }
      });
    // otherwise display page without pagination links
    } else {
      this.getPage(byMatch, selector, 1);
    }
  }
  

  // get a page of records from the server and display it on the page
  getPage(byMatch, selector, page) {
    throw new Error("Not implemented!");
  }
}