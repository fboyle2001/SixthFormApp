<?php
  define("AllowIncludes", 1);
  include("../../api_util.php");

	$reply = new Reply();

  // Need to be at least a student
  if(!validate(AccessLevel::student)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }

	$password = post("password");

	if($password == null || $password == "") {
		$reply->setStatus(ReplyStatus::withData(400, "No new password set"));
		die($reply->toJson());
	}

  // Hash with a cost of 12. Can be increased as needed to increase security.
  // Bare in mind though that the current web server can be easily overwhelmed
  // and hashing intense. This would impact performance here as well as during
  // login which may put people off using the app especially if they are using
  // a slow connection already.
	$username = get_username();
	$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);

  // Update the password in the accounts database
	$changePassword = Database::get()->prepare("UPDATE `accounts` SET `Password` = :hashed, `Reset` = 0 WHERE `Username` = :username");
	$changePassword->execute(["hashed" => $hashedPassword, "username" => $username]);

  // Something went wrong
	if($changePassword == false) {
		$reply->setStatus(ReplyStatus::withData(500, "Unable to change password"));
		die($reply->toJson());
	}

  // Tell them it's changed
	$reply->setStatus(ReplyStatus::withData(200, "Successfully changed password"));
	echo $reply->toJson();
?>
