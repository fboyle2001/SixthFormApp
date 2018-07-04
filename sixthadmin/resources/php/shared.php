<?php

  /**
  * This is a shared class that contains functions and variables which are useful on many pages or require
  * other shared functions. It also includes the database and reduces the amount of code needed to include
  * all necessary files.
  *
  * Also ensures the session is started on all pages.
  */

  // Includes the database connection
	require($_SERVER["DOCUMENT_ROOT"] . "/coursework/resources/php/database/DatabaseHandler.php");

  // Starts the session
	session_start();

  // Used to resolve the numerical value stored in the database to text that the user can understand
	$skipSizes = array(
		"0" => "4 yard",
		"1" => "8 yard",
		"2" => "20 yard",
		"3" => "30 yard",
		"4" => "40 yard"
	);

  // Used to convert the numerical value to the text value
	function resolveSkipSize($size) {
		global $skipSizes;
    // If the size is a key in the skip size array then return the associated text
		return array_key_exists($size, $skipSizes) ? $skipSizes[$size] : "Error";
	}

  // Used to convert the text value to the numerical value
	function reverseResolveSkipSize($type) {
		global $skipSizes;
    // If type is in the skip sizes then find the key associated with the value
		return in_array($type, $skipSizes) ? array_search($type, $skipSizes) : "Error";
	}

  // Used to resolve the numerical value stored in the database to text that the user can understand
	$paymentMethods = array(
		"0" => "Cash on Delivery",
		"1" => "Card",
		"2" => "Account",
		"3" => "N/A"
	);

  // Used to convert the numerical value to the text value
	function resolvePaymentMethod($method) {
		global $paymentMethods;
    // If the size is a key in the payment methods array then return the associated text
		return array_key_exists($method, $paymentMethods) ? $paymentMethods[$method] : "Error";
	}

  // Used to convert the text value to the numerical value
	function reverseResolvePaymentMethod($type) {
		global $paymentMethods;
    // If type is in the payment methods then find the key associated with the value
		return in_array($type, $paymentMethods) ? array_search($type, $paymentMethods) : "Error";
	}

  // Converts a date of format YYYY-MM-DD to DD/MM/YYYY
	function convertDate($date) {
    // Split the date at '-' into parts e.g. ['YYYY', 'MM', 'DD']
		$parts = explode("-", $date);

    // Reverse the array e.g. ['DD', 'MM', 'YYYY']
		$parts = array_reverse($parts);

    // If the year is '0000', the date was left empty so return a dash to indicate this
		if($parts[2] == "0000") {
			return "-";
		}

    // Join the date up using '/' e.g. DD/MM/YYYY
		return implode("/", $parts);
	}

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
		header("Location: /coursework/accounts/login.php");
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

?>
