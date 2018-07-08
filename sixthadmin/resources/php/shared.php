<?php

  /**
  * This is a shared class that contains functions and variables which are useful on many pages or require
  * other shared functions. It also includes the database and reduces the amount of code needed to include
  * all necessary files.
  *
  * Also ensures the session is started on all pages.
  */

  // Includes the database connection
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/database/DatabaseHandler.php");

  // Starts the session
	session_start();

	$cost = 12;

	// Checks if the user is logged in
	function isLoggedIn() {
		if(empty($_SESSION["user"])) {
			return false;
		}

		if($_SESSION["user"] == false) {
			return false;
		}

		return true;
	}

  // Prevents users who haven't logged in from viewing pages by redirecting them to the login page
	function rejectGuest() {
		if(isLoggedIn()) {
			return;
		}

    // Redirects the user to the login page
		header("Location: /sixthadmin/accounts/login.php");
    // Prevents the rest of the page loading in case they do not redirect
		die();
	}

  // Checks if the user is an admin
	function isAdmin() {
    // If they aren't logged in, they can't be an admin
		if(isLoggedIn() === false) {
			return false;
		}

    // If the admin is not stored in the session, find out if the user is an admin
		if(empty($_SESSION["admin"])) {
      // Select if the user is an admin from the database
			$username = $_SESSION["user"];
			$adminQuery = "SELECT `IsAdmin` FROM `users` WHERE `Username` = '$username'";
			$adminResult = DatabaseHandler::getInstance()->executeQuery($adminQuery);

			if($adminResult->wasDataReturned() === false) {
				return false;
			}

      // If the value of IsAdmin is 1 then set the session variable admin to true, otherwise set it to false
			$_SESSION["admin"] = $adminResult->getRecords()[0]["IsAdmin"] == 1 ? true : false;
		}

    // Check if the user is an admin based on the value in the session
		return $_SESSION["admin"] === true;
	}

  function has_arg($method , $name) {
		if(strtoupper($method) == "GET") {
		  return isset($_GET[$name]);
		} else if(strtoupper($method) == "POST") {
		  return isset($_POST[$name]);
		}

		return false;
  }

  function get_arg($method, $name) {
		if(!has_arg($method, $name)) {
		  return null;
		}

		if(strtoupper($method) == "GET") {
		  return $_GET[$name];
		} else if(strtoupper($method) == "POST") {
		  return $_POST[$name];
		}
  }

  function get($name) {
		return get_arg("GET", $name);
  }

  function post($name) {
		return get_arg("POST", $name);
  }
?>
