<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
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
		<title>View Accounts</title>
	<script src="/sixthadmin/resources/javascript/accounts.js"></script>
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
	<div>
		<h1>Manage Accounts</h1>
		<h2>List of Accounts</h2>
		<h3>Search</h3>
		<p>Username:</p><input type="text" id="usersearch"><br>
		<button id="search">Search</button>
		<br>
		<br>
		<table id="account_table" class="data-table">
			<thead>
				<tr><th>Username</th><th>Admin</th><th>Reset Password</th><th>Delete Account</th><th>Rollback Year Group</th></tr>
			</thead>
			<tbody></tbody>
		</table>
		<p id="message"></p>
	</div>
  </body>
</html>
