<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $username = get("username");

  if($username == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No username set"));
    die($reply->toJson());
  }

  $selectQuery = "SELECT * FROM `accounts` WHERE `Username` = '$username'";
  $selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

  if($selectQuery->wasDataReturned() == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid username"));
    die($reply->toJson());
  }

  $row = $selectQuery->getRecords()[0];

  $reply->setStatus(ReplyStatus::withData(200, "Successfully found account"));
  $reply->setValue("id", $row["ID"]);
  echo $reply->toJson();
?>
