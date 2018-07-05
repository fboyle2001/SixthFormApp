<?php
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/Reply.php");

	rejectGuest();

	$reply = new Reply();
	$title = get("title");
	$selectQuery = "SELECT * FROM `announcements`";

	if($title != null) {
		$selectQuery .= " WHERE `Title` LIKE '%$title%'";
	}

	$selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

	$reply->setValue("found", $selectQuery->wasDataReturned());
	$reply->setValue("records", $selectQuery->getRecords());
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
