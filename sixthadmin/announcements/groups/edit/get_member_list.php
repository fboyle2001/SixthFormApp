<?php
  require("../../../shared.php");

	rejectGuest();

	$reply = new Reply();
	$id = post("id");

  // Can't do anything without the ID
  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  // Select all users who are in the group
	$selectQuery = Database::get()->prepare("SELECT `accounts`.`ID`, `accounts`.`Username` FROM `grouplink` INNER JOIN `accounts` ON `grouplink`.`AccountID` = `accounts`.`ID` WHERE `grouplink`.`GroupID` = :id");
  $selectQuery->execute(["id" => $id]);

  // No members found
  if($selectQuery->rowCount() == 0) {
    $reply->setStatus(ReplyStatus::withData(400, "No members found"));
    die($reply->toJson());
  }

  // Return the records
  $reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
  echo $reply->toJson();
?>
