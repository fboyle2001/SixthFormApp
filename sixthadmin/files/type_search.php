<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$type = get("type");
  $selectQuery = null;

	if($type != null) {
		$selectQuery = Database::get()->prepare("SELECT * FROM `files` WHERE `Type` = :type");
    $selectQuery->execute(["type" => $type]);
	} else {
    $selectQuery = Database::get()->query("SELECT * FROM `files`");
  }

	$reply->setValue("found", $selectQuery == true);
	$reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
