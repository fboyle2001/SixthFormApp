<?php
  if(!defined("AllowIncludes")) {
    die("403");
  }

  require("Reply.php");
	require("Database.php");

  // Defines constants for permissions
  class AccessLevel {

  	const student = 0;
  	const admin = 1;

  }

  // Defines constants for the type of file uploaded
  class StoreType {

    const other = 0;
    const newsletters = 1;
    const notices = 2;

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

  // Called when rejecting invalid arguments
  // Not heavily used but should have been
  function bad_args($reason) {
    $reply = Reply::withStatus(ReplyStatus::withData(400, "Invalid arguments ($reason)"));
    return $reply->toJson();
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

  // Checks if the authorization header is set
  function has_auth() {
    return isset($_SERVER["HTTP_AUTHORIZATION"]);
  }

  // Gets the authorization header
  function get_auth() {
    if(!has_auth()) {
      return null;
    }

    return $_SERVER["HTTP_AUTHORIZATION"];
  }

  // Gets the user's auth level
  // Secret contains the auth level but can't be abused as it is still
  // validated by the database
  function get_level() {
    if(!has_auth()) {
      return null;
    }

    $auth = get_secret();
    $sections = explode(".", $auth);

    return intval($sections[1]);
  }

  // Decodes the auth key
  function get_json() {
    $auth = get_auth();

    if($auth == null) {
      return null;
    }

    return json_decode(base64_decode($auth));
  }

  // Gets the username from the auth key
  function get_username() {
    $json = get_json();

    if($json == null) {
      return "";
    }

    return $json->username;
  }

  // Gets the secret from the auth key
  function get_secret() {
    $json = get_json();

    if($json == null) {
      return "";
    }

    return $json->secret;
  }

  // Validates the auth key to prevent user's changing their auth key to access
  // resources that they shouldn't be able to access.
  function validate_auth() {
    $auth = get_auth();

    if($auth == null) {
      return false;
    }

    $decoded = json_decode(base64_decode($auth));

    if(!isset($decoded->username) || !isset($decoded->secret)) {
      return false;
    }

    $username = $decoded->username;
    $secret = $decoded->secret;

    $selectApi = Database::get()->prepare("SELECT * FROM `apikeys` WHERE `Username` = :username");
    $selectApi->execute(["username" => $username]);

    if($selectApi->rowCount() == 0) {
      return false;
    }

    $result = $selectApi->fetch(PDO::FETCH_ASSOC);

    if(time() > intval($result["ExpireTime"])) {
      return false;
    }

    if($result["Secret"] != $secret) {
      return false;
    }

    return true;
  }

  // Not always possible to send the header (e.g. open links)
  // validates it from an argument in the URL instead
  function non_header_auth_validate($auth) {
    if($auth == null) {
      return false;
    }

    $decoded = json_decode(base64_decode($auth));

    if(!isset($decoded->username) || !isset($decoded->secret)) {
      return false;
    }

    $username = $decoded->username;
    $secret = $decoded->secret;

    $selectApi = Database::get()->prepare("SELECT * FROM `apikeys` WHERE `Username` = :username");
    $selectApi->execute(["username" => $username]);

    if($selectApi->rowCount() == 0) {
      return false;
    }

    $result = $selectApi->fetch(PDO::FETCH_ASSOC);

    if(time() > intval($result["ExpireTime"])) {
      return false;
    }

    if($result["Secret"] != $secret) {
      return false;
    }

    return true;
  }

  // Validates the user has access to a resource
  function validate_level($level) {
    $auth = get_auth();

    if($auth == null) {
      return false;
    }

    $actual = get_level();

    return $actual >= $level;
  }

  // Performs the entire validation process
  function validate($level) {
    if(!validate_auth()) {
      return false;
    }

    return validate_level($level);
  }

?>
