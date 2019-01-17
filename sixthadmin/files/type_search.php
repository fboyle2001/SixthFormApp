<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$type = get("type");
  $selectQuery = null;

  // Use type if given otherwise list them all
	if($type != null) {
		$selectQuery = Database::get()->prepare("SELECT * FROM `files` WHERE `Type` = :type ORDER BY `ID` DESC");
    $selectQuery->execute(["type" => $type]);
	} else {
    $selectQuery = Database::get()->query("SELECT * FROM `files` ORDER BY `ID` DESC");
  }

  // Send the result back to the requester
	$reply->setValue("found", $selectQuery == true);
	$reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
