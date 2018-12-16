<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$title = get("title");
	$selectQuery = "SELECT `announcements`.*, `groups`.`GroupName` FROM `announcements` INNER JOIN `groups` ON `announcements`.`GroupID` = `groups`.`ID`";

	if($title != null) {
    $selectQuery = Database::get()->prepare("SELECT `announcements`.*, `groups`.`GroupName` FROM `announcements` INNER JOIN `groups` ON `announcements`.`GroupID` = `groups`.`ID` WHERE `Title` LIKE '%' :title '%' ORDER BY `ID` DESC");
    $selectQuery->execute(["title" => $title]);
	} else {
    $selectQuery = Database::get()->query("SELECT `announcements`.*, `groups`.`GroupName` FROM `announcements` INNER JOIN `groups` ON `announcements`.`GroupID` = `groups`.`ID` ORDER BY `ID` DESC");
  }

	$reply->setValue("found", $selectQuery == true);
	$reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
