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
	require(__DIR__ . "/resources/php/keys.php");

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

	// Checks if an argument exists
  function has_arg($method , $name) {
		if(strtoupper($method) == "GET") {
		  return isset($_GET[$name]);
		} else if(strtoupper($method) == "POST") {
		  return isset($_POST[$name]);
		}

		return false;
  }

  // Gets an argument
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

  // Gets an argument from the GET method
  function get($name) {
		return get_arg("GET", $name);
  }

  // Gets an argument from the POST method
  function post($name) {
		return get_arg("POST", $name);
  }

	// Deletes old files from the database and system
	function deleteOld() {
		$time = time();

	  $selectQuery = Database::get()->prepare("SELECT * FROM `files` WHERE `ExpiryDate` < :expiry");
	  $selectQuery->execute(["expiry" => $time]);

	  if($selectQuery->rowCount() > 0) {
			while($record = $selectQuery->fetch(PDO::FETCH_ASSOC)) {
				$id = $record["ID"];
				$resourceLink = "../../../files/" . $record["Link"];
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

	// Generates a random string using alphanumeric characters
	function random_str($length) {
	  $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; //len 62
    $result = "";

    for ($i = 0; $i < $length; ++$i) {
      $result .= $keyspace[rand(0, 61)];
    }

    return $result;
  }

	// Sends
	function sendNotification($heading, $description) {
		$contents = ["en" => $description];
		$headings = ["en" => $heading];

		$payload = [
			"app_id" => "a9171e05-26dd-49d2-9a57-c4ab6c423dcc",
			"included_segments" => ["All"],
			"contents" => $contents,
			"headings" => $headings,
			"ios_badgeType" => "Increase",
			"ios_badgeCount" => 1
		];

		$url = "https://onesignal.com/api/v1/notifications/";

		$headers = [
			"Content-Type: application/json; charset=utf-8",
			"Authorization: Basic " . ONESIGNAL_API_KEY
		];

		$payload = json_encode($payload);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
	}
?>
