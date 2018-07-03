<?php

	class Result {

		private $success = false;
		private $records = null;
		private $query = "";

		/**
		* Creates a new result instance with the set data in the variables.
		**/
		public function __construct($success, $records, $query) {
			$this->success = $success;
			$this->records = $records;
			$this->query = $query;
		}

		/**
		* Simple getter functions which return the data set by the constructor.
		**/
		
		public function getQuery() {
			return $this->query;
		}

		public function wasSuccessful() {
			return $this->success;
		}

		public function wasDataReturned() {
			return $this->records !== null;
		}
		
		public function getRecords() {
			if($this->wasDataReturned() === false) {
				return false;
			}

			return $this->records;
		}

	}

?>
