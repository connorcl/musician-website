"use strict";

// depends on util.js and database-target.js

// subclass of database target for interacting with events backend
class EventsDatabaseTarget extends DatabaseTarget
{
  // constructor
  constructor() {
    super("events-backend.php", "events", false, false);
  }


  // format event as HTML
  formatEvent(event) {
    var event_date = luxon.DateTime.fromSQL(event.date_and_time, {zone: event.timezone});
    var eventHTML = "<div class='card mb-3 midground rounded-0'>";
    eventHTML += `<img src="img/events/${event.img}" alt="${event.title}" class='card-image-top img-w100'>`;
    eventHTML += "<div class='card-img-overlay'>";
    eventHTML += `<h4 class="d-inline-block midground text-dark text-center p-2">${event_date.monthShort}<br><strong>${event_date.day}</strong></h4>`;
    eventHTML += "</div>";
    eventHTML += "<div class='card-body midground rounded-0'>";
    eventHTML += `<h4>${event.title}</h4>`;
    eventHTML += `What: ${event.description}<br>Where: ${event.location}<br>When: ${event_date.toLocaleString(luxon.DateTime.DATETIME_MED)}<br><a class="text-dark stretched-link" target="_blank" href="${event.link}">Event Site</a>`;
    eventHTML += "</div>";
    eventHTML += "</div>";
    return eventHTML;
  }


  // display a page of records
  displayPage(records) {
    // get target name
    var target = this.target;
    // format records as HTML
    var recordsHTML = "";
    for (var record of records) {
      recordsHTML += this.formatEvent(record);
    }
    // display on page
    $("#" + target + "-page").html(recordsHTML);
  }


  // get a page of records from the server and display on page
  getPage(byMatch, selector, page) {
    // compose data to be sent
    var data = {};
    data["page"] = page;
    // set action and searchTerm
    data["action"] = "get-page";
    data["searchTerm"] = selector;
    var obj = this;
    // make asynchronous POST request for page of threads
    $.ajax({
      url: obj.backendUrl, 
      type: "POST", 
      data: data, 
      success: response => {
        checkResponse(response, data => {
          obj.displayPage(data);
        });
      }
    });
  }

  
  // display dynamic countdown to soonest event
  displayCountdown(record) {
    if (!record) {
      return;
    }
    // generate luxon date object
    var event_date = luxon.DateTime.fromSQL(record.date_and_time, {zone: record.timezone});
    var now;
    var d;
    var eventHTML;
    // generate function to update countdown
    function countdown() {
      now = luxon.DateTime.local();
      d = event_date.diff(now, ['weeks', 'days', 'hours', 'minutes']);
      eventHTML = `<strong><span>${d.weeks}</span> ${d.weeks == 1 ? "week" : "weeks"}, `;
      eventHTML += `<span>${d.days}</span> ${d.days == 1 ? "day" : "days"}, `;
      eventHTML += `<span>${d.hours}</span> ${d.hours == 1 ? "hour" : "hours"}, `;
      eventHTML += `<span>${Math.floor(d.minutes)}</span> ${Math.floor(d.minutes) == 1 ? "minute" : "minutes"}</strong><br>`;
      eventHTML += `at ${record.title}`;
      $("#event-countdown-time").html(eventHTML);
    }
    // display countdown
    countdown();
    // update countdown every 10 seconds
    setInterval(countdown, 10000);
  }


  // get soonest event and start dynamic countdown
  getSoonest() {
    $.post(this.backendUrl, {action: "get-soonest"}, response => {
      checkResponse(response, event => {
        this.displayCountdown(event);
      });
    });
  }
}


// once page has loaded
$(document).ready(() => {
  // create events target object
  var eventsTarget = new EventsDatabaseTarget();

  // set search form handler
  $("#events-search").submit(function(event) {
    eventsTarget.getPagination(false, $("#events-search-box").val());
    event.preventDefault();
  });

  // display first page of events and start countdown to soonest event
  eventsTarget.getPagination(false, $("#events-search-box").val());
  eventsTarget.getSoonest();
});