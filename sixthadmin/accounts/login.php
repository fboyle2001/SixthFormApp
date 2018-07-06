<?php

  // Includes the shared resources such as database access
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");

	/**
	* This function attempts to login the user.
	* It uses the input entered by the user to query the database and verify their details.
	*/
	function login($username, $password) {
		// If the user is already logged in - which shouldn't happen - prevent them logging in again.
		if(isLoggedIn() == true) {
			return "You are already logged in.";
		}

		// Prevents the user logging in if their details are empty
		if(empty($username)) {
			return "The username cannot be blank.";
		}

		if(empty($password)) {
			return "The password cannot be blank.";
		}

		// Selects the user's details from the database
		$selectQuery = "SELECT * FROM `accounts` WHERE `Username` = '$username'";
		$selectResult = DatabaseHandler::getInstance()->executeQuery($selectQuery);

		// If no data is returned, the username must be incorrect
		if($selectResult->wasDataReturned() == false) {
			return "The username is incorrect.";
		}

		if(count($selectResult->getRecords()) != 1) {
			return "The username is incorrect.";
		}

		$record = $selectResult->getRecords()[0];

		// Checks the password against the hash stored in the database
		$passwordsMatch = password_verify($password, $record["Password"]);

		if($record["IsAdmin"] != 1) {
			return "You must be an admin to access this website.";
		}

		if($passwordsMatch == false) {
			return "The password entered is incorrect.";
		}

		// Sets the session username to the username entered by the user
		$_SESSION["user"] = $username;
		return "Success";
	}

	// Stores the message that will be displayed to the user to tell what is wrong with their input
	$message = "Please enter your username and password.";

	// Stores the username between requests to reduce the amount of input the user must do
	$username = "";

	// If the user submitted data then
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		// Get the username if it exists
		$username = empty($_POST["username"]) ? "" : $_POST["username"];

		// Attempt to the log the user in
		$message = login($_POST["username"], $_POST["password"]);
	}

	// If the user is logged in, redirect them to the home page
	// This must come here because then if their login attempt was successful they will be redirected
	if(isLoggedIn() === true) {
		header("Location: /sixthadmin/accounts/");
		die();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="/sixthadmin/resources/css/main.css">
		<title>Login</title>
	</head>
	<body>
		<div class="centred">
			<h1>Login</h1>
			<p>You must login to access the content on this site</p>
			<table>
				<form method="POST" action="login.php">
					<tr><td>Username</td><td><input type="text" name="username" value="<?php echo $username; ?>"></td></tr>
					<tr><td>Password</td><td><input type="password" name="password"></td></tr>
					<tr><td colspan="2"><input type="submit" value="Login"></td></tr>
				</form>
			</table>
			<p>
				<?php
					// Displays the message regarding what the user needs to do
					echo $message;
				?>
			</p>
		</div>
	</body>
</html>
