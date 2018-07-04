<?php

  // Include the shared functions page
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");

	// Logs a user out
	function logout() {
		// If the user is logged in, destroy the session
		if(isLoggedIn()) {
			$_SESSION["user"] = false;
			session_destroy();
		}

		// Redirect the user to the login page
		header("Location: http://localhost/sixthadmin/accounts/login.php");
		die();
	}

  // Call the logout function
	logout();

?>
