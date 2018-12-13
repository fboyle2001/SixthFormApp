<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
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
	   <script src="/sixthadmin/resources/javascript/control-accounts.js"></script>
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
    <div>
  		<h1>Control Accounts</h1>
  		<p>These actions affect all of the accounts in the system.</p>
  		<p>Be cautious in using these as they can cause major changes to the system.</p>
      <p>All actions on here will take some time to complete, do not close this window after starting an action.</p>
  		<br>
  		<table id="control_table" class="data-table">
        <thead>
          <tr><th>Action</th><th>Description</th><th>Select</th></tr>
        </thead>
        <tbody>
          <tr><td>Force Password Change (for All Users)</td><td>This will force all users, when they next login, to change their passwords.</td><td><a id="fc" href="javascript:forceChange()">Force Change</a></td></tr>
          <tr><td>Increment Year Groups</td><td>This will increment the year group of all students.</td><td><a id="iy" href="javascript:incrementYears()">Increment Year Groups</a></td></tr>
          <tr><td>Delete Old Users</td><td>This should be used following incrementing year groups. This will cause all users who were in year 13 to be deleted. Make sure to change any resitting students back to the correct year group before using this.</td><td><a id="do" href="javascript:deleteOld()">Delete</a></td></tr>
        </tbody>
  		</table>
    </div>
  </body>
</html>
