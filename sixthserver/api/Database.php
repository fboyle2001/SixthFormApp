<?php

  class Database {

    private static $instance;
    private $database;

    private function __construct() {
      $this->database = new PDO("mysql:host=localhost;dbname=sixthapp", "root", "root", [PDO::ATTR_PERSISTENT => true]);
    }

    public static function get() {
      if(Database::$instance == null) {
        Database::$instance = new Database();
      }

      return Database::$instance->database;
    }

  }

?>
