<?php
  include("../../api_util.php");

	$reply = new Reply();

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

	$username = get_username();
	$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);

	$changePassword = "UPDATE `accounts` SET `Password` = '$hashedPassword', `Reset` = 0 WHERE `Username` = '$username'";
	$changePassword = DatabaseHandler::getInstance()->executeQuery($changePassword);

	if($changePassword->wasSuccessful() == false) {
		$reply->setStatus(ReplyStatus::withData(500, "Unable to change password"));
		die($reply->toJson());
	}

	$reply->setStatus(ReplyStatus::withData(200, "Successfully changed password"));
	echo $reply->toJson();

?>
