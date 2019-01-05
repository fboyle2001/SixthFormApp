<?php
  require("../../shared.php");

	rejectGuest();

	$reply = new Reply();
	$id = get("id");

  // Can't do anything without the ID
  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  // Select all users who are in the group
	$selectQuery = Database::get()->prepare("SELECT `accounts`.`Username` FROM `grouplink` INNER JOIN `accounts` ON `grouplink`.`AccountID` = `accounts`.`ID` WHERE `grouplink`.`GroupID` = :id");
  $selectQuery->execute(["id" => $id]);

  // No members found
  if($selectQuery->rowCount() == 0) {
    $reply->setStatus(ReplyStatus::withData(400, "No members found"));
    die($reply->toJson());
  }

  $usernames = [];

  // Loop the usernames and push them to the list
  while($record = $selectQuery->fetch(PDO::FETCH_ASSOC)) {
    array_push($usernames, $record["Username"]);
  }

  $reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("records", $usernames);
  echo $reply->toJson();
?>
