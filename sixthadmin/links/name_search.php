<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$name = get("name");
	$selectQuery = "SELECT * FROM `links`";

  // If they have set a name parameter use it. Otherwise list them all.
	if($name != null) {
    $selectQuery = Database::get()->prepare("SELECT * FROM `links` WHERE `Name` LIKE '%' :name '%' ORDER BY `ID` DESC");
		$selectQuery->execute(["name" => $name]);
	} else {
    $selectQuery = Database::get()->query("SELECT * FROM `links` ORDER BY `ID` DESC");
  }

	$reply->setValue("found", $selectQuery == true);
	$reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
