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
    <script>
      // Declares a global variable with the auth token
      var serverAuthToken = "<?= $_SESSION["serverAuthToken"] ?>";
      alert(serverAuthToken);
    </script>
    <script src="/sixthadmin/resources/javascript/files.js"></script>
		<title>View Files</title>
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
	  ?>
		<datalist id="type">
			<option value="Newsletter" data-id="1">
			<option value="Notices" data-id="2">
		</datalist>
    <div>
	    <h1>Files</h1>
	  	<br>
	  	<h2>Search</h2>
	  	<p>File Name:</p><input type="text" id="namesearch"><br>
	  	<button id="search_by_name">Search by File Name</button>
	  	<br>
	  	<p>Type:</p><input list="type" id="typesearch"><br>
	  	<button id="search_by_type">Search by Type</button>
			<br>
			<br>
	    <h2>Manage Files</h2>
	    <table id="files_table" class="data-table">
	      <thead>
	        <tr><th>Name</th><th>Date Added</th><th>Expiry Date</th><th>Type</th><th>Download</th><th>Delete</th></tr>
	      </thead>
	      <tbody></tbody>
	    </table>
	    <p id="message"></p>
    </div>
  </body>
</html>
