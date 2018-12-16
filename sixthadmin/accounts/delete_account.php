<?php
  require("../shared.php");

  rejectGuest();

  $reply = new Reply();
  $id = post("id");

  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  $selectQuery = Database::get()->prepare("SELECT * FROM `accounts` WHERE `ID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  $deleteQuery = Database::get()->prepare("DELETE FROM `accounts` WHERE `ID` = :id");
  $deleteQuery->execute(["id" => $id]);

  if($deleteQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to delete account"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully deleted account"));
  echo $reply->toJson();
?>
