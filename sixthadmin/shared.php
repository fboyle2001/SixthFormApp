<?php

  /**
  * This is a shared class that contains functions and variables which are useful on many pages or require
  * other shared functions. It also includes the database and reduces the amount of code needed to include
  * all necessary files.
  *
  * Also ensures the session is started on all pages.
  */

  // Includes the database connection
	require(__DIR__ . "/resources/php/database/Database.php");
	require(__DIR__ . "/resources/php/Reply.php");

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
			$adminQuery = Database::get()->prepare("SELECT `IsAdmin` FROM `users` WHERE `Username` = :username");
			$adminQuery->execute(["username" => $username]);

			if($adminQuery == false) {
				return false;
			}

      // If the value of IsAdmin is 1 then set the session variable admin to true, otherwise set it to false
			$_SESSION["admin"] = $adminResult->fetch()["IsAdmin"] == 1 ? true : false;
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

	function deleteOld() {
		$time = time();

	  $selectQuery = Database::get()->prepare("SELECT * FROM `files` WHERE `ExpiryDate` < :expiry");
	  $selectQuery->execute(["expiry" => $time]);

	  if($selectQuery->rowCount() > 0) {
			while($record = $selectQuery->fetch(PDO::FETCH_ASSOC)) {
				$id = $record["ID"];
				$resourceLink = "../../../files/" . $record["Link"];
				echo 'Path:';
				echo realpath($resourceLink);
				$result = unlink($resourceLink);

				$deleteQuery = Database::get()->prepare("DELETE FROM `files` WHERE `ID` = :id");
				$deleteQuery->execute(["id" => $id]);
			}
	  }

		$selectQuery = Database::get()->prepare("SELECT * FROM `links` WHERE `ExpiryDate` < :expiry");
		$selectQuery->execute(["expiry" => $time]);

		if($selectQuery->rowCount() > 0) {
			while($record = $selectQuery->fetch(PDO::FETCH_ASSOC)) {
				$id = $record["ID"];
				$deleteQuery = Database::get()->prepare("DELETE FROM `links` WHERE `ID` = :id");
				$deleteQuery->execute(["id" => $id]);
			}
		}

	}

	function random_str($length) {
	  $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; //len 62
    $result = "";

    for ($i = 0; $i < $length; ++$i) {
      $result .= $keyspace[rand(0, 61)];
    }

    return $result;
  }
?>
