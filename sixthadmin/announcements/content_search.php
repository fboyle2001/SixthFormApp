<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$content = get("content");
	$selectQuery = null;

	if($content != null) {
    $selectQuery = Database::get()->prepare("SELECT `announcements`.*, `groups`.`GroupName` FROM `announcements` INNER JOIN `groups` ON `announcements`.`GroupID` = `groups`.`ID` WHERE `Content` LIKE '%' :content '%' ORDER BY `ID` DESC");
		$selectQuery->execute(["content" => $content]);
	} else {
    $selectQuery = Database::get()->query("SELECT `announcements`.*, `groups`.`GroupName` FROM `announcements` INNER JOIN `groups` ON `announcements`.`GroupID` = `groups`.`ID` ORDER BY `ID` DESC");
  }

	$reply->setValue("found", $selectQuery == true);
	$reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
