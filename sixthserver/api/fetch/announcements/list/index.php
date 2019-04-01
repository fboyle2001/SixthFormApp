<?php
  // TODO: CURRENT DISOBEYS LIMIT

  define("AllowIncludes", 1);
  include("../../../api_util.php");

	$reply = new Reply();

  // Need to be at least a student
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

  // Cap the limit at 20
  if($limit > 20) {
    $limit = 20;
  }

  // Need to make sure that we send the user announcements that are for their
  // group only. Shouldn't be bypassable as use the username to validate the
  // secret.
  $username = get_username();

  $selectId = Database::get()->prepare("SELECT `ID`, `IsAdmin`, `Year` FROM `accounts` WHERE `Username` = :username");
  $selectId->execute(["username" => $username]);

  // Internal server error or they have done something with their auth
  if($selectId == false) {
  	$reply->setStatus(ReplyStatus::withData(500, "Unable to get ID"));
  	die($reply->toJson());
  }

  $record = $selectId->fetch(PDO::FETCH_ASSOC);

  // Used to determine groups
  $id = $record["ID"];
  $admin = $record["IsAdmin"];
  $year = $record["Year"];

  // Get their groups
  $selectGroups = Database::get()->prepare("SELECT `GroupID` from `grouplink` WHERE `AccountID` = :id");
  $selectGroups->execute(["id" => $id]);

  // List of their groups. -999 corresponds to All.
  $groupList = [-999];

  if($admin == 1) {
    // Admin
    // Display year group specific announcements to admins
    array_push($groupList, -996);
    array_push($groupList, -997);
    array_push($groupList, -998);
  } else {
    if($year == 12) {
      // Year 12
      array_push($groupList, -998);
    } else {
      // Year 13
      array_push($groupList, -997);
    }
  }

  // If they are part of any groups push them to the array
  if($selectGroups->rowCount() != 0) {
    while($record = $selectGroups->fetch(PDO::FETCH_ASSOC)) {
      array_push($groupList, $record["GroupID"]);
    }
  }

  // Convert the array to an SQL list
  $sqlList = "";

  foreach($groupList as $group) {
    if($sqlList != "") {
      $sqlList .= ", ";
    }

    $sqlList .= $group;
  }

	$selectLatest = null;

  // Execute the query based on whether or not the user sets a contains
  // parameter. Ignores limit at the moment.
  if($contains != null) {
    $selectLatest = Database::get()->prepare("SELECT `announcements`.`ID`, `announcements`.`Title`, `announcements`.`Content`, `announcements`.`DateAdded`, `announcements`.`GroupID`, `groups`.`GroupName` FROM `announcements` INNER JOIN `groups` ON `groups`.`ID` = `announcements`.`GroupID` WHERE `Title` LIKE '%' :contains '%' OR `Content` LIKE '%' :contains '%' AND `GroupID` IN ($sqlList) ORDER BY `announcements`.`ID` DESC LIMIT 15");
    $selectLatest->execute(["contains" => $contains]);
  } else {
    $selectLatest = Database::get()->prepare("SELECT `announcements`.`ID`, `announcements`.`Title`, `announcements`.`Content`, `announcements`.`DateAdded`, `announcements`.`GroupID`, `groups`.`GroupName` FROM `announcements` INNER JOIN `groups` ON `groups`.`ID` = `announcements`.`GroupID` WHERE `announcements`.`GroupID` IN ($sqlList) ORDER BY `announcements`.`ID` DESC LIMIT 15");
    $selectLatest->execute();
  }

  // Send the results back
	$reply->setStatus(ReplyStatus::withData(200, "Success"));
	$reply->setValue("found", $selectLatest->rowCount() != 0);
	$reply->setValue("records", $selectLatest->fetchAll(PDO::FETCH_ASSOC));

	echo $reply->toJson();
?>
