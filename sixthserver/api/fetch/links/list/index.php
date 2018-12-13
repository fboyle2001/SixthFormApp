<?php
  include("../../../api_util.php");

	$reply = new Reply();

  if(!validate(AccessLevel::student)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }

	$validOnly = post("validOnly");
	$selectLatest = "SELECT * FROM `links`";

	if($validOnly != null) {
		$time = time();
		$selectLatest .= " WHERE `ExpiryDate` >= $time";
	}

	$selectLatest = DatabaseHandler::getInstance()->executeQuery($selectLatest);

	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("found", $selectLatest->wasDataReturned());
	$reply->setValue("records", $selectLatest->getRecords());

	echo $reply->toJson();

?>
