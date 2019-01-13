<?php
  require("../../shared.php");

  rejectGuest();

  $reply = new Reply();
  $name = get("name");

  // Check a name is set
  if($name == null) {
    $reply->setStatus(ReplyStatus::withData(400, "No group name set"));
    die($reply->toJson());
  }

  // Check the group name does not exist already
  $selectQuery = Database::get()->prepare("SELECT * FROM `groups` WHERE `GroupName` = :name");
  $selectQuery->execute(["name" => $name]);

  if($selectQuery->rowCount() != 0) {
    $reply->setStatus(ReplyStatus::withData(400, "Group name in use"));
    die($reply->toJson());
  }

  $reply->setStatus(ReplyStatus::withData(200, "No group found with name"));
  echo $reply->toJson();
?>
