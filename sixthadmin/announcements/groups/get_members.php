<?php
  require("../../shared.php");

	rejectGuest();

	$reply = new Reply();
	$id = get("id");

	$selectQuery = "SELECT `accounts`.`Username` FROM `grouplink` INNER JOIN `accounts` ON `grouplink`.`AccountID` = `accounts`.`ID` WHERE `grouplink`.`GroupID` = '$id'";
	$selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

  if($selectQuery->wasDataReturned() == false) {
    $reply->setStatus(ReplyStatus::withData(400, "No members found"));
    die($reply->toJson());
  }

  $usernames = [];

  foreach($selectQuery->getRecords() as $index => $record) {
    array_push($usernames, $record["Username"]);
  }

  $reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("records", $usernames);
  echo $reply->toJson();
?>
