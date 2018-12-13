<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$content = get("content");
	$selectQuery = "SELECT `announcements`.*, `groups`.`GroupName` FROM `announcements` INNER JOIN `groups` ON `announcements`.`GroupID` = `groups`.`ID`";

	if($content != null) {
		$selectQuery .= " WHERE `Content` LIKE '%$content%'";
	}

	$selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

	$reply->setValue("found", $selectQuery->wasDataReturned());
	$reply->setValue("records", $selectQuery->getRecords());
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
