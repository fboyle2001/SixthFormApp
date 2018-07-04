<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");

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
  </head>
  <body>
    <?php 
		// Includes the default body which includes the navigation menu at the top of the page
		require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php"); 
	?>
	<div>
		<h2>Web Application</h2>
		<ul>
			<li>Users with admin rights are the only users with access to this</li>
			<li>Used to push content to the app (notices, newsletters etc.)</li>
			<li>Used to create and reset account passwords</li>
			<li>Will be able to make announcements which will then appear on the app</li>
			<li>Will also be able to manage school events on the calendar</li>
		</ul>
	</div>
  </body>
</html>
