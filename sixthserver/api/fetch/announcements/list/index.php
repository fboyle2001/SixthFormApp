<?php
  include("../../../api_util.php");

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

  $selectId = Database::get()->prepare("SELECT `ID`, `IsAdmin`, `Year` FROM `accounts` WHERE `Username` = :username");
  $selectId->execute(["username" => $username]);

  if($selectId == false) {
  	$reply->setStatus(ReplyStatus::withData(500, "Unable to get ID"));
  	die($reply->toJson());
  }

  $record = $selectId->fetch(PDO::FETCH_ASSOC);
  $id = $record["ID"];
  $admin = $record["IsAdmin"];
  $year = $record["Year"];

  $selectGroups = Database::get()->prepare("SELECT `GroupID` from `grouplink` WHERE `AccountID` = :id");
  $selectGroups->execute(["id" => $id]);

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

  if($selectGroups->rowCount() != 0) {
    while($record = $selectGroups->fetch(PDO::FETCH_ASSOC)) {
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

  $sqlList = "$sqlList";

	$selectLatest = null;

  if($contains != null) {
    $selectLatest = Database::get()->prepare("SELECT * FROM `announcements` INNER JOIN `groups` ON `groups`.`ID` = `announcements`.`GroupID` WHERE `Title` LIKE '%' :contains '%' OR `Content` LIKE '%' :contains '%' AND `GroupID` IN (:idList) ORDER BY `announcements`.`ID` DESC LIMIT 10");
    $selectLatest->execute(["contains" => $contains, "idList" => $sqlList]);
  } else {
    $selectLatest = Database::get()->prepare("SELECT * FROM `announcements` INNER JOIN `groups` ON `groups`.`ID` = `announcements`.`GroupID` WHERE `GroupID` IN (:idList) ORDER BY `announcements`.`ID` DESC LIMIT 10");
    $selectLatest->execute(["idList" => $sqlList]);
  }

	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("found", $selectLatest->rowCount() != 0);
	$reply->setValue("records", $selectLatest->fetchAll(PDO::FETCH_ASSOC));

	echo $reply->toJson();
?>
