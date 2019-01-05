<?php
  require("../../shared.php");

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
    <script src="/sixthadmin/resources/javascript/view-groups.js"></script>
    <title>View Groups</title>
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
    <div>
      <h1>View Groups</h1>
      <br>
      <h2>Search</h2>
      <p>Group Name:</p><input type="text" id="groupsearch">
      <br>
      <button id="search_by_name">Search by Group Name</button>
      <br>
      <br>
      <table class="data-table" id="groups">
        <thead>
          <tr><th>Group Name</th><th>Members</th><th>Delete</th><th>Edit</th></tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <p id="message"></p>
    </div>
  </body>
</html>
