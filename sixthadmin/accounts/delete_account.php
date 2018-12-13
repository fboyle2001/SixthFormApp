<?php
  require("../shared.php");

  rejectGuest();

  $reply = new Reply();
  $id = post("id");

  if($id == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No ID set"));
    die($reply->toJson());
  }

  $selectQuery = "SELECT * FROM `accounts` WHERE `ID` = '$id'";
  $selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

  if($selectQuery->wasDataReturned() == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid ID"));
    die($reply->toJson());
  }

  $deleteQuery = "DELETE FROM `accounts` WHERE `ID` = '$id'";
  $deleteQuery = DatabaseHandler::getInstance()->executeQuery($deleteQuery);

  if($deleteQuery->wasSuccessful() == false) {
    $reply->setStatus(ReplyStatus::withData(500, "Unable to delete account"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Successfully deleted account"));
  echo $reply->toJson();
?>
