<?php
  require("../shared.php");

  rejectGuest();

  $reply = new Reply();
  $id = post("id");

  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  // Check the ID exists first
  $selectQuery = Database::get()->prepare("SELECT * FROM `links` WHERE `ID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  // Delete only that specific ID
  $deleteQuery = Database::get()->prepare("DELETE FROM `links` WHERE `ID` = :id");
  $deleteQuery->execute(["id" => $id]);

  if($deleteQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to delete link"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully deleted link"));
  echo $reply->toJson();
?>
