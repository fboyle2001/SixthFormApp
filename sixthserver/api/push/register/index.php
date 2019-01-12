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

	$pushId = post("pushId");

	if($pushId == null) {
		$reply->setStatus(ReplyStatus::withData(400, "No ID submitted"));
		die($reply->toJson());
	}

  $username = get_username();
  $selectId = Database::get()->prepare("SELECT `ID` FROM `accounts` WHERE `Username` = :username");
  $selectId->execute(["username" => $username]);

  if($selectId->rowCount() != 1) {
    // Error
  	$reply->setStatus(ReplyStatus::withData(500, "Unable to select user ID"));
		die($reply->toJson());
  }

  // Check if push ID is already in database

  $selectPush = Database::get()->prepare("SELECT `UserID` FROM `push` WHERE `PushID` = :pushId");
  $selectPush->execute(["pushId" => $pushId]);

  if($selectPush->rowCount() != 0) {
  	$reply->setStatus(ReplyStatus::withData(400, "Already registered"));
		die($reply->toJson());
  }

  $id = $selectId->fetch(PDO::FETCH_ASSOC)["ID"];

  $submitId = Database::get()->prepare("INSERT INTO `push` (`PushID`, `UserID`) VALUES (:pushId, :userId)");
  $success = $submitId->execute(["pushId" => $pushId, "userId" => $id]);

  if($success !== true) {
    // Error inserting
  	$reply->setStatus(ReplyStatus::withData(500, "Unable insert push ID"));
  }

	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	echo $reply->toJson();
?>
