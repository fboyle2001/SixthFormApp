<?php
  require("../shared.php");

  rejectGuest();

  $reply = new Reply();
  $id = post("id");

  // Make sure an ID is set
  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  // Check it was valid
  $selectQuery = Database::get()->prepare("SELECT * FROM `announcements` WHERE `ID` = :id");
  $selectQuery->execute(["id" => $id]);

  if($selectQuery == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  // Remove it from the database
  $deleteQuery = Database::get()->prepare("DELETE FROM `announcements` WHERE `ID` = :id");
  $deleteQuery->execute(["id" => $id]);

  if($deleteQuery == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to delete announcement"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully deleted announcement"));
  echo $reply->toJson();
?>
