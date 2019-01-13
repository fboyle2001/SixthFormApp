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

  // Check it exists
  $selectQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `ID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  // Remove the single ID from the database
  $deleteQuery = Database::get()->prepare("DELETE FROM `accounts` WHERE `ID` = :id");
  $deleteQuery->execute(["id" => $id]);

  if($deleteQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to delete account"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully deleted account"));
  echo $reply->toJson();
?>
