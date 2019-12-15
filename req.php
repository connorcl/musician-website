<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Requirements page</title>
	<link rel="stylesheet" href="css/reqStyle.css">
</head>
<body>

<h1>CMP204 Requirements Page</h1>

<p>If you have not met a requirement, do not delete it from the table.</p>

<table>
  <tr>
    <th class="reqCol">Requirement</th>
    <th class="metCol">How did you meet this requirement?</th>
    <th class="fileCol">File name(s), line no.</th>
  </tr>
  <tr>
    <td>A clear use of HTML5</td>
    <td>
      HTML documents use the HTML5 doctype, and are structured using HTML5
      semantic elements. Also, new HTML5 input types, such as email, are used.
    </td>
    <td>
      e.g. index.php lines 5, 28, 34; music.php lines 5, 33; navbar.php line 18; register.php line 170
    </td>
  </tr>
  <tr>
    <td>Use of the Bootstrap framework providing a responsive layout</td>
    <td>
      HTML documents are styled using Bootstrap's responsive grid
      system, and the navigation bar (which is included on each page) makes 
      use of Bootstrap to collapse its naviagtion links on smaller devices.
      Other bootstrap features are used throughout each page.
    </td>
    <td>
      e.g. index.php lines 28, 30, 32, 49; navbar.php lines 18, 22; music.php lines 45, 47, 54;
    </td>
  </tr>
  <tr>
    <td>Use of JavaScript to manipulate the DOM based on an event</td>
    <td>
      When an album is clicked on the music listing page, the hash in the url changes.
      An event handler is registered for the hashchange event which hides and clears
      the album details page and shows the main album listing if the hash is empty, and
      hides the main album listing and populates and shows the album details section
      (using the hash number as the album ID) if the hash is a number.
    </td>
    <td>
      js/music.js lines 41-60, 64
    </td>
  </tr>
  <tr>
    <td>JavaScript loading of dynamically changing information</td>
    <td>
      On the tour page, JavaScript is used to create a dynamic countdown to the soonest
      event. The luxon JavaScript date library is used to simplify handling the fact that 
      the event may take place in a different timezone from the user's current timezone, and
      that the event's time must be displayed in the event's timezone.
    </td>
    <td>
      js/tour.js lines 69-93
    </td>
  </tr>
  <tr>
    <td>Use of jQuery in conjunction with the DOM</td>
    <td>
      Extensive use of jQuery is made throughout the application. For example, the
      carousel controls on the homepage make use of jQuery to play, pause and advance
      the carousel, highlighting the control link corresponding to the current image.
      The forum page uses jQuery to slide the search, post creation and thread creation
      forms up and down at appropriate times, to alter the login/logout/register redirection
      link(s) in the navbar and show, hide and dynamically populate the appropriate posts 
      or threads view based on the page hash, and to handle the submission of the search and 
      thread/post creation, update and deletion forms. At least one use of jQuery in 
      conjunction with the DOM can be found in each JavaScript file.
    </td>
    <td>
      e.g. js/home.js lines 40-86; js/forum.js lines 87-116, 125-157, 430-570. Many more
      examples throughout js/forum.js, admin/js/admin.js, and all other js files under
      js/ (luxon.js and twbsPagination.jQuery.js are external libraries)
    </td>
  </tr>
  <tr>
    <td>Use of a jQuery plugin to enhance your application</td>
    <td>
      The twbsPagination jQuery plugin is used on the forum page, tour page and admin panel 
      to simplify the creation of pagination links for pages of database records. NOTE: all
      pages are set to be only 2 records long, to make it easier to evaluate whether the
      pagination system works without needing a large amount of test data.
    </td>
    <td>
      js/database-target.js lines 74-83
    </td>
  </tr>
  <tr>
    <td>Use of AJAX (pure JavaScript i.e. without the use of a library)</td>
    <td>
      The random lyric widget on the homepage works by using AJAX to fetch 
      random lyrics asynchronously without having to reload the page each 
      time or load all the lyrics up front.
    </td>
    <td>
      js/home.js lines 4-34
    </td>
  </tr>
  <tr>
    <td>Use of the jQuery AJAX function</td>
    <td>
      Extensive use of the jQuery ajax() function is made throughout the application.
      The user account management page makes use of the ajax() function to fetch the user's
      details from the backend and send requests to update or delete the user's account.
      Pages of forum posts and threads are fetched using jQuery's ajax() function, with
      the ajax() function also being used to send requests to create/edit/delete threads and posts.
      Additionally, when an album's card on the music page is clicked, the jQuery ajax() 
      function is used to asynchronously fetch the album's details and track listing. 
      jQuery's post() shorthand function is also used extensively on the admin page 
      for interacting with the database backend to fetch, insert, update and delete 
      records without reloading the page.
    </td>
    <td>
      js/account.js lines 8-27, 40-54, 62-72; 
      js/forum.js lines 70-78, 91-113, 129-155, 220-229, 266-288, 304-334, 350-375;
      js/music.js lines 6-17, 23-37;
      Frequent usage of post() throughout js/admin/admin.js
    </td>
  </tr>
  <tr>
    <td>User login functionality (PHP/MySQL)</td>
    <td>
      Users can register a new account and have their details stored in the database,
      and may then log in, having the details they enter verified against those stored
      in the database. Users can also edit all of their details via the account 
      management page, and log out.
    </td>
    <td>
      register.php (whole file), login.php (whole file), logout.php (whole file), 
      account.php (whole file), account-backend.php (whole-file), 
      class-account-database-target.php (whole file)
    </td>
  </tr>
  <tr>
    <td>Admin section of the website (PHP/MySQL)</td>
    <td>
      An admin login system separate from the user login system is used for authentication 
      with the admin page and its backend facilities. See 'Ability to select, add, edit 
      and delete information from a database (PHP/MySQL)' for details of the admin page's
      functionality.
    </td>
    <td>
      admin/login.php (whole file), admin/index.php (whole file), admin/manage-db.php (whole file),
      admin/class-admin-database-target.php (whole file), class-database-target.php (whole file)
    </td>
  </tr>
  <tr>
    <td>Ability to select, add, edit and delete information from a database (PHP/MySQL)</td>
    <td>
      The admin page provides the ability to administer the various tables in the database - users,
      admins, forum posts, forum threads, events, albums and tracks. Records in these tables may be 
      viewed (selected), updated, and deleted, and new records may be inserted. 
      The frontend uses AJAX requests to send commands to the backend, which is built
      using a system of PHP classes which provide reusable code for implementing backend features. 
      The abstract base class in this system, DatabaseTarget, provides the core functionality such as 
      getting the number of pages of records, getting a page of records based on a search string or 
      a match (e.g. posts from a certain thread), and inserting, updating and deleting records. 
      The subclass AdminDatabaseTarget implements the abstract methods from this base class, those used
      to validate inputs from POST data and save them so they can be used by the core methods. In the
      backend file which processes the POST requests sent via AJAX, objects for each 'target' to be
      administered (i.e. users, events, etc.) are created, with the required details specified in their
      constructors, such as which table to operate on, whether and how to join with another table when
      selecting, and which columns are required for insertion, etc. The code in DatabaseTarget is also
      reused for the forum, events and user account backends. Also, the database was configured so that
      when a user or thread is deleted, all associated forum posts are deleted (ON DELETE CASCADE), and
      an SQL trigger was defined so that when a user is deleted, threads they started are reassigned to
      a user with the username [Deleted].
    </td>
    <td>
      admin/index.php (whole file), class-database-target.php (whole file),
      admin/class-admin-database-target.php (whole file), admin/manage-db.php (whole-file). Additionally,
      the forum backend is composed of forum-backend.php, class-forum-database-target.php, 
      class-forum-thread-database-target.php, and class-forum-post-database-target.php.
      The tour backend is composed of events-backend.php and class-events-database-target.php,
      and the user account backend is composed of account-backend.php and class-account-database-target.php.
    </td>
  </tr>
  <tr>
    <td>Appropriate consideration of relevant laws</td>
    <td>
      The cookie law is addressed by having a cookie consent modal dialog appear when the
      website is visited. This dialog presents the website's cookie policy. Once this is accepted,
      it no longer appears. GDPR is addressed by including a GDPR-aware privacy policy which
      must be accepted when a user creates an account. Additionally, the registration form complies
      with GDPR in that neither the privacy policy agreement nor the marketing email consent checkboxes
      are checked by default.
    </td>
    <td>
      cookies-consent.php (whole file); js/main.js lines 29-36; privacy.php (whole file);
      register.php lines 95-95, 197-201
    </td>
  </tr>
  <tr>
    <td>Security measures</td>
    <td>See below</td>
    <td>See below</td>
  </tr>
  <tr>
    <td>SQL queries should be written as prepared statements</td>
    <td>
      All SQL queries are written as prepared statements, in that they use an executePreparedStmt utility
      function. All parameters which are derived from user input are bound as prepared statement parameters.
    </td>
    <td>
      util.php lines 16-48;
      e.g. class-database-target.php lines 83, 101, 117, 130, 140, 147, 148, 220, 249, 276, 298, 319, 344, 364. 
      class-forum-database-target.php line 29, 
      login.php line 51, 70, 114
      register.php lines 147-155
    </td>
  </tr>
  <tr>
    <td>Passwords should be salted and hashed</td>
    <td>
      User passwords are salted and hashed before being stored in the database, using PHP's password_hash function.
    </td>
    <td>
      register.php line 111
    </td>
  </tr>
  <tr>
    <td>Validation of user input</td>
    <td>
      All user input which is stored in the database is sanitized, i.e. trimmed of whitespace, unquoted and
      html special-character escaped, so that it is safe to be displayed when retrieved. 
      The same applies to user input sent via GET or POST which is used in any database query or 
      substituted into a page. Email addresses are validated based on PHP's FILTER_VALIDATE_EMAIL,
      and usernames are validated to only contain alphanumeric characters or dots, and to check they
      are between 4 and 20 characters long. Forum post and thread text is also validated for length.
      Search strings are validated to be at most 100 characters long and have the % symbol escaped
      as this is an SQL wildcard, and numbers sent as e.g. record IDs must be positive.
    </td>
    <td>
      e.g.  util.php lines 4-6; 
      class-database-target.php lines 405, 407, 419, 421, 484;
      class-forum-database-target.php line 46;
      class-forum-posts-database-target.php lines 44, 50, 51, 70, 72;
      class-forum-threads-database-target.php lines 39, 40, 59, 60;
      class-account-database-target lines 46, 50, 52, 87, 91, 116;
      admin/class-admin-database-target.php lines 38, 74; 
      register.php lines 14, 38, 44, 46, 48, 66, 67, 182, 192;
      login.php lines 17, 45, 54, 152;
      admin/login.php lines 30, 108
    </td>
  </tr>
  
  <tr>
    <td>Any other relevant security features</td>
    <td>
      The possibility of spam posting on the forum is addressed by limiting the number of posts and threads
      any user can create in a single day. Passwords are enforced to be at least 12 characters long. When
      setting the login target link in the navbar,  the hash number is extracted and validated and added to
      the page file name rather than simply taking the url, in order to prevent a potential reflected XSS scenario.
    </td>
    <td>
      class-forum-database-target.php lines 19-38;
      register.php line 84;
      class-database-target.php line 440;
      js/forum.js lines 423-435
    </td>
  </tr>
</table>
		
</body>
</html>