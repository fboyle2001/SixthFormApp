<?php
  include($_SERVER["DOCUMENT_ROOT"] . "/sixthserver/api/api_util.php");

	$reply = new Reply();
	
  if(!validate(AccessLevel::admin)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }
	
	$username = post("username");
	$year = post("year");
	$admin = post("admin");
	
	if($username == null || $year == null || $admin == null) {
		$status = ReplyStatus::withData(400, "No username, year or admin status.");
		$reply->setStatus($status);
		die($reply->toJson());
	}
	
	$accountExists = "SELECT * FROM `accounts` WHERE `Username` = '$username'";
	$accountExists = DatabaseHandler::getInstance()->executeQuery($accountExists);
	
	if($accountExists->wasDataReturned()) {
    $status = ReplyStatus::withData(400, "Username already in use");
    $reply->setStatus($status);
    die($reply->toJson());
	}
	
	$defaultPassword = '$2y$10$AgIklPcGSiV7dWDQjht7dOFw71wanND9SQfmUifXjIBYveWWkqBRm';
	
	$createUser = "INSERT INTO `accounts` (`Username`, `Password`, `Year`, `IsAdmin`) VALUES ('$username', '$defaultPassword', '$year', '$admin')";
	$createUser = DatabaseHandler::getInstance()->executeQuery($createUser);
	
	if($createUser->wasSuccessful() == false) {
    $status = ReplyStatus::withData(500, "Unable to create new user");
    $reply->setStatus($status);
    die($reply->toJson());
	}
	
	$status = ReplyStatus::withData(200, "Successfully created new user");
	$reply->setStatus($status);
	
	echo $reply->toJson();

?>
