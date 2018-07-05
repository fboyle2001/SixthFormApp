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
    <script src="/sixthadmin/resources/javascript/links.js"></script>
  </head>
  <body>
    <?php
		// Includes the default body which includes the navigation menu at the top of the page
		require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
	   ?>
     <div>
      <h1>Links</h1>
  		<br>
  		<h2>Search</h2>
  		<p>Name:</p><input type="text" id="namesearch"><br>
  		<button id="search">Search</button>
  		<br>
  		<br>
      <h2>Manage Links</h2>
      <table id="links_table" class="data-table">
        <thead>
          <tr><th>ID</th><th>Name</th><th>Expiry Date</th><th>Link</th><th>Delete</th></tr>
        </thead>
        <tbody></tbody>
      </table>
      <p id="message"></p>
     </div>
  </body>
</html>
