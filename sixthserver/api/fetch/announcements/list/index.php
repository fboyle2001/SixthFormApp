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

  if($limit > 20) {
    $limit = 20;
  }

  $username = get_username();

  $selectId = "SELECT `ID`, `IsAdmin`, `Year` FROM `accounts` WHERE `Username` = '$username'";
  $selectId = DatabaseHandler::getInstance()->executeQuery($selectId);

  if($selectId->wasDataReturned() == false) {
  	$reply->setStatus(ReplyStatus::withData(500, "Unable to get ID"));
  	die($reply->toJson());
  }

  $record = $selectId->getRecords()[0];
  $id = $record["ID"];
  $admin = $record["IsAdmin"];
  $year = $record["Year"];

  $selectGroups = "SELECT `GroupID` from `grouplink` WHERE `AccountID` = $id";
  $selectGroups = DatabaseHandler::getInstance()->executeQuery($selectGroups);

  $groupList = [-999];

  if($admin == 1) {
    array_push($groupList, -996);
  } else {
    if($year == 12) {
      array_push($groupList, -998);
    } else {
      array_push($groupList, -997);
    }
  }

  if($selectGroups->wasDataReturned() == true) {
    foreach($selectGroups->getRecords() as $record) {
      array_push($groupList, $record["GroupID"]);
    }
  }

  $sqlList = "";

  foreach($groupList as $group) {
    if($sqlList != "") {
      $sqlList .= ", ";
    }

    $sqlList .= $group;
  }

  $sqlList = "($sqlList)";

	$selectLatest = "SELECT * FROM `announcements` INNER JOIN `groups` ON `groups`.`ID` = `announcements`.`GroupID` ";
  $where = "";

  if($contains != null) {
    $where = "WHERE `Title` LIKE '%$contains%' OR `Content` LIKE '%$contains%' ";
  }

  if($where != "") {
    $where .= "AND `GroupID` IN $sqlList";
  } else {
    $where = "WHERE `GroupID` IN $sqlList";
  }


  $selectLatest .= "$where ORDER BY `announcements`.`ID` DESC LIMIT $limit";
	$selectLatest = DatabaseHandler::getInstance()->executeQuery($selectLatest);

	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("found", $selectLatest->wasDataReturned());
	$reply->setValue("records", $selectLatest->getRecords());

	echo $reply->toJson();
?>
