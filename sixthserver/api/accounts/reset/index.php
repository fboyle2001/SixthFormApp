<?php
  include("../../api_util.php");

	$reply = new Reply();

  if(!validate(AccessLevel::admin)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }

	$username = post("username");

	if($username == null) {
		$reply->setStatus(ReplyStatus::withData(400, "No username set"));
		die($reply->toJson());
	}

	$defaultPassword = password_hash("Passw0rd", PASSWORD_BCRYPT, ["cost" => 12]);

	$existsQuery = "SELECT * FROM `accounts` WHERE `Username` = '$username'";
	$existsQuery = DatabaseHandler::getInstance()->executeQuery($existsQuery);

	if($existsQuery->wasDataReturned() == false) {
		$reply->setStatus(ReplyStatus::withData(400, "Invalid username"));
		die($reply->toJson());
	}

	$updateQuery = "UPDATE `accounts` SET `Password` = '$defaultPassword' WHERE `Username` = '$username'";
	$updateQuery = DatabaseHandler::getInstance()->executeQuery($updateQuery);

	if($updateQuery->wasSuccessful() == false) {
		$reply->setStatus(ReplyStatus::withData(500, "Unable to reset password"));
		die($reply->toJson());
	}

	$reply->setStatus(ReplyStatus::withData(200, "Successfully reset password"));
	echo $reply->toJson();

?>
