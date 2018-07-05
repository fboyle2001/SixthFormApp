<?php
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/shared.php");
	require($_SERVER["DOCUMENT_ROOT"] . "/sixthadmin/resources/php/Reply.php");

	rejectGuest();

	$reply = new Reply();
	$name = get("name");
	$selectQuery = "SELECT * FROM `links`";

	if($name != null) {
		$selectQuery .= " WHERE `Name` LIKE '%$name%'";
	}

	$selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

	$reply->setValue("found", $selectQuery->wasDataReturned());
	$reply->setValue("records", $selectQuery->getRecords());
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
