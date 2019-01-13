<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $id = post("id");

  // Check an ID is set
  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  // Select the group from the database if it exists
  $selectQuery = Database::get()->prepare("SELECT * FROM `groups` WHERE `ID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  // Remove the group
  $deleteQuery = Database::get()->prepare("DELETE FROM `groups` WHERE `ID` = :id");
  $deleteQuery->execute(["id" => $id]);

  if($deleteQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to delete group"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Deleted"));
  echo $reply->toJson();
?>
