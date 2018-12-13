<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $name = get("name");

  if($name == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No group name set"));
    die($reply->toJson());
  }

  $selectQuery = "SELECT * FROM `groups` WHERE `GroupName` = '$name'";
  $selectQuery = DatabaseHandler::getInstance()->executeQuery($selectQuery);

  if($selectQuery->wasDataReturned() == true) {
    $reply->setStatus(ReplyStatus::withData(400, "Group name in use"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "No group found with name"));
  echo $reply->toJson();
?>
