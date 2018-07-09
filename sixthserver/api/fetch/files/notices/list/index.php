<?php
  include($_SERVER["DOCUMENT_ROOT"] . "/sixthserver/api/api_util.php");

	$reply = new Reply();

  if(!validate(AccessLevel::student)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }

	$validOnly = post("validOnly");
	$selectLatest = "SELECT * FROM `files` WHERE `Type` = " . StoreType::notices;

	if($validOnly != null) {
		$time = time();
		$selectLatest .= " AND `ExpiryDate` >= $time";
	}

  $selectLatest .= " ORDER BY `ID` DESC";
	$selectLatest = DatabaseHandler::getInstance()->executeQuery($selectLatest);

	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("found", $selectLatest->wasDataReturned());
	$reply->setValue("records", $selectLatest->getRecords());

	echo $reply->toJson();
?>
