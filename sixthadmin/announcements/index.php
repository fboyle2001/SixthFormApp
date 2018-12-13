<?php
  require("../shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
			// Includes the default header which includes the stylesheet and navigation JavaScript
			// It also includes jQuery in the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/head.php");
		?>
    <script src="/sixthadmin/resources/javascript/announcements.js"></script>
		<title>View Announcements</title>
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
    <div>
      <h1>Announcements</h1>
      <br>
      <h2>Search</h2>
      <p>Title:</p><input type="text" id="titlesearch"><br>
      <button id="search_by_title">Search by Title</button>
      <br>
      <p>Content:</p><textarea rows="8" cols="60" id="contentsearch" style="resize: none"></textarea><br>
      <button id="search_by_content">Search by Content</button>
      <br>
      <br>
      <h2>Manage Announcements</h2>
      <table id="announcements_table" class="data-table">
        <thead>
          <tr><th>ID</th><th>Title</th><th>Content</th><th>Group</th><th>Date Added</th><th>Delete</th></tr>
        </thead>
        <tbody></tbody>
      </table>
      <p id="message"></p>
    </div>
  </body>
</html>
