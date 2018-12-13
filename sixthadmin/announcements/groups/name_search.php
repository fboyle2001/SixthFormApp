<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $name = get("name");


  $selectQuery = "SELECT * FROM `groups`";

  if($name != null) {
    $selectQuery .= " WHERE `GroupName` LIKE '%$name%'";
  }

  $selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

  if($selectQuery->wasDataReturned() == false) {
    $reply->setStatus(ReplyStatus::withData(400, "Invalid group name"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "Data found"));
  $reply->setValue("records", $selectQuery->getRecords());
  $reply->setValue("found", $selectQuery->wasDataReturned());
  echo $reply->toJson();
?>
