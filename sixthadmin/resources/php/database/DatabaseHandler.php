<?php

  // Includes the Result class to store the processed results
	require("Result.php");

	class DatabaseHandler {

		private static $instance = false;

		/**
		* This either gets the current the instance or creates a new instance.
		* This prevents the need to reconnect to the database if multiple queries are made per page.
		**/
		public static function getInstance() {
			if(DatabaseHandler::$instance === false) {
				DatabaseHandler::$instance = new DatabaseHandler();
			}

			return DatabaseHandler::$instance;
		}

		private $database = false;
		private $error = false;

		/**
		* Creates a new instance and connects to the database.
		* This is a private constructor because it is called by the 'getInstance()' function if no instance exists.
		**/
		private function __construct() {
			$database = new mysqli("localhost", "root", "root", "sixthapp"); #School database

			// Checks if there was an error in connecting to the database because if there was then connect_errno will have a number and be therefore be true
			if($database->connect_errno) {
				$this->error = true;
				$this->handleError();
			}

			$this->database = $database;
		}

		/**
		* Validates a query by checking:
		* - It is not empty
		* - It does not contain a semi-colon (to prevent multiple queries)
		* - Has a WHERE clause if it is an UPDATE or DELETE query to prevent it affecting all data
		*
		* It then removes all of the HTML tags (such as <h1> and <script>) and returns the validated query.
		**/
		public function validate($query, $bypass = false) {
			if(empty($query)) {
				return false;
			}

			if(strpos($query, ';') !== false) {
				return false;
			}

			$checkQuery = strtoupper($query);
			$command = explode(" ", strtoupper($checkQuery))[0];

			// Uses regex to check if the command is an acceptable command.
			if(preg_match("/(SELECT|INSERT|DELETE|UPDATE)/i", $checkQuery) == false) {
				return false;
			}

			/**
			* Uses regex to find DELETE or UPDATE in the query.
			* (DELETE|UPDATE) - Creates a capture group that finds a DELETE or an UPDATE.
			* The /i makes the regex ignore the case.
			**/
			if(preg_match("/(DELETE|UPDATE)/i", $checkQuery)) {
				if(strpos($checkQuery, "WHERE") === false) {
					return false;
				}
			}

			// Allows the strip tags function to be bypassed in certain circumstances
			if($bypass == false) {
				$valid = strip_tags($query);
			} else {
				$valid = $query;
			}

			return $valid;
		}

		/**
		* This function executes a query by validating the query and then querying the database.
		* It then handles the result of the query which is then returned.
		**/
		public function executeQuery($query, $bypass = false) {
			if($this->error === true) {
				return new Result(false, null, $query);
			}

			$validatedQuery = $this->validate($query, $bypass);

			if($validatedQuery === false) {
				return new Result(false, null, $validatedQuery);
			}

      // Gets the command which should be 'SELECT', 'INSERT', 'DELETE' or 'UPDATE'
			$command = explode(" ", strtoupper($validatedQuery))[0];

      //Queries the actual database and stores the raw result
			$queryResult = $this->database->query($validatedQuery);
			$returnResult = array();

			if($command == "SELECT") {
				if($queryResult === false) {
					return new Result(false, null, $validatedQuery);
				}

				//Checks if any data was returned
				if($queryResult->num_rows == 0) {
					return new Result(false, null, $validatedQuery);
				}

				//Loops over the raw result and takes each record and pushes it into an array
				while($row = $queryResult->fetch_assoc()) {
					array_push($returnResult, $row);
				}

				//Stores the processed result array in a Result object to be returned.
				return new Result(true, $returnResult, $validatedQuery);
			} else if ($command == "INSERT" or $command == "DELETE" or $command == "UPDATE") {
				//Checks if there was an error in querying the database
				if($this->database->errno !== 0) {
					return new Result(false, null, $validatedQuery);
				}

				//Checks if the query actually affected any of the data in the database
				if($this->database->affected_rows == 0) {
					return new Result(false, null, $validatedQuery);
				}

				return new Result(true, null, $validatedQuery);
			}

			return new Result(false, null, $validatedQuery);
		}

		/**
		* Redirects the user to the 500.html if the database connection failed.
		**/
		public function handleError() {
			if($this->error === false) {
				return;
			}

			#Improve error handling
			die();
		}

	}

/* 	$query = "DROP TABLE `usernames`";
	$result = DatabaseHandler::getInstance()->executeQuery($query);

	echo "Query: $query <br>";

	echo "Successful: " . ($result->wasSuccessful() ? "True" : "False") . "<br>";
	echo "Was Data Returned: " . ($result->wasDataReturned() ? "True" : "False") . "<br>";  */

?>
