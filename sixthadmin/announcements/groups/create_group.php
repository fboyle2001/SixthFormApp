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
    <script src="/sixthadmin/resources/javascript/create-group.js"></script>
    <title>Create Group</title>
  </head>
  <body>
    <?php
			// Includes the default body which includes the navigation menu at the top of the page
			require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
		?>
    <div>
      <h1>Create A Group</h1>
      <br>
      <p>Groups allow you to send announcements directly. You can send an announcement to an individual, a group or everybody.</p>
      <br>
      <form method="POST">
        <label for="gname">Group Name: </label><input type="text" id="gname">
        <br>
        <br>
        <h3>Add Members</h3>
        <br>
        <label for="username">Username: </label><input type="text" id="username"><br>
        <br>
        <button id="add_member">Add Member</button>
        <br>
        <br>
        <h3>Members</h3>
        <table class="data-table" id="members">
          <thead>
            <tr><th>User Name</th><th>Remove Member</th></tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <br>
        <button id="submit">Create Group</button>
      </form>
    </div>
  </body>
</html>
