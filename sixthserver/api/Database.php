<?php
  if(!defined("AllowIncludes")) {
    die("403");
  }

  include("details.php");

  // Database access class. Uses PDO as the underlying framework.
  class Database {

    private static $instance;
    private $database;

    private function __construct() {
      $connectionString = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
      $this->database = new PDO($connectionString, DB_USERNAME, DB_PASSWORD, [PDO::ATTR_PERSISTENT => true]);
    }

    public static function get() {
      if(Database::$instance == null) {
        Database::$instance = new Database();
      }

      return Database::$instance->database;
    }

  }

?>
