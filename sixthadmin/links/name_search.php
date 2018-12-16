<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$name = get("name");
	$selectQuery = "SELECT * FROM `links`";

	if($name != null) {
    $selectQuery = Database::get()->prepare("SELECT * FROM `links` WHERE `Name` LIKE '%' :name '%'");
		$selectQuery->execute(["name" => $name]);
	} else {
    $selectQuery = Database::get()->execute("SELECT * FROM `links`");
  }

	$reply->setValue("found", $selectQuery == true);
	$reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
