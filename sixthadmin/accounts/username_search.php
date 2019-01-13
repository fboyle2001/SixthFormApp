<?php
  require("../shared.php");

	rejectGuest();

	$reply = new Reply();
	$username = get("username");

  // If no username then display all users
	if($username != null) {
    $selectQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `Username` LIKE '%' :username '%'");
    $selectQuery->execute(["username" => $username]);
	} else {
    $selectQuery = Database::get()->prepare("SELECT * FROM `accounts`");
    $selectQuery->execute();
  }

  // Return the result
	$reply->setValue("found", $selectQuery == true);
	$reply->setValue("records", $selectQuery->fetchAll(PDO::FETCH_ASSOC));
	$reply->setStatus(ReplyStatus::withData(200, "Success"));

	echo $reply->toJson();
?>
