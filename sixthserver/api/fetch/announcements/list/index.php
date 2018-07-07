<?php
  include($_SERVER["DOCUMENT_ROOT"] . "/sixthserver/api/api_util.php");

	$reply = new Reply();

  if(!validate(AccessLevel::student)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }

	$limit = post("limit");
  $contains = post("contains");

	if($limit == null) {
		$reply->setStatus(ReplyStatus::withData(400, "Must set a limit"));
		die($reply->toJson());
	}


	$selectLatest = "SELECT * FROM `announcements` ";

  if($contains != null) {
    $selectLatest .= "WHERE `Title` LIKE '%$contains%' OR `Content` LIKE '%$contains%'";
  }

  $selectLatest .= "ORDER BY `ID` DESC LIMIT $limit";
	$selectLatest = DatabaseHandler::getInstance()->executeQuery($selectLatest);

	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("found", $selectLatest->wasDataReturned());
	$reply->setValue("records", $selectLatest->getRecords());

	echo $reply->toJson();
?>
