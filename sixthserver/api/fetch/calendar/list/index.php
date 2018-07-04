<?php
  include($_SERVER["DOCUMENT_ROOT"] . "/sixthserver/api/api_util.php");
	
	$reply = new Reply();
		
  if(!validate(AccessLevel::student)) {
    $status = ReplyStatus::withData(403, "Unauthorised access is restricted");
    $reply->setStatus($status);
    die($reply->toJson());
  }
	
	$startDate = post("startDate"); //DD-MM-YYYY
	$endDate = post("endDate"); //DD-MM-YYYY
	
	$selectData = "SELECT * FROM `calendar`";
	
	$startTime = null;
	$endTime = null;
	
	if($startDate != null) {
		$parts = explode("-", $startDate);
		$startTime = mktime(0, 0, 0, $parts[1], $parts[0], $parts[2]);
		$selectData 
	}
	
	if($endDate != null) {
		$parts = explode("-", $endDate);
		$endTime = mktime(0, 0, 0, $parts[1], $parts[0], $parts[2]);
	}
	
	if($startTime != null && $endTime != null) {
		$selectData .= " WHERE `StartTime` BETWEEN $startTime AND $endTime";
	} else if ($startTime != null) {
		$selectData .= " WHERE `StartTime` >= $startTime";
	} else if ($endTime != null) {
		$selectData .= " WHERE `StartTime` <= $endTime";
	}
	
	$selectData = DatabaseHandler::getInstance()->executeQuery($selectData);
	
	$reply->setValue("found", $selectData->wasDataReturned());
	$reply->setValue("records", $selectData->getRecords());
	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	
	echo $reply->toJson();
?>
