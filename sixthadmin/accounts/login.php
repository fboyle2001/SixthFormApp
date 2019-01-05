<?php
  // Includes the shared resources such as database access
	require("../shared.php");

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
		$selectQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `Username` = :username");
		$selectQuery->execute(["username" => $username]);

		// If no data is returned, the username must be incorrect
		if($selectQuery == false) {
			return "The username is incorrect.";
		}

		if($selectQuery->rowCount() != 1) {
			return "The username is incorrect.";
		}

		$record = $selectQuery->fetch();

		// Checks the password against the hash stored in the database
		$passwordsMatch = password_verify($password, $record["Password"]);

		if($record["IsAdmin"] != 1) {
			return "You must be an admin to access this website.";
		}

		if($passwordsMatch == false) {
			return "The password entered is incorrect.";
		}

		$id = $record["ID"];

		// Trigger file and link deletion
		deleteOld();

		// Sets the session username to the username entered by the user
		$_SESSION["user"] = $username;
		$_SESSION["userId"] = $id;
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
		<link rel="stylesheet" href="/sixthadmin/resources/css/main.css">
		<script src="/sixthadmin/resources/javascript/jquery-3.2.1.min.js"></script>
		<script src="/sixthadmin/resources/javascript/shared.js"></script>
		<link rel="apple-touch-icon" sizes="57x57" href="/sixthadmin/resources/icons/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/sixthadmin/resources/icons/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/sixthadmin/resources/icons/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/sixthadmin/resources/icons/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/sixthadmin/resources/icons/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/sixthadmin/resources/icons/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/sixthadmin/resources/icons/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/sixthadmin/resources/icons/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/sixthadmin/resources/icons/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/sixthadmin/resources/icons/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/sixthadmin/resources/icons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/sixthadmin/resources/icons/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/sixthadmin/resources/icons/favicon-16x16.png">
		<link rel="manifest" href="/sixthadmin/resources/icons/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/sixthadmin/resources/icons/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
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
