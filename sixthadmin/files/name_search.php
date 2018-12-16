<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$name = get("name");
	$selectQuery = null;

	if($name != null) {
    $selectQuery = Database::get()->prepare("SELECT * FROM `files` WHERE `Name` LIKE '%' :name '%'");
    $selectQuery->execute(["name" => $name]);
	} else {
    $selectQuery = Database::get()->execute("SELECT * FROM `files`");
  }

	$reply->setValue("found", $selectQuery == true);
	$reply->setValue("records", $selectQuery->gfetchAll(PDO::FETCH_ASSOC));
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
