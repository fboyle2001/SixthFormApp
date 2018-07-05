<?php
	// Includes the shared PHP resource page which includes useful functions for data conversion
	// And it includes the necessary code for connecting to the database
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");

	// Calls a function from the shared page which prevents non-logged in users from accessing the page
	// Guests will be redirect to the login page
	rejectGuest();

	$message = "";

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = post("username");
		$admin = post("admin");
		$year = post("year");

		if($username == null) {
			$message = "The username must be set.";
		} else {
			if($admin == null && $year == null) {
				$message = "Either the admin field or year field must be filled.";
			} else {
				if($admin == null && ($year < 12 || $year > 13)) {
					$message = "The year group must be either 12 or 13.";
				} else {
					$existsQuery = "SELECT * FROM `accounts` WHERE `Username` = '$username'";
					$existsQuery = DatabaseHandler::getInstance()->executeQuery($existsQuery);

					if($existsQuery->wasDataReturned() == true) {
						$message = "An account already exists with username $username";
					} else {
						if($admin == "on") {
							$year = 0;
							$admin = 1;
						} else {
							$admin = 0;
						}

						$password = password_hash("Passw0rd", PASSWORD_BCRYPT);

						$insertQuery = "INSERT INTO `accounts` (`Username`, `Password`, `Year`, `IsAdmin`) VALUES ('$username', '$password', '$year', '$admin')";
						$insertQuery = DatabaseHandler::getInstance()->executeQuery($insertQuery);

						if($insertQuery->wasSuccessful()) {
							$message = "Created new account with username $username!";
						} else {
							$message = "Unable to create new account, please try again later.";
						}
					}
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
		// Includes the default header which includes the stylesheet and navigation JavaScript
		// It also includes jQuery in the page
		require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/head.php");
	?>
	<script src="/sixthadmin/resources/javascript/create-account.js"></script>
  </head>
  <body>
    <?php
		// Includes the default body which includes the navigation menu at the top of the page
		require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/body.php");
	?>
	<div>
		<h1>Create New Account</h1>
		<p>To create a new user, enter a unique username and select if they are an admin or enter their year group.</p>
		<p>By default, their password will be Passw0rd (which they can then change).</p>
		<br>
		<form method="POST" id="account_form">
			<table>
				<tr><td>Username</td><td><input required type="text" name="username" placeholder="Enter username..."></td></tr>
				<tr><td>Admin</td><td><input type="checkbox" name="admin" id="admin"></td></tr>
				<tr id="year_row"><td>Year Group</td><td><input id="year" name= "year" type="number" min="12" max="13"></td></tr>
				<tr><td colspan="2"><input type="submit" value="Create Account"></td></tr>
			</table>
		</form>
		<p><?php echo $message; ?></p>
	</div>
  </body>
</html>
