<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$username = get("username");
	$selectQuery = "SELECT * FROM `accounts`";

	if($username != null) {
		$selectQuery .= " WHERE `Username` LIKE '%$username%'";
	}

	$selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

	$reply->setValue("found", $selectQuery->wasDataReturned());
	$reply->setValue("records", $selectQuery->getRecords());
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
