<?php
  require("../../shared.php");

	rejectGuest();

	$reply = new Reply();
	$id = get("id");

	$selectQuery = Database::get()->prepare("SELECT `accounts`.`Username` FROM `grouplink` INNER JOIN `accounts` ON `grouplink`.`AccountID` = `accounts`.`ID` WHERE `grouplink`.`GroupID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "No members found"));
    die($reply->toJson());
  }

  $usernames = [];

  while($record = $selectQuery->fetch(PDO::FETCH_ASSOC)) {
    array_push($usernames, $record["Username"]);
  }

  $reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("records", $usernames);
  echo $reply->toJson();
?>
