<?php
  require("../shared.php");

  rejectGuest();

  $reply = new Reply();
  $id = post("id");

  // Must have an ID set
  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  // Check the ID is valid
  $selectQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `ID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  // Reduce their year group
  $data = $selectQuery->fetch(PDO::FETCH_OBJ);

  if($data->Year == 12) {
	 $Year = 0;
  } else {
	 $Year = 1;
  }

  $resetQuery = Database::get()->prepare("UPDATE `accounts` SET `Year` = `Year` - $Year WHERE `ID` = :id");
  $resetQuery->execute(["id" => $id]);

  if($resetQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to rollback"));
    die($reply->toJson());
  }

  // Return the new year for displaying
  $reply->setStatus(ReplyStatus::withData(200, "Successfully rolled back"));
  $reply->setValue("new_year", $data->Year - $Year);
  echo $reply->toJson();
?>
