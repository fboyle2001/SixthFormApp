<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$name = get("name");
	$selectQuery = null;

  // Use the name given otherwise display them all
	if($name != null) {
    $selectQuery = Database::get()->prepare("SELECT * FROM `files` WHERE `Name` LIKE '%' :name '%'");
    $selectQuery->execute(["name" => $name]);
	} else {
    $selectQuery = Database::get()->query("SELECT * FROM `files`");
  }

	$reply->setValue("found", $selectQuery == true);
	$reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
