<?php
  include("../../api_util.php");

	$reply = new Reply();

  if(!validate(AccessLevel::admin)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }

	$username = post("username");
	$id = post("id");

	if($username == null && $id == null) {
		$reply->setStatus(ReplyStatus::withData(400, "Must set either username or ID"));
		die($reply->toJson());
	}

	$existsQuery = "SELECT * FROM `accounts` WHERE ";
	$deleteQuery = "DELETE FROM `accounts` WHERE ";

	if($id != null) {
		$deleteQuery .= "`ID` = '$id'";
		$existsQuery .= "`ID` = '$id'";
	} else {
		$deleteQuery .= "`Username` = '$username'";
		$existsQuery .= "`Username` = '$username'";
	}

	$existsResult = DatabaseHandler::getInstance()->executeQuery($existsQuery);

	if($existsResult->wasDataReturned() == false) {
		$reply->setStatus(ReplyStatus::withData(400, "Invalid parameters"));
		die($reply->toJson());
	}

	$deleteResult = DatabaseHandler::getInstance()->executeQuery($deleteQuery);

	if($deleteResult->wasSuccessful() == false) {
		$reply->setStatus(ReplyStatus::withData(500, "Unable to delete from database"));
		die($reply->toJson());
	}

	$reply->setStatus(ReplyStatus::withData(200, "Successfully deleted user"));
	echo $reply->toJson();
?>
