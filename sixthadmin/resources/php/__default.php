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
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
  </body>
</html>
