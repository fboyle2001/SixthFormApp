<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$title = get("title");

  // Search by title or display them all if none given
	if($title != null) {
    $selectQuery = Database::get()->prepare("SELECT `announcements`.*, `groups`.`GroupName` FROM `announcements` INNER JOIN `groups` ON `announcements`.`GroupID` = `groups`.`ID` WHERE `Title` LIKE '%' :title '%' ORDER BY `ID` DESC");
    $selectQuery->execute(["title" => $title]);
	} else {
    $selectQuery = Database::get()->query("SELECT `announcements`.*, `groups`.`GroupName` FROM `announcements` INNER JOIN `groups` ON `announcements`.`GroupID` = `groups`.`ID` ORDER BY `ID` DESC");
  }

  // Provide all the data
	$reply->setValue("found", $selectQuery == true);
	$reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
